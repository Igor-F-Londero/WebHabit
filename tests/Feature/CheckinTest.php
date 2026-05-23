<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Checkin;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
            ->assertSessionHas('error', 'Você já concluiu esta missão hoje!');

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
            ->assertSessionHas('error', 'Você já concluiu esta missão nesta semana!');

        $this->assertDatabaseCount('checkins', 1);
    }

    public function test_weekly_habit_is_completed_for_the_current_cycle_after_checkin_earlier_in_week(): void
    {
        Carbon::setTestNow('2026-05-22 10:00:00');

        try {
            $user = User::factory()->create();
            $category = Category::create([
                'name' => 'Exercicio',
                'description' => 'Categoria semanal',
            ]);

            $habit = Habit::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'name' => 'Basquete',
                'frequency' => 'weekly',
                'color' => '#F97316',
                'active' => true,
            ]);

            Checkin::create([
                'habit_id' => $habit->id,
                'checked_date' => today()->startOfWeek()->toDateString(),
                'checked_at' => today()->startOfWeek()->setHour(19),
                'created_at' => now(),
            ]);

            $habit->load([
                'checkins' => fn ($query) => $query->where('checked_date', '>=', today()->startOfWeek()->toDateString()),
            ]);

            $this->assertFalse($habit->isCompletedToday());
            $this->assertTrue($habit->isCompletedForCurrentCycle());
        } finally {
            Carbon::setTestNow();
        }
    }
}
