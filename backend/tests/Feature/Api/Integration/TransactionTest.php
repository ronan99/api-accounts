<?php

namespace Tests\Feature\Api\Integration;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;

    /**
     * Transferir saldo com saldo.
     */
    public function testTransferBalanceSuccess(): void
    {
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

        $response->assertStatus(200)->assertJson(fn (AssertableJson $json) =>
        $json->where('data.from', $userFrom['id'])
             ->where('data.to', $userTo['id'])
             ->where('data.amount', 100)
             ->etc()
        );
    }

    /**
     * Transferir saldo sem saldo.
     */
    public function testInsufficientBalanceError(): void
    {
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
    }

    /**
     * Transferir saldo com usuário merchant
     */
    public function testUserTypeMerchantError(): void
    {
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
    }


    /**
     * Transferir saldo para o mesmo usuário
     */
    public function testSameUserError(): void
    {
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
    }


}
