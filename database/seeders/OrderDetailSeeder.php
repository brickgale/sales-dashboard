<?php

namespace Database\Seeders;

use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Pizza;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderDetailSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/data/order_details.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->warn('Order details CSV file not found at: ' . $csvPath);
            return;
        }

        $content = file_get_contents($csvPath);
        $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        
        $lines = explode("\n", $content);
        $data = array_map('str_getcsv', $lines);
        $data = array_filter($data, function($row) { return !empty($row[0]); });
        
        $header = array_shift($data);

        foreach ($data as $row) {
            $orderDetailData = array_combine($header, $row);
            
            // Find order by id
            $order = Order::where('id', $orderDetailData['order_id'])->first();
            if (!$order) {
                $this->command->warn('Order not found for id: ' . $orderDetailData['order_id']);
                continue;
            }
            
            // Find pizza by slug
            $pizza = Pizza::where('slug', Str::slug($orderDetailData['pizza_id']))->first();
            if (!$pizza) {
                $this->command->warn('Pizza not found for slug: ' . Str::slug($orderDetailData['pizza_id']));
                continue;
            }
            
            OrderDetail::create([
                'order_id' => $order->id,
                'pizza_id' => $pizza->id,
                'quantity' => $orderDetailData['quantity'],
            ]);
        }

        $this->command->info('Order details seeded successfully.');
    }
}
