<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'GERILYA - Layanan Digital Kelurahan Sukapada') }}</title>
    <meta name="description" content="GERILYA adalah Portal Layanan Digital resmi Kelurahan Sukapada, Kecamatan Cibeunying Kidul, Kota Bandung. Urus administrasi kependudukan dan surat menyurat lebih mudah, cepat, dan transparan.">
    <meta name="keywords" content="kelurahan sukapada, gerilya sukapada, layanan kelurahan sukapada, surat keterangan sukapada, kelurahan bandung, cibeunying kidul, administrasi kelurahan">
    <meta name="author" content="Pemerintah Kelurahan Sukapada">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="GERILYA - Kelurahan Sukapada">
    <meta property="og:description" content="Portal Layanan Digital resmi Kelurahan Sukapada. Urus administrasi kependudukan lebih mudah dan cepat tanpa antre.">
    <meta property="og:image" content="{{ asset('logo-gerilya.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url('/') }}">
    <meta property="twitter:title" content="GERILYA - Kelurahan Sukapada">
    <meta property="twitter:description" content="Portal Layanan Digital resmi Kelurahan Sukapada. Urus administrasi kependudukan lebih mudah dan cepat tanpa antre.">
    <meta property="twitter:image" content="{{ asset('logo-gerilya.png') }}">

    <link rel="icon" href="{{ asset('logo-gerilya.png') }}" type="image/png">
    
    <!-- Structured Data -->
    @verbatim
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "GovernmentOrganization",
      "name": "Kelurahan Sukapada",
      "alternateName": "GERILYA Kelurahan Sukapada",
      "url": "https://geriliya.com",
      "logo": "https://geriliya.com/logo-gerilya.png",
      "address": {
        "@type": "PostalAddress",
        "addressLocality": "Bandung",
        "addressRegion": "Jawa Barat",
        "addressCountry": "ID"
      }
    }
    </script>
    @endverbatim
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-800 selection:bg-primary-500 selection:text-white flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-gray-100 shadow-sm" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <img src="{{ asset('logo-gerilya.png') }}" alt="Gerilya Kelurahan Sukapada" class="h-12 w-auto object-contain">
                    <div class="flex flex-col justify-center">
                        <span class="font-bold text-2xl text-blue-900 leading-none tracking-tight">GERILYA</span>
                        <span class="text-xs font-bold text-green-600 tracking-widest uppercase mt-0.5">KELURAHAN SUKAPADA</span>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-8" id="desktopNav">
                    <a href="#beranda" class="nav-link active text-sm font-semibold text-primary-600 border-b-2 border-primary-600 pb-1 transition-all">Beranda</a>
                    <a href="#panduan" class="nav-link text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition-all pb-1">Panduan</a>
                    <a href="#kontak" class="nav-link text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-900 transition-all pb-1">Kontak</a>
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
                    <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-900 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t border-gray-100 py-4 px-4 shadow-lg absolute w-full" style="display: none;">
            <div class="flex flex-col space-y-4">
                <a href="#beranda" @click="mobileMenuOpen = false" class="text-base font-semibold text-primary-600">Beranda</a>
                <a href="#panduan" @click="mobileMenuOpen = false" class="text-base font-medium text-gray-600">Panduan</a>
                <a href="#kontak" @click="mobileMenuOpen = false" class="text-base font-medium text-gray-600">Kontak</a>
                <hr class="border-gray-100">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-base font-semibold text-gray-700">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-base font-semibold text-gray-700">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-base font-semibold text-primary-600">Daftar Sekarang</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </header>

    <main class="flex-grow">
        <!-- Hero Section -->
        <section id="beranda" class="relative bg-white overflow-hidden">
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
                        Layanan Surat Kelurahan Sukapada <br class="hidden md:block" />
                        Lebih <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-600 to-primary-400">Mudah & Cepat</span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-500 mb-10 leading-relaxed">
                        Tidak perlu lagi antre panjang. Ajukan permohonan surat keterangan dari rumah, pantau statusnya, dan ambil ke kantor kelurahan hanya saat surat sudah siap.
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
                        <p class="text-gray-500 text-sm leading-relaxed">Perangkat kelurahan akan memverifikasi data Anda. Anda bisa memantau statusnya langsung dari dashboard.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 relative group border border-gray-100">
                        <div class="w-14 h-14 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mb-6 text-xl font-bold group-hover:scale-110 transition-transform">4</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Ambil Surat</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">Anda akan menerima notifikasi WA jika surat siap. Datang ke kantor kelurahan untuk mengambil dokumen aslinya.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Layanan Surat Section -->
        <section class="py-20 bg-white border-y border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold text-secondary-900 mb-2">Layanan Surat Kelurahan Sukapada</h2>
                <p class="text-gray-500 mb-12">Klik nama surat di bawah ini untuk melihat detail panduan dan persyaratan yang dibutuhkan.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    @forelse($letterTypes as $letter)
                        @php
                            $colors = ['bg-primary-400', 'bg-indigo-400', 'bg-blue-400', 'bg-teal-400', 'bg-orange-400', 'bg-red-400', 'bg-green-400', 'bg-purple-400'];
                            $randomColor = $colors[$loop->index % count($colors)];
                            $statementUrl = $letter->statement_letter_file ? route('letter-types.download-statement', $letter->id) : '';
                        @endphp
                        <button type="button" 
                            onclick="openLetterModal('{{ $letter->name }}', {{ json_encode($letter->requirements ?? []) }}, '{{ $statementUrl }}')"
                            class="group px-6 py-3 bg-white text-gray-700 rounded-full font-medium border border-gray-100 shadow-sm hover:shadow-md hover:border-primary-200 hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 cursor-pointer">
                            <div class="w-2.5 h-2.5 rounded-full {{ $randomColor }} group-hover:scale-150 transition-transform"></div> {{ $letter->name }}
                        </button>
                    @empty
                        <p class="text-gray-400">Belum ada layanan surat yang tersedia.</p>
                    @endforelse
                </div>
            </div>
        </section>
        <!-- Lokasi & Kontak Section -->
        <section id="kontak" class="py-24 relative overflow-hidden bg-slate-50 border-t border-gray-100">
            <!-- Abstract Background Elements -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
                <div class="absolute -top-40 -right-40 w-[800px] h-[800px] rounded-full bg-primary-100/50 blur-3xl mix-blend-multiply"></div>
                <div class="absolute -bottom-40 -left-40 w-[800px] h-[800px] rounded-full bg-green-100/50 blur-3xl mix-blend-multiply"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center max-w-2xl mx-auto mb-16">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white shadow-sm border border-gray-100 text-primary-600 text-sm font-bold tracking-widest uppercase mb-6">
                        <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                        Hubungi Kami
                    </span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight mb-6">Lokasi & Kontak</h2>
                    <p class="text-lg text-gray-500 leading-relaxed">
                        Kami siap melayani Anda di kantor kelurahan. Jangan ragu untuk datang atau menghubungi kami melalui kontak di bawah ini.
                    </p>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-white flex flex-col lg:flex-row ring-1 ring-gray-100">
                    
                    <!-- Contact Info (Left) -->
                    <div class="lg:w-5/12 p-10 lg:p-14 bg-gradient-to-br from-white to-gray-50 relative flex flex-col justify-center">
                        <!-- Decorative grid -->
                        <div class="absolute top-0 left-0 w-full h-full opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#000 1.5px, transparent 1.5px); background-size: 24px 24px;"></div>
                        
                        <div class="relative z-10 space-y-10">
                            <div class="flex gap-6 group">
                                <div class="w-16 h-16 bg-white border border-gray-100 rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:bg-primary-600 group-hover:text-white group-hover:-translate-y-1 group-hover:shadow-xl group-hover:shadow-primary-600/30 transition-all duration-300 text-primary-600">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div class="pt-1">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Alamat Kantor</h4>
                                    <p class="text-gray-800 text-base font-semibold leading-relaxed">Jl. Sukapada No.Kelurahan, Cibeunying Kidul,<br/>Kota Bandung, Jawa Barat</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-6 group">
                                <div class="w-16 h-16 bg-white border border-gray-100 rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:bg-primary-600 group-hover:text-white group-hover:-translate-y-1 group-hover:shadow-xl group-hover:shadow-primary-600/30 transition-all duration-300 text-primary-600">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </div>
                                <div class="pt-2">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Telepon</h4>
                                    <p class="text-gray-900 text-lg font-bold">(021) 1234-5678</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-6 group">
                                <div class="w-16 h-16 bg-white border border-gray-100 rounded-2xl flex items-center justify-center shrink-0 shadow-sm group-hover:bg-primary-600 group-hover:text-white group-hover:-translate-y-1 group-hover:shadow-xl group-hover:shadow-primary-600/30 transition-all duration-300 text-primary-600">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="pt-2">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Email</h4>
                                    <p class="text-gray-900 text-lg font-bold">layanan@sukapada.go.id</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Map (Right) -->
                    <div class="lg:w-7/12 relative h-[400px] lg:h-auto lg:min-h-[600px] bg-white">
                        <div class="absolute inset-0 m-4 lg:m-6 rounded-[1.5rem] lg:rounded-[2rem] overflow-hidden shadow-inner border border-gray-100">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1980.4667705122022!2d107.64373583149913!3d-6.898552221463361!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e7bd64eb0c5d%3A0x7bb5c155998a1f40!2sKantor%20Kelurahan%20Sukapada!5e0!3m2!1sen!2sid!4v1788183841540!5m2!1sen!2sid" class="absolute inset-0 w-full h-full border-0" allowfullscreen="" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-secondary-950 text-white pt-16 pb-8 border-t-4 border-primary-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <!-- Tentang -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('logo-gerilya.png') }}" alt="Gerilya Kelurahan Sukapada" class="h-10 w-auto">
                        <div class="flex flex-col justify-center">
                            <span class="font-bold text-2xl text-white leading-none tracking-tight">GERILYA</span>
                            <span class="text-xs font-bold text-green-500 tracking-widest uppercase mt-0.5">KELURAHAN SUKAPADA</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed pr-4">
                        Inovasi digital untuk mempermudah warga dalam mengurus administrasi kependudukan. Cepat, transparan, dan efisien.
                    </p>
                </div>
                <!-- Kontak -->
                <div>
                    <h4 class="text-lg font-bold mb-6 text-gray-100">Hubungi Cepat</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-start">
                            <svg class="w-5 h-5 mr-3 text-primary-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Jl. Sukapada No.Kelurahan, Cibeunying Kidul, Kota Bandung, Jawa Barat</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span>(021) 1234-5678</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="w-5 h-5 mr-3 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>layanan@sukapada.go.id</span>
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
                <p>&copy; {{ date('Y') }} Kelurahan Sukapada. Hak Cipta Dilindungi.</p>
                <div class="mt-4 md:mt-0 space-x-4">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modal Requirements -->
    <!-- Modal Requirements -->
    <div id="letterModal" class="fixed inset-0 z-50 overflow-y-auto hidden py-8 px-4 sm:px-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity" onclick="closeLetterModal()"></div>
        
        <!-- Modal Content (No flex constraints) -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-auto text-left transform transition-all scale-95 opacity-0 duration-300 flex flex-col" id="letterModalContent">
            <!-- Header -->
                <div class="bg-secondary-900 px-6 py-4 flex justify-between items-center text-white rounded-t-2xl">
                    <h3 class="text-lg font-bold truncate pr-4" id="modalTitle">Nama Surat</h3>
                    <button onclick="closeLetterModal()" class="text-gray-300 hover:text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <!-- Body (No internal scroll) -->
                <div class="p-6 sm:p-8 bg-gray-50/50">
                <div class="flex justify-between items-center mb-4 border-b border-gray-200 pb-2">
                    <span class="text-xs font-bold text-secondary-800 uppercase tracking-widest">GERILYA - KELURAHAN SUKAPADA</span>
                    <span class="text-xs text-gray-500">Panduan Resmi</span>
                </div>
                
                <h4 class="text-base font-bold text-gray-800 mb-4" id="modalSubtitle">Dokumen Persyaratan:</h4>
                
                <ul class="space-y-3 text-sm text-gray-600 mb-6" id="modalReqsList">
                    <!-- Requirements injected via JS -->
                </ul>
                
                <p class="text-xs text-gray-400 italic mb-2">* Harap membawa dokumen fisik lengkap beserta map ke Kantor Kelurahan Sukapada saat pengambilan jika diperlukan.</p>
            </div>

            <!-- Footer (Fixed at bottom) -->
            <div class="p-6 bg-white border-t border-gray-100 shrink-0">
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#" id="modalStatementBtn" class="hidden w-full sm:w-1/2 text-center px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 font-bold rounded-xl border border-gray-200 shadow-sm transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Surat Pernyataan
                    </a>
                    <a href="{{ route('citizen.request.create') }}" id="modalSubmitBtn" class="w-full text-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-lg shadow-primary-500/30 transition-all hover:-translate-y-0.5">
                        Ajukan Sekarang
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS for Modal -->
    <script>
        const modal = document.getElementById('letterModal');
        const modalContent = document.getElementById('letterModalContent');
        const modalTitle = document.getElementById('modalTitle');
        const modalSubtitle = document.getElementById('modalSubtitle');
        const modalReqsList = document.getElementById('modalReqsList');
        const modalStatementBtn = document.getElementById('modalStatementBtn');
        const modalSubmitBtn = document.getElementById('modalSubmitBtn');

        function openLetterModal(name, reqs, statementUrl) {
            // Populate content
            modalTitle.textContent = name;
            modalSubtitle.textContent = 'Dokumen Persyaratan: ' + name;
            
            // Handle statement button visibility
            if (statementUrl) {
                modalStatementBtn.href = statementUrl;
                modalStatementBtn.classList.remove('hidden');
                modalSubmitBtn.classList.remove('w-full');
                modalSubmitBtn.classList.add('sm:w-1/2');
            } else {
                modalStatementBtn.href = '#';
                modalStatementBtn.classList.add('hidden');
                modalSubmitBtn.classList.remove('sm:w-1/2');
                modalSubmitBtn.classList.add('w-full');
            }
            
            // Clear previous reqs
            modalReqsList.innerHTML = '';
            
            if (reqs && reqs.length > 0) {
                reqs.forEach(req => {
                    const li = document.createElement('li');
                    li.className = 'flex items-start';
                    li.innerHTML = `
                        <svg class="w-5 h-5 text-primary-500 mr-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>${req}</span>
                    `;
                    modalReqsList.appendChild(li);
                });
            } else {
                const li = document.createElement('li');
                li.className = 'text-gray-500 italic';
                li.textContent = 'Tidak ada persyaratan khusus untuk surat ini.';
                modalReqsList.appendChild(li);
            }

            // Show modal
            modal.classList.remove('hidden');
            // Prevent background scrolling
            document.body.style.overflow = 'hidden';
            // Trigger animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeLetterModal() {
            // Animate out
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');
            // Hide after animation and restore scroll
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }

        // Scroll Spy for Navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');

            function onScroll() {
                let scrollY = window.pageYOffset;
                
                sections.forEach(current => {
                    const sectionHeight = current.offsetHeight;
                    // Add a small offset so it triggers slightly before hitting the exact top
                    const sectionTop = current.offsetTop - 100;
                    const sectionId = current.getAttribute('id');
                    
                    if (scrollY > sectionTop && scrollY <= sectionTop + sectionHeight) {
                        navLinks.forEach(link => {
                            link.classList.remove('text-primary-600', 'border-primary-600', 'font-semibold');
                            link.classList.add('text-gray-500', 'border-transparent', 'font-medium');
                            if(link.getAttribute('href') === '#' + sectionId) {
                                link.classList.remove('text-gray-500', 'border-transparent', 'font-medium');
                                link.classList.add('text-primary-600', 'border-primary-600', 'font-semibold');
                            }
                        });
                    }
                });
            }

            window.addEventListener('scroll', onScroll);
            
            // Trigger once on load to set initial state
            onScroll();
        });
    </script>
    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/6288226139635" target="_blank" rel="noopener noreferrer" class="fixed bottom-6 right-6 z-50 group flex items-center justify-center bg-green-500 text-white rounded-full border-2 border-black shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden">
        <div class="flex items-center p-3 sm:p-4">
            <svg class="w-7 h-7 sm:w-8 sm:h-8 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.888-.788-1.489-1.761-1.662-2.06-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
            <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-500 ease-in-out font-bold text-lg">
                <span class="pl-2 pr-2">Butuh Bantuan?</span>
            </span>
        </div>
    </a>
</body>
</html>
