<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta name="view-transition" content="same-origin" />
    <title>Verifikasi OTP - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50 flex items-center justify-center min-h-screen relative overflow-x-hidden">
    
    <!-- Background Accents -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-primary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-secondary-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>

    <div class="relative w-full max-w-md px-6">
        <div class="bg-white/80 backdrop-blur-xl border border-white/50 shadow-2xl rounded-3xl p-6 sm:p-10 text-center">
            
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-2xl mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            
            <h2 class="text-2xl font-bold text-secondary-950 tracking-tight mb-2">Verifikasi WhatsApp</h2>
            <p class="text-sm text-gray-500 mb-8">
                Kami telah mengirimkan 6-digit kode OTP ke nomor <br>
                <span class="font-bold text-gray-800">{{ $phone ?? 'WhatsApp Anda' }}</span>
            </p>

            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-xl text-sm mb-6 font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}" x-data="otpForm()">
                @csrf
                <input type="hidden" name="otp" x-model="otpValue">
                
                <div class="flex justify-between gap-2 mb-8" @paste="handlePaste($event)">
                    <template x-for="(box, index) in length" :key="index">
                        <input 
                            type="text" 
                            maxlength="1" 
                            class="otp-input w-12 h-14 text-center text-2xl font-bold bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors outline-none"
                            x-model="boxes[index]"
                            @input="handleInput($event, index)"
                            @keydown="handleKeydown($event, index)"
                            @focus="handleFocus($event)"
                        >
                    </template>
                </div>

                <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-primary-500/30 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-200">
                    Verifikasi Akun
                </button>
            </form>

            <div class="mt-8 text-sm text-gray-600">
                Belum menerima kode? 
                <a href="{{ route('register') }}" class="font-bold text-primary-600 hover:text-primary-500 transition-colors">Daftar Ulang</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('otpForm', () => ({
                length: 6,
                boxes: Array(6).fill(''),
                get otpValue() {
                    return this.boxes.join('');
                },
                getInputs() {
                    return Array.from(document.querySelectorAll('.otp-input'));
                },
                handleInput(e, index) {
                    const value = e.target.value;
                    // Only allow numbers
                    if (/[^0-9]/.test(value)) {
                        this.boxes[index] = '';
                        return;
                    }
                    if (value && index < this.length - 1) {
                        const inputs = this.getInputs();
                        if(inputs[index + 1]) inputs[index + 1].focus();
                    }
                },
                handleKeydown(e, index) {
                    if (e.key === 'Backspace' && !this.boxes[index] && index > 0) {
                        const inputs = this.getInputs();
                        if(inputs[index - 1]) inputs[index - 1].focus();
                    }
                },
                handleFocus(e) {
                    e.target.select();
                },
                handlePaste(e) {
                    e.preventDefault();
                    const pastedData = (e.clipboardData || window.clipboardData).getData('text');
                    const numbers = pastedData.replace(/[^0-9]/g, '').slice(0, this.length);
                    if(numbers) {
                        for(let i=0; i<numbers.length; i++) {
                            this.boxes[i] = numbers[i];
                        }
                        const focusIndex = Math.min(numbers.length, this.length - 1);
                        setTimeout(() => {
                            const inputs = this.getInputs();
                            if(inputs[focusIndex]) inputs[focusIndex].focus();
                        }, 50);
                    }
                }
            }));
        });
    </script>
</body>
</html>
