<x-layouts.app title="Contact" description="Contact Nusantara Build Construction for consultation, quotations, and project inquiries.">
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
                        <div><dt class="font-semibold text-navy-900">WhatsApp</dt><dd>{{ $companySetting->whatsapp_number }}</dd></div>
                    </dl>
                </div>
                <div class="overflow-hidden rounded border border-steel-200">
                    <iframe src="{{ $companySetting->google_maps_embed_url }}" class="h-72 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Company location map"></iframe>
                </div>
            </div>

            <div class="rounded border border-steel-200 bg-white p-8 lg:col-span-2">
                <x-ui.section-heading eyebrow="Send a Message" title="Contact Form" description="Fill out the form below and our team will get back to you promptly." />

                @if (session('success'))
                    <div class="mt-6 rounded border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="mt-8 grid gap-6 md:grid-cols-2">
                    @csrf
                    <x-ui.form.input label="Full Name" name="name" required />
                    <x-ui.form.input label="Email Address" name="email" type="email" required />
                    <x-ui.form.input label="Phone Number" name="phone" />
                    <x-ui.form.input label="Subject" name="subject" required />
                    <div class="md:col-span-2">
                        <x-ui.form.textarea label="Message" name="message" rows="6" required />
                    </div>
                    <div class="md:col-span-2">
                        <x-ui.button type="submit">Send Message</x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
