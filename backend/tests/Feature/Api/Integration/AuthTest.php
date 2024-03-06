<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('validation auth', function () {
    $response = $this->post('/api/auth/login');

    $response->assertStatus(422);
});

test('auth client fake', function () {
    $payload = [
        'email' => 'fakeemail@test.com',
        'password' => '1232131'
    ];

    $response = $this->postJson('/api/auth/login', $payload);
    $response->assertStatus(401);
});

test('auth client success', function () {
    $user = User::factory()->create();
    $payload = [
        "email" => $user['email'],
        "password" => 'password',
    ];
    $response = $this->post('/api/auth/login', $payload);
    info(json_encode($response));
    $response->assertStatus(200)->assertJsonStructure([
        "message",
        "data" =>
        [
            "access_token",
            "token_type",
            "expires_in",
        ]
    ]);
});

test('me error', function () {
    $response = $this->postJson('/api/auth/me');

    $response->assertStatus(401);
});

test('me success', function () {
    $user = User::factory()->create();
    $payload = [
        "email" => $user['email'],
        "password" => 'password',
    ];
    $this->post('/api/auth/login', $payload);

    $response = $this->postJson("/api/auth/me");
    $response->assertStatus(200)->assertJsonStructure([
        "data" => [
            "id",
            "name",
            "email",
            "cpfCnpj",
            "balance",
            "type",
            "email_verified_at",
            "created_at",
            "updated_at",
            "deleted_at"
        ]
    ]);
});

test('logout success', function () {
    $user = User::factory()->create();
    $payload = [
        "email" => $user['email'],
        "password" => 'password',
    ];
    $this->post('/api/auth/login', $payload);

    $response = $this->postJson("/api/auth/logout");

    $response->assertStatus(200)->assertJsonStructure([
        "message",
    ]);
});

test('logout error', function () {
    $response = $this->postJson("/api/auth/logout");

    $response->assertStatus(401)->assertJsonStructure([
        "error",
    ]);
});
