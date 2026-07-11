<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $expenses = [
            ['category_id' => 1, 'amount' => 25.50, 'description' => 'Lunch',      'date' => '2026-07-01'],
            ['category_id' => 2, 'amount' => 10.00, 'description' => 'Bus ticket', 'date' => '2026-07-02'],
            ['category_id' => 3, 'amount' => 15.00, 'description' => 'Cinema',     'date' => '2026-07-03'],
            ['category_id' => 1, 'amount' => 30.00, 'description' => 'Dinner',     'date' => '2026-07-04'],
            ['category_id' => 4, 'amount' => 50.00, 'description' => 'Pharmacy',   'date' => '2026-07-05'],
        ];

        foreach ($expenses as $expense) 
            {
                Expense::create([
                    'user_id' => 1,
                    ...$expense,
                ]);
            }
    }
}
