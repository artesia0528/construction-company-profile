@props(['testimonial'])

<article {{ $attributes->merge(['class' => 'h-full rounded border border-steel-200 bg-white p-8 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-accent-500/60 hover:shadow-md']) }}>
    <div class="text-accent-500">
        @for ($i = 0; $i < 5; $i++)
            <span class="{{ $i < $testimonial->rating ? 'text-accent-500' : 'text-steel-200' }}">★</span>
        @endfor
    </div>
    <p class="mt-5 text-sm leading-7 text-steel-700">“{{ $testimonial->testimonial_content }}”</p>
    <div class="mt-6 border-t border-steel-200 pt-5">
        <p class="font-semibold text-navy-950">{{ $testimonial->client_name }}</p>
        <p class="text-sm text-steel-500">{{ $testimonial->client_position }}@if($testimonial->company_name), {{ $testimonial->company_name }}@endif</p>
    </div>
</article>
