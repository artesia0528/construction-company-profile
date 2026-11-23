<?php

use App\Models\CompanySetting;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Support\Facades\URL;

beforeEach(function () {
    config()->set('app.url', 'https://example.com');
    URL::forceRootUrl('https://example.com');
    URL::forceScheme('https');
});

test('home page renders centralized seo metadata', function () {
    $this->seed();

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('<title>Home | Nusantara Build Construction</title>', false);
    $response->assertSee('<meta name="description" content="Nusantara Build Construction delivers reliable construction solutions for commercial, residential, and infrastructure projects across Indonesia.">', false);
    $response->assertSee('<meta name="robots" content="index,follow">', false);
    $response->assertSee('<link rel="canonical" href="https://example.com">', false);
    $response->assertSee('<meta property="og:title" content="Home | Nusantara Build Construction">', false);
    $response->assertSee('<meta property="og:description" content="Nusantara Build Construction delivers reliable construction solutions for commercial, residential, and infrastructure projects across Indonesia.">', false);
    $response->assertSee('<meta property="og:type" content="website">', false);
    $response->assertSee('<meta property="og:url" content="https://example.com">', false);
    $response->assertSee('<meta property="og:site_name" content="Nusantara Build Construction">', false);
    $response->assertSee('<meta property="og:locale" content="en">', false);
    $response->assertSee('<meta name="twitter:card" content="summary">', false);
    $response->assertSee('<meta name="twitter:title" content="Home | Nusantara Build Construction">', false);
});

test('service detail page renders model based seo metadata', function () {
    $this->seed();

    $service = Service::query()->where('is_active', true)->firstOrFail();
    $service->forceFill([
        'thumbnail_image' => 'services/test-service.webp',
        'thumbnail_alt_text' => 'Service thumbnail',
    ])->save();

    $response = $this->get(route('services.show', $service));

    $response->assertOk();
    $response->assertSee('<title>'.$service->name.' | Nusantara Build Construction</title>', false);
    $response->assertSee('<meta name="description" content="'.$service->short_description.'">', false);
    $response->assertSee('<link rel="canonical" href="'.route('services.show', $service).'">', false);
    $response->assertSee('<meta name="robots" content="index,follow">', false);
    $response->assertSee('<meta property="og:type" content="article">', false);
    $response->assertSee('<meta property="og:url" content="'.route('services.show', $service).'">', false);
    $response->assertSee('<meta property="og:image" content="https://example.com/storage/services/test-service.webp">', false);
    $response->assertSee('<meta property="og:image:alt" content="Service thumbnail">', false);
    $response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
    $response->assertSee('<meta name="twitter:image" content="https://example.com/storage/services/test-service.webp">', false);
});

test('project detail page renders model based seo metadata', function () {
    $this->seed();

    $project = Project::query()->where('is_published', true)->firstOrFail();
    $project->forceFill([
        'cover_image' => 'projects/test-project.webp',
        'cover_alt_text' => 'Project cover',
    ])->save();

    $response = $this->get(route('projects.show', $project));

    $response->assertOk();
    $response->assertSee('<title>'.$project->title.' | Nusantara Build Construction</title>', false);
    $response->assertSee('<meta name="description" content="'.$project->short_description.'">', false);
    $response->assertSee('<link rel="canonical" href="'.route('projects.show', $project).'">', false);
    $response->assertSee('<meta name="robots" content="index,follow">', false);
    $response->assertSee('<meta property="og:type" content="article">', false);
    $response->assertSee('<meta property="og:image" content="https://example.com/storage/projects/test-project.webp">', false);
    $response->assertSee('<meta property="og:image:alt" content="Project cover">', false);
    $response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
});

test('filtered project listing is noindex and canonicalizes to projects index', function () {
    $this->seed();

    $category = Project::query()->where('is_published', true)->whereNotNull('category')->value('category');

    $response = $this->get(route('projects.index', ['category' => $category]));

    $response->assertOk();
    $response->assertSee('<meta name="robots" content="noindex,follow">', false);
    $response->assertSee('<link rel="canonical" href="'.route('projects.index').'">', false);
    $response->assertSee('<meta property="og:url" content="'.route('projects.index').'">', false);
    $response->assertSee('<meta property="og:type" content="website">', false);
});

test('paginated project listing is indexable and keeps self canonical', function () {
    Project::factory()->count(12)->create([
        'is_published' => true,
        'category' => 'Commercial',
    ]);

    $response = $this->get(route('projects.index', ['page' => 2]));

    $response->assertOk();
    $response->assertSee('<meta name="robots" content="index,follow">', false);
    $response->assertSee('<link rel="canonical" href="'.route('projects.index', ['page' => 2]).'">', false);
    $response->assertSee('<meta property="og:url" content="'.route('projects.index', ['page' => 2]).'">', false);
});

test('filtered and paginated project listing is noindex and canonicalizes to projects index', function () {
    Project::factory()->count(12)->create([
        'is_published' => true,
        'category' => 'Commercial',
    ]);

    $response = $this->get(route('projects.index', ['category' => 'Commercial', 'page' => 2]));

    $response->assertOk();
    $response->assertSee('<meta name="robots" content="noindex,follow">', false);
    $response->assertSee('<link rel="canonical" href="'.route('projects.index').'">', false);
    $response->assertSee('<meta property="og:url" content="'.route('projects.index').'">', false);
});

test('service detail seo fields override defaults', function () {
    $this->seed();
    $service = Service::factory()->create([
        'is_active' => true,
        'name' => 'Service A',
        'short_description' => 'Short desc',
        'seo_title' => 'Custom SEO Service',
        'seo_description' => 'Custom SEO description',
        'canonical_url' => 'https://example.com/custom-service',
        'noindex' => true,
    ]);

    $response = $this->get(route('services.show', $service));

    $response->assertOk();
    $response->assertSee('<title>Custom SEO Service | Nusantara Build Construction</title>', false);
    $response->assertSee('<meta name="description" content="Custom SEO description">', false);
    $response->assertSee('<link rel="canonical" href="https://example.com/custom-service">', false);
    $response->assertSee('<meta name="robots" content="noindex,follow">', false);
});

test('project detail seo fields override defaults', function () {
    $this->seed();
    $project = Project::factory()->create([
        'is_published' => true,
        'title' => 'Project A',
        'short_description' => 'Short desc',
        'seo_title' => 'Custom SEO Project',
        'seo_description' => 'Custom SEO project description',
        'canonical_url' => '/custom-project-canonical',
        'noindex' => true,
    ]);

    $response = $this->get(route('projects.show', $project));

    $response->assertOk();
    $response->assertSee('<title>Custom SEO Project | Nusantara Build Construction</title>', false);
    $response->assertSee('<meta name="description" content="Custom SEO project description">', false);
    $response->assertSee('<link rel="canonical" href="https://example.com/custom-project-canonical">', false);
    $response->assertSee('<meta name="robots" content="noindex,follow">', false);
});

test('home seo fields override defaults', function () {
    $this->seed();
    $settings = CompanySetting::current();
    $settings->update([
        'seo_title' => 'Custom Home SEO',
        'seo_description' => 'Custom home description',
        'canonical_url' => 'https://example.com/custom-home',
        'noindex' => true,
    ]);

    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('<title>Custom Home SEO | Nusantara Build Construction</title>', false);
    $response->assertSee('<meta name="description" content="Custom home description">', false);
    $response->assertSee('<link rel="canonical" href="https://example.com/custom-home">', false);
    $response->assertSee('<meta name="robots" content="noindex,follow">', false);
});

test('canonical strips tracking params', function () {
    $this->seed();

    $response = $this->get(route('about', ['utm_source' => 'google', 'fbclid' => 'abc', 'id' => '1']));

    $response->assertOk();
    $response->assertSee('<link rel="canonical" href="https://example.com/about?id=1">', false);
});

test('404 page returns noindex metadata', function () {
    $this->seed();

    $response = $this->get('/this-page-does-not-exist');

    $response->assertNotFound();
    $response->assertSee('<meta name="robots" content="noindex,follow">', false);
    $response->assertSee('<title>Page Not Found | Nusantara Build Construction</title>', false);
    $response->assertSee('Page Not Found', false);
    $response->assertSee(route('home'), false);
});

test('static pages render expected schema types', function () {
    $this->seed();

    $this->get(route('about'))->assertOk()
        ->assertSee('"@type": "AboutPage"', false)
        ->assertSee('"@type": "BreadcrumbList"', false);

    $this->get(route('contact'))->assertOk()
        ->assertSee('"@type": "ContactPage"', false)
        ->assertSee('"@type": "BreadcrumbList"', false);

    $this->get(route('services.index'))->assertOk()
        ->assertSee('"@type": "CollectionPage"', false)
        ->assertSee('"@type": "BreadcrumbList"', false);

    $this->get(route('projects.index'))->assertOk()
        ->assertSee('"@type": "CollectionPage"', false)
        ->assertSee('"@type": "BreadcrumbList"', false);
});

test('company setting noindex applies to all public static pages', function () {
    $this->seed();
    CompanySetting::current()->update(['noindex' => true]);

    foreach ([route('home'), route('about'), route('services.index'), route('projects.index'), route('contact')] as $url) {
        $this->get($url)->assertOk()->assertSee('<meta name="robots" content="noindex,follow">', false);
    }

    $service = Service::query()->where('is_active', true)->firstOrFail();
    $project = Project::query()->where('is_published', true)->firstOrFail();

    $this->get(route('services.show', $service))->assertOk()->assertSee('<meta name="robots" content="index,follow">', false);
    $this->get(route('projects.show', $project))->assertOk()->assertSee('<meta name="robots" content="index,follow">', false);
});
