# WebHabit

Aplicação web gamificada para acompanhar hábitos, check-ins, metas pessoais e progresso do herói, com identidade visual própria, área administrativa e autenticação baseada em roles.

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
- Home pública/privada com visão resumida da campanha do herói
- Dashboard do usuário com missões, chefe semanal, recompensas e visual gamificado
- Relatórios do usuário com gráficos, heatmap e ranking de combos
- Dashboard admin com métricas e gráficos
- Gestão admin de categorias e usuários
- API JSON autenticada com Sanctum para hábitos, check-ins e stats
- Assets visuais próprios para herói, dragão, escudos, medalhas e ícones

## Arquivos que comprovam os requisitos do trabalho

Esta seção ajuda a localizar rapidamente onde cada requisito está implementado.

| Requisito | Arquivos principais | Observação |
| --- | --- | --- |
| `Login/autenticação` | `routes/auth.php`, `app/Http/Controllers/Auth/*`, `resources/views/auth/*` | Fluxo completo de registro, login, recuperação de senha e logout. |
| `Área pública` | `routes/web.php`, `resources/views/welcome.blade.php` | Landing page pública com identidade visual do projeto. |
| `Área autenticada do usuário` | `routes/web.php`, `app/Http/Controllers/HomeController.php`, `resources/views/home.blade.php` | Página principal do jogador/herói com resumo da campanha. |
| `Área administrativa` | `routes/web.php`, `app/Http/Controllers/Admin/*`, `resources/views/admin/*` | Painel admin, gestão de categorias, usuários e relatórios. |
| `CRUDs` | `app/Http/Controllers/HabitController.php`, `GoalController.php`, `app/Http/Controllers/Admin/CategoryController.php` | Criação, edição, listagem e exclusão de hábitos, metas e categorias. |
| `Banco relacional` | `database/migrations/0001_01_01_000000_create_users_table.php`, `database/migrations/2026_05_16_182805_create_categories_table.php`, `database/migrations/2026_05_16_182805_create_habits_table.php`, `database/migrations/2026_05_16_182806_create_checkins_table.php`, `database/migrations/2026_05_16_182806_create_goals_table.php`, `database/migrations/2026_05_22_230000_create_reward_redemptions_table.php` | Tabelas ligadas por chaves estrangeiras e relacionamentos entre usuário, hábitos, check-ins, metas e recompensas. |
| `Dashboard com gráficos` | `app/Http/Controllers/DashboardController.php`, `app/Http/Controllers/Admin/DashboardController.php`, `resources/views/reports/index.blade.php`, `resources/views/admin/reports/index.blade.php` | Métricas, gráficos, heatmap e cartões de resumo. |
| `Relatórios` | `app/Http/Controllers/ReportController.php`, `app/Http/Controllers/Admin/ReportController.php`, `resources/views/reports/index.blade.php` | Relatórios do usuário e do administrador. |
| `API JSON` | `routes/api.php`, `app/Http/Controllers/Api/HabitController.php`, `app/Http/Controllers/Api/CheckinController.php`, `app/Http/Controllers/Api/StatsController.php` | Endpoints protegidos por Sanctum com respostas JSON. |
| `Gamificação` | `app/Services/GamificationService.php`, `app/Http/Controllers/RewardRedemptionController.php`, `resources/views/dashboard.blade.php`, `resources/views/home.blade.php` | Cálculo de nível, combo, chefe semanal, recompensas e progresso. |
| `Identidade visual própria` | `resources/views/components/webhabit/icon.blade.php`, `resources/css/app.css`, `public/images/*` | Ícones e assets customizados para dragão, herói, medalhas e cenários. |
| `Tratamento de erros` | `bootstrap/app.php`, `resources/views/errors/*` | Páginas customizadas para 403, 404, 419, 500 e 503. |

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

- `admin@webhabit.com` / `password`
- `igor@webhabit.com` / `12345678`

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
$user = App\Models\User::where('email', 'igor@webhabit.com')->first();
$user->createToken('mobile')->plainTextToken;
```

## Pendências principais

- Refinar os assets próprios do dragão, do herói e das medalhas
- Ampliar a cobertura de testes para regras de negócio e fluxos principais
- Evoluir a API para novos relatórios e integrações futuras
