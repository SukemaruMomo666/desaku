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
                // Jangan konversi huruf kecil agar sesuai persis dengan case-sensitive dari template Word
                // (misal ${alamat_lengkap_sesuai_KTP} butuh key alamat_lengkap_sesuai_KTP)
                $templateProcessor->setValue($key, strtoupper($value));
            }
        }

        // 2. Replace basic placeholders (Fallback jika tidak ada di form submission)
        $templateProcessor->setValue('nomor_surat', $letterRequest->letter_number ?? '........................');
        $templateProcessor->setValue('nama', strtoupper($letterRequest->user->name));
        $templateProcessor->setValue('nik', $letterRequest->user->nik);
        $templateProcessor->setValue('tanggal_lahir', $letterRequest->user->birth_date ? \Carbon\Carbon::parse($letterRequest->user->birth_date)->format('d-m-Y') : '-');
        $templateProcessor->setValue('tanggal_lahir(dd/mm/yy)', $letterRequest->user->birth_date ? \Carbon\Carbon::parse($letterRequest->user->birth_date)->format('d-m-Y') : '-');
        $templateProcessor->setValue('jenis_kelamin', strtoupper($letterRequest->user->gender === 'L' ? 'Laki-Laki' : 'Perempuan'));
        $templateProcessor->setValue('agama', strtoupper($letterRequest->user->religion));
        $templateProcessor->setValue('pekerjaan', strtoupper($letterRequest->user->job));
        $templateProcessor->setValue('alamat', strtoupper($letterRequest->user->address));
        $templateProcessor->setValue('telepon', $letterRequest->user->phone);
        $templateProcessor->setValue('tanggal_pengajuan', $letterRequest->created_at->format('d-m-Y'));

        // 3. Tanda Tangan
        if ($letterRequest->signatory) {
            $templateProcessor->setValue('ttd_nama', $letterRequest->signatory->name);
            $templateProcessor->setValue('ttd_jabatan', $letterRequest->signatory->position);
            $templateProcessor->setValue('ttd_nip', $letterRequest->signatory->nip ? $letterRequest->signatory->nip : '');
        } else {
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
