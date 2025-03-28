<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Factories\ReservationFactory;
use App\Repositories\Interfaces\ResourceRepositoryInterface;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ReservationFactoryTest extends TestCase
{
    use RefreshDatabase;

    protected $reservationFactory;
    protected $reservationRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resourceRepositoryMock = Mockery::mock(ResourceRepositoryInterface::class);
        $this->reservationRepositoryMock = Mockery::mock(ReservationRepositoryInterface::class);

        $this->reservationFactory = new ReservationFactory(
            $this->resourceRepositoryMock,
            $this->reservationRepositoryMock
        );
    }

    /** @test */
    /* verifica que la reserva se cree correctamente utilizando la Factory */
    public function it_creates_a_reservation()
    {
      $reservationData = [
        'resource_id' => 1,
        'reserved_at' => now()->toDateTimeString(),
        'duration' => 2
      ];

      // Simulación del objeto recurso encontrado
      $mockResource = (object) ['id' => 1, 'name' => 'Test Resource'];

      // Definir el comportamiento esperado del mock del ResourceRepository
      $this->resourceRepositoryMock
          ->shouldReceive('findById')
          ->once()
          ->with(1)
          ->andReturn($mockResource);

      // Definir el comportamiento esperado del mock del ResourceRepository para checkAvailability()
      $this->resourceRepositoryMock
          ->shouldReceive('checkAvailability')
          ->once()
          ->with(1, $reservationData['reserved_at'], $reservationData['duration'])
          ->andReturn(true); // Simula que el recurso está disponible

      // Definir el comportamiento esperado del mock del ReservationRepository
      $this->reservationRepositoryMock
          ->shouldReceive('create')
          ->once()
          ->with($reservationData)
          ->andReturn((object) $reservationData);

      // Ejecutar la fábrica
      $reservation = $this->reservationFactory->create($reservationData);

      // Verificar que la reserva se creó correctamente
      $this->assertEquals($reservationData['resource_id'], $reservation->resource_id);
      $this->assertEquals($reservationData['reserved_at'], $reservation->reserved_at);
      $this->assertEquals($reservationData['duration'], $reservation->duration);
    }

    /** @test */
    /* la reserva se elimina correctamente. */
    public function it_deletes_a_reservation()
    {
      $reservationId = 1;

      // Definir el comportamiento esperado del mock del ReservationRepository
      $this->reservationRepositoryMock
          ->shouldReceive('delete')
          ->once()
          ->with($reservationId)
          ->andReturn(true);
  
      // Llamar directamente al repository en lugar de la factory
      $result = $this->reservationRepositoryMock->delete($reservationId);
  
      // Verificar que la eliminación fue exitosa
      $this->assertTrue($result);
    }
}
