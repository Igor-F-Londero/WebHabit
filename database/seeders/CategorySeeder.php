<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Saúde',                 'icon' => 'ti-heart',         'description' => 'Hábitos relacionados à saúde física'],
            ['name' => 'Estudo',                'icon' => 'ti-book',          'description' => 'Aprendizado e desenvolvimento intelectual'],
            ['name' => 'Exercício',             'icon' => 'ti-run',           'description' => 'Atividade física e esporte'],
            ['name' => 'Nutrição',              'icon' => 'ti-apple',         'description' => 'Alimentação saudável'],
            ['name' => 'Meditação',             'icon' => 'ti-peace',         'description' => 'Mindfulness e bem-estar mental'],
            ['name' => 'Finanças',              'icon' => 'ti-currency-real', 'description' => 'Hábitos financeiros saudáveis'],
            ['name' => 'Trabalho',              'icon' => 'ti-briefcase',     'description' => 'Produtividade profissional'],
            ['name' => 'Social',                'icon' => 'ti-users',         'description' => 'Relacionamentos e vida social'],
            ['name' => 'Alimentação',           'icon' => 'ti-apple',         'description' => 'Planejamento e qualidade das refeições'],
            ['name' => 'Hidratação',            'icon' => 'ti-droplet',       'description' => 'Controle diário de consumo de água'],
            ['name' => 'Humor',                 'icon' => 'ti-mood-smile',    'description' => 'Registro de bem-estar emocional'],
            ['name' => 'Tarefas Diárias',       'icon' => 'ti-list-check',    'description' => 'Organização das responsabilidades do dia'],
            ['name' => 'Tarefas de Faculdade',  'icon' => 'ti-school',        'description' => 'Atividades, trabalhos e prazos acadêmicos'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['icon' => $category['icon'], 'description' => $category['description']]
            );
        }
    }
}
