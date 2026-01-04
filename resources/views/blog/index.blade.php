@extends('layouts.app')

@section('title', 'Blog - Clinora')
@section('meta_title', 'Blog - Clinora | Recursos y Guías sobre Gestión Clínica')
@section('meta_description', 'Artículos y guías sobre psicología, gestión clínica, RGPD, y mejores prácticas para profesionales de la salud.')

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Blog",
  "name": "Blog de Clinora",
  "description": "Artículos y recursos para profesionales de la salud",
  "url": "{{ route('blog.index') }}",
  "publisher": {
    "@type": "Organization",
    "name": "Clinora",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset('images/logo.png') }}"
    }
  }
}
</script>
@endpush

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-b from-primary-50 to-background py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl sm:text-5xl font-bold text-text-primary mb-4">
                    Blog de Clinora
                </h1>
                <p class="text-xl text-text-secondary">
                    Recursos, guías y consejos para profesionales de la salud
                </p>
            </div>
        </div>
    </section>

    {{-- Blog Posts Grid --}}
    <section class="py-16 bg-background">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-6xl mx-auto">
                @if($posts->isEmpty())
                    <div class="text-center py-16">
                        <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-text-primary mb-2">No hay artículos publicados</h3>
                        <p class="text-text-secondary">Vuelve pronto para encontrar nuevos contenidos</p>
                    </div>
                @else
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($posts as $post)
                            <article class="bg-surface rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                                @if($post->featured_image)
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" 
                                         class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-primary-50 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-6">
                                    <div class="flex items-center gap-2 text-sm text-text-secondary mb-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <time datetime="{{ $post->published_at->toDateString() }}">
                                            {{ $post->published_at->format('d M Y') }}
                                        </time>
                                        @if($post->views_count > 0)
                                            <span class="mx-2">·</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span>{{ $post->views_count }}</span>
                                        @endif
                                    </div>
                                    
                                    <h2 class="text-xl font-bold text-text-primary mb-2 line-clamp-2">
                                        <a href="{{ route('blog.show', $post) }}" class="hover:text-primary-600 transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h2>
                                    
                                    <p class="text-text-secondary text-sm mb-4 line-clamp-3">
                                        {{ $post->excerpt }}
                                    </p>
                                    
                                    <a href="{{ route('blog.show', $post) }}" 
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

                    {{-- Pagination --}}
                    @if($posts->hasPages())
                        <div class="mt-12">
                            {{ $posts->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>
@endsection
