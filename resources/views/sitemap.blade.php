<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    
    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    
    {{-- Public Pages --}}
    
    {{-- Login Page --}}
    <url>
        <loc>{{ url('/login') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    {{-- Register Page --}}
    <url>
        <loc>{{ url('/register') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>
    
    {{-- Add more public pages here as they are created --}}
    {{-- Example:
    <url>
        <loc>{{ url('/blog') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.7</priority>
    </url>
    --}}
    
</urlset>
