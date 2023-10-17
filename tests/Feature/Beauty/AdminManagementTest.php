<?php

namespace Tests\Feature\Beauty;

use App\Models\Appointment;
use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_to_admin_services_page()
    {
        $this->seed();

        $response1 = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response1->assertRedirect('/admin/dashboard');

        $response2 = $this->get('/admin/service');
        $response2->assertOk();

        $response3 = $this->post('/admin/service/search?');
        $response3->assertOk();
    }

    public function test_create_service_from_admin_page()
    {
        $this->seed();

        $response1 = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response1->assertRedirect('/admin/dashboard');

        $response2 = $this->get('/admin/service/create');
        $response2->assertOk();

        $response3 = $this->post('/admin/service/', [
            'salon_id' => Salon::all()->first()->id,
            'service_type_id' => ServiceType::all()->first()->id,
            'name' => 'service1',
            'working_days' => '1,2,3,4,5',
            'working_hours' => '09:00,10:00',
            'is_enabled' => true,
        ]);
        $this->assertSame(Salon::all()->first()->id,
            Service::where('name', 'service1')->first()->salon_id);
        $this->assertSame(ServiceType::all()->first()->id,
            Service::where('name', 'service1')->first()->service_type_id);
        $response3->assertRedirect('/admin/service');
    }

    public function test_editing_service_from_admin_page()
    {
        $this->seed();
        $response1 = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response1->assertRedirect('/admin/dashboard');

        $serviceId = Service::all()->first()->id;

        $response3 = $this->put("/admin/service/$serviceId", [
            'salon_id' => Salon::all()->first()->id,
            'service_type_id' => ServiceType::all()->first()->id,
            'name' => 'service123',
            'working_days' => '1,2,3,4,5',
            'working_hours' => '09:00,10:00',
            'id' => $serviceId,
            '_save_action' => 'save_and_back'
        ]);
        $response3->assertRedirect('/admin/service');
        $this->assertSame('service123', Service::find($serviceId)->name);
    }

    public function test_deleting_service_from_admin_page()
    {
        $this->seed();
        $response1 = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response1->assertRedirect('/admin/dashboard');

        $serviceId = Service::all()->first()->id;
        $response2 = $this->delete("/admin/service/$serviceId");
        $response2->assertOk();
        $this->assertNull(Appointment::find($serviceId));
    }

    public function test_get_to_admin_appointments_page()
    {
        $this->seed();

        $response = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response->assertRedirect('/admin/dashboard');

        $response = $this->get('/admin/appointment');
        $response->assertOk();

        $response = $this->post('/admin/appointment/search?');
        $response->assertOk();
    }

    public function test_create_appointment_from_admin_page()
    {
        $this->seed();

        $response1 = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response1->assertRedirect('/admin/dashboard');

        $response2 = $this->get('/admin/appointment/create');
        $response2->assertOk();

        $response3 = $this->post('/admin/appointment/', [
            'service_id' => Service::all()->first()->id,
            'name' => 'serg',
            'phone' => '123123',
            'date' => Carbon::now()
        ]);
        $this->assertSame(Service::all()->first()->id,
            Appointment::where('name', 'serg')->where('phone', '123123')->first()->service_id);
        $response3->assertRedirect('/admin/appointment');
    }

    public function test_editing_appointment_from_admin_page()
    {
        $this->seed();

        $response1 = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response1->assertRedirect('/admin/dashboard');

        $appointment = Appointment::factory()->for(Service::all()->first())->create();
        $response2 = $this->get("/admin/appointment/$appointment->id/edit");
        $response2->assertOk();

        $response3 = $this->put("/admin/appointment/$appointment->id", [
            'service_id' => Service::all()->first()->id,
            'name' => 'serg',
            'phone' => '123123',
            'date' => Carbon::now(),
            'id' => $appointment->id,
            '_save_action' => 'save_and_back'
        ]);
        $response3->assertRedirect('/admin/appointment');
        $this->assertSame('serg', Appointment::find($appointment->id)->name);
    }

    public function test_deleting_appointment_from_admin_page()
    {
        $this->seed();

        $response1 = $this->post('/admin/login', [
            'email' => 'serg@email.com',
            'password' => 'password'
        ]);
        $response1->assertRedirect('/admin/dashboard');

        $appointment = Appointment::factory()->for(Service::all()->first())->create();
        $response2 = $this->delete("/admin/appointment/$appointment->id");
        $response2->assertOk();
        $this->assertNull(Appointment::find($appointment->id));
    }
}
