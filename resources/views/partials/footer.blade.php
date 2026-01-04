<footer class="bg-gray-900 text-gray-300">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Producto --}}
            <div>
                <h3 class="text-white font-semibold mb-4">Producto</h3>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}#caracteristicas" class="hover:text-white transition-colors">Características</a></li>
                    <li><a href="{{ url('/') }}#precios" class="hover:text-white transition-colors">Precios</a></li>
                    <li><a href="{{ url('/') }}#caracteristicas" class="hover:text-white transition-colors">Casos de Uso</a></li>
                    <li><a href="{{ route('faqs') }}" class="hover:text-white transition-colors">Preguntas Frecuentes</a></li>
                </ul>
            </div>

            {{-- Seguridad & Privacidad --}}
            <div>
                <h3 class="text-white font-semibold mb-4">Seguridad & Privacidad</h3>
                <ul class="space-y-2">
                    <li><a href="javascript:void(0)" class="hover:text-white transition-colors">Autenticación 2FA</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition-colors">Verificación de Email</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition-colors">Cambio de Contraseña</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition-colors">Gestión de Perfil</a></li>
                </ul>
            </div>

            {{-- Empresa --}}
            <div>
                <h3 class="text-white font-semibold mb-4">Empresa</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">Sobre Nosotros</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contacto</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white transition-colors">Blog</a></li>
                    <li><a href="javascript:void(0)" class="hover:text-white transition-colors">Centro de Ayuda</a></li>
                </ul>
            </div>

            {{-- Legal --}}
            <div>
                <h3 class="text-white font-semibold mb-4">Legal</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('legal.terms') }}" class="hover:text-white transition-colors">Términos de Servicio</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="hover:text-white transition-colors">Política de Privacidad</a></li>
                    <li><a href="{{ route('legal.cookies') }}" class="hover:text-white transition-colors">Cookies</a></li>
                    <li><a href="{{ route('legal.gdpr') }}" class="hover:text-white transition-colors">RGPD</a></li>
                </ul>
            </div>
        </div>

        {{-- Certification Badges --}}
        <div class="mt-12 pt-8 border-t border-gray-800">
            <div class="flex flex-col items-center">
                <p class="text-sm text-gray-400 mb-4">Certificaciones y Cumplimiento</p>
                <div class="flex flex-wrap justify-center gap-6">
                    {{-- GDPR Badge --}}
                    <div class="flex flex-col items-center bg-gray-800 px-6 py-4 rounded-lg border border-gray-700 hover:border-success-500 transition-colors">
                        <svg class="w-12 h-12 text-success-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-1 6h2v2h-2V7zm0 4h2v6h-2v-6z"/>
                        </svg>
                        <span class="text-xs font-semibold text-white">GDPR</span>
                        <span class="text-xs text-gray-400">Compliant</span>
                    </div>

                    {{-- SSL Badge --}}
                    <div class="flex flex-col items-center bg-gray-800 px-6 py-4 rounded-lg border border-gray-700 hover:border-primary-500 transition-colors">
                        <svg class="w-12 h-12 text-primary-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                        </svg>
                        <span class="text-xs font-semibold text-white">SSL/TLS</span>
                        <span class="text-xs text-gray-400">Encrypted</span>
                    </div>

                    {{-- ISO 27001 Badge --}}
                    <div class="flex flex-col items-center bg-gray-800 px-6 py-4 rounded-lg border border-gray-700 hover:border-accent-500 transition-colors">
                        <svg class="w-12 h-12 text-accent-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                            <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="1.5"/>
                        </svg>
                        <span class="text-xs font-semibold text-white">ISO 27001</span>
                        <span class="text-xs text-gray-400">Ready</span>
                    </div>

                    {{-- LOPD Badge --}}
                    <div class="flex flex-col items-center bg-gray-800 px-6 py-4 rounded-lg border border-gray-700 hover:border-secondary-500 transition-colors">
                        <svg class="w-12 h-12 text-secondary-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <span class="text-xs font-semibold text-white">LOPD</span>
                        <span class="text-xs text-gray-400">España</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="mt-8 pt-8 border-t border-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-sm">&copy; {{ date('Y') }} Clinora. Todos los derechos reservados.</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-success-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                        </svg>
                        <span>Encriptación SSL</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4 text-success-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Cumplimiento GDPR</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

