@extends('layouts.app')

@section('header_title', 'Profil Lengkap')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Header Section -->
    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Lengkapi Profil Anda</h2>
        <p class="text-gray-500 mt-2 text-sm leading-relaxed max-w-2xl">
            Harap isi data diri Anda dengan lengkap dan sebenar-benarnya sesuai KTP. Data ini akan <span class="font-bold text-primary-600">otomatis digunakan</span> untuk mengisi formulir setiap kali Anda mengajukan surat baru.
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-100 text-green-700 p-4 rounded-xl flex items-center gap-3 mb-6 shadow-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            <span class="font-bold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-700 p-4 rounded-xl flex items-start gap-3 mb-6 shadow-sm">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <div>
                <span class="font-bold text-sm block mb-1">Ada kesalahan pengisian data:</span>
                <ul class="list-disc pl-5 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('citizen.profile.update') }}" method="POST" class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-6 sm:p-8 space-y-8">
            
            <!-- Identitas Utama -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    Identitas Utama
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all font-mono" placeholder="16 Digit NIK Anda" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nomor Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" value="{{ old('no_kk', $user->no_kk) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all font-mono" placeholder="16 Digit No. KK Anda" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Nama Sesuai KTP" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Tempat Lahir</label>
                        <input type="text" name="place_of_birth" value="{{ old('place_of_birth', $user->place_of_birth) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Kota/Kabupaten Tempat Lahir" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" required>
                    </div>
                </div>
            </div>

            <!-- Data Diri Tambahan -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Jenis Kelamin</label>
                        <select name="gender" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" required>
                            <option value="">Pilih Jenis Kelamin...</option>
                            <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Agama</label>
                        <select name="religion" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" required>
                            <option value="">Pilih Agama...</option>
                            @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu', 'Kepercayaan Lainnya'] as $agama)
                                <option value="{{ $agama }}" {{ old('religion', $user->religion) == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Status Perkawinan</label>
                        <select name="marital_status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" required>
                            <option value="">Pilih Status...</option>
                            @foreach(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'] as $status)
                                <option value="{{ $status }}" {{ old('marital_status', $user->marital_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Pekerjaan</label>
                        <input type="text" name="job" value="{{ old('job', $user->job) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Sesuai di KTP" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Kewarganegaraan</label>
                        <select name="nationality" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" required>
                            <option value="WNI" {{ old('nationality', $user->nationality ?? 'WNI') == 'WNI' ? 'selected' : '' }}>WNI (Warga Negara Indonesia)</option>
                            <option value="WNA" {{ old('nationality', $user->nationality) == 'WNA' ? 'selected' : '' }}>WNA (Warga Negara Asing)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Kontak & Alamat -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-3 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Kontak & Alamat Lengkap
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-900 mb-2">Alamat Domisili (Jalan/Dusun)</label>
                        <textarea name="address" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all resize-none" placeholder="Isi alamat lengkap (contoh: Jl. Merdeka No. 10 atau Dusun Manis)" required>{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">RT</label>
                        <input type="text" name="rt" value="{{ old('rt', $user->rt) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Contoh: 001" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">RW</label>
                        <input type="text" name="rw" value="{{ old('rw', $user->rw) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Contoh: 002" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Kelurahan</label>
                        <input type="text" name="village" value="{{ old('village', $user->village) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Sesuai KTP" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Kecamatan</label>
                        <input type="text" name="district" value="{{ old('district', $user->district) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Sesuai KTP" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Kabupaten/Kota</label>
                        <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Sesuai KTP" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-900 mb-2">Provinsi</label>
                        <input type="text" name="province" value="{{ old('province', $user->province) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all" placeholder="Sesuai KTP" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-gray-900 mb-2">Nomor WhatsApp Aktif</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-all font-mono" placeholder="Misal: 08123456789" required>
                        <p class="text-xs text-gray-500 mt-1">Sangat penting untuk menerima notifikasi status pengajuan surat.</p>
                    </div>
                </div>
            </div>

        </div>

        <div class="px-6 sm:px-8 py-5 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-4">
            <button type="submit" class="px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 hover:-translate-y-0.5 transition-all">
                Simpan Profil Saya
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
@if(session('profile_incomplete_alert'))
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Lengkapi Profil Anda',
            text: 'Untuk membuat pengajuan surat, Anda harus melengkapi seluruh data KTP (termasuk RT/RW, Tempat Lahir, dll) pada form di bawah ini.',
            confirmButtonText: 'Baik, Mengerti',
            confirmButtonColor: '#3b82f6',
            allowOutsideClick: false,
        });
    });
</script>
@endif
@endpush
