@props([
    'title' => null,
    'description' => null,
    'canonical' => null,
    'robots' => null,
    'image' => null,
    'type' => null,
    'seo' => null,
])

@php
    $settings = $companySetting ?? \App\Models\CompanySetting::current();
    $seoMeta = $seo instanceof \App\Support\Seo\SeoMeta
        ? $seo
        : app(\App\Support\Seo\SeoResolver::class)->resolve(request(), $settings, array_filter([
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical,
            'robots' => $robots,
            'image' => $image,
            'type' => $type,
        ], fn ($value) => $value !== null && $value !== ''));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-seo.head :meta="$seoMeta" />
    @stack('head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-white text-slate-800">
    <a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[200] focus:rounded focus:bg-accent-500 focus:px-4 focus:py-2 focus:text-sm focus:font-semibold focus:text-navy-950">Skip to content</a>
    <x-layout.navbar :settings="$settings" />
    <main id="main-content" tabindex="-1" wire:transition.navigate>
        {{ $slot }}
    </main>
    <x-layout.footer :settings="$settings" />
    @livewireScriptConfig
</body>
</html>
