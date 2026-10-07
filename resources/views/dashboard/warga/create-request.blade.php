@extends('layouts.app')

@section('header_title', 'Ajukan Surat Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <a href="{{ route('citizen.dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 font-medium transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Dashboard
        </a>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            <span class="font-bold">{{ session('error') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-xl">
            <div class="flex items-center gap-3 font-bold mb-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                Ada kesalahan pada pengisian form:
            </div>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Step 1: Pilih Jenis Surat -->
        <div class="md:col-span-1 space-y-4 {{ $selectedType ? 'hidden md:block' : 'block' }}">
            <h3 class="text-lg font-bold text-gray-900">1. Pilih Jenis Surat</h3>
            <p class="text-sm text-gray-500">Pilih layanan surat yang ingin Anda ajukan.</p>

            
            <form action="{{ route('citizen.request.create') }}" method="GET" id="letterTypeForm">
                <div class="space-y-3">
                    @forelse($types as $type)
                        <label class="block relative cursor-pointer group">
                            <input type="radio" name="type_id" value="{{ $type->id }}" class="peer sr-only" onchange="document.getElementById('letterTypeForm').submit()" {{ ($selectedType && $selectedType->id == $type->id) ? 'checked' : '' }}>
                            <div class="p-4 rounded-2xl border-2 {{ ($selectedType && $selectedType->id == $type->id) ? 'border-primary-500 bg-primary-50/50' : 'border-gray-100 bg-white hover:border-primary-200' }} transition-all">
                                <div class="font-bold {{ ($selectedType && $selectedType->id == $type->id) ? 'text-primary-700' : 'text-gray-900 group-hover:text-primary-600' }}">{{ $type->code }}</div>
                                <div class="text-xs {{ ($selectedType && $selectedType->id == $type->id) ? 'text-primary-600' : 'text-gray-500' }} mt-1 leading-relaxed">{{ $type->name }}</div>
                            </div>
                        </label>
                    @empty
                        <div class="text-sm text-gray-500 italic p-4 bg-gray-50 rounded-xl">Belum ada layanan surat yang aktif.</div>
                    @endforelse
                </div>
            </form>
        </div>

        <!-- Step 2: Form Isian -->
        <div class="md:col-span-2 {{ $selectedType ? 'block' : 'hidden md:block' }}">
            @if($selectedType)
                <!-- Tombol Kembali Mobile -->
                <a href="{{ route('citizen.request.create') }}" class="md:hidden inline-flex items-center gap-2 text-primary-600 font-bold mb-4 bg-primary-50 px-4 py-2 rounded-xl hover:bg-primary-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Ganti Jenis Surat
                </a>

                <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 sm:px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="font-bold text-xl text-gray-900">2. Lengkapi Data Pengajuan</h3>
                        <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah ini dengan sebenar-benarnya untuk {{ $selectedType->name }}.</p>
                    </div>
                    
                    <form x-data="{ isSubmitting: false, showModal: false, agreed: false }" 
                          @submit.prevent="if(agreed) { isSubmitting = true; $el.submit(); } else { showModal = true; }" 
                          action="{{ route('citizen.request.store') }}" method="POST" enctype="multipart/form-data" class="p-6 sm:p-8 space-y-6">
                        @csrf
                        <input type="hidden" name="letter_type_id" value="{{ $selectedType->id }}">
                        
                        <!-- Informasi Pribadi (Info) -->
                        <div class="p-4 bg-blue-50 rounded-2xl border border-blue-100 mb-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <h4 class="text-sm font-bold text-blue-900">Periksa Kembali Data Anda</h4>
                                    <p class="text-xs text-blue-700 mt-1">Beberapa kolom di bawah ini telah diisi otomatis berdasarkan data profil Anda. Anda dapat mengubahnya jika diperlukan untuk keperluan surat ini.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        @if($selectedType->form_fields && count($selectedType->form_fields) > 0)
                            <div class="space-y-5">
                                <h4 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2">Formulir Isian Surat</h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    @php
                                        $hasAyahHeader = false;
                                        $hasIbuHeader = false;
                                    @endphp
                                    @foreach($selectedType->form_fields as $field)
                                        @php
                                            $autoFill = '';
                                            $fieldKey = str_replace(' ', '_', strtolower($field));
                                            
                                            // Jangan tampilkan field sistem rahasia & field profil warga (sudah otomatis)
                                            $hiddenFields = ['tanggal_hari_ini', 'blok_jabatan', 'ttd_nama', 'ttd_nip', 'tanggal_pengajuan', 'janda_duda', 'janda_duda_upper', 'rt_raw', 'rw_raw', 'status_perkawinan_title', 'status_laki_laki', 'status_perempuan', 'alamat_lengkap'];
                                            if (in_array($fieldKey, $hiddenFields)) {
                                                continue;
                                            }

                                            $inputType = 'text';
                                            $inputMode = 'text';
                                            $isCurrency = false;

                                            if (str_contains($fieldKey, 'tanggal') || str_contains($fieldKey, 'tgl') || str_contains($fieldKey, 'date')) {
                                                $inputType = 'date';
                                            } elseif (
                                                str_contains($fieldKey, 'penghasilan') || 
                                                str_contains($fieldKey, 'gaji') || 
                                                str_contains($fieldKey, 'upah') || 
                                                str_contains($fieldKey, 'nominal') || 
                                                str_contains($fieldKey, 'omset') || 
                                                str_contains($fieldKey, 'omzet') || 
                                                str_contains($fieldKey, 'biaya') || 
                                                str_contains($fieldKey, 'harga')
                                            ) {
                                                $inputType = 'number';
                                                $inputMode = 'numeric';
                                                $isCurrency = true;
                                            } elseif (
                                                str_contains($fieldKey, 'jumlah') || 
                                                str_contains($fieldKey, 'anak_ke') || 
                                                str_contains($fieldKey, 'tanggungan') || 
                                                str_contains($fieldKey, 'umur') || 
                                                str_contains($fieldKey, 'usia') || 
                                                in_array($fieldKey, ['nik', 'no_kk', 'rt', 'rw', 'telepon', 'no_hp', 'wa', 'whatsapp', 'kode_pos']) ||
                                                str_contains($fieldKey, 'nik_')
                                            ) {
                                                $inputType = 'number';
                                                $inputMode = 'numeric';
                                            }

                                            if ($fieldKey == 'nama') $autoFill = Auth::user()->name;
                                            elseif ($fieldKey == 'nik') $autoFill = Auth::user()->nik;
                                            elseif ($fieldKey == 'no_kk') $autoFill = Auth::user()->no_kk;
                                            elseif ($fieldKey == 'tempat_lahir') $autoFill = Auth::user()->place_of_birth;
                                            elseif ($fieldKey == 'alamat') $autoFill = Auth::user()->address;
                                            elseif ($fieldKey == 'rt') $autoFill = Auth::user()->rt;
                                            elseif ($fieldKey == 'rw') $autoFill = Auth::user()->rw;
                                            elseif ($fieldKey == 'jenis_kelamin') $autoFill = Auth::user()->gender === 'L' ? 'Laki-Laki' : 'Perempuan';
                                            elseif ($fieldKey == 'agama') $autoFill = Auth::user()->religion;
                                            elseif ($fieldKey == 'pekerjaan') $autoFill = Auth::user()->job;
                                            elseif ($fieldKey == 'kewarganegaraan' || $fieldKey == 'kewarganegaraan_ayah' || $fieldKey == 'kewarganegaraan_ibu') $autoFill = Auth::user()->nationality ?? 'WNI';
                                            elseif ($fieldKey == 'status_perkawinan') $autoFill = Auth::user()->marital_status;
                                            elseif ($fieldKey == 'telepon') $autoFill = Auth::user()->phone;
                                            elseif ($fieldKey == 'tanggal_lahir' || $fieldKey == 'tanggal_lahir(dd/mm/yy)') $autoFill = Auth::user()->birth_date ? \Carbon\Carbon::parse(Auth::user()->birth_date)->format($inputType == 'date' ? 'Y-m-d' : 'd-m-Y') : '';
                                            elseif ($fieldKey == 'tanggal_kk' || $fieldKey == 'tgl_kk' || $fieldKey == 'tanggal_kk(dd/mm/yy)') $autoFill = Auth::user()->kk_issued_date ? \Carbon\Carbon::parse(Auth::user()->kk_issued_date)->format($inputType == 'date' ? 'Y-m-d' : 'd-m-Y') : '';
                                            
                                            $labelText = str_replace('_', ' ', $field);
                                            $labelText = ucwords($labelText);
                                            $labelText = str_replace(
                                                ['Nik', 'No Kk', 'Tanggal Kk', 'Tgl Kk', 'Kk', 'Rt', 'Rw', 'Tgl', 'Nomor Pengantar', 'Tanggal Pengantar', 'Tanggal Pernyataan'], 
                                                ['NIK', 'Nomor Kartu Keluarga (KK)', 'Tanggal Dikeluarkan KK', 'Tanggal Dikeluarkan KK', 'KK', 'RT', 'RW', 'Tanggal', 'No Surat Pengantar', 'Tanggal Surat Pengantar', 'Tanggal Surat Pernyataan'], 
                                                $labelText
                                            );
                                            
                                            $placeholderText = str_replace('_', ' ', strtolower($field));
                                            $placeholderText = str_replace(
                                                ['nik', 'no kk', 'tanggal kk', 'tgl kk', 'kk', 'rt', 'rw', 'tgl', 'nomor pengantar', 'tanggal pengantar', 'tanggal pernyataan'], 
                                                ['NIK', 'nomor kartu keluarga (KK)', 'tanggal dikeluarkan KK', 'tanggal dikeluarkan KK', 'KK', 'RT', 'RW', 'tanggal', 'no surat pengantar', 'tanggal surat pengantar', 'tanggal surat pernyataan'], 
                                                $placeholderText
                                            );

                                            $isDropdown = false;
                                            $options = [];
                                            if (str_contains($fieldKey, 'status_perkawinan')) {
                                                $isDropdown = true;
                                                $options = ['Belum Kawin' => 'Belum Kawin', 'Kawin' => 'Kawin', 'Cerai Hidup' => 'Cerai Hidup', 'Cerai Mati' => 'Cerai Mati'];
                                            } elseif (str_contains($fieldKey, 'agama')) {
                                                $isDropdown = true;
                                                $options = ['Islam' => 'Islam', 'Kristen' => 'Kristen', 'Katolik' => 'Katolik', 'Hindu' => 'Hindu', 'Buddha' => 'Buddha', 'Konghucu' => 'Konghucu'];
                                            } elseif (str_contains($fieldKey, 'jenis_kelamin')) {
                                                $isDropdown = true;
                                                $options = ['Laki-Laki' => 'Laki-Laki', 'Perempuan' => 'Perempuan'];
                                            } elseif (str_contains($fieldKey, 'golongan_darah')) {
                                                $isDropdown = true;
                                                $options = ['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O', 'Tidak Tahu' => 'Tidak Tahu'];
                                            } elseif (str_contains($fieldKey, 'kewarganegaraan')) {
                                                $isDropdown = true;
                                                $options = ['WNI' => 'WNI', 'WNA' => 'WNA'];
                                            }
                                        @endphp
                                        
                                        @if(str_contains($fieldKey, 'ayah') && !$hasAyahHeader)
                                            <div class="sm:col-span-2 mt-4 mb-2">
                                                <h4 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2">Biodata Ayah</h4>
                                            </div>
                                            @php $hasAyahHeader = true; @endphp
                                        @endif
                                        
                                        @if(str_contains($fieldKey, 'ibu') && !$hasIbuHeader)
                                            <div class="sm:col-span-2 mt-4 mb-2">
                                                <h4 class="text-sm font-bold text-gray-900 border-b border-gray-100 pb-2">Biodata Ibu</h4>
                                            </div>
                                            @php $hasIbuHeader = true; @endphp
                                        @endif

                                        <div class="{{ in_array($fieldKey, ['alamat', 'keperluan']) || str_contains($fieldKey, 'alamat_') ? 'sm:col-span-2' : '' }}"
                                             @if($isCurrency) x-data="{ amount: '{{ old('form_fields.'.$field, $autoFill) }}' }" @endif>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $labelText }} <span class="text-red-500">*</span></label>
                                            
                                            @if($isCurrency)
                                                <div class="relative rounded-xl shadow-sm">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                                                        <span class="text-gray-500 font-bold text-sm">Rp</span>
                                                    </div>
                                                    <input type="number" 
                                                           inputmode="numeric" 
                                                           min="0"
                                                           step="1"
                                                           name="form_fields[{{ $field }}]" 
                                                           x-model="amount"
                                                           value="{{ old('form_fields.'.$field, $autoFill) }}" 
                                                           required 
                                                           class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none font-semibold text-gray-800" 
                                                           placeholder="Contoh: 2500000">
                                                </div>
                                                <template x-if="amount && amount > 0">
                                                    <p class="text-xs text-primary-700 font-semibold mt-1.5 flex items-center gap-1.5">
                                                        <svg class="w-3.5 h-3.5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        <span>Terbaca: <span class="text-primary-800 font-bold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(amount)"></span></span>
                                                    </p>
                                                </template>
                                            @elseif($isDropdown)
                                                <select name="form_fields[{{ $field }}]" 
                                                        required 
                                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none appearance-none">
                                                    <option value="" disabled {{ old('form_fields.'.$field, $autoFill) ? '' : 'selected' }}>-- Pilih {{ $labelText }} --</option>
                                                    @foreach($options as $val => $label)
                                                        <option value="{{ $val }}" {{ old('form_fields.'.$field, $autoFill) == $val ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 pt-8 text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                                </div>
                                            @else
                                                <input type="{{ $inputType }}" 
                                                       @if($inputMode === 'numeric') inputmode="numeric" min="0" @endif
                                                       name="form_fields[{{ $field }}]" 
                                                       value="{{ old('form_fields.'.$field, $autoFill) }}" 
                                                       required 
                                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none" 
                                                       placeholder="Masukkan {{ $placeholderText }}...">
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Syarat Dokumen (Informasi & Upload) -->
                        @if($selectedType->requirements && count($selectedType->requirements) > 0)
                            <div class="space-y-4 pt-4 border-t border-gray-100">
                                <div class="mb-4">
                                    <h4 class="text-sm font-bold text-gray-900">Persyaratan Dokumen</h4>
                                    <p class="text-xs text-gray-500 mt-1">Silakan unggah foto/scan dokumen di bawah ini (Format: JPG/PNG/PDF, Maks {{ $selectedType->max_file_size }}MB). Semua dokumen wajib diunggah.</p>
                                    
                                    @if($selectedType->statement_letter_file)
                                    <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-100 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-xs font-semibold text-blue-900">Format Surat Pernyataan tersedia</span>
                                        </div>
                                        <a href="{{ route('letter-types.download-statement', $selectedType->id) }}" class="text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg shadow-sm transition-colors">Unduh Format</a>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    @foreach($selectedType->requirements as $index => $req)
                                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                            <label class="block text-sm font-semibold text-gray-700 mb-2 truncate" title="{{ $req }}">{{ $req }}</label>
                                            <input type="file" name="files[{{ str_replace(' ', '_', strtolower($req)) }}]" accept=".jpg,.jpeg,.png,.pdf" required class="block w-full text-xs text-gray-500
                                            file:mr-3 file:py-1.5 file:px-3
                                            file:rounded-lg file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-primary-50 file:text-primary-700
                                            hover:file:bg-primary-100 cursor-pointer
                                            ">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Modal Persetujuan Data Pribadi -->
                        <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                <!-- Background overlay -->
                                <div x-show="showModal" 
                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                     class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                                <!-- This element is to trick the browser into centering the modal contents. -->
                                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                <!-- Modal panel -->
                                <div x-show="showModal" 
                                     @click.away="showModal = false"
                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                     class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl w-full border border-gray-100">
                                    <div class="bg-white px-6 pt-6 pb-6 sm:p-8">
                                        <div class="sm:flex sm:items-start">
                                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-14 w-14 rounded-full bg-blue-50 border border-blue-100 sm:mx-0 sm:h-12 sm:w-12">
                                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            </div>
                                            <div class="mt-4 text-center sm:mt-0 sm:ml-5 sm:text-left">
                                                <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                                                    Persetujuan Penggunaan Data
                                                </h3>
                                                <div class="mt-4 space-y-4 text-sm text-gray-600 max-h-64 overflow-y-auto pr-2" style="scrollbar-width: thin;">
                                                    <p class="leading-relaxed">Pemerintah Kelurahan Sukapada berkomitmen penuh dalam melindungi kerahasiaan dan keamanan data pribadi Anda sesuai dengan ketentuan perundang-undangan yang berlaku di Indonesia.</p>
                                                    <ul class="list-disc list-outside ml-4 space-y-2 leading-relaxed">
                                                        <li><strong class="text-gray-900">Tujuan Penggunaan:</strong> Seluruh dokumen dan data pribadi yang Anda unggah hanya akan digunakan secara eksklusif untuk keperluan verifikasi dan proses administrasi pelayanan surat ini.</li>
                                                        <li><strong class="text-gray-900">Keamanan & Kerahasiaan:</strong> Kami menerapkan standar keamanan sistem untuk melindungi data Anda. Data Anda tidak akan disebarluaskan, diperjualbelikan, atau dibagikan kepada pihak ketiga di luar kepentingan pelayanan Kelurahan.</li>
                                                        <li><strong class="text-gray-900">Keabsahan Dokumen:</strong> Anda menjamin sepenuhnya bahwa seluruh dokumen dan data yang diserahkan adalah benar, asli, dan sah secara hukum.</li>
                                                        <li><strong class="text-gray-900">Pelepasan Tuntutan:</strong> Segala bentuk pemalsuan data, manipulasi dokumen, maupun kebocoran yang murni diakibatkan oleh kelalaian pihak Anda sendiri, sepenuhnya berada di luar tanggung jawab kami. Dengan menyetujui ketentuan ini, Anda membebaskan Pemerintah Kelurahan Sukapada dari segala tuntutan hukum atau ganti rugi atas risiko tersebut.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-8 bg-gray-50 -mx-6 -mb-6 px-6 py-5 sm:px-8 sm:py-6 border-t border-gray-100">
                                            <label class="flex items-start gap-4 cursor-pointer group">
                                                <div class="flex items-center h-6 mt-0.5">
                                                    <input type="checkbox" x-model="agreed" class="w-5 h-5 text-primary-600 bg-white border-gray-300 rounded focus:ring-primary-500 focus:ring-offset-gray-50 cursor-pointer transition-colors shadow-sm">
                                                </div>
                                                <div class="text-sm">
                                                    <span class="font-bold text-gray-900 group-hover:text-primary-600 transition-colors">Saya telah membaca, memahami, dan menyetujui ketentuan di atas.</span>
                                                    <p class="text-gray-500 mt-1 leading-relaxed">Dengan mencentang kotak ini, saya bersedia melanjutkan proses pengajuan surat dengan data yang sebenarnya.</p>
                                                </div>
                                            </label>
                                            
                                            <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                                                <button type="button" 
                                                        @click="showModal = false"
                                                        class="w-full inline-flex justify-center items-center rounded-xl border border-gray-300 shadow-sm px-6 py-3 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:w-auto transition-all">
                                                    Batal
                                                </button>
                                                <button type="button" 
                                                        :disabled="!agreed || isSubmitting"
                                                        :class="{ 'opacity-50 cursor-not-allowed': !agreed || isSubmitting, 'hover:bg-primary-700 shadow-lg shadow-primary-500/30 hover:-translate-y-0.5': agreed && !isSubmitting }"
                                                        @click="if(agreed && !isSubmitting) { isSubmitting = true; showModal = false; $el.closest('form').submit(); }"
                                                        class="w-full inline-flex justify-center items-center rounded-xl border border-transparent px-6 py-3 bg-primary-600 text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:w-auto transition-all">
                                                    Lanjutkan & Kirim
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" 
                                    x-bind:disabled="isSubmitting"
                                    x-bind:class="{ 'opacity-70 cursor-not-allowed': isSubmitting }"
                                    class="w-full px-6 py-4 bg-primary-600 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:-translate-y-0.5 hover:bg-primary-700 hover:shadow-primary-500/40 transition-all text-lg flex items-center justify-center gap-2">
                                
                                <span x-show="!isSubmitting" class="flex items-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Kirim Pengajuan Surat
                                </span>
                                
                                <span x-show="isSubmitting" class="flex items-center gap-2" style="display: none;">
                                    <svg class="animate-spin -ml-1 mr-2 h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Mengunggah Berkas...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="h-full flex flex-col items-center justify-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-12 text-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Pilih Jenis Surat</h3>
                    <p class="text-gray-500 max-w-sm">Silakan klik salah satu jenis surat di panel sebelah kiri untuk mulai mengisi formulir pengajuan.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
