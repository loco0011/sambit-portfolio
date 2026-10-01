# Sambit Maity — Portfolio

Laravel 12 + React 19 (Vite, Tailwind v4, Motion, Lenis).

## Run locally

```bash
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate
npm run build        # or `npm run dev` while editing
php artisan serve
```

> On OneDrive folders, Windows marks directories read-only and PHP refuses to write.
> If you see "bootstrap/cache must be writable", run: `attrib -R bootstrap\cache /S /D` and `attrib -R storage /S /D`.

## Editing content

All text (profile, experience, projects, architecture diagrams, stats, principles) lives in
`config/portfolio.php`. No frontend rebuild is needed, since it's injected into the page at runtime.

Replace the résumé at `storage/app/private/resume.pdf` (served at `/resume`).

## Contact messages

Stored in the `contact_messages` table (rate-limited to 5 per 10 min per IP, with a honeypot).
View them with:

```bash
php artisan tinker --execute="App\Models\ContactMessage::latest()->get(['name','email','company','message','created_at'])->each(fn(\$m)=>dump(\$m->toArray()));"
```

## Structure

- `app/Http/Controllers/PortfolioController.php`: page + résumé download
- `app/Http/Controllers/ContactController.php`: contact form endpoint
- `resources/views/app.blade.php`: shell, SEO/OG tags, JSON-LD, no-JS fallback
- `resources/js/components/*`: sections (Hero, About, Experience, Work, Capabilities, …)
- `resources/js/ui/*`: primitives (Magnetic, SplitWords/FadeUp, Counter, SectionHead)

## Deploying (VPS)

Point Nginx at `public/`, set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL`, then run
`npm run build && php artisan migrate --force && php artisan config:cache && php artisan view:cache`.
