<?php

namespace Tests\Feature\Api\Integration;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test Auth with no data
     *
     * @return void
     */
    public function testValidationAuth(): void
    {
        $response = $this->post('/api/auth/login');

        $response->assertStatus(422);
    }

    /**
     * Test Auth with client fake for error
     *
     * @return void
     */
    public function testAuthClientFake()
    {
        $payload = [
            'email' => 'fakeemail@test.com',
            'password' => '1232131'
        ];

        $response = $this->postJson('/api/auth/login', $payload);
        $response->assertStatus(401);
    }

    /**
     * Test Auth with client fake for success
     *
     * @return void
     */
    public function testAuthClientSuccess(){

        $user = User::factory()->create();
        $payload = [
            "email" => $user['email'],
            "password" => 'password',
        ];
        $response = $this->post('/api/auth/login', $payload);

        $response->assertStatus(200)->assertJsonStructure([
            "access_token",
            "token_type",
            "expires_in",
        ]);
    }

    /**
     * Error Get Me
     *
     * @return void
     */
    public function testMeError()
    {
        $response = $this->postJson('/api/auth/me');

        $response->assertStatus(401);
    }

    /**
     * Success Get Me
     *
     * @return void
     */
    public function testMeSuccess()
    {
        $this->loginForTesting();

        $response = $this->postJson("/api/auth/me");

        $response->assertStatus(200)->assertJsonStructure([
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
        ]);
    }

    /**
     * Success logout
     *
     * @return void
     */
    public function testLogoutSuccess()
    {
        $this->loginForTesting();

        $response = $this->postJson("/api/auth/logout");

        $response->assertStatus(200)->assertJsonStructure([
            "message",
        ]);
    }

    /**
     * Error logout
     *
     * @return void
     */
    public function testLogoutError()
    {

        $response = $this->postJson("/api/auth/logout");

        $response->assertStatus(401)->assertJsonStructure([
            "error",
        ]);
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
