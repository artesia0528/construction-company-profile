# Nusantara Build Construction - Company Profile Template

Reusable Laravel + Filament starter template for construction company profile websites. It includes a public-facing marketing site, a Filament admin panel for content management, PostgreSQL-ready configuration, demo seed data, and reusable Blade components.

## Tech Stack

- **Backend:** Laravel 13 / PHP 8.3+ (project currently runs on PHP 8.5)
- **Admin/CMS:** Filament 5
- **Frontend:** Blade + Tailwind CSS v4
- **Interactivity:** Vanilla JS (mobile nav toggle)
- **Database:** PostgreSQL (recommended for app), SQLite for tests
- **Testing:** Pest 5
- **Assets:** Vite 8

## Features

- Public pages: Home, About, Services, Service Detail, Projects, Project Detail, Contact
- Dynamic content from database: services, projects, project gallery, testimonials, team, statistics, company settings, contact messages
- Filament admin resources for Services, Projects, Testimonials, Teams, Statistics, Contact Messages
- Singleton Company Settings page in Filament
- Dashboard stats + recent contact messages widget
- Contact form validation and database storage
- Reusable Blade components for layout, UI, cards, and sections
- SEO-friendly slugs and dynamic page titles/meta descriptions

## Project Structure

```text
app/
├── Filament/
│   ├── Pages/ManageCompanySetting.php
│   ├── Resources/                  # Filament CRUD resources
│   └── Widgets/                    # Dashboard widgets
├── Http/
│   ├── Controllers/                # Thin public controllers
│   └── Requests/ContactMessageRequest.php
├── Models/                         # Eloquent models + scopes/relations
└── Providers/

resources/views/
├── components/
│   ├── layouts/app.blade.php
│   ├── layout/{navbar,footer}.blade.php
│   ├── ui/{button,section-heading,breadcrumb}.blade.php
│   ├── ui/form/{input,textarea}.blade.php
│   ├── cards/{project,service,testimonial,team,statistic,feature}-card.blade.php
│   └── sections/{hero,company-intro,stats,services,featured-projects,why-choose-us,testimonials,cta,page-banner}.blade.php
└── pages/                          # Public page compositions
```

## Installation

1. Install PHP dependencies:

```bash
composer install
```

2. Install JS dependencies:

```bash
npm install
```

3. Create environment file and app key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure PostgreSQL in `.env` (defaults in `.env.example`):

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=construction_company_profile
DB_USERNAME=postgres
DB_PASSWORD=your_password
FILESYSTEM_DISK=public
MEDIA_DISK=public
```

5. Run migrations and seed demo data:

```bash
php artisan migrate --seed
```

6. Create storage symlink:

```bash
php artisan storage:link
```

7. Build assets (or run dev server):

```bash
npm run build
# or
npm run dev
```

8. Start Laravel:

```bash
php artisan serve
```

## Admin Panel

- URL: `/admin`
- Seeded local admin user:
  - Email: `admin@nusantarabuild.co.id`
  - Password: `password`

Change seeded credentials before using in any real environment.

## Development Commands

```bash
php artisan test --compact      # Run tests
vendor/bin/pint --dirty --format agent  # Format changed PHP files
npm run build                   # Build frontend assets
php artisan migrate:fresh --seed # Reset DB with demo data
```

## Adding Content

- **Company profile/social/hero/about:** Filament > Company Settings
- **Stats:** Filament > Statistics
- **Services/Projects/Testimonials/Team:** respective Filament resources
- **Contact messages:** submitted from public contact form, managed in Filament

## Adding a New Public Page

1. Create controller in `app/Http/Controllers`.
2. Add route in `routes/web.php`.
3. Create Blade page in `resources/views/pages/...` using `<x-layouts.app>`.
4. Reuse existing components from `resources/views/components`.

## Adding a New Blade Component

1. Create component in appropriate folder (`ui`, `cards`, `sections`, `layout`).
2. Keep it reusable with props/slots; avoid business logic.
3. Use it in pages/sections instead of duplicating markup.

## Image Optimization

- Uploaded images are optimized through `App\Support\ImageProcessor`: resize max 1920px (longest side), convert to WebP quality 80, and generate `{name}_thumb.webp`.
- If GD/Imagick/WebP support is unavailable, upload falls back to storing the original file and logs a warning.
- Legacy images can be migrated with:

```bash
php artisan images:optimize-legacy --dry-run
php artisan images:optimize-legacy --cleanup
```

- `--dry-run` reports without writing; `--cleanup` removes old files only after new paths are saved. Use `--model=Service` (or other model basename/FQCN) to limit scope.
- Ensure PHP has `gd` or `imagick` with WebP support on the target server.

## Notes

- Tests use SQLite in-memory (`phpunit.xml`), so app DB can remain PostgreSQL.
- Uploaded images use the configured `MEDIA_DISK` (default `public`); run `php artisan storage:link`.
- Demo content uses “Nusantara Build Construction” placeholder company data.
