@props(['testimonials'])

<section class="section-pad bg-steel-100">
    <div class="container-site">
        <x-ui.section-heading eyebrow="Testimonials" title="What Our Clients Say" description="Trusted by developers, businesses, and public sector partners across Indonesia." align="center" class="mx-auto" />
        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <x-cards.testimonial-card :testimonial="$testimonial" />
            @endforeach
        </div>
    </div>
</section>
