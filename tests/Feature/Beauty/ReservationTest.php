<?php

namespace Tests\Feature\Beauty;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_to_beauty_page()
    {
        $response = $this->get('/beauty');
        $response->assertOk();
    }

    public function test_book_appointment()
    {
        $this->seed();
        $appointment = Appointment::factory()->for(Service::all()->first())->create();

        $this->assertSame(Service::all()->first()->id, $appointment->service_id);
    }

    public function test_time_is_not_available()
    {
        $this->seed();

        $date = Carbon::parse(mktime(
            intval('09'),
            intval('00'),
            0,
            intval('10'),
            intval('19'),
            intval('2023')
        ));

        $appointment = Appointment::factory()->for(Service::all()->first())->create([
            'name' => 'serg',
            'phone' => '123123',
            'date' => $date,
        ]);

        $response = $this->post('/beauty/makeRecord', [
            'hour' => '09:00',
            'year' => '2023',
            'month' => '10',
            'day' => '19',
            'name' => 'serg',
            'phone' => '123123',
            'service' => Service::all()->first()->id
        ]);

        $response->assertJsonFragment(['error' => true]);
    }
}
