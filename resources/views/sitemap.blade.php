@php
use App\Shared\Helpers\SitemapHelper;
$entries = SitemapHelper::getSitemapEntries();
@endphp
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
@foreach($entries as $entry)
@if(!empty($entry['url']))
<url>
<loc>{{ htmlspecialchars($entry['url'], ENT_XML1 | ENT_QUOTES, 'UTF-8') }}</loc>
<lastmod>{{ $entry['lastmod'] ?? now()->toAtomString() }}</lastmod>
<changefreq>{{ $entry['changefreq'] ?? 'monthly' }}</changefreq>
<priority>{{ $entry['priority'] ?? '0.5' }}</priority>
@if(isset($entry['images']) && is_array($entry['images']) && count($entry['images']) > 0)
@foreach($entry['images'] as $image)
@if(!empty($image['loc']))
<image:image>
<image:loc>{{ htmlspecialchars($image['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') }}</image:loc>
@if(!empty($image['caption']))
<image:caption>{{ htmlspecialchars($image['caption'], ENT_XML1 | ENT_QUOTES, 'UTF-8') }}</image:caption>
@endif
@if(!empty($image['title']))
<image:title>{{ htmlspecialchars($image['title'], ENT_XML1 | ENT_QUOTES, 'UTF-8') }}</image:title>
@endif
</image:image>
@endif
@endforeach
@endif
</url>
@endif
@endforeach
</urlset>
