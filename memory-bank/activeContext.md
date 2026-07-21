# Active Context

## Current Work Focus

The Memory Bank is being initialized for the first time. The `memory-bank/` directory previously had no files.

## Recent Findings

- Repository is a Laravel 9 application using Twill CMS (`area17/twill`).
- Project-specific README documentation is not yet present; `README.md` is the default Laravel README.
- `.env.example` identifies the app as `Armin`, with API host `api.armin.test` and admin host `admin.armin.test`.
- Main public routes are in `routes/api.php`; Twill CMS module routes are in `routes/admin.php`.
- Core content models include `Project`, `Page`, `Cv`, `ProjectTechnology`, and `Cache`.
- Twill modules are registered for `projects`, `pages`, and `cvs`.
- `ProjectRepository` handles published project listing, search by `request('search')`, and project technologies repeater persistence.
- API resources transform models into frontend-oriented JSON.
- CMS save events dispatch `RefreshLQIP`, which runs `twill:lqip`.

## Active Decisions and Considerations

- Memory Bank documentation should remain factual and note unknowns rather than inventing project history.
- Future tasks should begin by reading all Memory Bank files, per `.clinerules/memory-bank.md`.
- Any future project-specific setup details discovered should be added to `techContext.md` and/or README.

## Important Patterns Observed

- Public API uses thin controllers and resource transformers.
- Data access for Twill-managed modules goes through Twill `ModuleRepository` subclasses.
- Twill behaviors provide blocks, slugs, media, files, position, revisions, and sortable behavior.
- Global settings are wrapped by `App\Services\Settings` before serialization.
- Media output is handled through Twill helpers like `image()`, `socialImage()`, `lowQualityImagePlaceholder()`, and `renderBlocks()`.

## Next Steps

- Verify the generated Memory Bank files exist and are readable.
- In future implementation tasks, update Memory Bank after significant changes or when new patterns are discovered.
- Consider replacing default Laravel README with project-specific setup instructions when requested.
