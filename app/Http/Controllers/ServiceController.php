<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Service;
use App\Support\Seo\SchemaBuilder;
use App\Support\Seo\SeoResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request, SeoResolver $seoResolver): View
    {
        $companySetting = CompanySetting::current();

        return view('pages.services.index', [
            'companySetting' => $companySetting,
            'services' => Service::query()->active()->ordered()->get(),
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => 'Services',
                'description' => 'Explore '.$companySetting->company_name.' services for building, infrastructure, renovation, and project management.',
                'noindex' => (bool) $companySetting->noindex,
                'breadcrumbs' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Services', 'url' => route('services.index')]],
                'schema' => [app(SchemaBuilder::class)->webPage('CollectionPage', 'Services', route('services.index'), 'Explore '.$companySetting->company_name.' services for building, infrastructure, renovation, and project management.')],
            ]),
        ]);
    }

    public function show(Request $request, Service $service, SeoResolver $seoResolver): View
    {
        abort_unless($service->is_active, 404);

        $companySetting = CompanySetting::current();

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Services', 'url' => route('services.index')],
            ['label' => $service->name, 'url' => route('services.show', $service)],
        ];

        return view('pages.services.show', [
            'companySetting' => $companySetting,
            'service' => $service,
            'otherServices' => Service::query()->active()->whereKeyNot($service->id)->ordered()->limit(5)->get(),
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => $service->name,
                'description' => $service->short_description,
                'image' => $service->thumbnailUrl(),
                'imageAlt' => $service->thumbnail_alt_text ?: $service->name,
                'seoTitle' => $service->seo_title,
                'seoDescription' => $service->seo_description,
                'seoImage' => $service->seoImageUrl(),
                'canonicalUrl' => $service->canonical_url,
                'noindex' => (bool) $service->noindex,
                'type' => 'article',
                'breadcrumbs' => $breadcrumbs,
                'schema' => [app(SchemaBuilder::class)->service($service, route('services.show', $service))],
            ]),
        ]);
    }
}
