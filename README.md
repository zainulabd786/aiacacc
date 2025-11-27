# Retrospective: How I would write this in 2025

⚠️ **Note to Reviewers:** This portal was assembled in 2017-era PHP (5.x) with tightly coupled scripts and inline SQL. While it still runs, it no longer reflects how I build production software today.

If I were rebuilding the same product in PHP 8.3, I would:

- **Replace manual SQLite queries with a first-class ORM** (e.g., Doctrine or Laravel Eloquent) layered behind repositories to eliminate SQL injection risk and gain schema migrations.
- **Adopt Composer with PSR-4 autoloading** so every class is namespaced, discoverable, and tested without relying on `include` chains.
- **Enable `declare(strict_types=1);`** and add scalar/union/DTO return types across controllers, services, and DTOs to let static analysers (Psalm/PHPStan) protect contracts.
- **Split presentation from behavior** by moving business logic into Services and HTTP/CLI entry points, then render views via Twig/Blade rather than echoing HTML in PHP files.
- **Secure authentication and secrets** using hashed credentials (Argon2id), `.env` config, and first-party CSRF/session hardening instead of storing plaintext settings.
- **Containerize and test**: run the app in Docker, add PHPUnit + Pest coverage, and wire GitHub Actions for lint + feature tests before deployment.
- **Modernize the UI stack** by replacing jQuery-era widgets with a component framework (Alpine.js/Livewire or Vue) and bundling assets via Vite.

---

## AIACACC Administration Console

A legacy PHP + SQLite web application used to manage members, donations, vouchers, SMS outreach, and day-to-day accounting tasks for the AIACACC organization.

### Feature Highlights

- Secure (session-gated) dashboard that surfaces live counts for members, balances, and expiring subscriptions.
- CRUD tooling for members, donations, vouchers, and cash/bank transactions.
- Bulk SMS composer with CSV import, Indian language IME support, and jQuery Confirm-based feedback.
- Search helpers for members, receipts, vouchers, and contacts, plus printable vouchers/receipts.
- Lightweight SQLite data store bundled with two seed databases under `data/` for quick demos.

### Tech Stack at a Glance

| Layer            | Details |
| ---------------- | ------- |
| Language         | PHP 5.x procedural scripts (no framework) |
| Storage          | SQLite (`data/AIACACC.sqlite3`, `AIACACCdb.sqlite3`) |
| Front-end        | Bootstrap 3, jQuery 3.2.1, Font Awesome 4.7, jQuery Confirm, PapaParse |
| SMS/Input tools  | Pramukh Indic IME, PapaParse CSV ingestion |
| Assets           | Static HTML/CSS/JS served from the repo; no build pipeline |

### Repository Layout

```
.
├── index.php              # Login form and session bootstrap
├── dashboard.php          # KPI dashboard, SMS composer, quick tables
├── donation.php           # Donations + member management workflows
├── voucher.php            # Voucher creation, printing, and search
├── transactions.php       # Cash/Bank transaction tabs
├── ajax-req-handler.php   # Centralized AJAX endpoint for dashboard interactions
├── header.php / footer.php# Page chrome, DB bootstrap, asset loading
├── data/                  # SQLite database files
└── assets (bootstrap, jquery, fonts, plugins)
```

### Getting Started

1. **Install prerequisites**
   - PHP 5.6+ with the SQLite3 extension enabled (CLI or Apache/Nginx + PHP-FPM).
   - Optional: Apache/Nginx virtual host pointing to the project root.

2. **Clone and configure**
   - Place the repository inside your web root or run PHP's built-in server.
   - Ensure the `data/` directory is writable if you plan to mutate the database.

3. **Launch the development server**

```bash
php -S 127.0.0.1:8000 -t /path/to/aiacacc-master
```

4. **Access the app**
   - Open `http://127.0.0.1:8000/index.php`.
   - Use the admin password stored in the `settings` table (`id=2`) within `data/AIACACC.sqlite3`.

### Database Notes

- The main data set lives in `data/AIACACC.sqlite3`. `AIACACCdb.sqlite3` appears to be a backup or alternate seed.
- `header.php` opens the SQLite file directly; no DSN changes are required if the file remains in place.
- You can browse or edit the schema with any SQLite GUI (e.g., DB Browser for SQLite) if you prefer not to manipulate data via the UI.

### Development Tips & Known Limitations

- **Single entry point AJAX**: `ajax-req-handler.php` multiplexes many actions based on a `key` parameter. When debugging, search for the key string to locate the handler block.
- **Hard-coded includes**: Assets and PHP files are pulled via relative paths; keep the directory structure intact.
- **Plaintext credentials**: The admin password resides in the SQLite `settings` table without hashing—treat the repository as sensitive.
- **No dependency manager**: All third-party libraries are vendored; updating them requires manual replacement.
- **No tests/build scripts**: Validation is manual; run-time errors show up in the browser or PHP logs.
