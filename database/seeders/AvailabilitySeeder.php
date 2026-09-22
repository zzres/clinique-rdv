<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Availability;

class AvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Lundi (1) à vendredi (5) : 9h-12h et 14h-17h
        foreach (range(1, 5) as $day) {
            Availability::firstOrCreate([
                'day_of_week' => $day,
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
            ]);

            Availability::firstOrCreate([
                'day_of_week' => $day,
                'start_time' => '14:00:00',
                'end_time' => '17:00:00',
            ]);
        }
    }
}
