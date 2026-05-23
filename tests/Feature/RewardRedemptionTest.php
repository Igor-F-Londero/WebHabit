<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Checkin;
use App\Models\Habit;
use App\Models\User;
use App\Services\GamificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RewardRedemptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_redeem_available_reward(): void
    {
        $user = User::factory()->create();
        $habit = $this->createHabitWithCheckins($user, 6);

        $this->actingAs($user)
            ->post(route('rewards.redeem'), ['reward_key' => 'planned_break'])
            ->assertSessionHas('success', 'Recompensa resgatada: Pausa planejada por 20 moedas.');

        $this->assertDatabaseHas('reward_redemptions', [
            'user_id' => $user->id,
            'reward_key' => 'planned_break',
            'reward_name' => 'Pausa planejada',
            'cost' => 20,
        ]);

        $game = app(GamificationService::class)->forUser($user->fresh());

        $this->assertSame(20, $game['spent_coins']);
        $this->assertSame($game['earned_coins'] - 20, $game['coins']);
        $this->assertSame(1, $game['inventory']['redeemed_count']);
        $this->assertSame(20, $game['inventory']['spent_coins']);
        $this->assertSame('Pausa planejada', $game['inventory']['items'][0]['name']);
        $this->assertSame(1, $game['inventory']['items'][0]['quantity']);
        $this->assertSame($habit->id, $habit->fresh()->id);
    }

    public function test_user_cannot_redeem_reward_without_enough_coins(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('rewards.redeem'), ['reward_key' => 'special_reward'])
            ->assertSessionHas('error', 'Você ainda precisa de 90 moedas para resgatar Recompensa especial.');

        $this->assertDatabaseCount('reward_redemptions', 0);
    }

    private function createHabitWithCheckins(User $user, int $days): Habit
    {
        $category = Category::create([
            'name' => 'Estudo',
            'description' => 'Categoria de teste',
        ]);

        $habit = Habit::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'name' => 'Estudo focado',
            'frequency' => 'daily',
            'color' => '#38BDF8',
            'active' => true,
        ]);

        foreach (range($days - 1, 0) as $daysAgo) {
            $date = today()->subDays($daysAgo);

            Checkin::create([
                'habit_id' => $habit->id,
                'checked_date' => $date->toDateString(),
                'checked_at' => $date->setHour(8),
                'created_at' => now(),
            ]);
        }

        return $habit;
    }
}
