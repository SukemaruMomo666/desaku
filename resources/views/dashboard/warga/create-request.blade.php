@extends('layouts.app')

@section('header_title', 'Ajukan Surat Baru')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ isSubmitting: false, showModal: false, agreed: false, hasScrolledToBottom: false, showAlert: false }">

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
                    
                    <form id="form-pengajuan" 
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

                <!-- Modal Persetujuan Data Pribadi (Dipindah ke body menggunakan x-teleport) -->
                <template x-teleport="body">
                    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <!-- Background overlay -->
                            <div x-show="showModal" 
                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                                 class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>

                            <!-- This element is to trick the browser into centering the modal contents. -->
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal panel -->
                            <div x-show="showModal" 
                                 @click.away="showModal = false"
                                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                 class="inline-block align-bottom bg-white rounded-3xl text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full border border-gray-100 relative z-[101]">
                                
                                <div class="flex flex-col max-h-[85vh]">
                                    
                                    <!-- Header (Fixed) -->
                                    <div class="px-5 pt-6 sm:px-8 sm:pt-8 flex-shrink-0 text-center">
                                        <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-blue-50 border border-blue-100 mb-4">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        </div>
                                        <h3 class="text-xl leading-6 font-bold text-gray-900" id="modal-title">
                                            Persetujuan Penggunaan Data & Pelayanan
                                        </h3>
                                    </div>

                                    <!-- Body (Scrollable) -->
                                    <div class="mt-6 px-5 sm:px-8 flex-1 min-h-0 relative">
                                        <div class="h-full overflow-y-auto border border-gray-100 rounded-xl p-5 bg-gray-50 text-sm text-gray-600 text-left" 
                                             style="scrollbar-width: thin;"
                                             x-init="$nextTick(() => { if ($el.scrollHeight <= $el.clientHeight + 10) hasScrolledToBottom = true; })"
                                             @scroll="if ($el.scrollHeight - $el.scrollTop <= $el.clientHeight + 50) { hasScrolledToBottom = true; }">
                                             
                                            <div class="space-y-6">
                                                <div class="space-y-4">
                                                    <p class="leading-relaxed">Dengan menggunakan sistem pelayanan administrasi digital Pemerintah Kelurahan Sukapada dan mengajukan permohonan pelayanan melalui sistem tersebut, pemohon dengan ini menyatakan bahwa pemohon telah membaca, mengetahui, memahami, dan menyetujui seluruh ketentuan yang berkaitan dengan penggunaan data pribadi, penyampaian dokumen, pemeriksaan administrasi, proses verifikasi dan validasi, penyimpanan dokumen, keamanan informasi, serta pelaksanaan pelayanan administrasi sebagaimana diuraikan dalam ketentuan ini.</p>
                                                    <p class="leading-relaxed">Ketentuan ini merupakan bagian yang tidak terpisahkan dari proses pengajuan pelayanan administrasi secara elektronik pada Pemerintah Kelurahan Sukapada dan dimaksudkan untuk memberikan penjelasan mengenai hak, kewajiban, tanggung jawab, batasan penggunaan data, serta mekanisme pemrosesan informasi yang diberikan oleh pemohon selama proses pelayanan berlangsung.</p>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">1. KETENTUAN UMUM</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pemerintah Kelurahan Sukapada merupakan penyelenggara pelayanan administrasi kepada masyarakat sesuai dengan kewenangan, tugas, fungsi, dan ketentuan administrasi pemerintahan yang berlaku.</li>
                                                        <li>Pemohon adalah setiap orang yang mengajukan permohonan pelayanan administrasi melalui sistem pelayanan Pemerintah Kelurahan Sukapada, baik untuk kepentingan dirinya sendiri maupun dalam kapasitas yang sah untuk mewakili pihak lain.</li>
                                                        <li>Sistem pelayanan administrasi adalah sarana elektronik yang digunakan untuk menerima, mengelola, memproses, memverifikasi, mencatat, dan/atau menyampaikan informasi yang berkaitan dengan permohonan pelayanan masyarakat.</li>
                                                        <li>Data pribadi adalah setiap data dan/atau informasi yang berkaitan dengan seseorang yang dapat digunakan untuk mengidentifikasi orang tersebut, baik secara langsung maupun tidak langsung.</li>
                                                        <li>Dokumen pelayanan adalah seluruh dokumen, formulir, surat, identitas, foto, bukti pendukung, pernyataan, dan informasi lain yang diperlukan dalam rangka memenuhi persyaratan pelayanan.</li>
                                                        <li>Verifikasi adalah proses pemeriksaan terhadap kesesuaian informasi dan dokumen yang disampaikan oleh pemohon dengan persyaratan pelayanan yang telah ditentukan.</li>
                                                        <li>Validasi adalah proses pemeriksaan lebih lanjut untuk memastikan bahwa data, informasi, dan dokumen yang diberikan dapat diterima dan diproses sesuai dengan ketentuan administrasi yang berlaku.</li>
                                                        <li>Pemrosesan data meliputi kegiatan memperoleh, menerima, mencatat, mengklasifikasikan, menyimpan, memeriksa, menggunakan, menghubungkan, memperbarui, menampilkan, dan/atau menghapus data sesuai dengan kebutuhan pelayanan dan ketentuan yang berlaku.</li>
                                                        <li>Arsip pelayanan merupakan data dan/atau dokumen yang dihasilkan atau diterima dalam proses penyelenggaraan pelayanan administrasi dan dapat disimpan sesuai dengan ketentuan kearsipan.</li>
                                                        <li>Dengan melanjutkan proses pengajuan, pemohon dianggap telah memahami bahwa pelayanan administrasi membutuhkan pemrosesan data dan dokumen tertentu sebagai bagian dari pelaksanaan tugas pelayanan pemerintahan.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">2. TUJUAN PENGGUNAAN DATA DAN DOKUMEN</h4>
                                                    <p class="leading-relaxed mb-2">Data pribadi dan dokumen yang diberikan oleh pemohon digunakan untuk tujuan yang berkaitan dengan penyelenggaraan pelayanan administrasi, antara lain:</p>
                                                    <ol class="list-decimal list-outside ml-5 space-y-1 leading-relaxed">
                                                        <li>Identifikasi dan verifikasi identitas pemohon.</li>
                                                        <li>Pemeriksaan kelengkapan persyaratan administrasi.</li>
                                                        <li>Pemeriksaan kesesuaian antara data yang diinput dengan dokumen pendukung.</li>
                                                        <li>Verifikasi kebenaran informasi yang diberikan dalam formulir permohonan.</li>
                                                        <li>Pemrosesan permohonan surat atau layanan administrasi yang dipilih.</li>
                                                        <li>Pembuatan, penerbitan, pencatatan, dan pengarsipan dokumen pelayanan.</li>
                                                        <li>Pelaksanaan administrasi internal Pemerintah Kelurahan Sukapada.</li>
                                                        <li>Pemenuhan kewajiban administrasi pemerintahan.</li>
                                                        <li>Pelaksanaan pemeriksaan, audit, evaluasi, dan pengawasan pelayanan apabila diperlukan.</li>
                                                        <li>Penanganan pengaduan, keberatan, atau permasalahan yang berkaitan dengan pelayanan.</li>
                                                        <li>Pencegahan penyalahgunaan sistem pelayanan.</li>
                                                        <li>Pemenuhan kewajiban berdasarkan ketentuan peraturan perundang-undangan.</li>
                                                        <li>Keperluan lain yang secara langsung berkaitan dengan penyelenggaraan pelayanan administrasi pemerintahan.</li>
                                                    </ol>
                                                    <p class="leading-relaxed mt-2">Data yang diberikan tidak dimaksudkan untuk digunakan sebagai sarana pemasaran, perdagangan data, atau kepentingan komersial yang tidak berkaitan dengan penyelenggaraan pelayanan administrasi.</p>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">3. JENIS DATA DAN DOKUMEN YANG DAPAT DIPROSES</h4>
                                                    <p class="leading-relaxed mb-2">Dalam rangka pelaksanaan pelayanan, sistem dapat meminta dan/atau menerima beberapa jenis data dan dokumen yang relevan dengan kebutuhan layanan, termasuk namun tidak terbatas pada:</p>
                                                    <ol class="list-decimal list-outside ml-5 space-y-1 leading-relaxed">
                                                        <li>Nama lengkap.</li>
                                                        <li>Nomor identitas kependudukan atau identitas lainnya sesuai kebutuhan pelayanan.</li>
                                                        <li>Tempat dan tanggal lahir.</li>
                                                        <li>Jenis kelamin.</li>
                                                        <li>Alamat tempat tinggal.</li>
                                                        <li>Informasi kontak.</li>
                                                        <li>Data keluarga apabila dipersyaratkan.</li>
                                                        <li>Data pekerjaan apabila dipersyaratkan.</li>
                                                        <li>Data administrasi lainnya yang diperlukan.</li>
                                                        <li>Dokumen identitas.</li>
                                                        <li>Dokumen pendukung permohonan.</li>
                                                        <li>Surat pernyataan.</li>
                                                        <li>Foto atau dokumen visual lainnya apabila dipersyaratkan.</li>
                                                        <li>Dokumen hasil pengajuan atau dokumen lain yang berkaitan dengan pelayanan.</li>
                                                    </ol>
                                                    <p class="leading-relaxed mt-2">Jenis data yang diminta dapat berbeda sesuai dengan jenis pelayanan yang dipilih oleh pemohon.</p>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">4. PERSETUJUAN PEMROSESAN DATA</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pemohon memberikan persetujuan kepada Pemerintah Kelurahan Sukapada untuk memproses data dan dokumen yang diberikan dalam rangka penyelenggaraan pelayanan.</li>
                                                        <li>Persetujuan diberikan secara sadar setelah pemohon memperoleh informasi mengenai tujuan dan kebutuhan pemrosesan data melalui ketentuan yang tersedia pada sistem.</li>
                                                        <li>Pemohon memahami bahwa beberapa jenis data dan dokumen merupakan persyaratan administratif yang diperlukan agar permohonan dapat diproses.</li>
                                                        <li>Apabila pemohon tidak memberikan data atau dokumen yang diwajibkan, proses pelayanan dapat mengalami keterlambatan, tidak dapat diverifikasi, atau tidak dapat dilanjutkan apabila persyaratan tersebut merupakan persyaratan wajib.</li>
                                                        <li>Pemrosesan data dilakukan sebatas kebutuhan pelayanan dan sesuai dengan kewenangan serta ketentuan yang berlaku.</li>
                                                        <li>Pemerintah Kelurahan Sukapada berupaya memastikan bahwa data yang diproses memiliki keterkaitan dengan tujuan pelayanan yang sedang diajukan.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">5. KERAHASIAAN DATA DAN INFORMASI</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pemerintah Kelurahan Sukapada berkomitmen menjaga kerahasiaan data dan dokumen pemohon sesuai dengan ketentuan peraturan perundang-undangan.</li>
                                                        <li>Data pemohon tidak diperjualbelikan, disewakan, atau dimanfaatkan untuk kepentingan komersial yang tidak berhubungan dengan pelayanan administrasi.</li>
                                                        <li>Akses terhadap data dibatasi berdasarkan kebutuhan pelaksanaan tugas dan kewenangan masing-masing pihak yang terlibat dalam penyelenggaraan pelayanan.</li>
                                                        <li>Data dan dokumen hanya dapat diakses oleh pihak yang memiliki kepentingan dan kewenangan yang sah sesuai dengan tugasnya.</li>
                                                        <li>Dalam keadaan tertentu, data dapat diproses atau diberikan kepada pihak yang berwenang apabila diwajibkan oleh ketentuan hukum, proses pemeriksaan, audit, pengawasan, penegakan hukum, atau kepentingan pemerintahan yang sah.</li>
                                                        <li>Penggunaan dan pemberian akses terhadap data dilakukan dengan memperhatikan prinsip kehati-hatian, kebutuhan pelayanan, dan ketentuan perlindungan data yang berlaku.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">6. KEAMANAN SISTEM ELEKTRONIK</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pemerintah Kelurahan Sukapada berupaya menerapkan langkah pengamanan teknis dan administratif yang wajar untuk melindungi data yang diproses melalui sistem pelayanan.</li>
                                                        <li>Pengamanan dapat mencakup pengendalian akses, pembatasan hak pengguna, autentikasi, pencatatan aktivitas sistem, pencadangan data, serta mekanisme pengamanan lainnya sesuai dengan kemampuan dan kebutuhan sistem.</li>
                                                        <li>Pemohon memahami bahwa tidak terdapat sistem elektronik yang dapat menjamin keamanan secara mutlak terhadap seluruh bentuk gangguan, kesalahan teknis, serangan siber, atau keadaan di luar kendali penyelenggara.</li>
                                                        <li>Pemerintah Kelurahan Sukapada akan melakukan langkah yang wajar dan sesuai kewenangan untuk mencegah, menangani, dan meminimalkan dampak gangguan keamanan terhadap sistem pelayanan.</li>
                                                        <li>Pemohon wajib menjaga keamanan informasi akses miliknya dan tidak memberikan kata sandi, kode verifikasi, atau informasi autentikasi kepada pihak lain.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">7. KEABSAHAN DAN KEBENARAN DATA</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pemohon bertanggung jawab atas seluruh data dan informasi yang dimasukkan ke dalam sistem.</li>
                                                        <li>Pemohon menyatakan bahwa informasi yang diberikan adalah benar, lengkap, akurat, dan sesuai dengan kondisi sebenarnya.</li>
                                                        <li>Pemohon bertanggung jawab terhadap keaslian, keabsahan, dan legalitas dokumen yang diunggah.</li>
                                                        <li>Pemohon dilarang memberikan dokumen palsu, dokumen hasil manipulasi, dokumen yang telah diubah secara tidak sah, atau dokumen milik orang lain tanpa kewenangan.</li>
                                                        <li>Pemohon wajib melakukan pemeriksaan kembali terhadap data sebelum permohonan dikirimkan.</li>
                                                        <li>Kesalahan data yang berasal dari kelalaian pemohon dapat menyebabkan proses pelayanan tertunda atau dokumen pelayanan yang diterbitkan tidak sesuai dengan data yang seharusnya.</li>
                                                        <li>Apabila ditemukan indikasi pemalsuan, manipulasi, atau pemberian keterangan yang tidak benar, Pemerintah Kelurahan Sukapada dapat melakukan pemeriksaan lebih lanjut dan mengambil tindakan administratif sesuai kewenangan dan ketentuan yang berlaku.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">8. KEWAJIBAN PEMOHON</h4>
                                                    <p class="leading-relaxed mb-2">Pemohon berkewajiban:</p>
                                                    <ol class="list-[lower-alpha] list-outside ml-5 space-y-1 leading-relaxed">
                                                        <li>Mengisi seluruh formulir secara benar dan lengkap;</li>
                                                        <li>Menggunakan data pribadi milik sendiri atau memiliki kewenangan yang sah apabila bertindak sebagai perwakilan;</li>
                                                        <li>Mengunggah dokumen sesuai dengan persyaratan pelayanan;</li>
                                                        <li>Memastikan dokumen dapat dibaca dengan jelas;</li>
                                                        <li>Memastikan dokumen tidak rusak, terpotong, atau tidak sesuai;</li>
                                                        <li>Memastikan informasi yang diberikan konsisten antara formulir dan dokumen pendukung;</li>
                                                        <li>Menjaga kerahasiaan akun dan informasi autentikasi;</li>
                                                        <li>Tidak memberikan akses akun kepada pihak yang tidak berwenang;</li>
                                                        <li>Melakukan koreksi terhadap kesalahan data apabila diketahui sebelum proses pelayanan selesai;</li>
                                                        <li>Memberikan informasi tambahan apabila diperlukan dalam proses verifikasi;</li>
                                                        <li>Mengikuti prosedur pelayanan yang ditetapkan;</li>
                                                        <li>Tidak menggunakan sistem untuk melakukan tindakan yang mengganggu keamanan atau operasional sistem; dan</li>
                                                        <li>Mematuhi seluruh ketentuan administrasi yang berlaku.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">9. VERIFIKASI DAN VALIDASI</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Setiap permohonan dapat melalui tahapan pemeriksaan administratif.</li>
                                                        <li>Pemeriksaan dilakukan untuk memastikan bahwa data dan dokumen memenuhi persyaratan pelayanan.</li>
                                                        <li>Pemerintah Kelurahan Sukapada dapat meminta klarifikasi atau dokumen tambahan apabila diperlukan.</li>
                                                        <li>Permohonan dapat dikembalikan kepada pemohon apabila terdapat kekurangan data atau dokumen.</li>
                                                        <li>Permohonan dapat ditunda sampai pemohon melengkapi persyaratan yang diperlukan.</li>
                                                        <li>Permohonan dapat ditolak apabila tidak memenuhi ketentuan atau persyaratan yang berlaku.</li>
                                                        <li>Pengajuan melalui sistem elektronik tidak secara otomatis berarti bahwa permohonan telah disetujui.</li>
                                                        <li>Persetujuan atau penerbitan dokumen hanya dilakukan setelah proses pemeriksaan sesuai prosedur pelayanan selesai.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">10. PENYIMPANAN DAN ARSIP DATA</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Data dan dokumen yang telah disampaikan dapat disimpan sebagai bagian dari administrasi pelayanan.</li>
                                                        <li>Penyimpanan dilakukan sesuai dengan kebutuhan operasional, ketentuan kearsipan, serta ketentuan hukum yang berlaku.</li>
                                                        <li>Data dapat tetap tercatat dalam arsip administrasi meskipun proses pelayanan telah selesai apabila terdapat kewajiban penyimpanan berdasarkan ketentuan yang berlaku.</li>
                                                        <li>Pemohon memahami bahwa dokumen pelayanan dapat diperlukan kembali untuk keperluan administrasi, pemeriksaan, audit, pembuktian, atau pelayanan lanjutan yang sah.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">11. PEMBATASAN TANGGUNG JAWAB PEMOHON</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pemohon bertanggung jawab atas kebenaran data dan dokumen yang diberikan.</li>
                                                        <li>Apabila pemohon dengan sengaja atau karena kelalaiannya memberikan informasi yang tidak benar, maka segala konsekuensi administratif dan/atau hukum yang timbul dapat menjadi tanggung jawab pemohon sesuai ketentuan yang berlaku.</li>
                                                        <li>Pemerintah Kelurahan Sukapada tidak bertanggung jawab atas kerugian yang timbul semata-mata akibat data, dokumen, atau informasi yang diberikan pemohon ternyata tidak benar, tidak lengkap, palsu, atau tidak sah.</li>
                                                        <li>Ketentuan mengenai tanggung jawab tersebut tetap tunduk pada batasan dan ketentuan tanggung jawab yang ditetapkan oleh peraturan perundang-undangan.</li>
                                                        <li>Tidak ada ketentuan dalam persetujuan ini yang dimaksudkan untuk menghilangkan hak pemohon yang diberikan berdasarkan hukum yang berlaku.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">12. GANGGUAN SISTEM DAN KEADAAN DI LUAR KENDALI</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pelayanan elektronik dapat mengalami gangguan akibat pemeliharaan sistem, gangguan jaringan internet, gangguan pusat data, kegagalan perangkat, gangguan listrik, bencana, serangan siber, atau kondisi teknis lainnya.</li>
                                                        <li>Dalam keadaan tersebut, proses pelayanan dapat mengalami keterlambatan.</li>
                                                        <li>Pemerintah Kelurahan Sukapada akan melakukan upaya yang wajar untuk memulihkan pelayanan dalam waktu yang memungkinkan.</li>
                                                        <li>Pemohon diharapkan tidak mengirimkan permohonan berulang kali apabila sistem sedang mengalami gangguan, kecuali terdapat instruksi dari petugas pelayanan.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">13. LARANGAN PENYALAHGUNAAN SISTEM</h4>
                                                    <p class="leading-relaxed mb-2">Pemohon dilarang menggunakan sistem pelayanan untuk:</p>
                                                    <ol class="list-decimal list-outside ml-5 space-y-1 leading-relaxed">
                                                        <li>Memasukkan data palsu.</li>
                                                        <li>Mengunggah dokumen yang tidak sah.</li>
                                                        <li>Menggunakan identitas orang lain tanpa hak.</li>
                                                        <li>Mengakses data pengguna lain.</li>
                                                        <li>Mencoba memperoleh akses ke bagian sistem yang tidak diperuntukkan bagi pemohon.</li>
                                                        <li>Mengganggu kinerja sistem.</li>
                                                        <li>Melakukan tindakan yang dapat merusak keamanan sistem.</li>
                                                        <li>Menggunakan sistem untuk kegiatan yang bertentangan dengan hukum.</li>
                                                        <li>Melakukan tindakan lain yang dapat menghambat penyelenggaraan pelayanan masyarakat.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">14. PERNYATAAN PEMOHON</h4>
                                                    <p class="leading-relaxed mb-2">Dengan melanjutkan proses pengajuan pelayanan, pemohon menyatakan bahwa:</p>
                                                    <ol class="list-decimal list-outside ml-5 space-y-1 leading-relaxed">
                                                        <li>Pemohon telah membaca ketentuan ini.</li>
                                                        <li>Pemohon telah memperoleh kesempatan untuk memahami informasi yang disampaikan.</li>
                                                        <li>Pemohon memahami tujuan penggunaan data yang diberikan.</li>
                                                        <li>Pemohon memahami bahwa data tertentu diperlukan sebagai persyaratan pelayanan.</li>
                                                        <li>Pemohon menyatakan bahwa data yang diberikan adalah benar.</li>
                                                        <li>Pemohon menyatakan bahwa dokumen yang diberikan adalah sah dan dapat dipertanggungjawabkan.</li>
                                                        <li>Pemohon memahami bahwa data dan dokumen dapat melalui proses verifikasi dan validasi.</li>
                                                        <li>Pemohon memahami bahwa permohonan tidak otomatis disetujui hanya karena telah dikirim melalui sistem.</li>
                                                        <li>Pemohon bersedia memberikan klarifikasi apabila diperlukan.</li>
                                                        <li>Pemohon bersedia melengkapi dokumen apabila terdapat kekurangan.</li>
                                                        <li>Pemohon memahami bahwa pemberian informasi palsu dapat menimbulkan konsekuensi administratif maupun hukum.</li>
                                                        <li>Pemohon memahami ketentuan mengenai keamanan dan kerahasiaan data.</li>
                                                        <li>Pemohon memahami bahwa sebagian data dapat disimpan sebagai arsip administrasi.</li>
                                                        <li>Pemohon menyetujui pemrosesan data sepanjang dilakukan untuk kepentingan pelayanan dan sesuai dengan ketentuan yang berlaku.</li>
                                                        <li>Pemohon bersedia mengikuti prosedur pelayanan Pemerintah Kelurahan Sukapada.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">15. KETENTUAN MENGENAI PERUBAHAN DATA</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Apabila terdapat perubahan informasi yang relevan dengan proses pelayanan, pemohon wajib menyampaikan perubahan tersebut melalui mekanisme yang tersedia.</li>
                                                        <li>Pemohon bertanggung jawab untuk memastikan bahwa informasi yang digunakan dalam proses pelayanan merupakan informasi yang terbaru dan benar.</li>
                                                        <li>Pemerintah Kelurahan Sukapada dapat meminta pembaruan atau klarifikasi data apabila diperlukan untuk memastikan kesesuaian administrasi.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">16. PENGADUAN DAN KLARIFIKASI</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Pemohon dapat menyampaikan pertanyaan, klarifikasi, atau pengaduan melalui saluran pelayanan resmi yang disediakan oleh Pemerintah Kelurahan Sukapada.</li>
                                                        <li>Pengaduan akan ditangani sesuai dengan mekanisme dan prosedur pelayanan yang berlaku.</li>
                                                        <li>Pemohon diharapkan memberikan informasi yang lengkap ketika menyampaikan pengaduan agar proses pemeriksaan dapat dilakukan secara efektif.</li>
                                                        <li>Pengaduan yang berkaitan dengan keamanan data akan ditangani dengan memperhatikan aspek kerahasiaan dan perlindungan informasi.</li>
                                                    </ol>
                                                </div>

                                                <div>
                                                    <h4 class="font-bold text-gray-900 mb-2 text-base">17. KETENTUAN PENUTUP</h4>
                                                    <ol class="list-decimal list-outside ml-5 space-y-2 leading-relaxed">
                                                        <li>Ketentuan ini merupakan bagian dari proses pelayanan administrasi digital Pemerintah Kelurahan Sukapada.</li>
                                                        <li>Dengan mencentang kotak persetujuan, pemohon menyatakan telah membaca, memahami, dan menyetujui ketentuan yang tercantum dalam dokumen ini.</li>
                                                        <li>Apabila terdapat ketentuan dalam dokumen ini yang kemudian dinyatakan tidak berlaku berdasarkan ketentuan hukum, ketentuan lainnya tetap berlaku sepanjang tidak bertentangan dengan peraturan perundang-undangan.</li>
                                                        <li>Pemerintah Kelurahan Sukapada dapat melakukan penyesuaian terhadap ketentuan pelayanan apabila terdapat perubahan prosedur, sistem, kebijakan administrasi, atau ketentuan peraturan perundang-undangan.</li>
                                                        <li>Setiap perubahan ketentuan yang bersifat material akan disampaikan melalui media atau sistem pelayanan yang sesuai.</li>
                                                        <li>Hal-hal yang belum diatur secara khusus dalam ketentuan ini akan mengikuti prosedur pelayanan dan ketentuan peraturan perundang-undangan yang berlaku.</li>
                                                    </ol>
                                                </div>
                                                
                                                <div class="pt-6 border-t border-gray-200">
                                                    <h4 class="font-bold text-gray-900 mb-4 text-base text-center">PERNYATAAN PERSETUJUAN PEMOHON</h4>
                                                    <div class="space-y-4">
                                                        <p class="leading-relaxed font-medium">Dengan mencentang kotak di bawah ini, saya menyatakan bahwa saya telah membaca, memahami, dan menyetujui seluruh ketentuan mengenai penggunaan data pribadi, keabsahan dokumen, proses verifikasi dan validasi, keamanan informasi, penyimpanan arsip, kewajiban pemohon, serta ketentuan pengajuan pelayanan administrasi Pemerintah Kelurahan Sukapada.</p>
                                                        <p class="leading-relaxed font-medium">Saya menyatakan bahwa seluruh data, informasi, dan dokumen yang saya berikan adalah benar, lengkap, sah, tidak dimanipulasi, dan dapat dipertanggungjawabkan. Saya memahami bahwa Pemerintah Kelurahan Sukapada dapat melakukan pemeriksaan, verifikasi, validasi, pencatatan, penyimpanan, dan pemrosesan data yang saya berikan sepanjang diperlukan untuk penyelenggaraan pelayanan administrasi dan sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</p>
                                                        <p class="leading-relaxed font-medium">Saya juga memahami bahwa pemberian data atau dokumen yang tidak benar, tidak lengkap, palsu, atau diperoleh tanpa hak dapat menyebabkan permohonan tidak dapat diproses serta dapat menimbulkan konsekuensi administratif dan/atau hukum sesuai dengan ketentuan yang berlaku.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Footer (Fixed) -->
                                    <div class="mt-6 bg-gray-50 px-5 py-5 sm:px-8 sm:py-6 border-t border-gray-100 flex-shrink-0 rounded-b-3xl">
                                        
                                        <!-- Alert Message -->
                                        <div x-show="showAlert" 
                                             x-transition:enter="transition ease-out duration-300"
                                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                                             x-transition:enter-end="opacity-100 transform translate-y-0"
                                             x-transition:leave="transition ease-in duration-200"
                                             x-transition:leave-start="opacity-100 transform translate-y-0"
                                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                                             class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-start gap-3">
                                            <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            <div class="text-sm font-medium">
                                                Silakan gulir (scroll) dokumen persyaratan di atas sampai ke paling bawah terlebih dahulu sebelum menyetujui.
                                            </div>
                                        </div>

                                        <label class="flex items-start gap-4" :class="hasScrolledToBottom ? 'cursor-pointer group' : 'cursor-pointer'">
                                            <div class="flex items-center h-6 mt-0.5" @click="if(!hasScrolledToBottom) { showAlert = true; setTimeout(() => showAlert = false, 4000); }">
                                                <input type="checkbox" 
                                                       x-model="agreed" 
                                                       @click="if(!hasScrolledToBottom) { $event.preventDefault(); }"
                                                       @change="if(!hasScrolledToBottom) { agreed = false; }"
                                                       class="w-5 h-5 text-primary-600 bg-white border-gray-300 rounded focus:ring-primary-500 focus:ring-offset-gray-50 transition-colors shadow-sm cursor-pointer"
                                                       :class="!hasScrolledToBottom ? 'opacity-50' : ''">
                                            </div>
                                            <div class="text-sm" @click.prevent="if(!hasScrolledToBottom) { showAlert = true; setTimeout(() => showAlert = false, 4000); } else { agreed = !agreed; }">
                                                <span class="font-bold text-gray-900 transition-colors" :class="hasScrolledToBottom ? 'group-hover:text-primary-600' : ''">Saya telah membaca, memahami, dan menyetujui ketentuan di atas.</span>
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
                                                    :disabled="!agreed || isSubmitting || !hasScrolledToBottom"
                                                    :class="{ 'opacity-50 cursor-not-allowed': !agreed || isSubmitting || !hasScrolledToBottom, 'hover:bg-primary-700 shadow-lg shadow-primary-500/30 hover:-translate-y-0.5': agreed && !isSubmitting && hasScrolledToBottom }"
                                                    @click="if(agreed && !isSubmitting && hasScrolledToBottom) { isSubmitting = true; showModal = false; document.getElementById('form-pengajuan').submit(); }"
                                                    class="w-full inline-flex justify-center items-center rounded-xl border border-transparent px-6 py-3 bg-primary-600 text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:w-auto transition-all">
                                                Lanjutkan & Kirim
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
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
