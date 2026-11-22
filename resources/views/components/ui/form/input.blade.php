@props(['label', 'name', 'type' => 'text', 'value' => null, 'required' => false])

<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-semibold text-navy-900">{{ $label }}</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        @if($required) required @endif
        {{ $attributes->merge(['class' => 'w-full rounded border border-steel-200 bg-white px-4 py-3 text-sm text-slate-800 focus:border-accent-500 focus:outline-none focus:ring-2 focus:ring-accent-500/20']) }}
    >
    @error($name)<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
</div>
