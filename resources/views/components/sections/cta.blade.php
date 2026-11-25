@props(['settings'])

<section class="bg-accent-500 py-16">
    <div class="container-site flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
        <div data-motion="reveal">
            <p class="font-display text-sm font-semibold uppercase tracking-[0.25em] text-navy-900">Ready to Build</p>
            <h2 class="mt-2 font-display text-4xl font-semibold uppercase text-navy-950 md:text-5xl">Let’s Build Your Next Project</h2>
            <p class="mt-3 max-w-2xl text-navy-900/80">Partner with {{ $settings->company_name }} for reliable delivery, quality workmanship, and professional project management.</p>
        </div>
        <x-ui.button href="{{ route('contact') }}" variant="outline" class="border-navy-950 bg-navy-950 text-white hover:bg-navy-800" data-motion="reveal" data-motion-delay="120" wire:navigate>Request a Consultation</x-ui.button>
    </div>
</section>
