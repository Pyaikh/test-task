<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarModel;
use App\Models\ComfortCategory;
use App\Models\Driver;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Users
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        $first = ComfortCategory::firstOrCreate(['name' => 'Первая']);
        $second = ComfortCategory::firstOrCreate(['name' => 'Вторая']);

        $manager = Position::create(['name' => 'Менеджер']);
        $director = Position::create(['name' => 'Директор']);

        $manager->comfortCategories()->syncWithoutDetaching([$second->id]);
        $director->comfortCategories()->syncWithoutDetaching([$first->id, $second->id]);

        // Position
        $user->position()->associate($manager);
        $user->save();

        // Car models
        $bmw = CarModel::firstOrCreate(['name' => 'BMW X5'], ['comfort_category_id' => $first->id]);
        $audi = CarModel::firstOrCreate(['name' => 'Audi A6'], ['comfort_category_id' => $second->id]);
        $additionalModelNames = [
            'Toyota Camry',
            'Ford Focus',
            'Mercedes C200',
            'Kia Rio',
            'Hyundai Solaris',
            'Skoda Octavia',
            'Volkswagen Passat',
            'Tesla Model 3',
            'Nissan Qashqai',
            'Renault Duster',
            'Mazda 6',
            'Honda Civic',
            'Peugeot 408',
            'Citroen C4',
            'Opel Insignia',
        ];
        $carModels = [$bmw, $audi];
        foreach ($additionalModelNames as $modelName) {
            $carModels[] = CarModel::firstOrCreate(
                ['name' => $modelName],
                ['comfort_category_id' => (count($carModels) % 2 === 0) ? $first->id : $second->id]
            );
        }

        $driver = Driver::create(['name' => 'Иван Петров']);

        Car::firstOrCreate([
            'license_plate' => 'AA1111AA',
        ], [
            'model_id' => $bmw->id,
            'driver_id' => $driver->id,
        ]);

        Car::firstOrCreate([
            'license_plate' => 'BB2222BB',
        ], [
            'model_id' => $audi->id,
            'driver_id' => $driver->id,
        ]);

        // Сars for pagination
        for ($i = 1; $i <= 20; $i++) {
            $plate = sprintf('P%02d%02dPP', $i, $i);
            Car::firstOrCreate([
                'license_plate' => $plate,
            ], [
                'model_id' => $carModels[$i % count($carModels)]->id,
                // Категория берётся с модели
                'driver_id' => $driver->id,
            ]);
        }
    }
}

