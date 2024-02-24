<?php

namespace Tests\Feature\Api\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithoutMiddleware;
    public function testCreateUserSuccess(){
        $this->loginForTesting();
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
    }


    private function loginForTesting(){
        $user = User::factory()->create();
        $payload = [
            "email" => $user['email'],
            "password" => 'password',
        ];
        $this->post('/api/auth/login', $payload);
    }

}
