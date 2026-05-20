<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_requires_authentication(): void
    {
        $response = $this->get('/home');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_home(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/home');

        $response->assertOk();
        $response->assertSee('Escolha sua próxima missão');
        $response->assertSee('Cockpit');
        $response->assertSee('Hábitos');
    }

    public function test_admin_home_shows_operation_card(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/home');

        $response->assertOk();
        $response->assertSee('Operação');
        $response->assertSee('modo admin');
    }
}
