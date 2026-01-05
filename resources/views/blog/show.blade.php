@extends('layouts.app')

@section('title', $post->title . ' - Blog Clinora')
@section('meta_title', $post->meta_description ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@if($post->meta_keywords)
    @section('meta_keywords', $post->meta_keywords)
@endif

@push('structured_data')
{{-- Article Schema --}}
<script type="application/ld+json">
{!! json_encode(array_filter([
    '@context' => 'https://schema.org',
    '@type' => 'BlogPosting',
    'headline' => $post->title,
    'description' => $post->excerpt,
    'url' => route('blog.show', $post),
    'datePublished' => $post->published_at->toIso8601String(),
    'dateModified' => $post->updated_at->toIso8601String(),
    'author' => [
        '@type' => 'Organization',
        'name' => 'Clinora',
        'url' => url('/')
    ],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Clinora',
        'url' => url('/'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => asset('images/logo.png'),
            'width' => 500,
            'height' => 500
        ]
    ],
    'image' => $post->featured_image ? [
        '@type' => 'ImageObject',
        'url' => $post->featured_image,
        'width' => 1200,
        'height' => 630
    ] : null,
    'mainEntityOfPage' => [
        '@type' => 'WebPage',
        '@id' => route('blog.show', $post)
    ],
    'articleSection' => 'Software para Psicólogos',
    'keywords' => $post->meta_keywords ?? 'software psicólogos, gestión clínica, psicología',
    'wordCount' => str_word_count(strip_tags($post->content ?? '')),
    'inLanguage' => 'es-ES'
]), JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

{{-- BreadcrumbList Schema --}}
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Inicio',
            'item' => url('/')
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Blog',
            'item' => route('blog.index')
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $post->title,
            'item' => route('blog.show', $post)
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    {{-- Article Header --}}
    <article>
        <header class="bg-gradient-to-b from-primary-50 to-background py-16">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto">
                    {{-- Breadcrumbs --}}
                    <nav class="text-sm text-text-secondary mb-6" aria-label="Breadcrumb">
                        <ol class="flex items-center gap-2" itemscope itemtype="https://schema.org/BreadcrumbList">
                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a href="{{ url('/') }}" class="hover:text-primary-600" itemprop="item">
                                    <span itemprop="name">Inicio</span>
                                </a>
                                <meta itemprop="position" content="1" />
                            </li>
                            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a href="{{ route('blog.index') }}" class="hover:text-primary-600" itemprop="item">
                                    <span itemprop="name">Blog</span>
                                </a>
                                <meta itemprop="position" content="2" />
                            </li>
                            <li><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                            <li class="text-text-primary" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <span itemprop="name">{{ Str::limit($post->title, 40) }}</span>
                                <meta itemprop="position" content="3" />
                            </li>
                        </ol>
                    </nav>

                    <h1 class="text-4xl sm:text-5xl font-bold text-text-primary mb-6">
                        {{ $post->title }}
                    </h1>
                    
                    <div class="flex items-center gap-6 text-text-secondary">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <time datetime="{{ $post->published_at->toDateString() }}">
                                {{ $post->published_at->format('d M Y') }}
                            </time>
                        </div>
                        
                        @if($post->views_count > 0)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>{{ $post->views_count }} {{ $post->views_count === 1 ? 'vista' : 'vistas' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        {{-- Featured Image --}}
        @if($post->featured_image)
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
                <div class="max-w-4xl mx-auto">
                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" 
                         class="w-full h-96 object-cover rounded-lg shadow-lg">
                </div>
            </div>
        @endif

        {{-- Article Content --}}
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="max-w-4xl mx-auto">
                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>

                {{-- CTA Box --}}
                <div class="mt-12 bg-primary-50 rounded-lg p-8 border-2 border-primary-200">
                    <h3 class="text-2xl font-bold text-text-primary mb-2">¿Te ha resultado útil este artículo?</h3>
                    <p class="text-text-secondary mb-6">
                        Descubre cómo Clinora puede ayudarte a gestionar tu clínica de forma más eficiente
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" 
                           class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors text-center">
                            Probar gratis
                        </a>
                        <a href="{{ route('contact') }}" 
                           class="bg-white hover:bg-gray-50 text-primary-600 border-2 border-primary-500 px-6 py-3 rounded-lg font-semibold transition-colors text-center">
                            Contactar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Posts --}}
        @if($relatedPosts->isNotEmpty())
            <section class="bg-surface py-16">
                <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="max-w-6xl mx-auto">
                        <h2 class="text-3xl font-bold text-text-primary mb-8">Artículos relacionados</h2>
                        
                        <div class="grid md:grid-cols-3 gap-8">
                            @foreach($relatedPosts as $relatedPost)
                                <article class="bg-background rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                                    @if($relatedPost->featured_image)
                                        <img src="{{ $relatedPost->featured_image }}" alt="{{ $relatedPost->title }}" 
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-primary-50 flex items-center justify-center">
                                            <svg class="w-16 h-16 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="p-6">
                                        <h3 class="text-lg font-bold text-text-primary mb-2 line-clamp-2">
                                            <a href="{{ route('blog.show', $relatedPost) }}" class="hover:text-primary-600 transition-colors">
                                                {{ $relatedPost->title }}
                                            </a>
                                        </h3>
                                        
                                        <p class="text-text-secondary text-sm mb-4 line-clamp-2">
                                            {{ $relatedPost->excerpt }}
                                        </p>
                                        
                                        <a href="{{ route('blog.show', $relatedPost) }}" 
                                           class="text-primary-600 hover:text-primary-700 font-semibold text-sm inline-flex items-center gap-1">
                                            Leer más
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                        
                        <div class="mt-8 text-center">
                            <a href="{{ route('blog.index') }}" 
                               class="inline-block bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                                Ver todos los artículos
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </article>
@endsection
