<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Project;
use App\Models\Service;
use App\Models\Statistic;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.home', [
            'companySetting' => CompanySetting::current(),
            'services' => Service::query()->active()->ordered()->limit(6)->get(),
            'projects' => Project::query()->published()->featured()->latestCompleted()->limit(3)->get(),
            'testimonials' => Testimonial::query()->active()->latest()->limit(3)->get(),
            'statistics' => Statistic::query()->active()->ordered()->get(),
        ]);
    }
}
