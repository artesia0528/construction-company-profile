<x-layouts.app title="Contact" description="Contact Nusantara Build Construction for consultation, quotations, and project inquiries." :seo="$seo">
    <x-sections.page-banner eyebrow="Contact Us" title="Let’s Discuss Your Project" description="Reach out to our team for consultations, project inquiries, or partnership opportunities." :breadcrumbs="[['url' => route('home'), 'label' => 'Home'], ['label' => 'Contact']]" />

    <section class="section-pad">
        <div class="container-site grid gap-10 lg:grid-cols-3">
            <div class="space-y-6">
                <div class="rounded border border-steel-200 bg-steel-100 p-6">
                    <p class="font-display text-2xl font-semibold uppercase text-navy-950">Contact Information</p>
                    <dl class="mt-5 space-y-4 text-sm text-steel-700">
                        <div><dt class="font-semibold text-navy-900">Address</dt><dd>{{ $companySetting->company_address }}</dd></div>
                        <div><dt class="font-semibold text-navy-900">Phone</dt><dd><a class="hover:text-accent-600" href="tel:{{ $companySetting->phone_number }}">{{ $companySetting->phone_number }}</a></dd></div>
                        <div><dt class="font-semibold text-navy-900">Email</dt><dd><a class="hover:text-accent-600" href="mailto:{{ $companySetting->email_address }}">{{ $companySetting->email_address }}</a></dd></div>
                        <div><dt class="font-semibold text-navy-900">WhatsApp</dt><dd><a class="hover:text-accent-600" href="https://wa.me/{{ $companySetting->whatsapp_number }}" target="_blank" rel="noopener noreferrer">{{ $companySetting->whatsapp_number }}</a></dd></div>
                    </dl>
                </div>
                <div class="overflow-hidden rounded border border-steel-200">
                    <iframe src="{{ $companySetting->google_maps_embed_url }}" class="h-72 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Company location map"></iframe>
                </div>
            </div>

            <div class="rounded border border-steel-200 bg-white p-8 lg:col-span-2">
                <x-ui.section-heading eyebrow="Send a Message" title="Contact Form" description="Fill out the form below and our team will get back to you promptly." />

                <div data-contact-form-feedback class="mt-6 space-y-3">
                    <div data-contact-form-success class="{{ session('success') ? '' : 'hidden' }} rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700" role="status" aria-live="polite">{{ session('success') }}</div>
                    <div data-contact-form-error class="hidden rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert"></div>
                </div>

                <form action="{{ route('contact.store') }}" method="POST" class="mt-8 grid gap-6 md:grid-cols-2" data-contact-form>
                    @csrf
                    <x-ui.form.input label="Full Name" name="name" required />
                    <x-ui.form.input label="Email Address" name="email" type="email" required />
                    <x-ui.form.input label="Phone Number" name="phone" />
                    <x-ui.form.input label="Subject" name="subject" required />
                    <div class="md:col-span-2">
                        <x-ui.form.textarea label="Message" name="message" rows="6" required />
                    </div>
                    <div class="md:col-span-2">
                        <x-ui.button type="submit" data-contact-form-submit>Send Message</x-ui.button>
                    </div>
                </form>
            </div>
        </div>

        <div class="container-site mt-16 max-w-4xl">
            <x-ui.section-heading eyebrow="FAQ" title="Frequently Asked Questions" description="Quick answers to common questions before you contact our team." align="center" class="mx-auto" />
            <div class="mt-10">
                <x-ui.accordion :single="true">
                    <x-ui.accordion.item title="What types of projects do you handle?" :open="true">
                        We handle commercial, residential, industrial, and infrastructure projects, including new construction, renovation, and project management services.
                    </x-ui.accordion.item>
                    <x-ui.accordion.item title="How can I request a project quotation?">
                        Use the contact form on this page or reach us by phone/email. Share your project location, scope, timeline, and any available drawings so we can prepare an accurate estimate.
                    </x-ui.accordion.item>
                    <x-ui.accordion.item title="Do you provide consultation before construction starts?">
                        Yes. We can support early planning, feasibility discussions, budgeting, scheduling, and technical recommendations before construction begins.
                    </x-ui.accordion.item>
                </x-ui.accordion>
            </div>
        </div>
    </section>
</x-layouts.app>
