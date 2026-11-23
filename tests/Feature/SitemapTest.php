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

test('sitemap returns xml with indexable static and dynamic urls', function () {
    $this->seed();

    $activeService = Service::factory()->create(['is_active' => true, 'slug' => 'active-service']);
    $inactiveService = Service::factory()->create(['is_active' => false, 'slug' => 'inactive-service']);
    $publishedProject = Project::factory()->create(['is_published' => true, 'slug' => 'published-project']);
    $draftProject = Project::factory()->create(['is_published' => false, 'slug' => 'draft-project']);

    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertHeader('content-type', 'application/xml; charset=UTF-8');
    $response->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false);
    $response->assertSee('<loc>https://example.com</loc>', false);
    $response->assertSee('<loc>https://example.com/about</loc>', false);
    $response->assertSee('<loc>https://example.com/services</loc>', false);
    $response->assertSee('<loc>https://example.com/projects</loc>', false);
    $response->assertSee('<loc>https://example.com/contact</loc>', false);
    $response->assertSee('<loc>https://example.com/services/'.$activeService->slug.'</loc>', false);
    $response->assertSee('<loc>https://example.com/projects/'.$publishedProject->slug.'</loc>', false);
    $response->assertDontSee('/services/'.$inactiveService->slug, false);
    $response->assertDontSee('/projects/'.$draftProject->slug, false);
    $response->assertDontSee('category=', false);
    $response->assertDontSee('page=', false);
    $response->assertDontSee('/404', false);
});

test('sitemap excludes noindex services and projects', function () {
    $service = Service::factory()->create(['is_active' => true, 'noindex' => true]);
    $project = Project::factory()->create(['is_published' => true, 'noindex' => true]);

    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertDontSee(route('services.show', $service), false);
    $response->assertDontSee(route('projects.show', $project), false);
});

test('sitemap excludes static pages when company setting noindex is enabled', function () {
    $this->seed();
    CompanySetting::current()->update(['noindex' => true]);

    $service = Service::query()->where('is_active', true)->where('noindex', false)->firstOrFail();
    $project = Project::query()->where('is_published', true)->where('noindex', false)->firstOrFail();

    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertDontSee('<loc>'.route('home').'</loc>', false);
    $response->assertDontSee('<loc>'.route('about').'</loc>', false);
    $response->assertDontSee('<loc>'.route('services.index').'</loc>', false);
    $response->assertDontSee('<loc>'.route('projects.index').'</loc>', false);
    $response->assertDontSee('<loc>'.route('contact').'</loc>', false);
    $response->assertSee('<loc>'.route('services.show', $service).'</loc>', false);
    $response->assertSee('<loc>'.route('projects.show', $project).'</loc>', false);
});
