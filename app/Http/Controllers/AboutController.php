<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Statistic;
use App\Models\Team;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.about', [
            'companySetting' => CompanySetting::current(),
            'statistics' => Statistic::query()->active()->ordered()->get(),
            'teams' => Team::query()->active()->ordered()->get(),
        ]);
    }
}
