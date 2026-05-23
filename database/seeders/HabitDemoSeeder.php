<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Checkin;
use App\Models\Goal;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class HabitDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'igor@habitflow.com')->first();

        if (! $user) {
            return;
        }

        $habits = [
            [
                'name' => 'Alimentação equilibrada',
                'category' => 'Alimentação',
                'description' => 'Registrar refeições completas e evitar pular horários importantes.',
                'frequency' => 'daily',
                'color' => '#22C55E',
                'target' => 24,
                'pattern' => [0, 1, 2, 3, 5, 6],
            ],
            [
                'name' => 'Beber água',
                'category' => 'Hidratação',
                'description' => 'Manter a hidratação ao longo do dia.',
                'frequency' => 'daily',
                'color' => '#06B6D4',
                'target' => 28,
                'pattern' => [0, 1, 2, 3, 4, 5, 6],
            ],
            [
                'name' => 'Registrar humor',
                'category' => 'Humor',
                'description' => 'Anotar como foi o dia e observar padrões de bem-estar.',
                'frequency' => 'daily',
                'color' => '#F472B6',
                'target' => 20,
                'pattern' => [0, 1, 3, 4, 6],
            ],
            [
                'name' => 'Organizar tarefas diárias',
                'category' => 'Tarefas Diárias',
                'description' => 'Planejar e revisar as principais tarefas do dia.',
                'frequency' => 'daily',
                'color' => '#F59E0B',
                'target' => 25,
                'pattern' => [0, 1, 2, 3, 4, 5],
            ],
            [
                'name' => 'Tarefas de faculdade',
                'category' => 'Tarefas de Faculdade',
                'description' => 'Avançar trabalhos, leituras, listas e entregas acadêmicas.',
                'frequency' => 'daily',
                'color' => '#8B5CF6',
                'target' => 22,
                'pattern' => [1, 2, 3, 4, 6],
            ],
            [
                'name' => 'Exercício: basquete',
                'category' => 'Exercício',
                'description' => 'Treinar fundamentos, arremessos ou jogar basquete.',
                'frequency' => 'weekly',
                'color' => '#F97316',
                'target' => 6,
                'pattern' => [2, 5],
            ],
            [
                'name' => 'Estudo focado',
                'category' => 'Estudo',
                'description' => 'Separar um bloco de estudo sem distrações.',
                'frequency' => 'daily',
                'color' => '#38BDF8',
                'target' => 24,
                'pattern' => [0, 1, 2, 3, 4, 6],
            ],
        ];

        foreach ($habits as $index => $item) {
            $category = Category::where('name', $item['category'])->first();

            if (! $category) {
                continue;
            }

            $habit = Habit::updateOrCreate(
                ['user_id' => $user->id, 'name' => $item['name']],
                [
                    'category_id' => $category->id,
                    'description' => $item['description'],
                    'frequency' => $item['frequency'],
                    'color' => $item['color'],
                    'active' => true,
                ]
            );

            Goal::updateOrCreate(
                ['user_id' => $user->id, 'habit_id' => $habit->id, 'title' => "Meta de {$item['name']}"],
                [
                    'description' => "Manter consistência em {$item['name']} durante o mês.",
                    'target_count' => $item['target'],
                    'start_date' => today()->startOfMonth(),
                    'end_date' => today()->endOfMonth(),
                    'status' => 'active',
                ]
            );

            $this->seedCheckins($habit, $item['pattern'], $index);
        }
    }

    private function seedCheckins(Habit $habit, array $pattern, int $offset): void
    {
        for ($daysAgo = 29; $daysAgo >= 0; $daysAgo--) {
            $date = today()->subDays($daysAgo);
            $weekday = (int) $date->dayOfWeek;
            $shouldCheckin = in_array($weekday, $pattern, true);

            if ($habit->frequency === 'weekly') {
                $shouldCheckin = in_array($weekday, $pattern, true) && $daysAgo % 2 === $offset % 2;
            }

            if (! $shouldCheckin) {
                continue;
            }

            Checkin::updateOrCreate(
                ['habit_id' => $habit->id, 'checked_date' => $date->toDateString()],
                [
                    'checked_at' => Carbon::parse($date->toDateString())->setTime(8 + ($offset % 8), 15),
                    'note' => $this->noteFor($habit->name),
                ]
            );
        }
    }

    private function noteFor(string $habitName): string
    {
        return match ($habitName) {
            'Alimentação equilibrada' => 'Refeições registradas com atenção.',
            'Beber água' => 'Meta de hidratação acompanhada.',
            'Registrar humor' => 'Humor do dia anotado.',
            'Organizar tarefas diárias' => 'Lista do dia revisada.',
            'Tarefas de faculdade' => 'Atividade acadêmica avançada.',
            'Exercício: basquete' => 'Treino de basquete concluído.',
            default => 'Bloco de estudo concluído.',
        };
    }
}
