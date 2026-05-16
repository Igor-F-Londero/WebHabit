<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Saúde',     'icon' => 'ti-heart',         'description' => 'Hábitos relacionados à saúde física', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Estudo',    'icon' => 'ti-book',          'description' => 'Aprendizado e desenvolvimento intelectual', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Exercício', 'icon' => 'ti-run',           'description' => 'Atividade física e esporte', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nutrição',  'icon' => 'ti-apple',         'description' => 'Alimentação saudável', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Meditação', 'icon' => 'ti-peace',         'description' => 'Mindfulness e bem-estar mental', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finanças',  'icon' => 'ti-currency-real', 'description' => 'Hábitos financeiros saudáveis', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trabalho',  'icon' => 'ti-briefcase',     'description' => 'Produtividade profissional', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Social',    'icon' => 'ti-users',         'description' => 'Relacionamentos e vida social', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
