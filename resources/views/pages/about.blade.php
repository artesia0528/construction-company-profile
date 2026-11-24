<x-layouts.app title="About Us" description="Learn about Nusantara Build Construction, our history, vision, mission, and team." :seo="$seo">
    <x-sections.page-banner eyebrow="About Us" title="Building With Integrity and Precision" description="We are a professional construction company focused on quality delivery, safety, and long-term client partnerships." :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['label' => 'About Us']]" />

    <section class="section-pad">
        <div class="container-site grid gap-12 lg:grid-cols-2">
            <div>
                <x-ui.section-heading eyebrow="Company Introduction" title="Who We Are" :description="$companySetting->company_intro" data-motion="reveal" />
                <p class="mt-6 leading-7 text-steel-500" data-motion="reveal" data-motion-delay="80">{{ $companySetting->company_description }}</p>
            </div>
            <div class="rounded bg-steel-100 p-8" data-motion="reveal" data-motion-delay="120">
                <p class="font-display text-sm font-semibold uppercase tracking-[0.2em] text-accent-600">Company History</p>
                <p class="mt-4 leading-7 text-steel-700">{{ $companySetting->company_history }}</p>
            </div>
        </div>
    </section>

    <section class="section-pad bg-steel-100">
        <div class="container-site grid gap-8 lg:grid-cols-2">
            <div class="rounded border border-steel-200 bg-white p-8" data-motion="reveal">
                <p class="font-display text-sm font-semibold uppercase tracking-[0.2em] text-accent-600">Our Vision</p>
                <p class="mt-4 text-lg leading-8 text-navy-900">{{ $companySetting->vision }}</p>
            </div>
            <div class="rounded border border-steel-200 bg-white p-8" data-motion="reveal" data-motion-delay="80">
                <p class="font-display text-sm font-semibold uppercase tracking-[0.2em] text-accent-600">Our Mission</p>
                <ul class="mt-4 space-y-3 text-steel-700">
                    @foreach (preg_split('/\r\n|\r|\n/', (string) $companySetting->mission) as $mission)
                        @if (trim($mission) !== '')
                            <li class="flex gap-3"><span class="mt-1 text-accent-500">■</span><span>{{ $mission }}</span></li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <section class="section-pad">
        <div class="container-site">
            <x-ui.section-heading eyebrow="Company Values" title="The Principles Behind Our Work" align="center" class="mx-auto" data-motion="reveal" />
            <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-5" data-motion="stagger" data-motion-delay="80">
                @foreach (($companySetting->company_values ?? []) as $value)
                    <div class="rounded border border-steel-200 p-6 text-center" data-motion-item="true">
                        <p class="font-display text-2xl font-semibold uppercase text-navy-950">{{ $value }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-sections.stats :statistics="$statistics" />

    <section class="section-pad">
        <div class="container-site">
            <x-ui.section-heading eyebrow="Our Team" title="Meet Our Professionals" description="Experienced leaders and specialists committed to delivering high-quality construction outcomes." data-motion="reveal" />
            <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4" data-motion="stagger" data-motion-delay="80">
                @foreach ($teams as $team)
                    <x-cards.team-card :team="$team" data-motion-item="true" />
                @endforeach
            </div>
        </div>
    </section>

    <x-sections.cta :settings="$companySetting" />
</x-layouts.app>
