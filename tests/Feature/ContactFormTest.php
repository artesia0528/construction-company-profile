<?php

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
