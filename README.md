# Armin Backend

Laravel 9 + Twill CMS backend/API for Armin's personal portfolio website.

The app exposes JSON endpoints for portfolio content and provides a Twill admin CMS for projects, pages, CV files, media, and global settings.

## Docker deployment with SQLite

This repository includes a lightweight Docker Compose setup that avoids a separate database container by using SQLite.

### Services

- `app` — PHP-FPM Laravel application container.
- `nginx` — serves `public/` and forwards PHP requests to `app`.
- `worker` — runs Laravel's database-backed queue worker for Twill/LQIP jobs.

Persistent Docker volumes are used for:

- Laravel/Twill uploads and runtime storage: `app_storage`
- SQLite database file: `app_sqlite`

### First deployment

1. Copy the Docker environment template:

   ```bash
   cp .env.docker.example .env.docker
   ```

2. Edit `.env.docker` for your domain/server:

   ```dotenv
   APP_URL=https://api.example.com
   ADMIN_APP_URL=https://admin.example.com
   MAIL_HOST=smtp.example.com
   MAIL_USERNAME=...
   MAIL_PASSWORD=...
   ```

3. Build the application image:

   ```bash
   docker compose build app
   ```

4. Generate an application key and place it in `.env.docker` as `APP_KEY`:

   ```bash
   docker compose run --rm app php artisan key:generate --show
   ```

   `APP_KEY` is a persistent production secret. Do not regenerate it on every deploy, because changing it can invalidate encrypted cookies, sessions, and any encrypted application data.

   The Docker entrypoint intentionally refuses to start if `APP_KEY` is missing instead of generating one automatically.

5. Start the stack:

   ```bash
   docker compose up -d --build
   ```

By default, Nginx is exposed at `http://localhost:8080` on the Docker host.

The entrypoint automatically creates the SQLite file at:

```text
/var/www/html/database/sqlite/database.sqlite
```

and runs migrations when `RUN_MIGRATIONS=true`.

### Seeding

The default Docker environment does **not** run seeders automatically:

```dotenv
RUN_SEEDERS=false
```

To run seeders manually:

```bash
docker compose exec app php artisan db:seed --force
```

Or run specific seeders:

```bash
docker compose exec app php artisan db:seed --class=CacheSeeder --force
docker compose exec app php artisan db:seed --class=CvSeeder --force
```

### Useful operations

```bash
# View logs
docker compose logs -f

# Run migrations
docker compose exec app php artisan migrate --force

# Clear/cache Laravel config and views
docker compose exec app php artisan optimize:clear
docker compose exec app php artisan config:cache
docker compose exec app php artisan view:cache

# Restart queue worker after deployments/config changes
docker compose restart worker

# Open a shell in the app container
docker compose exec app sh
```

> Note: route caching is intentionally not enabled because this application currently contains closure-based routes.

## HTTPS recommendation

The included Compose stack exposes plain HTTP from the `nginx` service. For production HTTPS, use a server-level reverse proxy in front of this stack.

Recommended options:

- Caddy
- Traefik
- Nginx Proxy Manager
- Host-level Nginx with Certbot

Typical flow:

```text
Internet HTTPS
  -> reverse proxy with Let's Encrypt certificate
  -> http://127.0.0.1:8080
  -> Docker nginx service
  -> PHP-FPM app service
```

On a public server, bind the Compose port to localhost if an external reverse proxy is used:

```yaml
ports:
  - "127.0.0.1:8080:80"
```

Then configure your reverse proxy to forward your API/admin hostnames to `127.0.0.1:8080`.

## Important environment settings

For SQLite deployment:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/sqlite/database.sqlite
QUEUE_CONNECTION=database
FILESYSTEM_DISK=public
```

Because API routes are domain-scoped, make sure these values match the real hostnames you will use:

```dotenv
APP_URL=https://api.example.com
ADMIN_APP_URL=https://admin.example.com
```

## Local development without Docker

The project can still be run like a normal Laravel app:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

For frontend assets:

```bash
npm install
npm run dev
```
