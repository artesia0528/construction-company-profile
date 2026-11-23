<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\Testimonial;
use App\Support\Seo\SchemaBuilder;
use App\Support\Seo\SeoResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request, SeoResolver $seoResolver): View
    {
        $companySetting = CompanySetting::current();

        return view('pages.home', [
            'companySetting' => $companySetting,
            'services' => Service::query()->active()->ordered()->limit(6)->get(),
            'projects' => Project::query()->published()->featured()->latestCompleted()->limit(3)->get(),
            'testimonials' => Testimonial::query()->active()->latest()->limit(3)->get(),
            'statistics' => Statistic::query()->active()->ordered()->get(),
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => 'Home',
                'description' => $companySetting->company_description,
                'seoTitle' => $companySetting->seo_title,
                'seoDescription' => $companySetting->seo_description,
                'seoImage' => $companySetting->seoImageUrl(),
                'canonicalUrl' => $companySetting->canonical_url,
                'noindex' => (bool) $companySetting->noindex,
                'breadcrumbs' => [['label' => 'Home', 'url' => route('home')]],
                'schema' => [app(SchemaBuilder::class)->webPage('WebPage', 'Home', route('home'), $companySetting->seo_description ?: $companySetting->company_description)],
            ]),
        ]);
    }
}
