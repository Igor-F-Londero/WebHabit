<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Checkin;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_habits_endpoint_returns_only_authenticated_user_habits(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $category = Category::create([
            'name' => 'API Habits',
            'description' => 'Categoria API',
        ]);

        $habit = Habit::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Ler 20 paginas',
            'frequency' => 'daily',
            'color' => '#22C55E',
            'active' => true,
        ]);

        Habit::create([
            'user_id' => $otherUser->id,
            'category_id' => $category->id,
            'name' => 'Outro habito',
            'frequency' => 'daily',
            'color' => '#3B82F6',
            'active' => true,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/habits')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $habit->id)
            ->assertJsonPath('data.0.name', 'Ler 20 paginas')
            ->assertJsonPath('data.0.category', 'API Habits');
    }

    public function test_checkins_endpoint_creates_a_checkin_and_returns_streak_data(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'API Checkins',
            'description' => 'Categoria API',
        ]);

        $habit = Habit::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Treinar',
            'frequency' => 'daily',
            'color' => '#EF4444',
            'active' => true,
        ]);

        Sanctum::actingAs($user);

        $this->postJson('/api/checkins', [
            'habit_id' => $habit->id,
        ])
            ->assertCreated()
            ->assertJsonPath('data.habit_id', $habit->id)
            ->assertJsonPath('data.current_streak', 1);

        $this->assertSame(1, $habit->fresh()->checkins()->count());
    }

    public function test_stats_endpoint_returns_summary_metrics(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'API Stats',
            'description' => 'Categoria API',
        ]);

        $habit = Habit::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Caminhar',
            'frequency' => 'daily',
            'color' => '#10B981',
            'active' => true,
        ]);

        Checkin::create([
            'habit_id' => $habit->id,
            'checked_date' => today(),
            'checked_at' => now(),
            'created_at' => now(),
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/stats')
            ->assertOk()
            ->assertJsonPath('data.total_habits', 1)
            ->assertJsonPath('data.best_streak.habit_id', $habit->id)
            ->assertJsonPath('data.most_checkins.habit_id', $habit->id);
    }

    public function test_inactive_user_cannot_access_api(): void
    {
        $user = User::factory()->create([
            'active' => false,
        ]);

        Sanctum::actingAs($user);

        $this->getJson('/api/habits')
            ->assertForbidden()
            ->assertJsonPath('message', 'Sua conta foi desativada. Entre em contato com o administrador.');
    }
}
