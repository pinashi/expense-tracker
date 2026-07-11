<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user     = User::factory()->create();
        $this->category = Category::create([
            'user_id' => $this->user->id,
            'name'    => 'Food',
        ]);
    }

    public function test_user_can_get_expenses(): void
    {
        Expense::create([
            'user_id'     => $this->user->id,
            'category_id' => $this->category->id,
            'amount'      => 25.50,
            'date'        => '2026-07-01',
        ]);

        $response = $this->actingAs($this->user)
                         ->getJson('/api/expenses');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [['id', 'amount', 'date', 'category']]]);
    }

    public function test_user_can_create_expense(): void
    {
        $response = $this->actingAs($this->user)
                         ->postJson('/api/expenses', [
                            'category_id' => $this->category->id,
                            'amount'      => 50.00,
                            'date'        => '2026-07-01',
                         ]);

        $response->assertStatus(201);
        $response->assertJsonStructure(['data' => ['id', 'amount', 'date', 'category']]);
    }

    public function test_user_cannot_create_expense_without_amount(): void 
    {
        $response = $this->actingAs($this->user)
                         ->postJson('/api/expenses', [
                            'category_id' => $this->category->id,
                            'date'        => '2026-07-01',
                         ]);

        $response->assertStatus(422);
    }

    public function test_user_cannot_delete_another_users_expense(): void
    {
        $anotherUser = User::factory()->create();
        $expense = Expense::create([
            'user_id'     => $anotherUser->id,
            'category_id' => $this->category->id,
            'amount'      => 25.50,
            'date'        => '2026-07-01',
        ]);

        $response = $this->actingAs($this->user)
                         ->deleteJson("/api/expenses/{$expense->id}");

        $response->assertStatus(403);
    }
}
