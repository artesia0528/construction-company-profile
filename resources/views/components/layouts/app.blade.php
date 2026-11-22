@props([
    'title' => null,
    'description' => null,
])

@php
    $settings = $companySetting ?? \App\Models\CompanySetting::current();
    $pageTitle = $title ? $title.' | '.$settings->company_name : $settings->company_name;
    $pageDescription = $description ?? $settings->company_description;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $pageDescription }}">
    <title>{{ $pageTitle }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-slate-800">
    <x-layout.navbar :settings="$settings" />
    <main>
        {{ $slot }}
    </main>
    <x-layout.footer :settings="$settings" />
</body>
</html>
