<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $catefories = ['Food', 'Transport', 'Entertainment', 'Health', 'Shopping'];

        foreach ($catefories as $name) 
            {
                Category::create([
                    'user_id' => 1,
                    'name'    => $name,
                ]);
            }
    }
}
