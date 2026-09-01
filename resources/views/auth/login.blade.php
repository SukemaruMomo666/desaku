<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="view-transition" content="same-origin" />
    <title>Login - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('logo-gerilya.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-800 bg-white overflow-x-hidden">
    
    <div class="min-h-screen flex">
        
        <!-- Bagian Kanan (Gambar & Dekorasi) - Dibalik posisinya untuk Login -->
        <div class="hidden lg:flex lg:w-1/2 bg-secondary-950 relative items-center justify-center overflow-hidden order-2" style="view-transition-name: auth-hero;">
            <!-- Gambar Desa dari Unsplash -->
            <img src="https://images.unsplash.com/photo-1596404981149-65b16982e0e4?q=80&w=1500&auto=format&fit=crop" 
                 alt="Pemandangan Kelurahan" 
                 class="absolute inset-0 w-full h-full object-cover opacity-30 mix-blend-overlay">
            
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-secondary-950/90"></div>
            
            <!-- Teks -->
            <div class="relative z-10 p-12 text-center text-white max-w-lg mt-32">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-md rounded-3xl mb-8 border border-white/20 shadow-2xl">
                    <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold tracking-tight mb-4">Keamanan Data Terjamin</h2>
                <p class="text-gray-300 text-lg">
                    Data kependudukan Anda dienkripsi dan disimpan dengan aman. Sistem kami hanya dapat diakses oleh pihak yang berwenang.
                </p>
            </div>
        </div>

        <!-- Bagian Kiri (Form) -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center py-12 px-6 sm:px-12 lg:px-24 xl:px-32 relative bg-gray-50/50 order-1" style="view-transition-name: auth-panel;">
            
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-96 h-96 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50"></div>
            </div>

            <div class="w-full max-w-md mx-auto relative z-10">
                <a href="/" class="inline-flex items-center gap-2 text-sm font-bold text-gray-600 bg-white border border-gray-200 px-4 py-2 rounded-full hover:bg-gray-50 hover:text-primary-600 hover:border-primary-200 transition-all shadow-sm mb-12 w-max group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Beranda
                </a>

                <div class="mb-10 text-center lg:text-left">
                    <div class="lg:hidden flex justify-center mb-6">
                        <img src="{{ asset('logo-gerilya.png') }}" alt="Griliya Kelurahan Sukapada" class="h-14 w-auto">
                    </div>
                    <h2 class="text-3xl font-extrabold text-secondary-950 tracking-tight">Selamat Datang!</h2>
                    <p class="text-gray-500 mt-2">Masuk untuk melanjutkan ke dashboard layanan.</p>
                </div>

                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100">
                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Induk Kependudukan (NIK)</label>
                            <input id="nik" type="tel" name="nik" value="{{ old('nik') }}" required autofocus
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                                placeholder="Masukkan 16 Digit NIK"
                                maxlength="16">
                            @error('nik')
                                <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div x-data="{ show: false }">
                            <div class="flex justify-between items-center mb-1">
                                <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                                <a href="#" class="text-xs font-semibold text-primary-600 hover:text-primary-700">Lupa sandi?</a>
                            </div>
                            <div class="relative">
                                <input id="password" :type="show ? 'text' : 'password'" name="password" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none"
                                    placeholder="••••••••">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 015.058-5.058m1.21-1.21A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.21 3.51m-1.92 1.92A10.05 10.05 0 0115 15a3 3 0 01-4.24-4.24m-1.63 1.63L3 3m18 18l-18-18"></path></svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-2 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-primary-500/30 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                                Masuk Sistem
                            </button>
                        </div>
                    </form>
                </div>

                <div class="mt-8 text-center text-sm text-gray-600">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-primary-600 hover:text-primary-500 transition-colors ml-1">Buat akun sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
