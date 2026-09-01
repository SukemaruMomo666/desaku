<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <title>Dashboard - {{ config('app.name') }}</title>
    <link rel="icon" href="{{ asset('logo-gerilya.png') }}" type="image/png">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js (Optional but great for simple interactions like Dropdowns) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 flex h-screen overflow-hidden selection:bg-primary-500 selection:text-white" x-data="{ sidebarOpen: false }">

    <!-- Sidebar / Navigation -->
    <aside class="flex-shrink-0 w-64 bg-secondary-950 flex flex-col transition-transform duration-300 z-40 fixed inset-y-0 left-0 md:relative md:translate-x-0"
           :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
        
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-white/10 bg-secondary-950">
            <a href="/" class="flex items-center">
                <img src="{{ asset('logo-gerilya.png') }}" alt="Griliya Kelurahan Sukapada" class="h-10 w-auto">
            </a>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            @can('is-admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                @can('manage-requests')
                <a href="{{ route('admin.letter-requests.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.letter-requests.*') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Data Pengajuan
                </a>
                @endcan
                @can('manage-letter-types')
                <a href="{{ route('admin.letter-types.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.letter-types.*') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Master Jenis Surat
                </a>
                @endcan
                @can('manage-users')
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Data Warga
                </a>
                @endcan
                @can('is-master-admin')
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Pengaturan & Arsip
                </a>
                @endcan
            @else
                <a href="{{ route('citizen.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('citizen.dashboard') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Beranda / Riwayat
                </a>
                <a href="{{ route('citizen.request.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('citizen.request.create') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Ajukan Surat Baru
                </a>
                <a href="{{ route('citizen.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('citizen.profile') ? 'bg-primary-600/10 text-primary-400 font-semibold border border-primary-500/20' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profil Lengkap
                </a>
            @endcan
        </nav>
    </aside>

    <!-- Overlay for mobile sidebar -->
    <div x-show="sidebarOpen" class="fixed inset-0 bg-secondary-950/50 backdrop-blur-sm z-30 md:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 bg-gray-50/50 relative overflow-x-hidden">
        
        <!-- Abstract Background inside content -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-100 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>

        <!-- Top Header -->
        <header class="h-16 md:h-20 bg-white/70 backdrop-blur-xl border-b border-gray-200/60 sticky top-0 z-20 flex items-center justify-between px-4 sm:px-6 lg:px-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-primary-600 focus:outline-none transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h2 class="text-xl font-bold text-gray-800 hidden sm:block">@yield('header_title', 'Dashboard')</h2>
            </div>
            
            <div class="flex items-center gap-6">
                <!-- User Profile Dropdown -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" @click.away="dropdownOpen = false" class="flex items-center gap-3 hover:opacity-80 transition-opacity focus:outline-none">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->nik }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-primary-600 to-primary-400 flex items-center justify-center text-white font-bold shadow-md shadow-primary-500/20">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="dropdownOpen" x-transition.origin.top.right
                         style="display: none;"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Keluar Sistem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-10 relative z-10">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
