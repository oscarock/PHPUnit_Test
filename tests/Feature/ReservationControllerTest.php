<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Reservation;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    /*  crea un resource correctamente antes */
    public function test_it_deletes_a_reservation()
    {
      $resource = Resource::factory()->create(); // Asegurar que haya un recurso
      $reservation = Reservation::factory()->create(['resource_id' => $resource->id]); 

      $response = $this->deleteJson("/api/reservations/{$reservation->id}");

      $response->assertStatus(200);
      $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }

    /** @test */
    /*  crea una reserva correctamente */
    public function it_creates_a_reservation()
    {
        $resource = Resource::factory()->create();

        $reservationData = [
            'resource_id' => $resource->id,
            'reserved_at' => now()->toDateTimeString(),
            'duration' => 2
        ];

        $response = $this->postJson('/api/reservations', $reservationData);

        $response->assertStatus(200);
      
        $this->assertDatabaseHas('reservations', $reservationData);
    }

    /** @test */
    /* elimina la reserva correctamente. */
    public function it_deletes_a_reservation()
    {
        $reservation = Reservation::factory()->create();

        $response = $this->deleteJson("/api/reservations/{$reservation->id}");

        $response->assertStatus(200)
        ->assertJson(['data' => 'Reservation cancelled successful']);


        $this->assertDatabaseMissing('reservations', ['id' => $reservation->id]);
    }
}
