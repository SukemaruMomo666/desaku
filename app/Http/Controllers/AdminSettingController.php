<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signatory;
use App\Models\LetterRequest;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use File;

class AdminSettingController extends Controller
{
    public function index()
    {
        $signatories = Signatory::orderBy('created_at', 'desc')->get();
        
        // Cek ukuran folder requirements
        $size = 0;
        $path = storage_path('app/public/requirements');
        if (File::exists($path)) {
            foreach (File::allFiles($path) as $file) {
                $size += $file->getSize();
            }
        }
        $archiveSize = round($size / 1024 / 1024, 2); // dalam MB

        return view('dashboard.admin.settings.index', compact('signatories', 'archiveSize'));
    }

    public function storeSignatory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'nip' => 'nullable|string|max:255',
        ]);

        Signatory::create($validated);

        return redirect()->back()->with('success', 'Penandatangan berhasil ditambahkan.');
    }

    public function destroySignatory($id)
    {
        Signatory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Penandatangan berhasil dihapus.');
    }

    public function backupArchive()
    {
        $requests = LetterRequest::with(['user', 'letterType'])->whereNotNull('uploaded_files')->get();
        
        if ($requests->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data arsip untuk di-backup.');
        }

        $zipFileName = 'Backup_Arsip_Desa_' . date('Y_m_d_His') . '.zip';
        $zipFilePath = storage_path('app/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($requests as $req) {
                $files = $req->uploaded_files;
                if (is_array($files)) {
                    $folderName = str_replace(['/', '\\'], '_', $req->user->name) . '_' . $req->user->nik;
                    $subfolderName = str_replace(['/', '\\'], '_', $req->letterType->name) . '_' . $req->created_at->format('d_M_Y');
                    
                    foreach ($files as $key => $filePath) {
                        $absolutePath = storage_path('app/public/' . $filePath);
                        if (file_exists($absolutePath)) {
                            // extension
                            $ext = pathinfo($absolutePath, PATHINFO_EXTENSION);
                            $zipPath = $folderName . '/' . $subfolderName . '/' . $key . '.' . $ext;
                            $zip->addFile($absolutePath, $zipPath);
                        }
                    }
                }
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Gagal membuat file backup .zip');
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

    public function cleanArchive()
    {
        // Set all uploaded_files to null in DB
        LetterRequest::query()->update(['uploaded_files' => null]);
        
        // Delete all physical files in requirements directory
        if (Storage::disk('public')->exists('requirements')) {
            Storage::disk('public')->deleteDirectory('requirements');
            Storage::disk('public')->makeDirectory('requirements');
        }

        return redirect()->back()->with('success', 'Arsip lampiran berhasil dikosongkan. Ruang penyimpanan telah bersih.');
    }
}
