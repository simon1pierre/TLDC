# BGM — Agent Instructions

## PHP

- **Requires PHP ^8.4.1** (composer platform check). System PHP is 8.2 from XAMPP.
- **Use Herd's PHP**: `C:\Users\user\.config\herd\bin\php84\php.exe` (aliased as `php` below).

## Database

- **SQLite** by default (`DB_CONNECTION=sqlite`). File: `database/database.sqlite`.
- `SESSION_DRIVER=database`, `QUEUE_CONNECTION=database`, `CACHE_STORE=database` — all use SQLite.
- If `sessions` table is missing, run: `php artisan session:table && php artisan migrate`.
- Tests use `:memory:` SQLite.

## Commands

```bash
# Dev (server + queue + Vite concurrently)
composer dev

# Test (clears config first)
composer test

# Full setup
composer setup

# Build frontend
npm run build

# Export DB to JSON snapshots
php artisan data:snapshot-seed

# Auto-fill content translations
php artisan translations:auto-fill {all|videos|audios|audiobooks|books}
```

## Routes

- **Public**: `/`, `/videos`, `/books`, `/audios`, `/audiobooks`, `/devotionals`, `/about`, `/contact`, `/events`, `/give`
- **Admin**: `/beacons/admin/*` — requires `auth` + `admin` middleware
- **Auth**: `/beacons/admin/login` → 2FA verify → dashboard
- **Admin dashboard JSON**: `/beacons/admin/stats`

## Architecture

- **Laravel 13**, Livewire 4, Tailwind CSS 4, Vite 7, Pest
- Content types: `Video`, `Audio`, `Book` (document), `Audiobook`, `Devotional`
- Engagement: polymorphic `ContentLike`, `ContentComment` on all content types
- Analytics: `VideoEvent` (video), `ContentEvent` (audio/book), `AudiencePageEvent`
- Translations: `ContentTranslation` (polymorphic), `SettingTranslation`, trait `App\Models\Concerns\HasTranslations`
- Locale: primary `rw` (Kinyarwanda), supported: `en`, `fr`, `rw`
- SoftDeletes + Trash system with restore/force-delete on most resources
- Pagination: Bootstrap 5 style, custom views in `resources/views/pagination/`

## Seeders

- `DatabaseSeeder` checks `database/seeders/snapshots/latest/manifest.json` first, then `content/manifest.json`, falls back to individual seeders.
- Snapshot files are JSON exports with FK-safe ordering.

## Key Middleware (in global kernel)

- `SetLocale` — reads `?lang=` query or session `locale`, supports `en`/`fr`/`rw`
- `EnsureAdminUser` — checks `role in [admin, super_admin, superadmin, owner]` + `is_active`
- `LogActivity` — logs all requests to `user_activity_logs` (guarded by `Schema::hasTable`)

## Settings

- `Setting` model — single-row config table. `Setting::current()` or `Setting::currentOrDefault()`.
- Overrides `app.name`, mail config at boot. View-shared as `$siteSettings`.
- All defaults defined in `Setting::defaults()`.

## Mail

- Gmail SMTP configured in `.env`. Can be overridden via DB settings (mail mailer, host, port, etc.).
- Mail settings from DB override `.env` at boot (`AppServiceProvider`).

## Translation Pipeline

- `TranslatorInterface` bound to `LibreTranslateTranslator` or `NullTranslator` based on config.
- Scheduler runs `SendEmailCampaignJob` every minute for scheduled campaigns.
- GeoIP lookup via `ipapi.co` (cached 1 day).
