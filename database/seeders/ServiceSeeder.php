<?php

namespace Database\Seeders;

use App\Models\Salon;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceType1 = ServiceType::factory()->create();
        $salon1 = Salon::factory()->create();
        $serviceType2 = ServiceType::factory()->create();
        $salon2 = Salon::factory()->create();
        $serviceType3 = ServiceType::factory()->create();
        $salon3 = Salon::factory()->create();

        Service::factory()->count(3)->for($serviceType1)->for($salon1)->create();
        Service::factory()->count(3)->for($serviceType2)->for($salon2)->create();
        Service::factory()->count(3)->for($serviceType3)->for($salon3)->create();
    }
}
