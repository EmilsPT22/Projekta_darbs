<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');
    $response->assertStatus(200);
});

test('new users can register and are students', function () {
    $this->get('/register');

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect('/internships');

    $user = User::where('email', 'test@example.com')->first();
    expect($user->hasRole('student'))->toBeTrue();
});
