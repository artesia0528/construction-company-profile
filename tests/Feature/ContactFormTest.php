<?php

use Livewire\Livewire;

test('contact form validates required fields', function () {
    $response = $this->post(route('contact.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
});

test('contact form stores message', function () {
    $response = $this->post(route('contact.store'), [
        'name' => 'Jane Client',
        'email' => 'jane@example.com',
        'phone' => '+62 811 1111 1111',
        'subject' => 'Project Inquiry',
        'message' => 'I would like to discuss a new construction project.',
    ]);

    $response->assertRedirect(route('contact'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'jane@example.com',
        'subject' => 'Project Inquiry',
        'is_read' => false,
    ]);
});

test('contact form returns json validation errors for ajax requests', function () {
    $response = $this->postJson(route('contact.store'), []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
});

test('contact form returns json success for ajax requests', function () {
    $response = $this->postJson(route('contact.store'), [
        'name' => 'Jane Client',
        'email' => 'jane@example.com',
        'phone' => '+62 811 1111 1111',
        'subject' => 'Project Inquiry',
        'message' => 'I would like to discuss a new construction project.',
    ]);

    $response->assertOk()
        ->assertJson(['message' => 'Thank you for your message. Our team will contact you shortly.']);

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'jane@example.com',
        'subject' => 'Project Inquiry',
    ]);
});

test('contact page renders livewire form with post fallback', function () {
    $this->get(route('contact'))
        ->assertOk()
        ->assertSeeLivewire('contact-form')
        ->assertSee('action="'.route('contact.store').'"', false)
        ->assertSee('method="POST"', false)
        ->assertSee('wire:submit="submit"', false);
});

test('livewire contact form validates required fields', function () {
    Livewire::test('contact-form')
        ->call('submit')
        ->assertHasErrors(['name', 'email', 'subject', 'message']);

    $this->assertDatabaseEmpty('contact_messages');
});

test('livewire contact form stores message and resets fields', function () {
    Livewire::test('contact-form')
        ->set([
            'name' => 'Jane Client',
            'email' => 'jane@example.com',
            'phone' => '+62 811 1111 1111',
            'subject' => 'Project Inquiry',
            'message' => 'I would like to discuss a new construction project.',
        ])
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('sent', true)
        ->assertSet('name', '')
        ->assertSet('email', '')
        ->assertSee('Thank you for your message. Our team will contact you shortly.');

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'jane@example.com',
        'subject' => 'Project Inquiry',
        'is_read' => false,
    ]);
});

test('livewire contact form restores old input after post fallback', function () {
    $this->withSession(['_old_input' => [
        'name' => 'Jane Client',
        'message' => 'Please call me about the project.',
    ]])
        ->get(route('contact'))
        ->assertOk()
        ->assertSee('value="Jane Client"', false)
        ->assertSee('Please call me about the project.');
});
