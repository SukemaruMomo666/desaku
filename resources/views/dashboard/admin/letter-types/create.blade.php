@extends('layouts.app')

@section('header_title', 'Tambah Master Surat')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.letter-types.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Daftar
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 sm:px-8 py-6 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-xl text-gray-900">Form Tambah Jenis Surat</h3>
            <p class="text-sm text-gray-500 mt-1">Lengkapi informasi di bawah ini untuk menambahkan layanan surat baru.</p>
        </div>
        
        <form action="{{ route('admin.letter-types.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6" x-data="requirementsForm()">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-xl mb-6">
                    <div class="flex items-center gap-3 font-bold mb-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        Gagal menyimpan! Periksa kesalahan berikut:
                    </div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Kode Surat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kode Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none uppercase" placeholder="Misal: SKTM, SKU, SK-DOMISILI">
                    @error('code') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
                
                <!-- Nama Surat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" placeholder="Misal: Surat Keterangan Tidak Mampu">
                    @error('name') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi Kegunaan</label>
                <textarea name="description" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none resize-none" placeholder="Jelaskan kegunaan surat ini agar warga mengerti...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-100">

            <!-- Syarat Dokumen (Dynamic) -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Persyaratan Dokumen</label>
                        <p class="text-xs text-gray-500 mt-0.5">Tentukan dokumen fisik/foto apa saja yang harus dibawa/diunggah warga.</p>
                    </div>
                    <button type="button" @click="addRequirement()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 font-semibold text-sm rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Tambah Syarat
                    </button>
                </div>

                <div class="space-y-3">
                    <template x-for="(req, index) in requirements" :key="index">
                        <div class="flex items-center gap-3">
                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 font-bold" x-text="(index + 1) + '.'"></span>
                                </div>
                                <input type="text" x-model="requirements[index]" :name="`requirements[]`" class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" placeholder="Misal: Fotokopi KTP / Surat Pengantar RT">
                            </div>
                            <button type="button" @click="removeRequirement(index)" class="p-3 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors" title="Hapus Syarat">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </template>
                    <div x-show="requirements.length === 0" class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <p class="text-sm text-gray-500">Tidak ada persyaratan khusus.</p>
                    </div>
                </div>
            </div>
            <!-- Maksimal Ukuran File Syarat -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Maksimal Ukuran File Syarat (MB) <span class="text-red-500">*</span></label>
                <input type="number" name="file_size_limit" value="{{ old('file_size_limit', 2) }}" min="1" max="20" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" placeholder="Contoh: 2">
                <p class="text-xs text-gray-500 mt-2">Batas ukuran maksimal per file saat warga mengunggah syarat dokumen.</p>
                @error('file_size_limit') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
            </div>

            <!-- Template Surat Pernyataan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Template Surat Pernyataan (Opsional)</label>
                <div class="border border-gray-200 border-dashed rounded-xl p-6 text-center bg-gray-50 hover:bg-gray-100 transition-colors">
                    <input type="file" name="statement_letter_file" accept=".doc,.docx,.pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/pdf" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2.5 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-primary-50 file:text-primary-700
                    hover:file:bg-primary-100
                    ">
                </div>
                <p class="text-xs text-gray-500 mt-2">Unggah file kosong/contoh surat pernyataan agar bisa diunduh oleh warga saat mengajukan surat.</p>
                @error('statement_letter_file') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
            </div>
            <!-- Cetak Biru / Template File -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Template Surat (Word / .docx)</label>
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4 rounded-r-xl">
                    <p class="text-sm text-blue-800 font-semibold mb-2">💡 Belum tahu cara membuatnya?</p>
                    <p class="text-xs text-blue-700 mb-3">Di dalam file Microsoft Word, ketikkan kode <code>${variabel}</code> yang nantinya akan otomatis diubah menjadi data warga.<br>Contoh: <code>${nama}</code>, <code>${nik}</code>, <code>${alamat}</code>, <code>${tanggal_lahir}</code>, <code>${keperluan}</code>.</p>
                    <a href="{{ asset('Contoh_Template_SKTM.docx') }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh Contoh Template (.docx)
                    </a>
                </div>
                
                <div class="border border-gray-200 border-dashed rounded-xl p-6 text-center bg-gray-50 hover:bg-gray-100 transition-colors">
                    <input type="file" name="template_file" accept=".doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2.5 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-primary-50 file:text-primary-700
                    hover:file:bg-primary-100
                    ">
                </div>
                @error('template_file') <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p> @enderror
            </div>

            <hr class="border-gray-100">

            <!-- Status Aktif -->
            <div class="flex items-center justify-between p-5 rounded-2xl border border-gray-100 bg-gray-50">
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Aktifkan Layanan Surat</h4>
                    <p class="text-xs text-gray-500 mt-1">Jika dimatikan, warga tidak dapat mengajukan jenis surat ini.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                </label>
            </div>

            <div class="pt-6 flex justify-end gap-3">
                <a href="{{ route('admin.letter-types.index') }}" class="px-6 py-3 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">Batal</a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:-translate-y-0.5 hover:bg-primary-700 hover:shadow-primary-500/40 transition-all">
                    Simpan Jenis Surat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('requirementsForm', () => ({
            requirements: [''], // Mulai dengan 1 input kosong
            addRequirement() {
                this.requirements.push('');
            },
            removeRequirement(index) {
                this.requirements.splice(index, 1);
            }
        }));
    });
</script>
@endsection
