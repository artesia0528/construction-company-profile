@php
    $settings = \App\Models\CompanySetting::current();
    $seo = new \App\Support\Seo\SeoMeta(
        title: 'Page Not Found | ' . ($settings->company_name ?: config('app.name')),
        description: 'The page you requested could not be found.',
        canonical: url('/404'),
        robots: 'noindex,follow',
        image: $settings->seoImageUrl() ?: $settings->logoUrl(),
        type: 'website',
        breadcrumbs: [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Page Not Found'],
        ],
        schema: [],
    );
@endphp

<x-layouts.app title="Page Not Found" description="The page you requested could not be found." :seo="$seo">
    <section class="section-pad bg-steel-100">
        <div class="container-site">
            <div class="mx-auto max-w-3xl rounded border border-steel-200 bg-white p-10 text-center shadow-sm">
                <p class="font-display text-sm font-semibold uppercase tracking-[0.25em] text-accent-600">404</p>
                <h1 class="mt-4 font-display text-5xl font-semibold uppercase leading-none text-navy-950 md:text-6xl">Page Not Found</h1>
                <p class="mt-6 text-base leading-7 text-steel-600">The page you are looking for may have been moved, deleted, or never existed.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <x-ui.button href="{{ route('home') }}">Back to Home</x-ui.button>
                    <x-ui.button href="{{ route('contact') }}" variant="outline">Contact the Team</x-ui.button>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
