<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => '開発',
                'color_class' => 'blue-500',
                'bg_color_class' => 'blue-100',
            ],
            [
                'name' => 'デザイン',
                'color_class' => 'pink-500',
                'bg_color_class' => 'pink-100',
            ],
            [
                'name' => 'マーケティング',
                'color_class' => 'green-500',
                'bg_color_class' => 'green-100',
            ],
            [
                'name' => '未分類',
                'color_class' => 'gray-300',
                'bg_color_class' => 'gray-100',
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}
