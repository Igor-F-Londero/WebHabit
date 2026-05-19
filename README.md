# HabitFlow

Aplicação web para acompanhamento de hábitos, check-ins e metas pessoais, com área administrativa e autenticação baseada em roles.

## Stack

- PHP 8.2
- Laravel 12
- Laravel Breeze (Blade + Tailwind)
- Alpine.js + Vite
- MySQL no ambiente local
- PHPUnit para testes

## Funcionalidades atuais

- Cadastro, login, logout e perfil de usuário
- Roles `user` e `admin`
- Bloqueio de contas inativas
- CRUD de hábitos
- Check-in diário e semanal
- Cálculo de streak diário e semanal
- CRUD de metas com progresso e status
- Dashboard do usuário com resumo, gráfico e heatmap
- Dashboard admin com métricas e gráficos
- Relatórios do usuário e admin
- Gestão admin de categorias e usuários
- API JSON autenticada com Sanctum para hábitos, check-ins e stats

## Ambiente local

O projeto está configurado para usar MySQL no `.env.example`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webhabit_db
DB_USERNAME=root
DB_PASSWORD=
```

No ambiente de teste, o `phpunit.xml` usa SQLite em memória.

## Usuários de teste

- `admin@habitflow.com` / `password`
- `igor@habitflow.com` / `password`

## Comandos úteis

```bash
composer install
npm install
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan test
```

## API

Endpoints disponíveis:

- `GET /api/habits`
- `POST /api/checkins`
- `GET /api/stats`

Todos exigem `Bearer Token` via Sanctum e respeitam o bloqueio de conta inativa.

Exemplo de geração de token no Tinker:

```bash
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'igor@habitflow.com')->first();
$user->createToken('mobile')->plainTextToken;
```

## Pendências principais

- Substituir a landing page padrão
- Ampliar a cobertura de testes para regras de negócio e fluxos principais
