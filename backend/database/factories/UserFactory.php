<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = rand(1, 2);
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'cpfCnpj' => $this->randomCpfCnpj($type),
            'type' => $type,
            'balance' => rand(1, 100000)
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    private function randomCpfCnpj($type) {
        $numero = '';
        $tamanho = ($type == 1) ? 11 : 14;

        // Verifica se o tamanho é maior que 0
        if ($tamanho > 0) {
            // Gera os dígitos aleatórios (pode conter zeros à esquerda)
            for ($i = 0; $i < $tamanho - 1; $i++) {
                $numero .= mt_rand(0, 9);
            }

            // Gera um dígito aleatório diferente de zero como último dígito
            $ultimoDigito = mt_rand(1, 9);
            $numero .= $ultimoDigito;
        }

        return $numero;
    }
}
