<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Models\CompanySetting;
use App\Models\ContactMessage;
use App\Support\Seo\SchemaBuilder;
use App\Support\Seo\SeoResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(Request $request, SeoResolver $seoResolver): View
    {
        $companySetting = CompanySetting::current();
        $faqs = config('contact.faqs', []);
        $description = 'Contact '.$companySetting->company_name.' for consultation, quotations, and project inquiries.';
        $schemaBuilder = app(SchemaBuilder::class);
        $schema = [$schemaBuilder->webPage('ContactPage', 'Contact', route('contact'), $description)];
        $faqSchema = $schemaBuilder->faqPage($faqs);

        if ($faqSchema !== null) {
            $schema[] = $faqSchema;
        }

        return view('pages.contact', [
            'companySetting' => $companySetting,
            'faqs' => $faqs,
            'seo' => $seoResolver->resolve($request, $companySetting, [
                'title' => 'Contact',
                'description' => $description,
                'noindex' => (bool) $companySetting->noindex,
                'breadcrumbs' => [['label' => 'Home', 'url' => route('home')], ['label' => 'Contact', 'url' => route('contact')]],
                'schema' => $schema,
            ]),
        ]);
    }

    public function store(ContactMessageRequest $request): RedirectResponse|JsonResponse
    {
        ContactMessage::query()->create($request->safe()->only([
            'name', 'email', 'phone', 'subject', 'message',
        ]));

        $message = 'Thank you for your message. Our team will contact you shortly.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route('contact')->with('success', $message);
    }
}
