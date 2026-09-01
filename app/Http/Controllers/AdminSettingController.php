<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Signatory;
use App\Models\LetterRequest;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
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
        
        // Data akun admin
        $admins = User::whereIn('role', ['master_admin', 'super_admin', 'admin'])->get();

        // Data role permissions
        $role_super_admin_permissions = Setting::where('key', 'role_super_admin_permissions')->first()->value ?? [];
        $role_admin_permissions = Setting::where('key', 'role_admin_permissions')->first()->value ?? [];

        return view('dashboard.admin.settings.index', compact('signatories', 'archiveSize', 'admins', 'role_super_admin_permissions', 'role_admin_permissions'));
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

        $zipFileName = 'Backup_Arsip_Kelurahan_' . date('Y_m_d_His') . '.zip';
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

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:16|unique:users,nik', // Menggunakan NIK sebagai username untuk login
            'password' => 'required|string|min:6',
            'role' => 'required|in:master_admin,super_admin,admin'
        ]);

        User::create([
            'name' => $validated['name'],
            'nik' => $validated['nik'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role']
        ]);

        return redirect()->back()->with('success', 'Akun admin berhasil ditambahkan.');
    }

    public function destroyAdmin($id)
    {
        $user = User::findOrFail($id);
        
        // Mencegah master admin menghapus dirinya sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Akun admin berhasil dihapus.');
    }

    public function updateRolePermissions(Request $request)
    {
        $request->validate([
            'role_super_admin_permissions' => 'array',
            'role_admin_permissions' => 'array',
        ]);

        Setting::updateOrCreate(
            ['key' => 'role_super_admin_permissions'],
            ['value' => $request->role_super_admin_permissions ?? []]
        );

        Setting::updateOrCreate(
            ['key' => 'role_admin_permissions'],
            ['value' => $request->role_admin_permissions ?? []]
        );

        // Clear cache
        \Illuminate\Support\Facades\Cache::forget('role_super_admin_permissions');
        \Illuminate\Support\Facades\Cache::forget('role_admin_permissions');

        return redirect()->back()->with('success', 'Hak akses role berhasil diperbarui.');
    }
}
