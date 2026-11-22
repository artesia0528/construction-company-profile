<?php

use App\Models\User;

test('admin panel requires authentication', function () {
    $this->get('/admin')->assertRedirect('/admin/login');
});

test('authenticated user can access admin panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/admin')->assertOk();
});
