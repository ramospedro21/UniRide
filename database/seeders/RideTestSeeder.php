<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Rating;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RideTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $rides = [
            Ride::create([
                'driver_id' => 5,
                'car_id' => 7,
                'departure_address' => 'Av. Mal. Floriano Peixoto, São José dos Campos',
                'departure_location_lat' => -23.1895,
                'departure_location_long' => -45.8842,
                'arrival_address' => 'UNIP São José dos Campos – Campus Dutra, Av. Dr. Adhemar de Barros, 999',
                'arrive_location_lat' => -23.1890,
                'arrive_location_long' => -45.8850,
                'departure_time' => now()->addHour(),
                'capacity' => 4,
                'ride_fare' => 10.0,
            ]),

            Ride::create([
                'driver_id' => 6,
                'car_id' => 8,
                'departure_address' => 'Rua Marechal Deodoro, São José dos Campos',
                'departure_location_lat' => -23.1870,
                'departure_location_long' => -45.8845,
                'arrival_address' => 'UNIP São José dos Campos – Campus Dutra, Av. Dr. Adhemar de Barros, 999',
                'arrive_location_lat' => -23.1890,
                'arrive_location_long' => -45.8850,
                'departure_time' => now()->addHour()->addMinutes(30),
                'capacity' => 3,
                'ride_fare' => 12.0,
            ]),

            Ride::create([
                'driver_id' => 7,
                'car_id' => 9,
                'departure_address' => 'Rua Quinze de Novembro, São José dos Campos',
                'departure_location_lat' => -23.1905,
                'departure_location_long' => -45.8830,
                'arrival_address' => 'UNIP São José dos Campos – Campus Dutra, Av. Dr. Adhemar de Barros, 999',
                'arrive_location_lat' => -23.1890,
                'arrive_location_long' => -45.8850,
                'departure_time' => now()->addHour()->addMinutes(15),
                'capacity' => 4,
                'ride_fare' => 11.0,
            ]),
        ];

        // --- WeekDays ---
        $ridesWithDays = [
            ['ride' => $rides[0], 'days' => [1,3,5]],
            ['ride' => $rides[1], 'days' => [2,3,4]],
            ['ride' => $rides[2], 'days' => [1,2,3,4,5]],
        ];

        foreach ($ridesWithDays as $item) {
            $ride = $item['ride'];
            $weekDays = $item['days'];

            $rideWeekDays = [];
            foreach ($weekDays as $day) {
                $rideWeekDays[] = [
                    'ride_id' => $ride->id,
                    'day_of_week' => $day,
                ];
            }

            $ride->weekDays()->createMany($rideWeekDays);
        }

        // --- Avaliações ---
        Rating::create(['ride_id'=>$rides[0]->id,'reviewer_id'=>5,'reviewed_id'=>7,'score'=>5,'comment'=>'Ótimo motorista']);
        Rating::create(['ride_id'=>$rides[1]->id,'reviewer_id'=>6,'reviewed_id'=>6,'score'=>4,'comment'=>'Bom motorista']);
        Rating::create(['ride_id'=>$rides[2]->id,'reviewer_id'=>7,'reviewed_id'=>5,'score'=>3,'comment'=>'Motorista regular']);}
}
