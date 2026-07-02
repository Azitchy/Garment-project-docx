<?php

namespace Database\Seeders;

use App\Models\Garment;
use Illuminate\Database\Seeder;

class GarmentSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Classic Cotton Shirt',
                'category' => 'Shirts',
                'price' => 2499,
                'stock' => 24,
                'description' => 'A clean everyday cotton shirt designed for comfort and a sharp retail display.',
                'is_active' => true,
            ],
            [
                'name' => 'Slim Fit Trousers',
                'category' => 'Bottoms',
                'price' => 3199,
                'stock' => 12,
                'description' => 'Slim fit trousers for formal and semi-formal collections.',
                'is_active' => true,
            ],
            [
                'name' => 'Denim Work Jacket',
                'category' => 'Outerwear',
                'price' => 4599,
                'stock' => 5,
                'description' => 'A durable denim jacket for seasonal catalog highlights.',
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            Garment::create($item);
        }
    }
}
