<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="view-transition" content="same-origin" />
    <title>Daftar - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-800 bg-white overflow-x-hidden">
    
    <div class="min-h-screen flex">
        
        <!-- Bagian Kiri (Gambar & Dekorasi) -->
        <div class="hidden lg:flex lg:w-5/12 bg-primary-900 relative items-center justify-center overflow-hidden" style="view-transition-name: auth-hero;">
            <!-- Gambar Desa dari Unsplash -->
            <img src="https://images.unsplash.com/photo-1588614959060-4d144f28b207?q=80&w=1500&auto=format&fit=crop" 
                 alt="Pemandangan Desa" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay">
            
            <!-- Overlay Gradient -->
            <div class="absolute inset-0 bg-gradient-to-t from-secondary-950/90 via-secondary-900/40 to-transparent"></div>
            
            <!-- Teks -->
            <div class="relative z-10 p-12 text-white max-w-lg">
                <a href="/" class="inline-flex items-center gap-3 mb-10 group">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-lg flex items-center justify-center group-hover:bg-white/30 transition-colors">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </div>
                    <span class="font-medium">Kembali ke Beranda</span>
                </a>
                
                <h1 class="text-4xl font-extrabold tracking-tight mb-4 leading-tight">Portal Digital<br>Layanan Desa</h1>
                <p class="text-primary-100 text-lg leading-relaxed">
                    Sistem persuratan elektronik terpadu. Urus administrasi kependudukan lebih cepat, transparan, dan mudah tanpa harus antre di Balai Desa.
                </p>
                
                <div class="mt-12 flex items-center gap-4">
                    <div class="flex -space-x-4">
                        <div class="w-10 h-10 rounded-full border-2 border-primary-900 bg-gray-200"><img src="https://ui-avatars.com/api/?name=Warga+1&background=random" class="rounded-full"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-primary-900 bg-gray-200"><img src="https://ui-avatars.com/api/?name=Warga+2&background=random" class="rounded-full"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-primary-900 bg-gray-200"><img src="https://ui-avatars.com/api/?name=Warga+3&background=random" class="rounded-full"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-primary-900 bg-primary-600 flex items-center justify-center text-xs font-bold">+1k</div>
                    </div>
                    <p class="text-sm font-medium text-primary-200">Bergabung dengan ribuan warga lainnya.</p>
                </div>
            </div>
        </div>

        <!-- Bagian Kanan (Form) -->
        <div class="w-full lg:w-7/12 flex flex-col justify-center py-12 px-6 sm:px-12 lg:px-24 xl:px-32 relative overflow-y-auto overflow-x-hidden bg-gray-50/50" style="view-transition-name: auth-panel;">
            
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>

            <div class="w-full max-w-md mx-auto relative z-10">
                <div class="text-center lg:text-left mb-10">
                    <div class="lg:hidden flex justify-center mb-6">
                        <div class="w-14 h-14 bg-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    </div>
                    <h2 class="text-3xl font-extrabold text-secondary-950 tracking-tight">Buat Akun Baru</h2>
                    <p class="text-gray-500 mt-2">Daftarkan diri Anda untuk mengakses layanan.</p>
                </div>

                <div class="bg-white p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="nik" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                            <input id="nik" type="tel" name="nik" value="{{ old('nik') }}" required autofocus
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                                placeholder="Harus 16 Digit Angka" maxlength="16">
                            @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap (Sesuai KTP)</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                                placeholder="Cth: Budi Santoso">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor WhatsApp Aktif</label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                                placeholder="Cth: 08123456789">
                            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div x-data="{ show: false, password: '', strength: 0, 
                            checkStrength() {
                                let s = 0;
                                if(this.password.length >= 8) s += 1;
                                if(/[A-Z]/.test(this.password)) s += 1;
                                if(/[0-9]/.test(this.password)) s += 1;
                                if(/[^A-Za-z0-9]/.test(this.password)) s += 1;
                                this.strength = s;
                            }
                        }">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi</label>
                            <div class="relative">
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                    x-model="password" @input="checkStrength()"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                                    placeholder="Minimal 8 karakter (Huruf besar & angka)">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.058-5.058m1.21-1.21A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.21 3.51m-1.92 1.92A10.05 10.05 0 0115 15a3 3 0 01-4.24-4.24m-1.63 1.63L3 3m18 18l-18-18"></path></svg>
                                </button>
                            </div>
                            
                            <!-- Password Strength Indicator -->
                            <div class="mt-2 flex gap-1 h-1.5" x-show="password.length > 0">
                                <div class="flex-1 rounded-full transition-colors duration-300" :class="strength >= 1 ? (strength >= 3 ? 'bg-green-500' : (strength >= 2 ? 'bg-yellow-400' : 'bg-red-500')) : 'bg-gray-200'"></div>
                                <div class="flex-1 rounded-full transition-colors duration-300" :class="strength >= 2 ? (strength >= 3 ? 'bg-green-500' : 'bg-yellow-400') : 'bg-gray-200'"></div>
                                <div class="flex-1 rounded-full transition-colors duration-300" :class="strength >= 3 ? 'bg-green-500' : 'bg-gray-200'"></div>
                            </div>
                            <p class="text-xs mt-1 font-medium transition-colors duration-300" x-show="password.length > 0"
                               :class="strength >= 3 ? 'text-green-600' : (strength >= 2 ? 'text-yellow-600' : 'text-red-500')"
                               x-text="strength >= 3 ? 'Sandi Kuat' : (strength >= 2 ? 'Sandi Sedang' : 'Sandi Lemah')">
                            </p>

                            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div x-data="{ show: false }">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                            <div class="relative">
                                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                                    placeholder="Ulangi kata sandi">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.058-5.058m1.21-1.21A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.21 3.51m-1.92 1.92A10.05 10.05 0 0115 15a3 3 0 01-4.24-4.24m-1.63 1.63L3 3m18 18l-18-18"></path></svg>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-primary-500/30 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                Daftar Sekarang
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-8 text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="{{ route('login') }}" class="font-bold text-primary-600 hover:text-primary-500 transition-colors ml-1">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
