@props(['label', 'name', 'rows' => 5, 'value' => null, 'required' => false])

@php
    $errorId = $name.'-error';
    $hasError = $errors->has($name);
@endphp

<div data-field="{{ $name }}">
    <label for="{{ $name }}" class="mb-2 block text-sm font-semibold text-navy-900">{{ $label }}</label>
    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        @if($required) required @endif
        aria-describedby="{{ $errorId }}"
        @if($hasError) aria-invalid="true" @endif
        {{ $attributes->merge(['class' => 'w-full rounded border border-steel-200 bg-white px-4 py-3 text-sm text-slate-800 focus:border-accent-500 focus:outline-none focus:ring-2 focus:ring-accent-500/20']) }}
    >{{ old($name, $value) }}</textarea>
    <p id="{{ $errorId }}" data-field-error="{{ $name }}" class="mt-2 text-sm text-red-600 {{ $hasError ? '' : 'hidden' }}">{{ $hasError ? $errors->first($name) : '' }}</p>
</div>
