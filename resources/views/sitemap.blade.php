<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    {{-- ✅ SEO: Homepage / Landing Page - Máxima prioridad --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    
    {{-- ✅ SEO: Páginas Legales - Importantes para confianza y cumplimiento --}}
    
    {{-- Privacy Policy --}}
    <url>
        <loc>{{ url('/legal/privacy') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    {{-- Terms of Service --}}
    <url>
        <loc>{{ url('/legal/terms') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    {{-- GDPR / Data Protection --}}
    <url>
        <loc>{{ url('/legal/gdpr') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    {{-- Cookies Policy --}}
    <url>
        <loc>{{ url('/legal/cookies') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    {{-- Nota: Login, Register y otras páginas de autenticación están excluidas
         porque están bloqueadas en robots.txt para evitar indexación --}}
    
</urlset>
