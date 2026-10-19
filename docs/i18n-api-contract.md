# Backend localization contract

## Locale selection

Public `GET` content endpoints accept `?locale=en` or `?locale=de`. An omitted parameter resolves to English. Public URLs remain unprefixed on the API: the frontend's `/de/about` page requests `/about?locale=de`; there are no `/en` backend routes. An unsupported value, including an array value, returns HTTP 400:

```json
{
  "message": "Unsupported locale.",
  "errors": { "locale": ["The selected locale is invalid."] }
}
```

Locale is scoped to public reads and restored after the response is prepared. The email `POST` route does not require a locale.

## Response envelopes and fields

Existing response envelopes and field names are retained. `GET /settings` is an unwrapped object. Page and project detail responses keep the `data` wrapper. The project list remains a `data` collection:

```json
{
  "data": [
    {
      "slug": "project-slug",
      "technologies": ["Laravel", "Vue"],
      "title": "Localized title",
      "image": "https://…",
      "image_mobile": "https://…",
      "image_lqpi": "…",
      "image_lqpi_mobile": "…",
      "aspect_ratio": "16/9"
    }
  ]
}
```

`GET /projects/{slug}` returns the same detail fields as before:

```json
{
  "data": {
    "title": "Localized title",
    "image": "https://…",
    "description": "Localized description",
    "content": "<div>Rendered block HTML</div>"
  }
}
```

Page detail remains `{"data":{"title":"…","content":"…"}}`. English responses keep their existing field sets and ordering, image URL behavior, technology values, and resource wrappers. Slugs and project identifiers remain language-neutral and unchanged.

Settings use the existing unwrapped keys, including `projects_meta_title`, `projects_meta_description`, `about_meta_title`, and shared social links and images. Settings select the requested locale first and fall back per key to English when that translation is absent or empty; the query is constrained to those locales, so values from another language cannot overwrite one another.

## Translation and publication behavior

Page titles; project titles and descriptions; text fields in page/project blocks; and textual SEO settings are editable in both CMS locales. Block text is held in Twill's locale-keyed block JSON. Existing English block strings are backfilled to the `en` key. A missing field in a published German translation falls back to its English value. Project technologies, media relationships, image/file assets, and media alt text/captions remain shared. Project slugs and page slugs are the same across locales; the CMS synchronizes locale slug entries from the English slug.

German availability follows Twill's locale publication state. `GET /projects?locale=de` lists only base-published projects with an active German translation; a detail request for a project without an active German translation returns 404. A page request behaves the same way. A published German variant may still use English for individual fields that have not yet been translated. The frontend should use the German project list and page detail status to decide which German routes are publishable; it does not need a new response field. To create or publish a German page/project, use the CMS locale switcher, add the German content, and enable German publication in the per-language publisher. Media and technologies stay shared.

Project list search uses the selected locale's title. Publication and ordering remain the existing base `published`, `position`, and latest-created ordering.

## Deployment and rollback

Run `php artisan migrate` after deploying. The additive migration creates Twill-native `page_translations` and `project_translations`, copies existing English title/description values into active English translation rows consistent with existing publication state, and leaves legacy columns intact. It also wraps existing translated block strings under `en`. It does not rewrite project/page identities, media, ordering, technologies, or existing English slugs. No historical migration is edited.

Rolling the migration down copies the current English translation values back to legacy columns, unwraps English block values, and drops the translation tables. German translations are removed by that rollback. Existing settings translations already use Twill's `setting_translations`; their API reads are now locale-filtered. The shared cache token is invalidated when settings translations are saved or deleted; no locale-specific response cache is introduced.
