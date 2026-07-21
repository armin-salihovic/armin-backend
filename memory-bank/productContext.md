# Product Context

## Why This Project Exists

This backend supports a personal portfolio website for Armin. It separates content management from frontend presentation by using Twill CMS for editing and Laravel API endpoints for delivery.

## Problems It Solves

- Allows portfolio projects to be managed without code changes.
- Allows pages and structured content blocks to be authored in a CMS.
- Provides reusable global site settings for SEO metadata, social links, images, favicon, and CV access.
- Handles contact form submissions from the frontend.
- Provides optimized image-related data such as specific crops, social images, aspect ratios, and LQIP values.

## Intended Users

- **Site visitors**: Interact with the frontend portfolio, browse projects/pages, download CV, and submit contact messages.
- **Content administrator**: Uses Twill admin to create, update, publish, order, and enrich portfolio content.
- **Developer/maintainer**: Extends backend APIs, CMS modules, and content structures.

## User Experience Goals

- Frontend should receive predictable JSON payloads for pages, projects, settings, and contact responses.
- CMS should offer an efficient editorial workflow for portfolio content.
- Media should be served in frontend-friendly formats/crops, including mobile and social variants where configured.
- SEO and social sharing metadata should be centrally managed.

## Key API Experiences

- `GET /projects` returns published projects ordered for listing, optionally searchable by title.
- `GET /projects/{slug}` returns detailed project content or `404` if absent.
- `GET /settings` returns global site metadata, social links, Open Graph images, favicon, and CV file.
- `GET /{slug}` returns CMS page content by slug or `404` if absent.
- `POST /send-email` validates contact form input and sends an email.
- `GET /cache` returns a database cache value.
- `GET /robots.txt` serves different robots files for API/admin host context.
