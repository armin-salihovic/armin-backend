# Technical Context

## Runtime and Framework

- PHP: `^8.0.2`
- Laravel Framework: `^9.19`
- CMS: `area17/twill ^2.9`
- API authentication package present: `laravel/sanctum ^3.0`
- HTTP client: `guzzlehttp/guzzle ^7.2`
- Sitemap package: `spatie/laravel-sitemap ^6.2`

## Development Dependencies

- Laravel Pint: `^1.0`
- Laravel Sail: `^1.0.1`
- PHPUnit: `^9.5.10`
- Mockery, Faker, Collision, Laravel Ignition

## Frontend/Asset Tooling

- Node package scripts:
  - `npm run dev` -> `vite`
  - `npm run build` -> `vite build`
- Vite config uses `laravel-vite-plugin` with inputs:
  - `resources/css/app.css`
  - `resources/js/app.js`
- Frontend dependencies are minimal and appear mainly for Laravel asset scaffolding: Axios, Lodash, PostCSS, Vite.

## Environment Configuration

From `.env.example`:

- `APP_NAME=Armin`
- `APP_URL=api.armin.test`
- `ADMIN_APP_URL=admin.armin.test`
- Database: MySQL (`DB_CONNECTION=mysql`, database `armin`, user `root`)
- Queue: database (`QUEUE_CONNECTION=database`)
- Cache/session: file
- Mail: SMTP via Mailhog (`mailhog:1025`)
- Twill media/file library endpoint types: local
- Twill image service: `A17\Twill\Services\MediaLibrary\Glide`

## Important Commands

Common Laravel commands likely relevant:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
php artisan queue:work
php artisan test
vendor/bin/pint
npm install
npm run dev
npm run build
```

Docker deployment commands:

```bash
cp .env.docker.example .env.docker
docker compose build app
docker compose up -d --build
docker compose -f docker-compose.prod.yml pull
docker compose -f docker-compose.prod.yml up -d
docker compose exec app php artisan migrate --force
docker compose exec app php artisan db:seed --force
docker compose restart worker
```

Twill-specific command observed in code:

```bash
php artisan twill:lqip
```

## Testing

- Configured through `phpunit.xml`.
- Unit tests path: `tests/Unit`
- Feature tests path: `tests/Feature`
- Coverage includes `app/`.
- Mail and queues are configured to non-external drivers in tests.

## Technical Constraints / Considerations

- Project uses Laravel 9 and PHP 8.0-era constraints; avoid introducing syntax or dependencies requiring newer PHP unless project constraints are updated.
- Twill conventions should be preserved for module controllers, repositories, models, media crops, files, blocks, slugs, repeaters, and settings.
- Queue-backed jobs depend on queue worker/database queue setup in non-test environments.
- Public API routes are domain-scoped to `config('app.url')`; local hosts matter.
- README now includes project-specific Docker deployment documentation, with local non-Docker setup notes.
- Docker deployment is configured with PHP-FPM + Nginx + queue worker and SQLite at `/var/www/html/database/sqlite/database.sqlite` to avoid a separate database service.
- `docker-compose.yml` is intended for local production-like builds/tests; `docker-compose.prod.yml` is intended for servers and pulls prebuilt images from GHCR.
- GitHub Actions builds and publishes app/nginx images to GitHub Container Registry, then deploys by SSH with `docker compose -f docker-compose.prod.yml pull` and `up -d`.
- Docker volumes persist `storage/` and `database/sqlite/`; production HTTPS is expected to be handled by a server-level reverse proxy such as Caddy, Traefik, Nginx Proxy Manager, or host Nginx/Certbot.
- Route caching is intentionally not used in the Docker entrypoint because `routes/api.php` and `routes/web.php` currently contain closure routes.
- The Docker entrypoint fails fast when `APP_KEY` is missing; it intentionally does not auto-generate keys because production keys must remain stable across restarts/deployments.
- Worker services override `RUN_MIGRATIONS=false` and `RUN_SEEDERS=false` so only the app service performs startup database bootstrap against SQLite.

## Repository / Remote

- Workspace path: `C:/Users/Armin/Documents/Projects/Personal/armin-backend`
- Git remote: `git@github.com:armin-salihovic/armin-backend.git`
