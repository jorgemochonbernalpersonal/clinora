<div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-center text-text-primary mb-2">
            Verifica tu Email
        </h2>
        <p class="text-center text-text-secondary mb-6">
            Hemos enviado un enlace de verificación a <strong>{{ auth()->user()->email }}</strong>
        </p>

        {{-- Success Message --}}
        @if($successMessage)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                <p class="text-sm">{{ $successMessage }}</p>
            </div>
        @endif

        {{-- Error Message --}}
        @if($errorMessage)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                <p class="text-sm">{{ $errorMessage }}</p>
            </div>
        @endif

        {{-- Verified - Redirecting --}}
        @if($isVerified)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                <p class="text-sm">✅ ¡Email verificado! Redirigiendo al dashboard...</p>
            </div>
            <script>
                setTimeout(() => {
                    window.location.href = '{{ route('dashboard') }}';
                }, 1000);
            </script>
        @else
            {{-- Instructions --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-blue-800 mb-2">
                    📧 Revisa tu bandeja de entrada y haz clic en el enlace para verificar tu cuenta.
                </p>
                <p class="text-xs text-blue-600">
                    ✨ Esta página se actualizará automáticamente cuando verifiques tu email.
                </p>
            </div>

            {{-- Resend Button --}}
            <button 
                wire:click="resend"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50 mb-4"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Reenviar Email</span>
                <span wire:loading>Enviando...</span>
            </button>

            {{-- Logout Link --}}
            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-sm text-primary-600 hover:text-primary-700">
                    Cerrar Sesión
                </button>
            </form>
        @endif
    </div>

    {{-- JavaScript to check verification status on window focus --}}
    <script>
        // Check verification when user returns to this tab
        window.addEventListener('focus', function() {
            // Check if email was verified (set by verification page)
            if (localStorage.getItem('email_verified') === 'true') {
                localStorage.removeItem('email_verified');
                window.location.reload();
            }
        });

        // Check every 5 seconds if still on page
        setInterval(function() {
            if (localStorage.getItem('email_verified') === 'true') {
                localStorage.removeItem('email_verified');
                window.location.reload();
            }
        }, 5000);
    </script>
</div>
