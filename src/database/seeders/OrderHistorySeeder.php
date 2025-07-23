<?php

namespace Database\Seeders;

use App\Models\OrderHistory;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::all();
        $admin = User::where('role', 'admin')->first();

        if ($orders->isEmpty() || !$admin) {
            return;
        }

        foreach ($orders as $order) {
            $histories = [];
            $currentDate = $order->created_at;

            // Riwayat pembuatan pesanan
            $histories[] = [
                'order_id' => $order->id,
                'status' => 'pending',
                'notes' => 'Pesanan dibuat',
                'changed_by' => $admin->id,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ];

            // Tambahkan riwayat berdasarkan status saat ini
            switch ($order->status) {
                case 'delivered':
                    $currentDate = $currentDate->addHours(2);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'confirmed',
                        'notes' => 'Pesanan dikonfirmasi',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    $currentDate = $currentDate->addHours(12);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'in_progress',
                        'notes' => 'Pesanan sedang diproses',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    $currentDate = $currentDate->addHours(24);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'ready',
                        'notes' => 'Pesanan siap diambil',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    $currentDate = $currentDate->addHours(6);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'delivered',
                        'notes' => 'Pesanan telah diambil pelanggan',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];
                    break;

                case 'ready':
                    $currentDate = $currentDate->addHours(2);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'confirmed',
                        'notes' => 'Pesanan dikonfirmasi',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    $currentDate = $currentDate->addHours(12);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'in_progress',
                        'notes' => 'Pesanan sedang diproses',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    $currentDate = $currentDate->addHours(24);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'ready',
                        'notes' => 'Pesanan siap diambil',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];
                    break;

                case 'in_progress':
                    $currentDate = $currentDate->addHours(2);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'confirmed',
                        'notes' => 'Pesanan dikonfirmasi',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];

                    $currentDate = $currentDate->addHours(12);
                    $histories[] = [
                        'order_id' => $order->id,
                        'status' => 'in_progress',
                        'notes' => 'Pesanan sedang diproses',
                        'changed_by' => $admin->id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                    ];
                    break;
            }

            // Insert semua riwayat
            foreach ($histories as $history) {
                OrderHistory::create($history);
            }
        }
    }
}