<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DataSeeder extends Seeder
{
    /**
     * A tábla adatainak seedelése.
     *
     * @return void
     */
    public function run()
    {
        // Ellenőrizzük, hogy a clients tábla üres-e
        if (DB::table('clients')->count() == 0) {
            // JSON fájl beolvasása
            $clientsJson = File::get(database_path('seeders/data/clients.json'));
            $clientsData = json_decode($clientsJson, true);

            // A JSON adatokat átalakítjuk, hogy illeszkedjenek az adatbázis szerkezetéhez
            $formattedClientsData = array_map(function ($client) {
                return [
                    'id' => $client['id'],
                    'name' => $client['name'],
                    'card_number' => $client['idcard'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $clientsData);

            // Adatok beszúrása a táblába
            DB::table('clients')->insert($formattedClientsData);
        }

        // Ellenőrizzük, hogy a cars tábla üres-e
        if (DB::table('cars')->count() == 0) {
            // JSON fájl beolvasása
            $carsJson = File::get(database_path('seeders/data/cars.json'));
            $carsData = json_decode($carsJson, true);

            // A JSON adatokat átalakítjuk, hogy illeszkedjenek az adatbázis szerkezetéhez
            $formattedCarsData = array_map(function ($car) {
                return [
                    'client_id' => $car['client_id'],
                    'car_id' => $car['car_id'],
                    'type' => $car['type'],
                    'registered' => $car['registered'],
                    'ownbrand' => $car['ownbrand'],
                    'accidents' => $car['accident'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $carsData);

            // Adatok beszúrása a táblába
            DB::table('cars')->insert($formattedCarsData);

        }

        // Ellenőrizzük, hogy a services tábla üres-e
        if (DB::table('services')->count() == 0) {
            $servicesJson = File::get(database_path('seeders/data/services.json'));
            $servicesData = json_decode($servicesJson, true);

            // A JSON adatokat átalakítjuk, hogy illeszkedjenek az adatbázis szerkezetéhez
            $formattedServicesData = array_map(function ($service) {
                return [
                    'client_id'   => $service['client_id'],
                    'car_id'      => $service['car_id'],
                    'log_number'  => $service['lognumber'],
                    'event'       => $service['event'],
                    'event_time'  => $service['eventtime'],
                    'document_id' => $service['document_id'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }, $servicesData);

            // Adatok beszúrása a táblába
            DB::table('services')->insert($formattedServicesData);
        }
    }
}
