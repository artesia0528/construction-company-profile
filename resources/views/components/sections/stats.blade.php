@props(['statistics', 'light' => false])

<section class="{{ $light ? 'bg-navy-950 py-16' : 'section-pad bg-steel-100' }}">
    <div class="container-site">
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($statistics as $statistic)
                <x-cards.statistic-card :statistic="$statistic" :light="$light" />
            @endforeach
        </div>
    </div>
</section>
