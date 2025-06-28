<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/data/orders.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->warn('Orders CSV file not found at: ' . $csvPath);
            return;
        }

        $content = file_get_contents($csvPath);
        $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        
        $lines = explode("\n", $content);
        $data = array_map('str_getcsv', $lines);
        $data = array_filter($data, function($row) { return !empty($row[0]); });
        
        $header = array_shift($data);

        foreach ($data as $row) {
            $orderData = array_combine($header, $row);
            
            Order::create([
                'id' => $orderData['order_id'],
                'created_at' => $orderData['date'] . ' ' . $orderData['time'],
            ]);
        }

        $this->command->info('Orders seeded successfully.');
    }
}
