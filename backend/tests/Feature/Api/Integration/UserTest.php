<?php

use App\Models\User;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Illuminate\Foundation\Testing\WithoutMiddleware::class);

test('create user success', function () {
    $user = User::factory()->create();
    $payload = [
        "email" => $user['email'],
        "password" => 'password',
    ];
    $this->post('/api/auth/login', $payload);

    $payload = [
        "name" => "Test",
        "email" => "test@test.com",
        "password" => "123456789",
        "cpfCnpj" => "12345678912345",
        "type" => "1",
        "balance" => "12000",
    ];
    $response = $this->post("/api/users", $payload);

    $response->assertStatus(200)->assertJsonStructure(
        [
            "message",
            "data" => [
                "name",
                "email",
                "cpfCnpj",
                "type",
                "balance"
            ]
        ]
    );
});

