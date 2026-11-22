<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactMessageRequest;
use App\Models\CompanySetting;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact', [
            'companySetting' => CompanySetting::current(),
        ]);
    }

    public function store(ContactMessageRequest $request): RedirectResponse
    {
        ContactMessage::query()->create($request->safe()->only([
            'name', 'email', 'phone', 'subject', 'message',
        ]));

        return redirect()->route('contact')->with('success', 'Thank you for your message. Our team will contact you shortly.');
    }
}
