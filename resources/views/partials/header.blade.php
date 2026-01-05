<header class="sticky top-0 z-50 bg-surface border-b border-gray-200 shadow-sm">
    <nav class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <div class="flex-shrink-0 overflow-hidden">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Clinora" class="h-16 w-auto mix-blend-multiply transform scale-150 origin-center">
                </a>
            </div>

            {{-- CTA Buttons --}}
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" 
                       class="text-text-secondary hover:text-primary-600 transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" 
                       class="text-text-secondary hover:text-primary-600 transition-colors">
                        Iniciar Sesión
                    </a>
                    <a href="{{ route('register') }}" 
                       class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg transition-colors font-medium">
                        Crea tu Cuenta Gratis
                    </a>
                @endauth
            </div>

            {{-- Mobile Menu Button --}}
            <div class="md:hidden">
                <button type="button" 
                        class="text-text-secondary hover:text-primary-600"
                        id="mobile-menu-button"
                        aria-label="Toggle menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div class="hidden md:hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1 border-t border-gray-200 mt-4">
                <a href="{{ route('home') }}#caracteristicas" class="mobile-nav-link block px-3 py-2 text-text-secondary hover:text-primary-600">
                    Características
                </a>
                <a href="{{ route('home') }}#caracteristicas" class="mobile-nav-link block px-3 py-2 text-text-secondary hover:text-primary-600">
                    Para tu Profesión
                </a>
                <a href="{{ route('home') }}#precios" class="mobile-nav-link block px-3 py-2 text-text-secondary hover:text-primary-600">
                    Precios
                </a>
                <a href="{{ route('home') }}#faq" class="mobile-nav-link block px-3 py-2 text-text-secondary hover:text-primary-600">
                    FAQ
                </a>
            </div>
        </div>
    </nav>
</header>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileLinks = document.querySelectorAll('.mobile-nav-link');

        mobileMenuButton?.addEventListener('click', function() {
            mobileMenu?.classList.toggle('hidden');
        });

        // Cerrar menú al hacer click en un link
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu?.classList.add('hidden');
            });
        });
    });
</script>
@endpush

