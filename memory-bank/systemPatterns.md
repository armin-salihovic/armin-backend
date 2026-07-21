# System Patterns

## High-Level Architecture

The project is a Laravel 9 backend with Twill CMS. It exposes public API endpoints for a separate frontend and provides an admin CMS for managing content.

```text
Frontend client
    -> Laravel API routes (`routes/api.php`)
        -> Controllers (`app/Http/Controllers`)
            -> Repositories / Twill models
            -> API Resources (`app/Http/Resources`)
                -> JSON responses

Admin user
    -> Twill admin routes (`routes/admin.php`)
        -> Twill module controllers/repositories/models
        -> Database, media library, file library
```

## Routing Patterns

### Public API (`routes/api.php`)

- Domain grouped by `config('app.url')`.
- Endpoints include:
  - `GET cache`
  - `GET projects`
  - `GET projects/{slug}`
  - `POST send-email`
  - `GET settings`
  - `GET {slug}` for CMS pages
- `robots.txt` route selects API/admin robots file based on host subdomain.

### Admin CMS (`routes/admin.php`)

- Twill modules registered via:
  - `Route::module('projects')`
  - `Route::module('pages')`
  - `Route::module('cvs')`
- Custom CV route maps `GET cvs` to `Admin\CvController::cv()`.

## Controller Pattern

- Public controllers are thin and delegate data access to repositories where appropriate.
- They return Laravel JSON resources or validation/mail responses.
- Missing project/page slugs return empty JSON with `404`.

Examples:
- `ProjectController@index` -> `ProjectRepository::allProjects()` -> `ProjectResourceBasic::collection(...)`
- `ProjectController@show` -> `ProjectRepository::forSlug($slug)` -> `ProjectResource`
- `PageController@show` -> `PageRepository::forSlug($slug)` -> `PageResource`
- `PageController@getSettings` -> Twill settings query -> `Settings` service -> `SettingsResource`
- `MailController@sendEmail` -> validate request -> send `MessageSent` mail

## Twill Model Patterns

### `Project`

- Extends `A17\Twill\Models\Model` and implements `Sortable`.
- Uses `HasBlocks`, `HasSlug`, `HasMedias`, and `HasPosition`.
- Slug generated from `title`.
- Defines media crops for default, mobile, Open Graph, and flexible variants.
- Has many `ProjectTechnology` records ordered by `position`.
- Provides `getAspectRatioFormatted()` for frontend image aspect ratio metadata.

### `Page`

- Extends Twill model and implements `Sortable`.
- Uses `HasBlocks`, `HasSlug`, `HasMedias`, `HasRevisions`, `HasPosition`, and `HasFiles`.
- Slug generated from `title`.
- Supports `single_file` file parameter.
- Defines media crops similar to projects except no explicit Open Graph crop in the model.

### `Cv`

- Extends Twill model.
- Uses `HasFiles` with `cv` file parameter.
- Exposes `Cv::getCv()` to return the first CV file URL.
- Index publish controls disabled via `$indexOptions = ['publish' => false]`.

## Repository Patterns

- Twill module repositories extend `A17\Twill\Repositories\ModuleRepository`.
- Repositories use Twill behavior traits such as `HandleBlocks`, `HandleSlugs`, and `HandleMedias`.
- `ProjectRepository::allProjects()` fetches published projects ordered by position/latest, with optional title search.
- `ProjectRepository::afterSave()` persists `technologies` repeater data.
- `ProjectRepository::getFormFields()` loads repeater fields for admin forms.

## Resource Serialization Patterns

- `ProjectResourceBasic` returns listing fields: slug, technologies, title, desktop/mobile images, LQIP values, and aspect ratio.
- `ProjectResource` returns detailed fields: title, social image, description, and rendered Twill blocks.
- `PageResource` returns title and rendered blocks.
- `SettingsResource` returns favicon, social links, meta fields, Open Graph images, and CV file.

## Event / Job Pattern

- `EventServiceProvider` listens for Twill `cms-module.saved` events.
- On CMS save, it dispatches `App\Jobs\RefreshLQIP`.
- `RefreshLQIP` implements `ShouldQueue`, runs `Artisan::call('twill:lqip')`, and logs progress.
- Queue connection defaults to `database` in `.env.example`.

## Settings Pattern

- `PageController@getSettings()` queries Twill settings where `section = settings`, joins translations, and plucks values by key.
- `App\Services\Settings` wraps settings and provides magic property access plus `image()` / `socialImage()` helpers.
- `config/twill.php` defines settings media crops for logo, favicon, and Open Graph images.

## Testing Pattern

- PHPUnit is configured with Unit and Feature test suites.
- Test environment uses array cache/session, array mailer, sync queue, and app coverage for `app/`.
- SQLite in-memory settings are present but commented out.
