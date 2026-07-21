# Active Context

## Current Work Focus

Docker/Compose deployment support has been added for the Laravel/Twill backend using SQLite to keep memory usage small.

## Recent Findings

- Repository is a Laravel 9 application using Twill CMS (`area17/twill`).
- Project-specific README documentation is now present, including Docker/SQLite deployment instructions.
- `.env.example` identifies the app as `Armin`, with API host `api.armin.test` and admin host `admin.armin.test`.
- Main public routes are in `routes/api.php`; Twill CMS module routes are in `routes/admin.php`.
- Core content models include `Project`, `Page`, `Cv`, `ProjectTechnology`, and `Cache`.
- Twill modules are registered for `projects`, `pages`, and `cvs`.
- `ProjectRepository` handles published project listing, search by `request('search')`, and project technologies repeater persistence.
- API resources transform models into frontend-oriented JSON.
- CMS save events dispatch `RefreshLQIP`, which runs `twill:lqip`.
- Docker deployment uses PHP-FPM + Nginx + a Laravel queue worker, with SQLite stored at `/var/www/html/database/sqlite/database.sqlite`.
- HTTPS is planned as a server-level reverse proxy concern rather than being baked into the app compose file.

## Active Decisions and Considerations

- Memory Bank documentation should remain factual and note unknowns rather than inventing project history.
- Future tasks should begin by reading all Memory Bank files, per `.clinerules/memory-bank.md`.
- Any future project-specific setup details discovered should be added to `techContext.md` and/or README.
- Docker keeps the app stack lightweight by avoiding MySQL/Postgres/Redis containers.
- The queue worker remains part of Compose because Twill save events dispatch queued LQIP refresh jobs and the queue uses the SQLite-backed database driver.
- Route caching should not be enabled until closure routes are refactored to controller methods.
- `APP_KEY` should be generated once and set in `.env.docker`; the entrypoint should fail fast if it is missing rather than generating a new production key.

## Important Patterns Observed

- Public API uses thin controllers and resource transformers.
- Data access for Twill-managed modules goes through Twill `ModuleRepository` subclasses.
- Twill behaviors provide blocks, slugs, media, files, position, revisions, and sortable behavior.
- Global settings are wrapped by `App\Services\Settings` before serialization.
- Media output is handled through Twill helpers like `image()`, `socialImage()`, `lowQualityImagePlaceholder()`, and `renderBlocks()`.

## Next Steps

- Validate Docker build and Compose configuration.
- Finalize production `.env.docker` values, especially `APP_KEY`, `APP_URL`, `ADMIN_APP_URL`, and mail settings.
- Configure a server-level HTTPS reverse proxy when the target server/domain setup is known.
