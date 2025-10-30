@props(['seoData' => []])

@if($seoData)
    @if(isset($seoData['meta_title']))
        <title>{{ $seoData['meta_title'] }}</title>
    @endif

    @if(isset($seoData['meta_description']))
        <meta name="description" content="{{ $seoData['meta_description'] }}">
    @endif

    @if(isset($seoData['meta_keywords']))
        <meta name="keywords" content="{{ $seoData['meta_keywords'] }}">
    @endif

    @if(isset($seoData['og_title']))
        <meta property="og:title" content="{{ $seoData['og_title'] }}">
    @endif

    @if(isset($seoData['og_description']))
        <meta property="og:description" content="{{ $seoData['og_description'] }}">
    @endif

    @if(isset($seoData['og_image']))
        <meta property="og:image" content="{{ $seoData['og_image'] }}">
    @endif

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Bitra Services">

    @if(isset($seoData['schema_markup']) && !empty($seoData['schema_markup']))
        <script type="application/ld+json">
            {!! json_encode($seoData['schema_markup'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
        </script>
    @endif
@endif
