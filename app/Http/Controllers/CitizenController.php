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
        if (
            empty($user->no_kk) || empty($user->gender) || empty($user->birth_date) || empty($user->address) || empty($user->religion) || empty($user->job) ||
            empty($user->place_of_birth) || empty($user->rt) || empty($user->rw) || empty($user->village) || empty($user->district) || 
            empty($user->city) || empty($user->province) || empty($user->marital_status) || empty($user->nationality)
        ) {
            return redirect()->route('citizen.profile')
                ->with('profile_incomplete_alert', true)
                ->with('error', 'PENTING: Silakan lengkapi seluruh data diri Anda (sesuai KTP) di bawah ini sebelum mengajukan surat baru.');
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
        ]);

        $letterType = LetterType::findOrFail($request->letter_type_id);
        $maxSizeKB = ($letterType->max_file_size ?? 2) * 1024;

        $request->validate([
            'form_fields' => 'nullable|array',
            'files' => 'nullable|array',
            'files.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:' . $maxSizeKB
        ]);

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
            'no_kk' => 'required|numeric|digits:16',
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'place_of_birth' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'blood_type' => 'nullable|in:A,B,AB,O,-',
            'religion' => 'required|string',
            'marital_status' => 'required|string',
            'job' => 'required|string',
            'nationality' => 'required|string',
            'address' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'village' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
        ]);

        $user->update($validated);

        return redirect()->route('citizen.profile')->with('success', 'Profil Anda berhasil diperbarui! Data ini akan otomatis digunakan untuk pengajuan surat selanjutnya.');
    }
}
