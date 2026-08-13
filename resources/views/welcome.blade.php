<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sistem Persuratan Desa') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800 selection:bg-primary-500 selection:text-white flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center shadow-lg shadow-primary-500/30">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-secondary-900 leading-tight tracking-tight">Portal <span class="text-primary-600">Desa</span></h1>
                        <p class="text-xs text-gray-500 font-medium tracking-wider uppercase">Layanan Mandiri Warga</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8">
                    <a href="#" class="text-sm font-semibold text-primary-600 border-b-2 border-primary-600 pb-1">Beranda</a>
                    <a href="#panduan" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors pb-1">Panduan</a>
                    <a href="#kontak" class="text-sm font-medium text-gray-500 hover:text-gray-900 transition-colors pb-1">Kontak</a>
                </nav>

                <!-- Auth Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-gray-700 hover:text-primary-600 transition-colors">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-700 hover:text-primary-600 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white transition-all bg-primary-600 rounded-full shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Daftar Sekarang
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="text-gray-500 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Hero Section -->
        <section class="relative bg-white overflow-hidden">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>
            <div class="absolute -top-40 -right-40 w-96 h-96 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
            <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-secondary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-24 lg:pt-32 lg:pb-32">
                <div class="text-center max-w-3xl mx-auto">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-50 text-primary-700 text-sm font-semibold mb-6 border border-primary-100">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-primary-500"></span>
                        </span>
                        Sistem Layanan Buka 24/7
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-secondary-950 tracking-tight leading-tight mb-8">
                        Urus Surat Desa <br class="hidden md:block" />
                        Lebih <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">Mudah & Cepat</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-500 mb-10 leading-relaxed">
                        Tidak perlu lagi antre panjang. Ajukan permohonan surat keterangan dari rumah, pantau statusnya, dan ambil ke balai desa hanya saat surat sudah siap.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="/login" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-secondary-900 transition-all bg-accent hover:bg-accent-hover rounded-full shadow-lg shadow-accent/30 hover:-translate-y-1">
                            Ajukan Surat Sekarang
                            <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <a href="#panduan" class="inline-flex items-center justify-center px-8 py-3.5 text-base font-semibold text-gray-700 transition-all bg-white border-2 border-gray-200 rounded-full hover:border-gray-300 hover:bg-gray-50">
                            Lihat Panduan
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Panduan / Alur Section -->
        <section id="panduan" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-secondary-900 mb-4">Bagaimana Caranya?</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">Empat langkah mudah untuk mendapatkan surat keterangan yang Anda butuhkan tanpa ribet.</p>
                </div>

                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Step 1 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 relative group border border-gray-100">
                        <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-6 text-xl font-bold group-hover:scale-110 transition-transform">1</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Daftar / Login</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Buat akun menggunakan NIK Anda atau masuk jika sudah pernah mendaftar sebelumnya.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 relative group border border-gray-100">
                        <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center mb-6 text-xl font-bold group-hover:scale-110 transition-transform">2</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Pilih Surat</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Pilih jenis surat yang dibutuhkan dan lengkapi formulir isian serta upload dokumen syarat pendukung.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 relative group border border-gray-100">
                        <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-6 text-xl font-bold group-hover:scale-110 transition-transform">3</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Proses Verifikasi</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Perangkat desa akan memverifikasi data Anda. Anda bisa memantau statusnya langsung dari dashboard.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 relative group border border-gray-100">
                        <div class="w-14 h-14 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mb-6 text-xl font-bold group-hover:scale-110 transition-transform">4</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Ambil Surat</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Anda akan menerima notifikasi WA jika surat siap. Datang ke balai desa untuk mengambil dokumen aslinya.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layanan Surat Section -->
        <section class="py-20 bg-white border-y border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-secondary-900 mb-12">Layanan Surat Tersedia</h2>
                <div class="flex flex-wrap justify-center gap-4">
                    <span class="px-6 py-3 bg-gray-50 text-gray-700 rounded-full font-medium border border-gray-200">Surat Keterangan Tidak Mampu (SKTM)</span>
                    <span class="px-6 py-3 bg-gray-50 text-gray-700 rounded-full font-medium border border-gray-200">Surat Keterangan Usaha (SKU)</span>
                    <span class="px-6 py-3 bg-gray-50 text-gray-700 rounded-full font-medium border border-gray-200">Keterangan Domisili</span>
                    <span class="px-6 py-3 bg-gray-50 text-gray-700 rounded-full font-medium border border-gray-200">Pengantar KTP/KK</span>
                    <span class="px-6 py-3 bg-gray-50 text-gray-700 rounded-full font-medium border border-gray-200">Keterangan Kelahiran / Kematian</span>
                    <span class="px-6 py-3 bg-gray-50 text-primary-600 rounded-full font-semibold border border-primary-200 bg-primary-50">Dan Lain-lain...</span>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer id="kontak" class="bg-secondary-950 text-white pt-16 pb-8 border-t-4 border-primary-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <!-- Tentang -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 bg-primary-500 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight">Portal <span class="text-primary-400">Desa</span></h3>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed pr-4">
                        Inovasi digital untuk mempermudah warga dalam mengurus administrasi kependudukan. Cepat, transparan, dan efisien.
                    </p>
                </div>
                <!-- Kontak -->
                <div>
                    <h4 class="text-lg font-bold mb-6 text-gray-100">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-primary-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Raya Pemdes No. 1, Kecamatan Contoh, Kabupaten Teladan 12345</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>(021) 1234-5678</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>layanan@desa.go.id</span>
                        </li>
                    </ul>
                </div>
                <!-- Jam Operasional -->
                <div>
                    <h4 class="text-lg font-bold mb-6 text-gray-100">Jam Operasional (Ambil Fisik)</h4>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex justify-between border-b border-gray-800 pb-2">
                            <span>Senin - Kamis</span>
                            <span class="text-white font-medium">08:00 - 15:00 WIB</span>
                        </li>
                        <li class="flex justify-between border-b border-gray-800 pb-2">
                            <span>Jumat</span>
                            <span class="text-white font-medium">08:00 - 11:30 WIB</span>
                        </li>
                        <li class="flex justify-between pb-2">
                            <span>Sabtu - Minggu</span>
                            <span class="text-red-400 font-medium">Tutup</span>
                        </li>
                    </ul>
                    <p class="text-xs text-primary-400 mt-4 italic">*Pengajuan online buka 24 jam.</p>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} Pemerintah Desa. Hak Cipta Dilindungi.</p>
                <div class="mt-4 md:mt-0 space-x-4">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
