@props(['settings'])

<footer class="bg-navy-950 text-white">
    <div class="container-site grid gap-10 py-16 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <p class="font-display text-3xl font-semibold">{{ $settings->company_name }}</p>
            <p class="mt-4 text-sm leading-7 text-white/70">{{ $settings->company_description }}</p>
        </div>
        <div>
            <p class="font-display text-xl font-semibold uppercase tracking-wide">Quick Links</p>
            <ul class="mt-4 space-y-3 text-sm text-white/75">
                <li><a class="hover:text-accent-500" href="{{ route('about') }}">About Us</a></li>
                <li><a class="hover:text-accent-500" href="{{ route('services.index') }}">Services</a></li>
                <li><a class="hover:text-accent-500" href="{{ route('projects.index') }}">Projects</a></li>
                <li><a class="hover:text-accent-500" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>
        <div>
            <p class="font-display text-xl font-semibold uppercase tracking-wide">Contact</p>
            <ul class="mt-4 space-y-3 text-sm text-white/75">
                <li>{{ $settings->company_address }}</li>
                <li><a class="hover:text-accent-500" href="tel:{{ $settings->phone_number }}">{{ $settings->phone_number }}</a></li>
                <li><a class="hover:text-accent-500" href="mailto:{{ $settings->email_address }}">{{ $settings->email_address }}</a></li>
            </ul>
        </div>
        <div>
            <p class="font-display text-xl font-semibold uppercase tracking-wide">Follow Us</p>
            <div class="mt-4 flex flex-wrap gap-3 text-sm">
                @if ($settings->facebook_url)<a class="rounded border border-white/20 px-3 py-2 hover:border-accent-500 hover:text-accent-500" href="{{ $settings->facebook_url }}" target="_blank" rel="noopener">Facebook</a>@endif
                @if ($settings->instagram_url)<a class="rounded border border-white/20 px-3 py-2 hover:border-accent-500 hover:text-accent-500" href="{{ $settings->instagram_url }}" target="_blank" rel="noopener">Instagram</a>@endif
                @if ($settings->linkedin_url)<a class="rounded border border-white/20 px-3 py-2 hover:border-accent-500 hover:text-accent-500" href="{{ $settings->linkedin_url }}" target="_blank" rel="noopener">LinkedIn</a>@endif
            </div>
        </div>
    </div>
    <div class="border-t border-white/10 py-5">
        <div class="container-site text-sm text-white/60">{{ $settings->copyright_text }}</div>
    </div>
</footer>
