# WebHabit — Contexto Didatico Para Estudantes

> Este arquivo explica o projeto como se fosse uma visita guiada.
> A ideia e ajudar quem ainda nao vive no mundo da programacao a entender o que foi construido, por que foi construido e onde cada coisa mora.

Atualizado em: 2026-05-19

---

## 1. O Que E O WebHabit?

O WebHabit e um sistema para acompanhar habitos, metas, check-ins e progresso.

Uma analogia simples:

Imagine uma academia futurista.

- Os **habitos** sao os exercicios do treino.
- Os **check-ins** sao as marcacoes de que voce treinou naquele dia.
- As **metas** sao objetivos, como "treinar 20 vezes este mes".
- O **dashboard** e o painel na parede mostrando seu desempenho.
- Os **relatorios** sao como uma conversa com um treinador olhando seu historico.
- A area **admin** e a recepcao da academia, onde alguem gerencia usuarios, categorias e indicadores gerais.

No nosso projeto, transformamos essa ideia em uma aplicacao Laravel com visual gamificado cyberpunk.

---

## 2. A Pilha De Tecnologias

Pense na aplicacao como um predio.

| Parte | Tecnologia | Analogia |
|---|---|---|
| Estrutura principal | Laravel 12 | A planta e as paredes do predio |
| Login e cadastro | Laravel Breeze | A portaria do predio |
| Visual | Blade + Tailwind CSS | Pintura, luzes, placas e organizacao dos comodos |
| Interacoes simples | Alpine.js | Interruptores e pequenas automacoes |
| API | Sanctum | Uma entrada lateral segura para apps externos |
| Banco local | MySQL | O arquivo onde ficam guardadas as fichas |
| Testes | PHPUnit | Uma lista de verificacoes antes de abrir o predio |

---

## 3. Como O Projeto Esta Organizado

### `app/`

E a parte "cerebro" da aplicacao.

Aqui ficam regras, controllers, models e middlewares.

Analogia:

Se o WebHabit fosse um restaurante, `app/` seria a cozinha e a gerencia. E onde as decisoes acontecem.

Arquivos importantes:

- `app/Models/User.php`: representa o usuario.
- `app/Models/Habit.php`: representa um habito.
- `app/Models/Goal.php`: representa uma meta.
- `app/Http/Controllers/DashboardController.php`: monta os dados do dashboard do usuario.
- `app/Http/Controllers/HomeController.php`: monta a nova home logada.
- `app/Http/Middleware/EnsureUserIsActive.php`: impede usuario inativo de continuar usando o sistema.

### `resources/views/`

E a parte "tela" da aplicacao.

Analogia:

Se `app/` e a cozinha, `resources/views/` e o salao onde o usuario ve o prato servido.

Arquivos importantes:

- `welcome.blade.php`: landing page publica.
- `home.blade.php`: home depois do login.
- `dashboard.blade.php`: dashboard pessoal.
- `habits/`: telas de habitos.
- `goals/`: telas de metas.
- `reports/`: relatorios pessoais.
- `admin/`: telas administrativas.
- `layouts/`: molduras gerais do site.
- `components/`: pecas reutilizaveis, como botoes e inputs.

### `routes/`

E o mapa de caminhos.

Analogia:

Como placas dentro de um shopping: "para chegar na loja X, siga por este corredor".

O arquivo principal e:

- `routes/web.php`

Exemplo:

```php
Route::get('/home', HomeController::class)->name('home');
```

Isso quer dizer:

"Quando alguem acessar `/home`, chame o `HomeController` e de a essa rota o nome `home`."

### `tests/`

E a area de conferencia.

Analogia:

Como um checklist antes de um aviao decolar. Ninguem quer descobrir no ar que esqueceu uma peca.

Foi criado:

- `tests/Feature/HomeTest.php`

Ele verifica se:

- a home exige login;
- usuario logado consegue abrir a home;
- admin ve o card de operacao.

---

## 4. O Que Foi Implementado Ao Longo Das Fases

## Fase 1 — API E Base De Acesso

Nesta etapa, o projeto ganhou uma API autenticada com Sanctum.

Analogia:

Imagine que o WebHabit tem uma porta principal, que e o site. A API e uma porta lateral, usada por aplicativos mobile ou outros sistemas. Mas essa porta precisa de cracha.

Implementacoes principais:

- Endpoints para listar habitos.
- Endpoint para registrar check-ins.
- Endpoint para consultar estatisticas.
- Usuario passou a usar tokens com Sanctum.
- Usuario inativo recebe resposta JSON adequada quando tenta acessar pela API.

Arquivos relacionados:

- `routes/api.php`
- `app/Http/Controllers/Api/HabitController.php`
- `app/Http/Controllers/Api/CheckinController.php`
- `app/Http/Controllers/Api/StatsController.php`
- `app/Models/User.php`
- `app/Http/Middleware/EnsureUserIsActive.php`
- `tests/Feature/ApiTest.php`

---

## Fase 2 — Landing Page Publica

Criamos uma pagina inicial publica mais forte visualmente.

Depois, ela foi refinada para seguir a referencia preto/rosa com visual futurista.

Analogia:

A `welcome.blade.php` e a fachada da loja. Mesmo antes de entrar, a pessoa ja entende o estilo do produto.

Implementacoes principais:

- Landing page escura.
- Paleta preto/rosa.
- Hero central com chamada forte.
- Botoes de login/cadastro.
- Secoes explicando valor, fluxo, recursos e API.

Arquivo principal:

- `resources/views/welcome.blade.php`

---

## Fase 3 — Visual Cyberpunk No Sistema

Aplicamos um visual gamificado cyberpunk ao restante do projeto.

Analogia:

Antes, o sistema era como uma sala funcional com moveis basicos. Depois dessa fase, ele virou um centro de comando com telas, luzes neon e paineis de missao.

O que mudou:

- Layout geral escuro.
- Botoes com destaque neon.
- Cards com bordas e brilho sutil.
- Tabelas mais escuras.
- Inputs e formularios com estilo unificado.
- Dashboards com visual mais tecnico.

Arquivos importantes:

- `resources/css/app.css`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/guest.blade.php`
- `resources/views/layouts/navigation.blade.php`
- `resources/views/components/*.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/habits/*`
- `resources/views/goals/*`
- `resources/views/reports/*`
- `resources/views/admin/*`

---

## Fase 4 — Home Logada Com Cards Visuais

Criamos uma nova tela para onde o usuario vai depois de logar: `/home`.

Analogia:

Pense na home como o lobby de um jogo. Antes de entrar em uma missao, voce escolhe para onde quer ir:

- Dashboard;
- Habitos;
- Metas;
- Relatorios;
- Perfil;
- Admin, se for administrador.

Cada card tem uma imagem simbolica.

Arquivos principais:

- `app/Http/Controllers/HomeController.php`
- `resources/views/home.blade.php`
- `public/images/home-cards/*.svg`
- `routes/web.php`

Tambem ajustamos redirecionamentos:

- depois do login;
- depois do cadastro;
- depois da verificacao de email;
- depois da confirmacao de senha.

Arquivos relacionados:

- `app/Http/Controllers/Auth/AuthenticatedSessionController.php`
- `app/Http/Controllers/Auth/RegisteredUserController.php`
- `app/Http/Controllers/Auth/VerifyEmailController.php`
- `app/Http/Controllers/Auth/EmailVerificationPromptController.php`
- `app/Http/Controllers/Auth/EmailVerificationNotificationController.php`
- `app/Http/Controllers/Auth/ConfirmablePasswordController.php`

---

## Fase 5 — Refinamento Interno Estilo SaaS Cyberpunk

Depois da home logada, refinamos dashboards, CRUDs e paginas internas usando uma referencia mais "SaaS cyberpunk".

Analogia:

Se a fase anterior criou o lobby do jogo, esta fase melhorou os menus, paineis e telas de operacao para parecerem parte do mesmo universo.

Mudancas principais:

- Paleta interna baseada em cyan, magenta e preto.
- Menos laranja, mais identidade tecnica.
- Cards mais densos.
- Tabelas com melhor contraste.
- Graficos com cores alinhadas ao tema.
- Navegacao mais consistente.

Arquivos principais:

- `resources/css/app.css`
- `resources/views/layouts/navigation.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/reports/index.blade.php`
- `resources/views/admin/reports/index.blade.php`

---

## Fase 6 — Polimento De UX E Responsividade

Nesta fase, ajustamos pequenos problemas que normalmente aparecem quando a tela muda de tamanho.

Analogia:

Depois de montar uma casa bonita, voce anda por ela e percebe detalhes:

- uma porta abre batendo na parede;
- uma cadeira esta apertada;
- uma placa esta dificil de ler;
- uma mesa fica ruim em um comodo menor.

Foi esse tipo de ajuste.

Melhorias feitas:

- Cards da home mais seguros em mobile.
- Textos com quebra melhor.
- Tabelas admin com scroll horizontal controlado.
- Filtros mais confortaveis em telas pequenas.
- Estados vazios com melhor espacamento.
- Alerts mais consistentes.
- Acoes de editar/excluir/check-in mais flexiveis.

Arquivos principais:

- `resources/views/home.blade.php`
- `resources/views/dashboard.blade.php`
- `resources/views/habits/index.blade.php`
- `resources/views/goals/index.blade.php`
- `resources/views/reports/index.blade.php`
- `resources/views/admin/users/index.blade.php`
- `resources/views/admin/categories/index.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/admin/reports/index.blade.php`

---

## 5. Conceitos Importantes Para Entender O Projeto

## Controller

Um controller e como um atendente.

O usuario faz um pedido:

"Quero ver meu dashboard."

O controller pega os dados necessarios e entrega a tela pronta.

Exemplo:

- `DashboardController`
- `HomeController`
- `HabitController`
- `GoalController`

## Model

Um model representa uma coisa importante do sistema.

Analogia:

Como uma ficha de cadastro.

Exemplos:

- `User`: ficha de usuario.
- `Habit`: ficha de habito.
- `Goal`: ficha de meta.
- `Checkin`: registro de presenca.

## View

Uma view e a tela que o usuario enxerga.

Analogia:

Como uma vitrine organizada com informacoes vindas do estoque.

Exemplo:

- `resources/views/home.blade.php`

## Route

Uma rota e um endereco.

Analogia:

Como digitar um endereco no GPS.

Exemplo:

- `/home`
- `/dashboard`
- `/habits`
- `/goals`
- `/admin/dashboard`

## Middleware

Um middleware e um guarda na porta.

Ele verifica se a pessoa pode passar.

Exemplos:

- usuario esta logado?
- usuario esta ativo?
- usuario e admin?

---

## 6. Fluxo Do Usuario

Fluxo comum:

1. Usuario acessa a landing page.
2. Cria conta ou faz login.
3. E enviado para `/home`.
4. Escolhe um card:
   - Dashboard;
   - Habitos;
   - Metas;
   - Relatorios;
   - Perfil.
5. Registra check-ins.
6. Acompanha progresso.

Fluxo admin:

1. Admin faz login.
2. E enviado para `/home`.
3. Ve tambem o card "Operacao".
4. Pode acessar dashboards e controles administrativos.

---

## 7. Como Validamos Que Nao Quebrou

Sempre que alteramos algo importante, rodamos:

```bash
npm run build
php artisan test
```

Analogia:

- `npm run build` verifica se a parte visual consegue ser empacotada sem erro.
- `php artisan test` verifica se as regras principais do sistema continuam funcionando.

Ultimo estado validado:

- Build OK.
- Testes OK.
- `34 passed (91 assertions)`.

---

## 8. Arquivos Que Um Estudante Deve Ler Primeiro

Ordem sugerida:

1. `routes/web.php`
2. `app/Http/Controllers/HomeController.php`
3. `resources/views/home.blade.php`
4. `app/Http/Controllers/DashboardController.php`
5. `resources/views/dashboard.blade.php`
6. `app/Models/User.php`
7. `app/Models/Habit.php`
8. `app/Models/Goal.php`
9. `resources/css/app.css`
10. `tests/Feature/HomeTest.php`

Essa ordem ajuda porque vai do mapa geral para os detalhes.

---

## 9. Proximos Passos Possiveis

Ideias para evoluir o projeto:

- Criar XP por check-in.
- Criar niveis de usuario.
- Criar badges/conquistas.
- Criar animacao quando um check-in e feito.
- Criar ranking pessoal por periodo.
- Criar mais testes para admin e relatorios.
- Componentizar cards repetidos.
- Criar seeders com dados demo mais ricos.

Analogia final:

O WebHabit hoje ja tem o predio construido, a fachada estilizada, o lobby funcionando e os principais comodos mobiliados.

As proximas fases seriam como instalar sistemas especiais: placar de pontos, trofeus, missoes avancadas e efeitos de feedback para deixar a experiencia mais viva.

