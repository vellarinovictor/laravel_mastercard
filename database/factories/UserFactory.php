<?php

namespace Database\Factories;

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

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nombre= UserFactory::quitar_tildes(fake()->firstName);
        $apellidos = UserFactory::quitar_tildes(fake()->colorName);
        $domcorreo = fake()->freeEmailDomain();
        $emailaddress = strtolower($nombre . "." . $apellidos . "@" . $domcorreo);

        return [
            'name' => $nombre . " " . $apellidos,
            'email' => $emailaddress,
            'email_verified_at' => NULL,
            'password' => Hash::make("pass"),
            'remember_token' => NULL,
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

    private function quitar_tildes($cadena)
    {
        $cadBuscar = array("á", "Á", "é", "É", "í", "Í", "ó", "Ó", "ú", "Ú");
        $cadPoner = array("a", "A", "e", "E", "i", "I", "o", "O", "u", "U");
        $cadena = str_replace ($cadBuscar, $cadPoner, $cadena);
        return $cadena;
    }
}
