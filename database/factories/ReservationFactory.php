<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Resource; // Asegúrate de importar el modelo correcto
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    protected $model = Reservation::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'resource_id' => Resource::factory(), // Crea un recurso ficticio
            'reserved_at' => $this->faker->dateTimeBetween('+1 week', '+2 weeks'),
            'duration' => 60,
            'status' => 'pending'
        ];
    }
}
