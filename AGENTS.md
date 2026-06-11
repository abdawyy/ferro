# FERRO — AI project reference

This document is the canonical technical overview for AI assistants working on the FERRO codebase. Pair it with the human-facing **Stakeholder manual** (PDF from admin or `php artisan ferro:export-stakeholder-manual`).

---

## What this project is

**FERRO** is a bilingual (English + Arabic RTL) skincare e-commerce storefront with a full **admin portal** at `/admin`. It targets **shared hosting (Hostinger)** with Apache/LiteSpeed, document root often pointing at the repo root via `.htaccess` → `public/`.

| Layer | Technology |
|-------|------------|
| Backend | Laravel 13, PHP 8.3+ |
| Frontend | Blade, Alpine.js, Tailwind CSS 4 |
| PDF | mPDF (`mpdf/mpdf`) |
| i18n | Laravel locales (`en`, `ar`), `lang/en/*`, `lang/ar/*`, admin lang switch |
| DB | MySQL (migrations in `database/migrations/`) |

---

## Repository layout (high signal)

```
app/
  Http/Controllers/          # Storefront + Admin controllers
  Http/Controllers/Admin/    # All /admin/* handlers
  Models/                    # Eloquent models
  Services/                  # Business logic (Newsletter, StorefrontMedia, StorefrontSeo, …)
  Support/                   # Storage helpers (ProductImageStorage, StorefrontMediaStorage, FerroMail)
  Mail/                      # Mailable classes (User/*, Admin/*)
  helpers.php                # Global helpers (autoloaded)
config/
  ferro.php                  # FERRO_ADMIN_EMAIL, mail queue, low stock threshold
  ferro_storefront_seo.php   # Default SEO copy per page key
resources/views/
  admin/                     # Admin Blade UI
  layouts/app.blade.php      # Storefront layout (includes newsletter popup)
  partials/                  # nav, footer, newsletter-popup, brand-mark-f
  pdf/stakeholder-manual.blade.php
public/
  uploads/products/          # Product featured + gallery images (direct web serve)
  uploads/brand/             # Storefront media uploads from admin
routes/web.php               # All HTTP routes
lang/en/, lang/ar/           # Storefront + admin translations
```

---

## Conventions AI must follow

1. **Minimal diffs** — match existing naming, Blade patterns, and service extraction style.
2. **Bilingual fields** — user-facing DB text uses `_en` / `_ar` suffixes; resolve with model helpers (`title()`, `message()`, etc.) or locale-aware pick logic.
3. **Uploads on Hostinger** — prefer `public/uploads/...` via `ProductImageStorage` and `StorefrontMediaStorage`, not `storage/app/public` symlinks, unless the task explicitly requires otherwise.
4. **Admin access** — middleware `auth` + `admin`; flag on `users.is_admin`.
5. **Mail** — use `App\Support\FerroMail` (respects queue config and locale).
6. **Asset URLs** — use `ferro_public_url()`, `ferro_request_asset_root()`, or `asset()` consistently with existing call sites.
7. **Do not commit** `.env`, uploaded product PNGs, or generated PDFs in `storage/app/`.

---

## Storefront routes (customer)

| Path | Purpose |
|------|---------|
| `/` | Home |
| `/shop`, `/shop/{slug}` | Catalog + product detail |
| `/cart`, `/checkout` | Cart + 3-step checkout |
| `/order/thanks/{order}` | Signed thank-you page |
| `/orders/track/{order}` | Guest order tracking |
| `/account`, `/orders/{orderNumber}`, `/invoices/{orderNumber}` | Auth customer area |
| `/newsletter/subscribe` (POST), `/newsletter/unsubscribe` (GET) | Newsletter |
| `/waitlist`, `/quiz/capture`, `/cart/abandon` | Lead capture |
| `/about`, `/quiz`, `/contact` | Content |
| `/privacy-policy`, `/terms-of-service`, `/return-policy`, `/pages/{slug}` | CMS |
| `/lang/{locale}` | Storefront locale switch |
| `/api/cart/*`, `/api/shop/catalog` | JSON helpers |

Layout: `resources/views/layouts/app.blade.php` — injects `@include('partials.newsletter-popup')` when settings enabled.

---

## Admin routes (staff)

Prefix: `/admin` · Name prefix: `admin.*` · Sidebar: `resources/views/admin/partials/sidebar.blade.php`

| Admin area | Route name prefix | Notes |
|------------|-------------------|-------|
| Dashboard | `admin.dashboard` | KPIs, recent orders/leads |
| Products | `admin.products.*` | CRUD, soft delete + restore, image upload/delete |
| Categories | `admin.product-categories.*` | |
| Shop filters | `admin.shop-quick-filters.*` | |
| Shipping (EG) | `admin.shipping-cities.*` | Active cities only at checkout |
| Storefront contact | `admin.contact-settings.*` | |
| Storefront pages | `admin.pages.*` | CMS slugs |
| Storefront SEO | `admin.storefront-seo.*` | Per-page meta EN/AR |
| Storefront images | `admin.storefront-media.*` | Slot-based media + brand toggles |
| Orders | `admin.orders.*` | Status, tracking, returns, invoice PDF |
| Users / Admins | `admin.users.*`, `admin.admins.*` | Block, promote admin |
| Leads & waitlist | `admin.leads.*` | CSV export |
| Newsletter | `admin.newsletter.*` | Settings hub → subscribers, campaigns |
| Skin quiz | `admin.quiz-responses.*` | |
| Stakeholder manual | `admin.stakeholder-manual` | PDF download |

Admin locale: `/admin/lang/{locale}` — does not change storefront locale.

---

## Database models & tables

| Model | Table | Role |
|-------|-------|------|
| `User` | `users` | Customers + admins (`is_admin`, `is_blocked`) |
| `Product` | `products` | Catalog, soft deletes, bilingual fields |
| `ProductCategory` | `product_categories` | |
| `ShopQuickFilter` | `shop_quick_filters` | |
| `ShippingCity` | `shipping_cities` | Egypt shipping list |
| `Order`, `OrderItem` | `orders`, `order_items` | |
| `OrderReturnRequest` | `order_return_requests` | Customer return flow |
| `Lead`, `WaitlistEntry` | `leads`, `waitlist_entries` | Marketing leads |
| `QuizSession` | `quiz_sessions` | Skin quiz submissions |
| `ContactSetting` | `contact_settings` | Singleton storefront contact |
| `Page` | `pages` | CMS pages |
| `StorefrontSeoPage` | `storefront_seo_pages` | Overrides per `page_key` |
| `StorefrontMedia` | `storefront_media` | key → relative path under `public/uploads/brand/` |
| `StorefrontBrandSetting` | `storefront_brand_settings` | `show_logo`, `show_favicon` for **custom** uploads |
| `NewsletterSetting` | `newsletter_settings` | Popup + coupon config (singleton) |
| `NewsletterSubscriber` | `newsletter_subscribers` | Active/unsubscribed, coupon fields |
| `NewsletterCampaign` | `newsletter_campaigns` | Draft/sent campaigns |
| — | `newsletter_campaign_subscriber` | Pivot: send log per recipient |

Migrations of note (2026-06-11): `create_newsletter_tables`, `create_storefront_media_table`, `create_storefront_brand_settings_table`.

---

## Global helpers (`app/helpers.php`)

| Function | Use |
|----------|-----|
| `ferro_request_asset_root()` | Base URL for assets when `APP_URL`/host quirks matter |
| `ferro_public_url($path)` | Public file URL from relative path |
| `ferro_money($amount)` | Formatted currency |
| `ferro_storefront_media($key)` | Resolved URL for media slot (custom or fallback) |
| `ferro_storefront_logo_enabled()` / `ferro_storefront_logo_url()` | Custom logo visibility |
| `ferro_storefront_favicon_enabled()` / `ferro_storefront_favicon_url()` | Custom favicon |
| `ferro_storefront_seo($pageKey, $replacements)` | Meta title/description for a page |

Media keys are defined in `StorefrontMediaService::definitions()` — groups: `brand`, `heroes`, `backdrops`, `quiz`.

---

## Newsletter system

**Flow**

1. Admin enables popup in `NewsletterSetting` (`is_enabled`, `delay_seconds`, bilingual copy, discount %, coupon prefix/days).
2. Storefront popup (`partials/newsletter-popup.blade.php`) — Alpine component, `x-teleport="body"`, `z-index: 99999`.
3. Hidden when `localStorage` has `ferro_newsletter_dismissed` or `ferro_newsletter_subscribed`.
4. `POST /newsletter/subscribe` → `NewsletterService::subscribe()` → creates/updates subscriber, syncs `Lead`, sends `NewsletterWelcomeCoupon` mailable.
5. Admin **Campaigns** — bilingual subject/body, optional featured product, send to all active or selected subscribers → `NewsletterCampaignMail`.
6. Unsubscribe: `GET /newsletter/unsubscribe?email=…`.

**Key files**

- `app/Services/NewsletterService.php`
- `app/Http/Controllers/NewsletterController.php`
- `app/Http/Controllers/Admin/Newsletter*Controller.php`
- `app/Support/NewsletterCouponGenerator.php`

**Admin navigation**

- Hub: `/admin/newsletter/settings` (`admin.newsletter.settings.edit`)
- Sub-pages link back via “← Back to Newsletter” (`admin.newsletter.back_to_newsletter` in lang files).

---

## Storefront media & brand

- Admin uploads per **slot key** (logo, favicon, heroes, page backdrops, quiz images).
- Files stored under `public/uploads/brand/` via `StorefrontMediaStorage`.
- **Default brand mark**: orange SVG “F” + “FERRO” text in nav when custom logo disabled or not uploaded.
- `StorefrontBrandSetting`: toggles only apply when a custom file exists for logo/favicon.
- PDF stakeholder manual cover uses `StorefrontMediaService::visibleLogoAbsolutePath()` when custom logo shown.

**Upload bug pattern to avoid**: use `$request->file('media')[$key]`, not `$request->file("media.$key")` (Laravel dot notation pitfall).

---

## Product images

- `ProductImageStorage` → `public/uploads/products/` (featured) and `public/uploads/products/gallery/`.
- Admin: `POST admin/products/{product}/images`, `DELETE …/images/{index}`.
- Helper/resolver logic in `app/helpers.php` and product model accessors — check `ProductController` before changing paths.

---

## Email triggers

| Event | Mailable / area |
|-------|-----------------|
| Order placed | Customer confirmation + invoice PDF; admin alert to `FERRO_ADMIN_EMAIL` |
| Order shipped | Shipping update |
| Waitlist / lead | Welcome nurture |
| High-priority lead | Admin alert |
| Quiz submitted | Admin alert |
| Product coming soon → active | Waitlist release |
| Newsletter subscribe | `NewsletterWelcomeCoupon` |
| Newsletter campaign send | `NewsletterCampaignMail` |

Config: `config/ferro.php` — `admin_email`, `mail.queue` (`FERRO_MAIL_QUEUE`).

---

## Environment variables (common)

```env
APP_URL=                    # Must match public URL (emails, PDFs, signed URLs)
FERRO_ADMIN_EMAIL=          # Operations inbox
FERRO_MAIL_QUEUE=false      # true → requires queue worker
FERRO_LOW_STOCK_THRESHOLD=10
MAIL_*                      # SMTP / provider
```

---

## Deployment (Hostinger checklist)

1. Upload code; ensure root `.htaccess` forwards to `public/`.
2. `composer install --no-dev`, `php artisan migrate --force`.
3. `php artisan config:clear && php artisan cache:clear && php artisan view:clear`.
4. Writable: `storage/`, `bootstrap/cache/`, `public/uploads/`.
5. Build frontend if CSS changed (`npm run build`) — some admin fixes use inline styles when Tailwind bundle is stale.
6. Do **not** rely on `php artisan storage:link` for product/storefront uploads (direct `public/uploads`).

---

## Artisan commands

| Command | Purpose |
|---------|---------|
| `php artisan ferro:export-stakeholder-manual` | Write PDF to `storage/app/FERRO_Stakeholder_Manual.pdf` |
| Standard Laravel | `migrate`, `config:clear`, `view:clear`, `route:list` |

---

## Testing & quality

- PHPUnit in `tests/`; run `php artisan test` or `./vendor/bin/phpunit`.
- Laravel Pint for PHP style: `./vendor/bin/pint`.

---

## When modifying docs

- **Human stakeholders** → update `resources/views/pdf/stakeholder-manual.blade.php`, then regenerate PDF.
- **AI / developers** → update this file (`AGENTS.md`) in the same PR when architecture, routes, or storage strategy changes.

---

## Quick “where do I change X?”

| Goal | Start here |
|------|------------|
| Popup text / discount | Admin newsletter settings + `NewsletterSetting` model |
| Send marketing blast | `NewsletterCampaignController` + admin campaigns views |
| Nav logo | `partials/nav.blade.php`, `StorefrontMediaService`, brand settings |
| Page meta tags | `StorefrontSeoService`, admin storefront SEO |
| Checkout shipping cities | `ShippingCity` model + admin shipping CRUD |
| Order status emails | `Admin\OrderController::updateStatus`, mail classes in `app/Mail/` |
| New admin sidebar link | `admin/partials/sidebar.blade.php` + `lang/*/admin.php` nav keys |

---

*Last aligned with codebase: June 2026 — newsletter, storefront media, brand settings, product public uploads.*
