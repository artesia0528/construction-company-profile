<?php

use Illuminate\Support\Facades\URL;

beforeEach(function () {
    config()->set('app.url', 'https://example.com');
    URL::forceRootUrl('https://example.com');
    URL::forceScheme('https');
});

test('robots txt returns crawl rules and sitemap', function () {
    $response = $this->get('/robots.txt');

    $response->assertOk();
    $this->assertStringContainsString('text/plain', (string) $response->headers->get('content-type'));
    $response->assertSee('User-agent: *', false);
    $response->assertSee('Disallow:', false);
    $response->assertSee('Sitemap: https://example.com/sitemap.xml', false);
});
