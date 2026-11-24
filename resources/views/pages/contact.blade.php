<x-layouts.app title="Contact" description="Contact Nusantara Build Construction for consultation, quotations, and project inquiries." :seo="$seo">
    <x-sections.page-banner eyebrow="Contact Us" title="Let’s Discuss Your Project" description="Reach out to our team for consultations, project inquiries, or partnership opportunities." :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['label' => 'Contact']]" />

    <section class="section-pad">
        <div class="container-site grid gap-10 lg:grid-cols-3">
            <div class="space-y-6">
                <div class="rounded border border-steel-200 bg-steel-100 p-6" data-motion="reveal">
                    <p class="font-display text-2xl font-semibold uppercase text-navy-950">Contact Information</p>
                    <dl class="mt-5 space-y-4 text-sm text-steel-700">
                        <div><dt class="font-semibold text-navy-900">Address</dt><dd>{{ $companySetting->company_address }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Phone</dt><dd><a class="hover:text-accent-600" href="tel:{{ $companySetting->phone_number }}">{{ $companySetting->phone_number }}</a></dd></div>
                        <div><dt class="font-semibold text-navy-900">Email</dt><dd><a class="hover:text-accent-600" href="mailto:{{ $companySetting->email_address }}">{{ $companySetting->email_address }}</a></dd></div>
                        <div><dt class="font-semibold text-navy-900">WhatsApp</dt><dd><a class="hover:text-accent-600" href="https://wa.me/{{ $companySetting->whatsapp_number }}" target="_blank" rel="noopener noreferrer">{{ $companySetting->whatsapp_number }}</a></dd></div>
                    </dl>
                </div>
                <div class="overflow-hidden rounded border border-steel-200" data-motion="reveal" data-motion-delay="80">
                    <iframe src="{{ $companySetting->google_maps_embed_url }}" class="h-72 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Company location map"></iframe>
                </div>
            </div>

            <div class="rounded border border-steel-200 bg-white p-8 lg:col-span-2" data-motion="reveal" data-motion-delay="120">
                <x-ui.section-heading eyebrow="Send a Message" title="Contact Form" description="Fill out the form below and our team will get back to you promptly." />

                <div class="mt-8">
                    <livewire:contact-form />
                </div>
            </div>
        </div>

        <div class="container-site mt-16 max-w-4xl">
            <x-ui.section-heading eyebrow="FAQ" title="Frequently Asked Questions" description="Quick answers to common questions before you contact our team." align="center" class="mx-auto" data-motion="reveal" />
            <div class="mt-10">
                <x-ui.accordion>
                    @foreach ($faqs as $faq)
                        <x-ui.accordion.item name="contact-faq" :title="$faq['question']" :open="$loop->first">
                            {{ $faq['answer'] }}
                        </x-ui.accordion.item>
                    @endforeach
                </x-ui.accordion>
            </div>
        </div>
    </section>
</x-layouts.app>
