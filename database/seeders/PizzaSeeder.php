<?php

namespace Database\Seeders;

use App\Models\Pizza;
use App\Models\PizzaType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/data/pizzas.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->warn('Pizzas CSV file not found at: ' . $csvPath);
            return;
        }

        $content = file_get_contents($csvPath);
        $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        
        $lines = explode("\n", $content);
        $data = array_map('str_getcsv', $lines);
        $data = array_filter($data, function($row) { return !empty($row[0]); });
        
        $header = array_shift($data);

        foreach ($data as $row) {
            $pizzaData = array_combine($header, $row);
            
            // Find pizza type by slug
            $pizzaType = PizzaType::where('slug', Str::slug($pizzaData['pizza_type_id']))->first();
            
            if (!$pizzaType) {
                $this->command->warn('Pizza type not found for slug: ' . Str::slug($pizzaData['pizza_type_id']));
                continue;
            }
            
            // Map size values
            $sizeMapping = [
                'S' => 'small',
                'M' => 'medium',
                'L' => 'large',
                'XL' => 'extra_large',
                'XXL' => 'extra_extra_large',
            ];
            
            $size = $sizeMapping[$pizzaData['size']] ?? $pizzaData['size'];
            
            Pizza::create([
                'slug' => Str::slug($pizzaData['pizza_id']),
                'pizza_type_id' => $pizzaType->id,
                'size' => $size,
                'price' => $pizzaData['price'],
            ]);
        }

        $this->command->info('Pizzas seeded successfully.');
    }
}
