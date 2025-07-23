<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Kering',
                'description' => 'Layanan cuci dan kering pakaian standar',
                'price_per_kg' => 5000,
                'estimated_hours' => 24,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Setrika',
                'description' => 'Layanan cuci, kering, dan setrika pakaian',
                'price_per_kg' => 7000,
                'estimated_hours' => 48,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Express',
                'description' => 'Layanan cuci cepat dalam 6 jam',
                'price_per_kg' => 10000,
                'estimated_hours' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Dry Clean',
                'description' => 'Layanan dry cleaning untuk pakaian khusus',
                'price_per_kg' => 15000,
                'estimated_hours' => 72,
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Sepatu',
                'description' => 'Layanan cuci sepatu dan sandal',
                'price_per_kg' => 12000,
                'estimated_hours' => 24,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}