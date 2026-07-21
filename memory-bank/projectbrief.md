# Project Brief

## Project Name

Armin Backend (`armin-backend`)

## Purpose

This repository is the backend/API and CMS for Armin's personal website/portfolio. It provides public JSON endpoints consumed by a frontend and an admin CMS for managing portfolio content.

## Core Requirements

- Serve portfolio project data through API endpoints.
- Serve CMS-managed pages and rendered Twill block content.
- Expose global site settings such as metadata, social links, Open Graph images, favicon, and CV file URL.
- Support a contact form endpoint that validates input and sends email.
- Provide an admin interface through Twill for content management.
- Manage media, files, slugs, ordering, revisions, and repeaters through Twill conventions.
- Refresh low-quality image placeholders after CMS saves.
- Generate/serve robots files based on API/admin host context.

## Primary Domain Concepts

- **Project**: A portfolio item with title, description, slug, ordered position, media crops, Twill blocks, and associated technologies.
- **Project Technology**: Ordered repeater-style technology entries attached to projects.
- **Page**: CMS-managed content page with slug, Twill blocks, media, files, position, and revisions.
- **CV**: CMS-managed file entry exposing the current CV file URL.
- **Settings**: Twill settings used for global metadata, social profiles, favicon, Open Graph images, and CV-related frontend configuration.
- **Cache**: A database-backed cache value exposed at the API `cache` endpoint.

## Scope

The repository is currently focused on backend/API and CMS behavior. Frontend rendering appears to live outside this repository, while this backend exposes structured JSON and media URLs for it.

## Out of Scope / Unknowns

- The consuming frontend application is not included in this repository.
- Deployment details are not documented yet.
- README is still the default Laravel README and does not describe project-specific setup.
