<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\LetterType;

class AdminLetterTypeController extends Controller
{
    public function index()
    {
        $types = LetterType::withCount('requests')->orderBy('created_at', 'desc')->get();
        return view('dashboard.admin.letter-types.index', compact('types'));
    }

    public function create()
    {
        return view('dashboard.admin.letter-types.create');
    }

    public function store(Request $request)
    {
        $messages = [
            'statement_letter_file.uploaded' => 'File gagal diunggah. Pastikan ukuran file tidak melebihi batas sistem (maks 10MB) dan berformat .doc/.docx/.pdf.',
            'statement_letter_file.mimes' => 'Format file tidak diizinkan. Harus berupa .doc, .docx, atau .pdf.',
            'statement_letter_file.max' => 'Ukuran file terlalu besar. Maksimal 10MB.',
            'template_file.uploaded' => 'File template gagal diunggah. Pastikan ukuran file tidak terlalu besar.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:letter_types,code',
            'description' => 'nullable|string',
            'requirements' => 'nullable|array',
            'requirements.*' => 'string|max:255',
            'template_file' => 'nullable|file|mimes:doc,docx,zip|max:10240',
            'file_size_limit' => 'required|integer|min:1',
            'statement_letter_file' => 'nullable|file|mimes:doc,docx,pdf,zip|max:10240',
            'is_active' => 'boolean',
        ], $messages);

        // Hapus elemen kosong dari array
        $requirements = array_values(array_filter($validated['requirements'] ?? []));
        $formFields = [];

        $templateFilePath = null;
        if ($request->hasFile('template_file')) {
            $templateFilePath = $request->file('template_file')->store('templates', 'public');
            // Otomatis ekstrak form fields
            $formFields = $this->extractFormFieldsFromDocx(storage_path('app/public/' . $templateFilePath));
        }

        $statementFilePath = null;
        if ($request->hasFile('statement_letter_file')) {
            $statementFilePath = $request->file('statement_letter_file')->store('templates', 'public');
        }

        LetterType::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'description' => $validated['description'],
            'requirements' => $requirements,
            'form_fields' => $formFields,
            'template_file' => $templateFilePath,
            'max_file_size' => $validated['file_size_limit'],
            'statement_letter_file' => $statementFilePath,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.letter-types.index')->with('success', 'Jenis Surat berhasil ditambahkan!');
    }

    public function edit(LetterType $letterType)
    {
        return view('dashboard.admin.letter-types.edit', compact('letterType'));
    }

    public function update(Request $request, LetterType $letterType)
    {
        if ($request->hasFile('statement_letter_file')) {
            \Log::info('Statement letter upload error code: ' . $request->file('statement_letter_file')->getError());
        }

        $messages = [
            'statement_letter_file.uploaded' => 'File gagal diunggah. Pastikan ukuran file tidak melebihi batas sistem (maks 10MB) dan berformat .doc/.docx/.pdf.',
            'statement_letter_file.mimes' => 'Format file tidak diizinkan. Harus berupa .doc, .docx, atau .pdf.',
            'statement_letter_file.max' => 'Ukuran file terlalu besar. Maksimal 10MB.',
            'template_file.uploaded' => 'File template gagal diunggah. Pastikan ukuran file tidak terlalu besar.',
        ];

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:letter_types,code,'.$letterType->id,
                'description' => 'nullable|string',
                'requirements' => 'nullable|array',
                'requirements.*' => 'string|max:255',
                'template_file' => 'nullable|file|mimes:doc,docx,zip|max:10240',
                'file_size_limit' => 'required|integer|min:1',
                'statement_letter_file' => 'nullable|file|mimes:doc,docx,pdf,zip|max:10240',
            ], $messages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->hasFile('statement_letter_file')) {
                \Log::error('Validation failed. statement_letter_file error code: ' . $request->file('statement_letter_file')->getError());
            } else {
                \Log::error('Validation failed. statement_letter_file not in request or hasFile returned false. Raw $_FILES: ' . json_encode($_FILES));
            }
            throw $e;
        }

        // Hapus elemen kosong dari array
        $requirements = array_values(array_filter($validated['requirements'] ?? []));
        $formFields = $letterType->form_fields ?? [];

        $templateFilePath = $letterType->template_file;
        if ($request->hasFile('template_file')) {
            // Hapus file lama jika ada
            if ($templateFilePath && Storage::disk('public')->exists($templateFilePath)) {
                Storage::disk('public')->delete($templateFilePath);
            }
            $templateFilePath = $request->file('template_file')->store('templates', 'public');
            // Otomatis ekstrak form fields
            $formFields = $this->extractFormFieldsFromDocx(storage_path('app/public/' . $templateFilePath));
        }

        $statementFilePath = $letterType->statement_letter_file;
        if ($request->hasFile('statement_letter_file')) {
            if ($statementFilePath && Storage::disk('public')->exists($statementFilePath)) {
                Storage::disk('public')->delete($statementFilePath);
            }
            $statementFilePath = $request->file('statement_letter_file')->store('templates', 'public');
        }

        $letterType->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'description' => $validated['description'],
            'requirements' => $requirements,
            'form_fields' => $formFields,
            'template_file' => $templateFilePath,
            'max_file_size' => $validated['file_size_limit'],
            'statement_letter_file' => $statementFilePath,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.letter-types.index')->with('success', 'Jenis Surat berhasil diperbarui!');
    }

    public function destroy(LetterType $letterType)
    {
        if ($letterType->requests()->count() > 0) {
            return redirect()->route('admin.letter-types.index')->with('error', 'Tidak dapat menghapus jenis surat ini karena sudah ada warga yang mengajukan surat ini.');
        }

        // Hapus file template jika ada
        if ($letterType->template_file && Storage::disk('public')->exists($letterType->template_file)) {
            Storage::disk('public')->delete($letterType->template_file);
        }

        $letterType->delete();
        return redirect()->route('admin.letter-types.index')->with('success', 'Jenis Surat berhasil dihapus!');
    }

    public function downloadTemplate($id)
    {
        $letterType = LetterType::findOrFail($id);
        
        if (!$letterType->template_file || !Storage::disk('public')->exists($letterType->template_file)) {
            return back()->with('error', 'File template tidak ditemukan.');
        }

        return Storage::disk('public')->download($letterType->template_file, 'Template_' . $letterType->code . '.docx');
    }

    public function downloadStatementLetter($id)
    {
        $letterType = LetterType::findOrFail($id);
        
        if (!$letterType->statement_letter_file || !Storage::disk('public')->exists($letterType->statement_letter_file)) {
            return back()->with('error', 'File template surat pernyataan tidak ditemukan.');
        }

        $extension = pathinfo($letterType->statement_letter_file, PATHINFO_EXTENSION);
        return Storage::disk('public')->download($letterType->statement_letter_file, 'Surat_Pernyataan_' . $letterType->code . '.' . $extension);
    }

    private function extractFormFieldsFromDocx($filePath)
    {
        $zip = new \ZipArchive;
        if ($zip->open($filePath) === TRUE) {
            $xml = $zip->getFromName('word/document.xml');
            $zip->close();
            
            if ($xml) {
                // Hapus tags XML
                $text = strip_tags($xml);
                
                // Cari format ${variabel}
                preg_match_all('/\$\{([^}]+)\}/', $text, $matches);
                
                if (isset($matches[1]) && count($matches[1]) > 0) {
                    $variables = array_unique($matches[1]);
                    
                    // Ambil semua variabel kecuali yang di-generate sistem (seperti tanggal_pengajuan)
                    $systemVars = ['tanggal_pengajuan', 'nomor_surat'];
                    $customVars = array_diff($variables, $systemVars);
                    
                    return array_values($customVars);
                }
            }
        }
        
        return [];
    }
}
