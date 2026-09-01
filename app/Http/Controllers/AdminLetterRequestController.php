<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LetterRequest;
use App\Models\Signatory;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Services\FonnteService;

class AdminLetterRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = LetterRequest::with(['user', 'letterType'])->orderBy('created_at', 'desc');

        // Optional: Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15);
        return view('dashboard.admin.letter-requests.index', compact('requests'));
    }

    public function show($id)
    {
        $letterRequest = LetterRequest::with(['user', 'letterType', 'signatory'])->findOrFail($id);
        $signatories = Signatory::where('is_active', true)->get();
        return view('dashboard.admin.letter-requests.show', compact('letterRequest', 'signatories'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:menunggu,diproses,siap_diambil,selesai,ditolak',
            'letter_number' => 'nullable|string|max:255',
            'signatory_id' => 'nullable|exists:signatories,id',
            'admin_notes' => 'nullable|string',
        ]);

        $letterRequest = LetterRequest::with(['user', 'letterType'])->findOrFail($id);
        $oldStatus = $letterRequest->status;

        $letterRequest->update([
            'status' => $validated['status'],
            'letter_number' => $validated['letter_number'] ?? $letterRequest->letter_number,
            'signatory_id' => $validated['signatory_id'] ?? $letterRequest->signatory_id,
            'admin_notes' => $validated['admin_notes'] ?? $letterRequest->admin_notes,
        ]);

        // Kirim Notifikasi Otomatis jika status berubah ke siap_diambil atau ditolak
        if ($oldStatus !== $validated['status']) {
            if ($validated['status'] === 'siap_diambil' || $validated['status'] === 'ditolak') {
                $user = $letterRequest->user;
                if ($user && $user->phone) {
                    $waText = "Halo Bpk/Ibu " . $user->name . ",\n\n";
                    if ($validated['status'] === 'siap_diambil') {
                        $waText .= "Permohonan *" . $letterRequest->letterType->name . "* Anda telah selesai diproses dan *SIAP DIAMBIL* di Balai Desa.\n\n";
                    } else {
                        $waText .= "Mohon maaf, permohonan *" . $letterRequest->letterType->name . "* Anda *DITOLAK*.\n\n";
                    }

                    if ($letterRequest->admin_notes) {
                        $waText .= "Catatan: " . $letterRequest->admin_notes . "\n\n";
                    }

                    if ($validated['status'] === 'siap_diambil') {
                        $waText .= "Terima kasih.";
                    } else {
                        $waText .= "Silakan cek dashboard atau ajukan ulang permohonan Anda. Terima kasih.";
                    }

                    try {
                        FonnteService::sendMessage($user->phone, $waText);
                    } catch (\Exception $e) {
                        // Jangan hentikan flow jika gagal kirim WA
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Status pengajuan berhasil diperbarui!');
    }

    public function downloadDocx($id)
    {
        $letterRequest = LetterRequest::with(['user', 'letterType', 'signatory'])->findOrFail($id);
        
        $templateFile = $letterRequest->letterType->template_file;
        
        if (!$templateFile || !Storage::disk('public')->exists($templateFile)) {
            return back()->with('error', 'Jenis surat ini belum memiliki Template Word (.docx). Silakan unggah template di menu Master Jenis Surat terlebih dahulu.');
        }

        $templatePath = Storage::disk('public')->path($templateFile);
        $templateProcessor = new TemplateProcessor($templatePath);

        // 1. Custom form fields from user submission (Prioritas Utama, menimpa auto-fill jika diedit)
        if ($letterRequest->submitted_data && is_array($letterRequest->submitted_data)) {
            foreach ($letterRequest->submitted_data as $key => $value) {
                // Konversi tanggal dari format YYYY-MM-DD (input type date) ke d-m-Y agar rapi di surat
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', (string)$value)) {
                    $value = \Carbon\Carbon::parse($value)->format('d-m-Y');
                }
                
                // Jangan konversi huruf kecil agar sesuai persis dengan case-sensitive dari template Word
                // (misal ${alamat_lengkap_sesuai_KTP} butuh key alamat_lengkap_sesuai_KTP)
                $templateProcessor->setValue($key, strtoupper($value));
            }
        }

        // 2. Replace basic placeholders (Fallback jika tidak ada di form submission)
        $templateProcessor->setValue('nomor_surat', $letterRequest->letter_number ?? '........................');
        $templateProcessor->setValue('nama', strtoupper($letterRequest->user->name));
        $templateProcessor->setValue('nik', $letterRequest->user->nik);
        $templateProcessor->setValue('no_kk', $letterRequest->user->no_kk ?? '-');
        $templateProcessor->setValue('tempat_lahir', strtoupper($letterRequest->user->place_of_birth));
        $templateProcessor->setValue('tanggal_lahir', $letterRequest->user->birth_date ? \Carbon\Carbon::parse($letterRequest->user->birth_date)->format('d-m-Y') : '-');
        $templateProcessor->setValue('tanggal_lahir(dd/mm/yy)', $letterRequest->user->birth_date ? \Carbon\Carbon::parse($letterRequest->user->birth_date)->format('d-m-Y') : '-');
        $templateProcessor->setValue('jenis_kelamin', strtoupper($letterRequest->user->gender === 'L' ? 'Laki-Laki' : 'Perempuan'));
        $templateProcessor->setValue('suami_istri', $letterRequest->user->gender === 'L' ? 'ISTRI' : 'SUAMI');
        $templateProcessor->setValue('suami_istri_title', $letterRequest->user->gender === 'L' ? 'Istri' : 'Suami');
        $templateProcessor->setValue('kewarganegaraan', strtoupper($letterRequest->user->nationality));
        $templateProcessor->setValue('agama', strtoupper($letterRequest->user->religion));
        $templateProcessor->setValue('pekerjaan', strtoupper($letterRequest->user->job));
        $templateProcessor->setValue('status_perkawinan', strtoupper($letterRequest->user->marital_status));
        $templateProcessor->setValue('alamat', strtoupper($letterRequest->user->address));
        
        // Format RT/RW (misal: 005 / 001)
        $rt = str_pad($letterRequest->user->rt, 3, '0', STR_PAD_LEFT);
        $rw = str_pad($letterRequest->user->rw, 3, '0', STR_PAD_LEFT);
        $templateProcessor->setValue('rt', $rt);
        $templateProcessor->setValue('rw', $rw);
        $templateProcessor->setValue('alamat_lengkap', strtoupper($letterRequest->user->address . ' RT ' . $rt . ' RW ' . $rw));
        
        $templateProcessor->setValue('telepon', $letterRequest->user->phone);
        $templateProcessor->setValue('tanggal_pengajuan', $letterRequest->created_at->format('d-m-Y'));

        // Format Tanggal Hari Ini (Format Indonesia)
        $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $tgl = \Carbon\Carbon::now();
        $tanggal_hari_ini = $tgl->format('d') . ' ' . $months[(int)$tgl->format('m')] . ' ' . $tgl->format('Y');
        $templateProcessor->setValue('tanggal_hari_ini', $tanggal_hari_ini);

        // 3. Tanda Tangan
        if ($letterRequest->signatory) {
            $jabatan = trim(strtoupper($letterRequest->signatory->position));
            
            // Cek apakah yang tanda tangan adalah Lurah langsung
            if (str_contains($jabatan, 'LURAH') && !str_contains($jabatan, 'SEKRETARIS')) {
                $blok_jabatan = 'Lurah Sukapada';
            } else {
                // Gunakan XML tag </w:t><w:br/><w:t> untuk membuat baris baru (enter) di dalam tabel Word
                $blok_jabatan = 'a.n. Lurah Sukapada</w:t><w:br/><w:t>' . ucwords(strtolower($letterRequest->signatory->position));
            }

            $templateProcessor->setValue('blok_jabatan', $blok_jabatan);
            $templateProcessor->setValue('ttd_nama', $letterRequest->signatory->name);
            $templateProcessor->setValue('ttd_jabatan', $letterRequest->signatory->position);
            
            // Tambahkan tulisan "NIP. " secara otomatis jika ada
            $nip_text = $letterRequest->signatory->nip ? 'NIP. ' . $letterRequest->signatory->nip : '';
            $templateProcessor->setValue('ttd_nip', $nip_text);
        } else {
            $templateProcessor->setValue('blok_jabatan', '.............................');
            $templateProcessor->setValue('ttd_nama', '.............................');
            $templateProcessor->setValue('ttd_jabatan', '.............................');
            $templateProcessor->setValue('ttd_nip', '');
        }

        // Generate final filename
        $filename = 'SURAT_' . $letterRequest->letterType->code . '_' . $letterRequest->user->nik . '.docx';
        $tempPath = storage_path('app/temp_' . time() . '.docx');
        
        // Save to temp file
        $templateProcessor->saveAs($tempPath);

        // Download and then delete temp file
        return response()->download($tempPath, $filename)->deleteFileAfterSend(true);
    }
}
