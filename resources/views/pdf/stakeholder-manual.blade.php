<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FERRO — Stakeholder &amp; Operations Manual</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #1a1a1a; line-height: 1.45; margin: 0; padding: 28px 36px; }
        h1 { font-size: 22px; color: #0a0a0a; margin: 0 0 8px 0; letter-spacing: 0.06em; }
        h2 { font-size: 14px; color: #E8500A; margin: 18px 0 8px 0; border-bottom: 1px solid #e8e8e8; padding-bottom: 4px; page-break-after: avoid; }
        h3 { font-size: 11px; color: #333; margin: 12px 0 6px 0; page-break-after: avoid; }
        p { margin: 0 0 8px 0; }
        ul, ol { margin: 0 0 10px 18px; padding: 0; }
        li { margin-bottom: 4px; }
        .muted { color: #555; font-size: 9.5px; }
        .cover { text-align: center; padding: 48px 24px 36px; }
        .cover-tag { font-size: 11px; color: #E8500A; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 12px; }
        .cover-title { font-size: 26px; font-weight: bold; color: #0a0a0a; margin-bottom: 8px; }
        .cover-sub { font-size: 12px; color: #444; margin-bottom: 24px; }
        .box { background: #f8f8f8; border-left: 3px solid #E8500A; padding: 10px 12px; margin: 10px 0; font-size: 9.5px; }
        .page-break { page-break-before: always; }
        table.steps { width: 100%; border-collapse: collapse; margin: 8px 0; font-size: 9.5px; }
        table.steps th, table.steps td { border: 1px solid #ddd; padding: 6px 8px; vertical-align: top; }
        table.steps th { background: #0a0a0a; color: #fff; text-align: left; font-size: 9px; }
        code { font-family: DejaVu Sans Mono, monospace; font-size: 9px; background: #eee; padding: 1px 4px; }
        .footer-note { margin-top: 24px; font-size: 8.5px; color: #777; border-top: 1px solid #ddd; padding-top: 8px; }
    </style>
</head>
<body>

<div class="cover">
    @php $brandLogoPath = app(\App\Services\StorefrontMediaService::class)->visibleLogoAbsolutePath(); @endphp
    @if($brandLogoPath)
        <img src="{{ $brandLogoPath }}" alt="FERRO" style="height:48px;width:auto;margin-bottom:16px;">
    @endif
    <div class="cover-tag">Operations guide</div>
    <div class="cover-title">FERRO</div>
    <div class="cover-sub">Stakeholder manual — storefront, admin portal, and customer communications</div>
    <p class="muted">Generated {{ $generatedAt }} · Store URL: {{ $appUrl }}</p>
</div>

<p><strong>Who this document is for:</strong> brand owners, operations managers, and customer-support leads who need a single reference for what the platform does and how day-to-day tasks are performed in the admin area and on the public website.</p>

<p class="muted"><strong>For developers &amp; AI assistants:</strong> a technical companion file <code>AGENTS.md</code> in the project root describes architecture, routes, models, and deployment conventions in detail.</p>

<h2>Contents</h2>
<ol>
    <li>Public website overview</li>
    <li>Customer shopping &amp; checkout</li>
    <li>Customer accounts &amp; self-service</li>
    <li>Leads, waitlist &amp; skin quiz</li>
    <li>Admin portal — access &amp; dashboard</li>
    <li>Products, categories &amp; shop configuration</li>
    <li>Orders &amp; fulfilment</li>
    <li>Customers, leads &amp; quiz insights</li>
    <li>Newsletter popup &amp; email campaigns</li>
    <li>Storefront content, SEO &amp; images</li>
    <li>Automated emails (what customers and staff receive)</li>
    <li>Technical notes for IT / hosting</li>
</ol>

<div class="page-break"></div>
<h2>1. Public website overview</h2>
<p>The storefront is bilingual: <strong>English</strong> and <strong>Arabic</strong> (RTL). Visitors switch language via the site language control (routes under <code>/lang/{locale}</code>).</p>
<h3>Main areas (customer-facing)</h3>
<table class="steps">
    <tr><th style="width:22%">Area</th><th>URL pattern (relative to your domain)</th><th>Purpose</th></tr>
    <tr><td>Home</td><td><code>/</code></td><td>Brand story, featured products, waitlist call-to-action.</td></tr>
    <tr><td>Shop</td><td><code>/shop</code></td><td>Product listing with filters driven by admin “shop quick filters”.</td></tr>
    <tr><td>Product page</td><td><code>/shop/{slug}</code></td><td>Product detail, pricing, add to cart.</td></tr>
    <tr><td>Cart</td><td><code>/cart</code></td><td>Review cart; proceed to checkout.</td></tr>
    <tr><td>Checkout</td><td><code>/checkout</code></td><td>Three-step checkout (see section 2).</td></tr>
    <tr><td>About</td><td><code>/about</code></td><td>Brand narrative.</td></tr>
    <tr><td>Quiz</td><td><code>/quiz</code></td><td>Guided skin quiz; captures leads and notifies admin.</td></tr>
    <tr><td>Contact</td><td><code>/contact</code></td><td>Contact page (details from admin contact settings).</td></tr>
    <tr><td>Legal &amp; CMS</td><td><code>/privacy-policy</code>, <code>/terms-of-service</code>, <code>/return-policy</code>, <code>/pages/{slug}</code></td><td>Editable legal pages and additional CMS pages.</td></tr>
    <tr><td>Order tracking</td><td><code>/orders/track/{order}</code></td><td>Guest-friendly order status lookup (signed link).</td></tr>
</table>
<h3>Newsletter popup (storefront)</h3>
<p>When enabled in admin (<em>Newsletter &amp; Popup</em>), a timed modal appears on storefront pages after a configurable delay (default 5 seconds). Visitors who subscribe receive a <strong>unique coupon code by email</strong>. The popup respects dismiss/subscribe state in the browser so repeat visitors are not nagged. Disable the popup entirely from admin without code changes.</p>

<div class="page-break"></div>
<h2>2. Customer shopping &amp; checkout</h2>
<h3>Cart</h3>
<p>Guests and logged-in users add products via the “Add to cart” actions on product cards and product pages. The cart is held in the browser session and synced through lightweight API endpoints (cart count in the header).</p>
<h3>Checkout funnel (three steps)</h3>
<ol>
    <li><strong>Information</strong> — billing / contact details (and login prompt if applicable).</li>
    <li><strong>Shipping</strong> — delivery address; <strong>shipping city</strong> options come from the admin-maintained <em>Shipping (EG)</em> list (only active cities are offered).</li>
    <li><strong>Payment</strong> — payment method selection as configured at checkout (e.g. card, cash on delivery, regional methods where enabled).</li>
</ol>
<p>Submitting the order creates a record, triggers transactional email (order confirmation to the customer and an alert to the operations inbox), and generates a <strong>PDF invoice</strong> when totals validate successfully.</p>
<h3>After purchase</h3>
<ul>
    <li>Customer sees a <strong>thank-you</strong> page (accessed via a signed URL so the link is not guessable).</li>
    <li>Registered customers can revisit orders under <strong>Account</strong> and download invoices (section 3).</li>
</ul>

<div class="page-break"></div>
<h2>3. Customer accounts &amp; self-service</h2>
<h3>Registration &amp; login</h3>
<ul>
    <li><code>/register</code> — create a customer account.</li>
    <li><code>/login</code> — sign in.</li>
    <li><code>/forgot-password</code> / <code>/reset-password/{token}</code> — password recovery (rate-limited for security).</li>
</ul>
<h3>Account area (<code>/account</code>, requires login)</h3>
<ul>
    <li>List of the customer’s orders with status.</li>
    <li>Order detail at <code>/orders/{orderNumber}</code> for tracking information you expose on the storefront template.</li>
    <li><strong>Invoice download</strong> at <code>/invoices/{orderNumber}</code> — regenerates the latest PDF template and downloads <code>FERRO_Invoice_…</code>.</li>
    <li><strong>Cancel order</strong> and <strong>return request</strong> actions where order status and policy allow (admin reviews returns in the order screen).</li>
</ul>
<div class="box"><strong>Note:</strong> If a user account is marked as an administrator, visiting <code>/account</code> redirects them to the admin dashboard instead.</div>

<div class="page-break"></div>
<h2>4. Leads, waitlist &amp; skin quiz</h2>
<h3>Waitlist</h3>
<p>The homepage and other surfaces invite visitors to join a waitlist. Submissions create <strong>leads</strong> in the admin <em>Leads &amp; Waitlist</em> area. You can export leads and waitlist segments to CSV for marketing or CRM import.</p>
<h3>Product-specific waitlist</h3>
<p>When a product is in <strong>coming soon</strong> status, customers can signal interest for that SKU. When an administrator changes the product to <strong>active</strong>, the system can notify matching waitlist leads by email (release campaign).</p>
<h3>Skin quiz</h3>
<p>The quiz captures preferences and contact details. Each submission notifies the admin inbox and stores structured data under <em>Skin Quiz</em> in admin for review and follow-up.</p>
<h3>Newsletter subscribers vs. general leads</h3>
<p>Popup newsletter sign-ups are stored separately under <em>Newsletter &amp; Popup</em> (with coupon codes) and also sync to the leads table with source “newsletter” for unified CRM visibility.</p>

<div class="page-break"></div>
<h2>5. Admin portal — access &amp; dashboard</h2>
<h3>Access</h3>
<p>Administrators sign in with the same <code>/login</code> page as customers, then use the <strong>Admin</strong> area (URL prefix <code>/admin</code>). Only users with the administrator flag may access these routes.</p>
<h3>Admin language</h3>
<p>Admin UI can be switched between English and Arabic via <code>/admin/lang/{locale}</code> without affecting the storefront locale.</p>
<h3>Sidebar sections</h3>
<ul>
    <li><strong>Core</strong> — Dashboard, Products, Categories, Shop filters, Shipping (EG), Storefront contact, Storefront pages, Storefront SEO, Storefront images, Orders.</li>
    <li><strong>Customers</strong> — Users, Administrators, Leads &amp; Waitlist, Newsletter &amp; Popup, Skin Quiz.</li>
    <li><strong>Help</strong> — Stakeholder manual (PDF download).</li>
</ul>
<h3>Dashboard</h3>
<p>The home admin screen summarises:</p>
<ul>
    <li><strong>Revenue</strong> from paid / fulfilled-style order statuses.</li>
    <li><strong>Order counts</strong> and pending payment alerts.</li>
    <li><strong>Customers</strong> and blocked-user counts.</li>
    <li><strong>Leads / waitlist</strong> totals.</li>
    <li><strong>Low-stock</strong> warnings linking to the product catalogue.</li>
    <li>Recent orders and recent leads for quick navigation.</li>
</ul>

<div class="page-break"></div>
<h2>6. Products, categories &amp; shop configuration</h2>
<h3>Products</h3>
<ul>
    <li>Create, edit, archive, and <strong>restore</strong> soft-deleted products.</li>
    <li>Manage <strong>featured image</strong> and <strong>gallery</strong> images (upload and remove individual gallery entries). Images are stored under <code>public/uploads/products/</code> for reliable delivery on shared hosting.</li>
    <li>Set <strong>status</strong> (e.g. <em>coming soon</em>, <em>active</em>, <em>out of stock</em>, <em>archived</em>) — controls visibility on the shop and whether purchases are allowed.</li>
    <li>Stock, SKU, bilingual names/descriptions, pricing, and subscription flags as implemented on the edit form.</li>
</ul>
<h3>Categories</h3>
<p>Organise the catalogue into product categories shown on the storefront.</p>
<h3>Shop quick filters</h3>
<p>Define filter chips or shortcuts on the shop listing (admin-managed labels and rules).</p>
<h3>Shipping (EG)</h3>
<p>Maintain the list of Egyptian shipping cities (or your configured region): names, sort order, and active flag. <strong>Only active cities</strong> appear at checkout.</p>

<div class="page-break"></div>
<h2>7. Orders &amp; fulfilment</h2>
<h3>Order list</h3>
<p>Under <em>Orders</em>, filter by status or search by order number, customer email, or name. Status counts help prioritise work queues.</p>
<h3>Order detail</h3>
<p>Open an order to see line items, addresses, payment state, notes, timestamps, and customer return requests.</p>
<h3>Updating status &amp; shipping</h3>
<p>Use the status form to move orders through your operational workflow, for example:</p>
<table class="steps">
    <tr><th>Typical stage</th><th>Meaning for stakeholders</th></tr>
    <tr><td>Pending payment</td><td>Awaiting confirmation of payment (e.g. bank transfer or gateway callback).</td></tr>
    <tr><td>Confirmed / Processing</td><td>Paid and being prepared for dispatch.</td></tr>
    <tr><td>Shipped</td><td>Handed to carrier — customer receives a <strong>shipping update email</strong> when this status is set (ensure tracking fields are filled when you use them on templates).</td></tr>
    <tr><td>Delivered</td><td>Confirmed receipt (timestamp recorded when first set).</td></tr>
    <tr><td>Cancelled / Refunded</td><td>Exception paths for support.</td></tr>
</table>
<p>You may record <strong>tracking number</strong>, <strong>carrier</strong>, and internal <strong>admin notes</strong> on the same form where applicable.</p>
<h3>Return requests</h3>
<p>When a customer submits a return from their account, review and approve or reject from the order detail screen.</p>
<h3>Invoice from admin</h3>
<p>Download the customer invoice PDF from the order screen for finance or customer service.</p>

<div class="page-break"></div>
<h2>8. Customers, leads &amp; quiz insights</h2>
<h3>Users</h3>
<ul>
    <li>Browse registered customers, open profiles, and <strong>block</strong> or <strong>unblock</strong> accounts where policy requires.</li>
    <li>Promote a trusted customer to <strong>administrator</strong> or revoke admin rights (guard carefully — admins have full catalogue and order power).</li>
</ul>
<h3>Administrators</h3>
<p>Create staff accounts and assign admin privileges from the dedicated administrators section.</p>
<h3>Leads &amp; waitlist</h3>
<p>Review captured emails, sources, priorities, and waitlist flags. Export CSV for campaigns. Manually add waitlist entries from admin when needed.</p>
<h3>Skin quiz responses</h3>
<p>Open each quiz session to see answers and recommended routines — useful for concierge sales or dermatologist partnerships.</p>

<div class="page-break"></div>
<h2>9. Newsletter popup &amp; email campaigns</h2>
<p>All newsletter tools live under <strong>Newsletter &amp; Popup</strong> in the admin sidebar. Sub-pages include a <strong>← Back to Newsletter</strong> link to return to the main settings hub.</p>
<h3>Popup settings (main hub)</h3>
<ul>
    <li><strong>Show popup on storefront</strong> — master on/off switch.</li>
    <li><strong>Delay (seconds)</strong> — how long after page load before the modal appears (default 5).</li>
    <li><strong>Bilingual copy</strong> — title, message, button label, and success message (EN + AR).</li>
    <li><strong>Discount</strong> — percentage off, coupon code prefix (e.g. FERRO), and validity in days.</li>
</ul>
<p>On subscribe, the visitor receives a <strong>welcome email with a unique coupon code</strong>. Subscribers are added to the newsletter list and synced to leads.</p>
<h3>Subscribers</h3>
<p>View active and unsubscribed emails, export CSV, and jump to campaign creation. Unsubscribe links in campaign emails use <code>/newsletter/unsubscribe</code>.</p>
<h3>Campaigns</h3>
<ol>
    <li>Click <strong>New campaign</strong> — enter bilingual subject and body (HTML supported in body fields).</li>
    <li>Optionally attach a <strong>featured product</strong> for rich email layout.</li>
    <li>Choose recipients: <strong>all active subscribers</strong> or a selected subset.</li>
    <li>Save as <strong>draft</strong>, preview on the campaign detail page, then <strong>Send now</strong> when ready.</li>
</ol>
<div class="box"><strong>Tip:</strong> Send a test campaign to a small internal list first. Once sent, a campaign cannot be unsent — create a new campaign for corrections.</div>

<div class="page-break"></div>
<h2>10. Storefront content, SEO &amp; images</h2>
<h3>Storefront contact</h3>
<p>Edit support email, phone, social links, and related fields used in the footer, contact page, and transactional email footers.</p>
<h3>Storefront pages (CMS)</h3>
<p>Create and edit additional content pages (slug-based URLs under <code>/pages/{slug}</code>) plus fixed legal routes for privacy, terms, and returns — all manageable without code deploys.</p>
<h3>Storefront SEO</h3>
<p>Per-page meta titles, descriptions, and keywords in English and Arabic for home, shop, product templates, cart, checkout, account, legal pages, and more. Empty fields fall back to sensible defaults configured in the application.</p>
<h3>Storefront images</h3>
<p>Upload or replace site visuals without a developer:</p>
<ul>
    <li><strong>Brand</strong> — custom logo, favicon, Apple touch icon, default social share (OG) image.</li>
    <li><strong>Heroes</strong> — large header images for home, shop, about, contact, quiz, and story sections.</li>
    <li><strong>Backdrops</strong> — page background textures per route (shop, cart, checkout, login, etc.).</li>
    <li><strong>Quiz</strong> — step headers and option images for the skin quiz flow.</li>
</ul>
<p>Use <strong>Remove custom</strong> on any slot to revert to the built-in default asset. For logo and favicon, separate toggles control whether your <em>custom upload</em> replaces the default orange F mark and SVG favicon — if no custom file is uploaded, the default brand mark always shows.</p>

<div class="page-break"></div>
<h2>11. Automated emails (what customers and staff receive)</h2>
<table class="steps">
    <tr><th style="width:28%">Trigger</th><th>Audience</th><th>Content (high level)</th></tr>
    <tr><td>Order placed</td><td>Customer</td><td>Order confirmation; <strong>PDF invoice attached</strong> when generation succeeds.</td></tr>
    <tr><td>Order placed</td><td>Operations</td><td>New-order alert to <code>FERRO_ADMIN_EMAIL</code>.</td></tr>
    <tr><td>Order marked shipped</td><td>Customer</td><td>Shipping update (localised where templates support it).</td></tr>
    <tr><td>Waitlist / lead capture</td><td>Lead</td><td>Welcome or nurture template for non-quiz sources.</td></tr>
    <tr><td>High-priority lead</td><td>Operations</td><td>VIP / high-priority lead alert.</td></tr>
    <tr><td>Quiz submitted</td><td>Operations</td><td>Quiz submission alert.</td></tr>
    <tr><td>Product released (coming soon → active)</td><td>Waitlist leads for that SKU</td><td>Waitlist release message.</td></tr>
    <tr><td>Newsletter popup subscribe</td><td>Subscriber</td><td>Welcome email with <strong>unique coupon code</strong> and discount details.</td></tr>
    <tr><td>Newsletter campaign sent</td><td>Active subscribers (all or selected)</td><td>Admin-authored bilingual campaign; optional featured product block.</td></tr>
</table>
<p>Email branding uses your global <strong>from</strong> name and address (<code>MAIL_FROM_*</code>). Ensure DNS and provider authentication (SPF/DKIM) are configured in production to maximise deliverability.</p>

<div class="page-break"></div>
<h2>12. Technical notes for IT / hosting</h2>
<ul>
    <li><strong>Application URL</strong> — <code>APP_URL</code> must match the public site URL so links inside emails, PDFs, and signed routes resolve correctly.</li>
    <li><strong>Mail transport</strong> — configure <code>MAIL_MAILER</code> and related variables (e.g. SMTP or Mailpit in development).</li>
    <li><strong>Admin alerts</strong> — set <code>FERRO_ADMIN_EMAIL</code> to a monitored shared inbox.</li>
    <li><strong>Queued mail (optional)</strong> — if <code>FERRO_MAIL_QUEUE=true</code>, run a Laravel queue worker or messages will remain pending.</li>
    <li><strong>Database migrations</strong> — after deploying new features run <code>php artisan migrate</code> (newsletter, storefront media, and SEO tables require this).</li>
    <li><strong>Uploaded files</strong> — product images live in <code>public/uploads/products/</code>; storefront media in <code>public/uploads/brand/</code>. Ensure these folders are writable on the server.</li>
    <li><strong>Document root</strong> — on Hostinger, the repo root <code>.htaccess</code> may forward requests to <code>public/</code>; do not break that redirect when uploading files.</li>
    <li><strong>Cache</strong> — after config or view changes on production: <code>php artisan config:clear && php artisan cache:clear && php artisan view:clear</code>.</li>
    <li><strong>AI / developer reference</strong> — see <code>AGENTS.md</code> in the project root for routes, models, services, and coding conventions.</li>
    <li><strong>Updating this PDF</strong> — from the project root run <code>php artisan ferro:export-stakeholder-manual</code> to write <code>storage/app/FERRO_Stakeholder_Manual.pdf</code>, or while signed in as an admin open <code>{{ $appUrl }}/admin/documentation/stakeholder-manual.pdf</code> for an on-demand download.</li>
</ul>

<div class="footer-note">
    FERRO stakeholder manual — for internal and partner use. Features reflect the codebase at generation time; after major upgrades, regenerate this PDF from the admin link or Artisan command.
</div>

</body>
</html>
