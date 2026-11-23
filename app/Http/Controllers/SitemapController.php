<?php

namespace App\Http\Controllers;

use App\Support\Seo\SitemapBuilder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(SitemapBuilder $builder): Response
    {
        return response()
            ->view('seo.sitemap', ['entries' => $builder->entries()])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
