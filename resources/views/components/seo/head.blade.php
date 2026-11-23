@props(['meta'])

<title>{{ $meta->title }}</title>
<meta name="description" content="{{ $meta->description }}">
<meta name="robots" content="{{ $meta->robots }}">
<link rel="canonical" href="{{ $meta->canonical }}">
@if ($meta->themeColor)
    <meta name="theme-color" content="{{ $meta->themeColor }}">
@endif

<meta property="og:title" content="{{ $meta->title }}">
<meta property="og:description" content="{{ $meta->description }}">
<meta property="og:type" content="{{ $meta->type }}">
<meta property="og:url" content="{{ $meta->canonical }}">
@if ($meta->siteName)
    <meta property="og:site_name" content="{{ $meta->siteName }}">
@endif
@if ($meta->locale)
    <meta property="og:locale" content="{{ $meta->locale }}">
@endif
@if ($meta->image)
    <meta property="og:image" content="{{ $meta->image }}">
    @if ($meta->imageAlt)
        <meta property="og:image:alt" content="{{ $meta->imageAlt }}">
    @endif
@endif

<meta name="twitter:card" content="{{ $meta->image ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $meta->title }}">
<meta name="twitter:description" content="{{ $meta->description }}">
@if ($meta->image)
    <meta name="twitter:image" content="{{ $meta->image }}">
@endif

@foreach ($meta->schema as $schema)
    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}</script>
@endforeach
