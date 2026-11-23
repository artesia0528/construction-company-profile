<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Statistic;
use App\Models\Team;
use App\Support\Seo\SchemaBuilder;
use App\Support\Seo\SeoResolver;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(Request $request, SeoResolver $seoResolver): View
    {
        $companySetting = CompanySetting::current();

        return view('pages.about', [
            'companySetting' => $companySetting,
            'statistics' => Statistic::query()->active()->ordered()->get(),
            'teams' => Team::query()->active()->ordered()->get(),
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => 'About Us',
                'description' => 'Learn about '.$companySetting->company_name.', our history, vision, mission, and team.',
                'breadcrumbs' => [['label' => 'Home', 'url' => route('home')], ['label' => 'About Us', 'url' => route('about')]],
                'schema' => [app(SchemaBuilder::class)->webPage('AboutPage', 'About Us', route('about'), 'Learn about '.$companySetting->company_name.', our history, vision, mission, and team.')],
            ]),
        ]);
    }
}
