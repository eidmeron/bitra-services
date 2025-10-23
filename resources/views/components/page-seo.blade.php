{{-- Component to inject SEO data from PageContent --}}
@props(['pageKey' => null])

@php
$pageContent = $pageKey ? page_content($pageKey) : null;
@endphp

@if($pageContent)
    {{-- Meta Tags --}}
    @if($pageContent->meta_title)
        @section('title', $pageContent->meta_title)
    @endif
    
    @if($pageContent->meta_description)
        @section('meta_description', $pageContent->meta_description)
    @endif
    
    @if($pageContent->meta_keywords)
        @section('meta_keywords', $pageContent->meta_keywords)
    @endif
    
    {{-- Open Graph --}}
    @if($pageContent->og_title)
        @section('og_title', $pageContent->og_title)
    @endif
    
    @if($pageContent->og_description)
        @section('og_description', $pageContent->og_description)
    @endif
    
    @if($pageContent->og_image)
        @push('seo')
            <meta property="og:image" content="{{ Storage::url($pageContent->og_image) }}">
        @endpush
    @endif
    
    {{-- Canonical URL --}}
    @if($pageContent->canonical_url)
        @section('canonical', $pageContent->canonical_url)
    @endif
    
    {{-- Schema Markup --}}
    @if($pageContent->schema_markup)
        @push('seo')
            <script type="application/ld+json">
                @json($pageContent->schema_markup)
            </script>
        @endpush
    @endif
@endif


