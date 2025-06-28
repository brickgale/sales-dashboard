<?php

namespace Database\Seeders;

use App\Models\PizzaType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PizzaTypeSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('seeders/data/pizza_types.csv');
        
        if (!file_exists($csvPath)) {
            $this->command->warn('Pizza types CSV file not found at: ' . $csvPath);
            return;
        }

        $content = file_get_contents($csvPath);
        $content = mb_convert_encoding($content, 'UTF-8', 'auto');
        
        $lines = explode("\n", $content);
        $data = array_map('str_getcsv', $lines);
        $data = array_filter($data, function($row) { return !empty($row[0]); });
        
        $header = array_shift($data);

        foreach ($data as $row) {
            $pizzaTypeData = array_combine($header, $row);
            
            PizzaType::create([
                'slug' => Str::slug($pizzaTypeData['pizza_type_id']),
                'name' => $pizzaTypeData['name'],
                'category' => $pizzaTypeData['category'],
                'ingredients' => $pizzaTypeData['ingredients'],
            ]);
        }

        $this->command->info('Pizza types seeded successfully.');
    }
}
