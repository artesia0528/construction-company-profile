<x-layouts.app title="Home" :description="$companySetting->company_description" :seo="$seo">
    <x-sections.hero :settings="$companySetting" :statistics="$statistics" />
    <x-sections.company-intro :settings="$companySetting" />
    <x-sections.services :services="$services" />
    <x-sections.featured-projects :projects="$projects" />
    <x-sections.stats :statistics="$statistics" />
    <x-sections.why-choose-us />
    <x-sections.testimonials :testimonials="$testimonials" />
    <x-sections.cta :settings="$companySetting" />
</x-layouts.app>
