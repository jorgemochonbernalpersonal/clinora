@php
    use App\Shared\Helpers\SitemapHelper;
    $entries = SitemapHelper::getSitemapEntries();
@endphp
{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
{!! '<?xml-stylesheet type="text/xsl" href="' . url('/sitemap.xsl') . '"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    @foreach($entries as $entry)
    {{-- ✅ SEO: {{ $entry['description'] }} --}}
    <url>
        <loc>{{ $entry['url'] }}</loc>
        <lastmod>{{ $entry['lastmod'] }}</lastmod>
        <changefreq>{{ $entry['changefreq'] }}</changefreq>
        <priority>{{ $entry['priority'] }}</priority>
    </url>
    @endforeach
    
    {{-- Nota: Login, Register y otras páginas de autenticación están excluidas
         porque están bloqueadas en robots.txt para evitar indexación --}}
    
</urlset>
{{-- 
    Sitemap generado automáticamente por Clinora
    Última actualización: {{ now()->toDateTimeString() }}
    Total de URLs: {{ count($entries) }}
    Generado dinámicamente basado en fechas de modificación de archivos
--}}
