<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $admin = User::where('role', 'admin')->first();
        $services = Service::all();

        if ($customers->isEmpty() || !$admin || $services->isEmpty()) {
            return;
        }

        $orders = [
            [
                'order_number' => 'LND-' . Carbon::now()->addDays(1)->format('Ymd') . '-001',
                'customer_id' => $customers->random()->id,
                'service_id' => $services->random()->id,
                'weight' => 3.5,
                'pickup_date' => Carbon::now()->addDays(1),
                'delivery_date' => Carbon::now()->addDays(3),
                'notes' => 'Pisahkan pakaian putih dan berwarna',
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'created_by' => $admin->id,
            ],
            [
                'order_number' => 'LND-' . Carbon::now()->subDays(2)->format('Ymd') . '-001',
                'customer_id' => $customers->random()->id,
                'service_id' => $services->random()->id,
                'weight' => 2.0,
                'pickup_date' => Carbon::now()->subDays(2),
                'delivery_date' => Carbon::now(),
                'notes' => 'Pakaian anak-anak',
                'status' => 'in_progress',
                'payment_status' => 'unpaid',
                'created_by' => $admin->id,
            ],
            [
                'order_number' => 'LND-' . Carbon::now()->subDays(5)->format('Ymd') . '-001',
                'customer_id' => $customers->random()->id,
                'service_id' => $services->random()->id,
                'weight' => 5.0,
                'pickup_date' => Carbon::now()->subDays(5),
                'delivery_date' => Carbon::now()->subDays(2),
                'notes' => 'Sudah selesai, siap diambil',
                'status' => 'ready',
                'payment_status' => 'paid',
                'created_by' => $admin->id,
            ],
            [
                'order_number' => 'LND-' . Carbon::now()->subDays(7)->format('Ymd') . '-001',
                'customer_id' => $customers->random()->id,
                'service_id' => $services->random()->id,
                'weight' => 1.5,
                'pickup_date' => Carbon::now()->subDays(7),
                'delivery_date' => Carbon::now()->subDays(5),
                'notes' => 'Pesanan sudah selesai dan diambil',
                'status' => 'delivered',
                'payment_status' => 'paid',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($orders as $orderData) {
            $service = Service::find($orderData['service_id']);
            $orderData['total_price'] = $orderData['weight'] * $service->price_per_kg;
            
            Order::create($orderData);
        }
    }
}