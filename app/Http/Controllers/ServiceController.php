<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('pages.services.index', [
            'companySetting' => CompanySetting::current(),
            'services' => Service::query()->active()->ordered()->get(),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('pages.services.show', [
            'companySetting' => CompanySetting::current(),
            'service' => $service,
            'otherServices' => Service::query()->active()->whereKeyNot($service->id)->ordered()->limit(5)->get(),
        ]);
    }
}
