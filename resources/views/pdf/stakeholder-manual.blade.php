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
    @if(file_exists(public_path('images/brand/ferro-hex-logo.png')))
        <img src="{{ public_path('images/brand/ferro-hex-logo.png') }}" alt="FERRO" style="height:48px;width:auto;margin-bottom:16px;">
    @endif
    <div class="cover-tag">Operations guide</div>
    <div class="cover-title">FERRO</div>
    <div class="cover-sub">Stakeholder manual — storefront, admin portal, and customer communications</div>
    <p class="muted">Generated {{ $generatedAt }} · Store URL: {{ $appUrl }}</p>
</div>

<p><strong>Who this document is for:</strong> brand owners, operations managers, and customer-support leads who need a single reference for what the platform does and how day-to-day tasks are performed in the admin area and on the public website.</p>

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
    <li>Storefront content &amp; contact settings</li>
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
    <tr><td>Checkout</td><td><code>/checkout</code></td><td>Three-step checkout (see section 3).</td></tr>
    <tr><td>About</td><td><code>/about</code></td><td>Brand narrative.</td></tr>
    <tr><td>Quiz</td><td><code>/quiz</code></td><td>Guided skin quiz; captures leads and notifies admin.</td></tr>
    <tr><td>Contact</td><td><code>/contact</code></td><td>Contact page (details from admin contact settings).</td></tr>
    <tr><td>Legal &amp; CMS</td><td><code>/privacy-policy</code>, <code>/terms-of-service</code>, <code>/return-policy</code>, <code>/pages/{slug}</code></td><td>Editable legal pages and additional CMS pages.</td></tr>
</table>

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

<div class="page-break"></div>
<h2>5. Admin portal — access &amp; dashboard</h2>
<h3>Access</h3>
<p>Administrators sign in with the same <code>/login</code> page as customers, then use the <strong>Admin</strong> area (URL prefix <code>/admin</code>). Only users with the administrator flag may access these routes.</p>
<h3>Admin language</h3>
<p>Admin UI can be switched between English and Arabic via <code>/admin/lang/{locale}</code> without affecting the storefront locale.</p>
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
    <li>Manage <strong>featured image</strong> and <strong>gallery</strong> images (upload and remove individual gallery entries).</li>
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
<p>Open an order to see line items, addresses, payment state, notes, and timestamps.</p>
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
<p>Review captured emails, sources, priorities, and waitlist flags. Export CSV for campaigns.</p>
<h3>Skin quiz responses</h3>
<p>Open each quiz session to see answers and recommended routines — useful for concierge sales or dermatologist partnerships.</p>

<div class="page-break"></div>
<h2>9. Storefront content &amp; contact settings</h2>
<h3>Storefront contact</h3>
<p>Edit support email, phone, social links, and related fields used in the footer, contact page, and transactional email footers.</p>
<h3>Storefront pages (CMS)</h3>
<p>Create and edit additional content pages (slug-based URLs under <code>/pages/{slug}</code>) plus fixed legal routes for privacy, terms, and returns — all manageable without code deploys.</p>

<div class="page-break"></div>
<h2>10. Automated emails (what customers and staff receive)</h2>
<table class="steps">
    <tr><th style="width:28%">Trigger</th><th>Audience</th><th>Content (high level)</th></tr>
    <tr><td>Order placed</td><td>Customer</td><td>Order confirmation; <strong>PDF invoice attached</strong> when generation succeeds.</td></tr>
    <tr><td>Order placed</td><td>Operations</td><td>New-order alert to <code>FERRO_ADMIN_EMAIL</code>.</td></tr>
    <tr><td>Order marked shipped</td><td>Customer</td><td>Shipping update (localised where templates support it).</td></tr>
    <tr><td>Waitlist / lead capture</td><td>Lead</td><td>Welcome or nurture template for non-quiz sources.</td></tr>
    <tr><td>High-priority lead</td><td>Operations</td><td>VIP / high-priority lead alert.</td></tr>
    <tr><td>Quiz submitted</td><td>Operations</td><td>Quiz submission alert.</td></tr>
    <tr><td>Product released (coming soon → active)</td><td>Waitlist leads for that SKU</td><td>Waitlist release message.</td></tr>
</table>
<p>Email branding uses your global <strong>from</strong> name and address (<code>MAIL_FROM_*</code>). Ensure DNS and provider authentication (SPF/DKIM) are configured in production to maximise deliverability.</p>

<div class="page-break"></div>
<h2>11. Technical notes for IT / hosting</h2>
<ul>
    <li><strong>Application URL</strong> — <code>APP_URL</code> must match the public site URL so links inside emails and PDFs resolve correctly.</li>
    <li><strong>Mail transport</strong> — configure <code>MAIL_MAILER</code> and related variables (e.g. SMTP or Mailpit in development).</li>
    <li><strong>Admin alerts</strong> — set <code>FERRO_ADMIN_EMAIL</code> to a monitored shared inbox.</li>
    <li><strong>Queued mail (optional)</strong> — if <code>FERRO_MAIL_QUEUE=true</code>, run a Laravel queue worker or messages will remain pending.</li>
    <li><strong>Updating this PDF</strong> — from the project root run <code>php artisan ferro:export-stakeholder-manual</code> to write <code>storage/app/FERRO_Stakeholder_Manual.pdf</code>, or while signed in as an admin open <code>{{ $appUrl }}/admin/documentation/stakeholder-manual.pdf</code> for an on-demand download.</li>
</ul>

<div class="footer-note">
    FERRO stakeholder manual — for internal and partner use. Features reflect the codebase at generation time; after major upgrades, regenerate this PDF from the admin link or Artisan command.
</div>

</body>
</html>
