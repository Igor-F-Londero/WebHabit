# Diagrama Visual do Projeto WebHabit

Este documento resume a arquitetura do WebHabit, os relacionamentos principais do banco e o fluxo de check-in. Os diagramas usam Mermaid e podem ser visualizados em editores com suporte a Mermaid, como GitHub, GitLab e extensões do VS Code.

## Visao Geral

```mermaid
flowchart LR
    visitante[Visitante] --> welcome["/"]
    visitante --> auth["Auth Breeze<br/>login, register, reset"]

    usuario[Usuario autenticado] --> webRoutes["routes/web.php<br/>middleware: auth"]
    admin[Admin autenticado] --> adminRoutes["/admin/*<br/>middleware: auth + role:admin"]
    clienteApi[Cliente API] --> apiRoutes["routes/api.php<br/>auth:sanctum + active"]

    webRoutes --> dashboard["DashboardController<br/>cockpit do usuario"]
    webRoutes --> habits["HabitController<br/>CRUD de missoes"]
    webRoutes --> goals["GoalController<br/>CRUD de chefes/metas"]
    webRoutes --> checkins["CheckinController<br/>concluir missao"]
    webRoutes --> rewards["RewardRedemptionController<br/>resgatar recompensas"]
    webRoutes --> reports["ReportController<br/>relatorios pessoais"]
    webRoutes --> profile["ProfileController<br/>perfil"]

    adminRoutes --> adminDashboard["Admin\\DashboardController"]
    adminRoutes --> categories["Admin\\CategoryController<br/>categorias"]
    adminRoutes --> users["Admin\\UserController<br/>usuarios ativos/inativos"]
    adminRoutes --> adminReports["Admin\\ReportController<br/>relatorios admin"]

    apiRoutes --> apiHabits["Api\\HabitController<br/>listar habitos"]
    apiRoutes --> apiCheckins["Api\\CheckinController<br/>registrar check-in"]
    apiRoutes --> apiStats["Api\\StatsController<br/>estatisticas JSON"]

    dashboard --> gamification["GamificationService<br/>XP, moedas, nivel, conquistas"]
    reports --> gamification
    rewards --> gamification
    apiStats --> gamification
    apiStats --> streak["StreakService<br/>sequencias diarias/semanais"]
    gamification --> streak

    habits --> models["Eloquent Models"]
    goals --> models
    checkins --> models
    rewards --> models
    reports --> models
    categories --> models
    users --> models
    apiHabits --> models
    apiCheckins --> models
    apiStats --> models

    models --> db[(Banco de Dados)]
    models --> views["Blade Views<br/>resources/views"]
    dashboard --> views
    habits --> views
    goals --> views
    reports --> views
    adminDashboard --> views
    categories --> views
    users --> views
    adminReports --> views
```

## Modelo de Dados

```mermaid
erDiagram
    USERS ||--o{ HABITS : possui
    USERS ||--o{ GOALS : possui
    USERS ||--o{ REWARD_REDEMPTIONS : resgata
    CATEGORIES ||--o{ HABITS : classifica
    HABITS ||--o{ CHECKINS : recebe
    HABITS ||--o{ GOALS : alimenta

    USERS {
        bigint id PK
        string name
        string email UK
        enum role "user|admin"
        boolean active
        timestamp email_verified_at
        timestamps timestamps
    }

    CATEGORIES {
        bigint id PK
        string name UK
        text description
        string icon
        timestamps timestamps
    }

    HABITS {
        bigint id PK
        bigint user_id FK
        bigint category_id FK
        string name
        text description
        enum frequency "daily|weekly"
        string color
        boolean active
        timestamps timestamps
    }

    CHECKINS {
        bigint id PK
        bigint habit_id FK
        date checked_date
        timestamp checked_at
        text note
        timestamp created_at
        unique habit_date "habit_id + checked_date"
    }

    GOALS {
        bigint id PK
        bigint user_id FK
        bigint habit_id FK
        string title
        text description
        integer target_count
        date start_date
        date end_date
        enum status "active|completed|expired"
        timestamps timestamps
    }

    REWARD_REDEMPTIONS {
        bigint id PK
        bigint user_id FK
        string reward_key
        string reward_name
        unsignedInteger cost
        timestamp redeemed_at
        timestamps timestamps
    }
```

## Fluxo de Check-in

```mermaid
sequenceDiagram
    actor U as Usuario
    participant V as Blade/View
    participant R as routes/web.php
    participant C as CheckinController
    participant H as Habit
    participant CI as Checkin
    participant G as GamificationService

    U->>V: Clica em concluir missao
    V->>R: POST /checkins com habit_id
    R->>C: store(request)
    C->>C: Valida habit_id
    C->>H: Busca habit
    C->>C: Confere se o habit pertence ao usuario
    C->>CI: Verifica check-in existente no dia/semana

    alt Ja existe check-in
        C-->>V: Retorna erro de missao ja concluida
    else Ainda nao existe
        C->>CI: Cria checked_date e checked_at
        C->>G: Calcula recompensa do check-in
        G-->>C: XP e moedas
        C-->>V: Retorna sucesso com recompensa
    end
```

## Modulos por Responsabilidade

```mermaid
mindmap
  root((WebHabit))
    Interface
      Blade layouts
      Dashboard
      Habitos
      Metas
      Relatorios
      Perfil
      Admin
    HTTP
      Web routes
      API Sanctum
      Auth Breeze
      Middlewares
        role:admin
        active
    Dominio
      User
      Habit
      Category
      Checkin
      Goal
      RewardRedemption
    Servicos
      GamificationService
        XP
        Moedas
        Nivel
        Conquistas
        Loja
      StreakService
        Daily streak
        Weekly streak
    Persistencia
      Migrations
      Seeders
      Factories
    Testes
      Feature
      Unit
```

