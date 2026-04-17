# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

All PHP/Artisan commands run inside Sail. Use `./vendor/bin/sail` (or alias `sail`).

### Development

```bash
sail composer dev          # Start all services concurrently: Laravel server, queue worker, log viewer (pail), and Vite
sail composer dev:ssr      # Same as above but with SSR (runs npm run build:ssr first)
```

### Testing

```bash
sail composer test                                            # Clear config cache, then run all Pest tests
sail artisan test --filter "test name"                        # Run a single test by name
sail artisan test tests/Feature/DashboardTest.php             # Run a specific test file
```

### Frontend

```bash
sail npm run build         # Vite production build (client-side)
sail npm run build:ssr     # Vite production build (client + SSR)
sail npm run lint          # ESLint with auto-fix
sail npm run format        # Prettier format for resources/
sail npm run format:check  # Check Prettier formatting without writing
```

### Initial Setup

```bash
sail up -d
sail composer install && sail npm install
cp .env.example .env
sail artisan key:generate
sail artisan migrate
```

## Architecture

This is a **Laravel 12 + Vue 3 + Inertia.js** application. Inertia eliminates the need for a separate API — controllers return `inertia()` responses that render Vue page components server-side and hydrate them client-side. There is no REST API; all data flows through Inertia props.

### Request / Response Flow

1. Browser requests a route → Laravel router → Controller
2. Controller validates via a **Form Request** (`app/Http/Requests/`), loads data, wraps it in an **API Resource** (`app/Http/Resources/`), and returns `inertia('PageName', [...props])`
3. Inertia renders the matching Vue page (`resources/js/pages/`) with props typed from `resources/js/types/models.d.ts`
4. Subsequent navigations are XHR; Inertia swaps only the page component without a full reload

### Backend Structure

- **Models** (`app/Models/`): All use UUID7 primary keys (auto-assigned in `booted()`), soft deletes, and cast `game_data` as array. Core models: `Game → players, awards, cards, drawnNumbers, playerInvites`
- **Controllers**: Thin — validate with Form Requests, call model methods, return Inertia or redirect. `GameController` is the primary resource.
- **Resources** (`app/Http/Resources/`): Transform Eloquent models before sending to the frontend. Always use these; never pass raw models to Inertia.

### Frontend Structure

- **Pages** (`resources/js/pages/`): One Vue component per route. Named to match the string passed to `inertia()` in the controller (e.g. `'games/Upsert'` → `pages/games/Upsert.vue`).
- **Layouts** (`resources/js/layouts/`): `AppLayout` (authenticated, sidebar) and `AuthLayout` (unauthenticated). Applied via `<script setup>` `defineOptions({ layout })`.
- **Types** (`resources/js/types/models.d.ts`): TypeScript interfaces for all backend models. Keep these in sync with migrations and API Resources.
- **Composables** (`resources/js/composables/`): `useInitials` (avatar initials), `useAppearance` (dark mode).

### Game Domain

- **Game modes** (hardcoded in `GameController::create`): `traditional`, `speed`, `progressive`, `tournament`
- **Game statuses**: `active | inactive | archived | completed`
- **`game_data`** JSON column on `Game` stores all configurable options (draw speed, max cards, auto-verify, etc.) — see `GameData` interface in `models.d.ts`
- Players join games via `PlayerInvite` (status: `pending | accepted | declined`); they then have `Card` records with a `numbers` JSON array

### Routing

Routes are split across three files: `routes/web.php` (main app), `routes/auth.php` (authentication), `routes/settings.php` (profile/password). Named routes use Ziggy on the frontend (`route('games.index')`).

## Conventions

- **PHP**: 4-space indentation, PSR-4 autoloading. Use `back()->withErrors()` for failure responses; `to_route()` for success redirects after mutations.
- **TypeScript/Vue**: Strict mode. 4-space indentation, single quotes, 150-char print width (Prettier). Tailwind classes sorted automatically by `prettier-plugin-tailwindcss`.
- **Database**: SQLite by default for local dev. All primary keys are UUID strings (`$keyType = "string"`, `$incrementing = false`).
- **Testing**: Pest PHP. Tests use `actingAs(User::factory()->create())` for authenticated routes. Feature tests hit real routes; no mocking of the DB layer.
