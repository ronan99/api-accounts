<?php

use App\Enums\UserType;
use App\Jobs\TransactionDispatcher;
use App\Models\User;
use Illuminate\Support\Facades\Queue;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);
uses(\Illuminate\Foundation\Testing\WithoutMiddleware::class);

test('transfer balance success', function () {
    Queue::fake();
    $userFrom = User::factory()->state(["type" => UserType::PERSON])->create();
    $payload = [
        "email" => $userFrom['email'],
        "password" => 'password',
    ];
    $loginRes = $this->post('/api/auth/login', $payload);

    $loginRes->assertStatus(200);

    $userTo = User::factory()->create();

    $payload = [
        "userTo" => $userTo['id'],
        "amount" => 100
    ];
    $response = $this->post("/api/transactions", $payload);

    $response->assertOk();

    Queue::assertPushed(TransactionDispatcher::class);
});

test('insufficient balance error', function () {
    Queue::fake();
    Queue::assertNothingPushed();
    $userFrom = User::factory()->state(["type" => UserType::PERSON])->create();
    $payload = [
        "email" => $userFrom['email'],
        "password" => 'password',
    ];
    $loginRes = $this->post('/api/auth/login', $payload);

    $loginRes->assertStatus(200);

    $userTo = User::factory()->create();

    $payload = [
        "userTo" => $userTo['id'],
        "amount" => 1000000
    ];
    $response = $this->post("/api/transactions", $payload);

    $response->assertStatus(402)->assertJsonStructure(["message"]);

    Queue::assertNotPushed(TransactionDispatcher::class);
});

test('user type merchant error', function () {
    $userFrom = User::factory()->state(["type" => UserType::MERCHANT])->create();
    $payload = [
        "email" => $userFrom['email'],
        "password" => 'password',
    ];
    $loginRes = $this->post('/api/auth/login', $payload);

    $loginRes->assertStatus(200);

    $userTo = User::factory()->create();

    $payload = [
        "userTo" => $userTo['id'],
        "amount" => 1000
    ];
    $response = $this->post("/api/transactions", $payload);

    $response->assertStatus(400)->assertJsonStructure(["message"]);
});

test('same user error', function () {
    $userFrom = User::factory()->state(["type" => UserType::MERCHANT])->create();
    $payload = [
        "email" => $userFrom['email'],
        "password" => 'password',
    ];
    $loginRes = $this->post('/api/auth/login', $payload);

    $loginRes->assertStatus(200);

    $payload = [
        "userTo" => $userFrom['id'],
        "amount" => 1000
    ];
    $response = $this->post("/api/transactions", $payload);

    $response->assertStatus(400)->assertJsonStructure(["message"]);
});
