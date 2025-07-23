<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();

        if ($orders->isEmpty()) {
            return;
        }

        $itemTypes = [
            'Kemeja',
            'Celana',
            'Kaos',
            'Jaket',
            'Rok',
            'Dress',
            'Underwear',
            'Kaus Kaki',
            'Handuk',
            'Seprai',
        ];

        foreach ($orders as $order) {
            // Setiap pesanan memiliki 2-5 item
            $itemCount = rand(2, 5);
            
            for ($i = 0; $i < $itemCount; $i++) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => $itemTypes[array_rand($itemTypes)],
                    'quantity' => rand(1, 10),
                    'description' => rand(0, 1) ? 'Kondisi baik' : null,
                ]);
            }
        }
    }
}