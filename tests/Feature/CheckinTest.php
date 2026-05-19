<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_habit_allows_only_one_checkin_per_day(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Saude',
            'description' => 'Categoria de teste',
        ]);

        $habit = Habit::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Beber agua',
            'frequency' => 'daily',
            'color' => '#22C55E',
            'active' => true,
        ]);

        $this->actingAs($user)->post('/checkins', ['habit_id' => $habit->id])
            ->assertSessionHas('success');

        $this->actingAs($user)->post('/checkins', ['habit_id' => $habit->id])
            ->assertSessionHas('error', 'Você já fez check-in neste hábito hoje!');

        $this->assertDatabaseCount('checkins', 1);
    }

    public function test_weekly_habit_allows_only_one_checkin_per_week(): void
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Estudo',
            'description' => 'Categoria semanal',
        ]);

        $habit = Habit::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Revisao semanal',
            'frequency' => 'weekly',
            'color' => '#3B82F6',
            'active' => true,
        ]);

        $this->actingAs($user)->post('/checkins', ['habit_id' => $habit->id])
            ->assertSessionHas('success');

        $this->actingAs($user)->post('/checkins', ['habit_id' => $habit->id])
            ->assertSessionHas('error', 'Você já fez check-in neste hábito nesta semana!');

        $this->assertDatabaseCount('checkins', 1);
    }
}
