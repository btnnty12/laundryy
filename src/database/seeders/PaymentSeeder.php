<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::whereIn('payment_status', ['paid', 'partial'])->get();
        $admin = User::where('role', 'admin')->first();

        if ($orders->isEmpty() || !$admin) {
            return;
        }

        $paymentMethods = ['cash', 'transfer', 'e_wallet'];

        foreach ($orders as $order) {
            if ($order->payment_status === 'paid') {
                // Pembayaran penuh
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $order->total_price,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'payment_date' => Carbon::now()->subDays(rand(1, 7)),
                    'reference_number' => 'PAY' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'notes' => 'Pembayaran lunas',
                    'created_by' => $admin->id,
                ]);
            } elseif ($order->payment_status === 'partial') {
                // Pembayaran sebagian (50-80% dari total)
                $partialAmount = $order->total_price * (rand(50, 80) / 100);
                
                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $partialAmount,
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'payment_date' => Carbon::now()->subDays(rand(1, 5)),
                    'reference_number' => 'PAY' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'notes' => 'Pembayaran sebagian (DP)',
                    'created_by' => $admin->id,
                ]);
            }
        }
    }
}