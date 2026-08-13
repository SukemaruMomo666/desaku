<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LetterType;
use App\Models\LetterRequest;

class CitizenController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $requests = $user->letterRequests()->with('letterType')->orderBy('created_at', 'desc')->get();
        return view('dashboard.warga.index', compact('requests'));
    }

    public function createRequest(Request $request)
    {
        $types = LetterType::where('is_active', true)->get();
        
        // Cek jika user sudah memilih tipe surat
        $selectedType = null;
        if ($request->has('type_id')) {
            $selectedType = LetterType::where('is_active', true)->find($request->type_id);
        }
        
        return view('dashboard.warga.create-request', compact('types', 'selectedType'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'letter_type_id' => 'required|exists:letter_types,id',
            'form_fields' => 'nullable|array',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $letterType = LetterType::findOrFail($request->letter_type_id);

        // Validasi jika surat punya form_fields wajib
        if ($letterType->form_fields && is_array($letterType->form_fields) && count($letterType->form_fields) > 0) {
            foreach ($letterType->form_fields as $field) {
                if (!isset($request->form_fields[$field]) || empty($request->form_fields[$field])) {
                    return back()->withInput()->with('error', "Kolom '$field' wajib diisi!");
                }
            }
        }

        // Handle file upload if required by LetterType (requirements)
        $uploadedFiles = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $key => $file) {
                if ($file->isValid()) {
                    $path = $file->store('requirements', 'public');
                    $uploadedFiles[$key] = $path;
                }
            }
        }

        LetterRequest::create([
            'user_id' => Auth::id(),
            'letter_type_id' => $letterType->id,
            'status' => 'menunggu',
            'submitted_data' => $request->form_fields ?? [],
            'uploaded_files' => $uploadedFiles,
        ]);

        return redirect()->route('citizen.dashboard')->with('success', 'Pengajuan surat berhasil dikirim! Silakan pantau status pengajuan Anda.');
    }
}
