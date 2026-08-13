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
        $user = Auth::user();
        
        // Cek apakah data diri sudah lengkap
        if (empty($user->gender) || empty($user->birth_date) || empty($user->address) || empty($user->religion) || empty($user->job)) {
            return redirect()->route('citizen.profile')->with('error', 'PENTING: Silakan lengkapi seluruh data diri Anda di bawah ini sebelum mengajukan surat baru.');
        }

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

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.warga.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nik' => 'required|numeric|digits:16|unique:users,nik,' . $user->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'gender' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'religion' => 'required|string',
            'job' => 'required|string',
        ]);

        $user->update($validated);

        return redirect()->route('citizen.profile')->with('success', 'Profil Anda berhasil diperbarui! Data ini akan otomatis digunakan untuk pengajuan surat selanjutnya.');
    }
}
