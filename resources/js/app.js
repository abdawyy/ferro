// ─────────────────────────────────────────────────────────────────────────────
// FERRO — Frontend Application Entry Point
// Stack: Alpine.js (reactive UI) + Axios (API calls) + custom FERRO utilities
// ─────────────────────────────────────────────────────────────────────────────

import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// ── FERRO Global Utilities ────────────────────────────────────────────────

/**
 * Toast notification system.
 * Usage: showToast('Message', 'success' | 'error' | 'info')
 */
window.showToast = function(message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container') || (() => {
        const el = document.createElement('div');
        el.id = 'toast-container';
        el.className = 'fixed bottom-6 end-6 z-[500] flex flex-col gap-3';
        document.body.appendChild(el);
        return el;
    })();

    const toast = document.createElement('div');
    const typeClass = type === 'success' ? 'border-green-500/50' :
                      type === 'error'   ? 'border-red-500/50'   :
                                           'border-ferro-orange/50';

    toast.className = `flex items-center gap-3 px-5 py-4 bg-[#1A1A1A] border ${typeClass}
                       text-white shadow-[0_25px_80px_rgba(0,0,0,0.6)] rounded-sm
                       animate-[fadeUp_0.4s_cubic-bezier(0.22,1,0.36,1)_both]`;
    toast.style.minWidth = '280px';
    toast.style.maxWidth = '400px';

    const icon = type === 'success' ? '✓' : type === 'error' ? '✕' : '◆';
    const iconColor = type === 'success' ? '#22C55E' : type === 'error' ? '#EF4444' : '#E8500A';

    toast.innerHTML = `
        <span style="color:${iconColor}; font-weight:bold; flex-shrink:0;">${icon}</span>
        <span style="font-size:0.875rem; line-height:1.5;">${message}</span>
        <button onclick="this.parentElement.remove()" style="margin-left:auto; color:#6B6B6B; font-size:1.2rem; line-height:1; padding:0 4px;" aria-label="Close">×</button>
    `;

    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        toast.style.transition = 'opacity 0.3s, transform 0.3s';
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

/**
 * Admin tables: copy column titles into data-label on each body cell so CSS can
 * render stacked “card” rows on small viewports (see admin layout @media rules).
 */
function initAdminTableMobileLabels() {
    document.querySelectorAll('table.admin-table').forEach((table) => {
        const ths = table.querySelectorAll('thead tr:first-of-type th');
        if (!ths.length) {
            return;
        }
        const headers = [...ths].map((th) =>
            th.textContent.replace(/\s+/g, ' ').trim()
        );

        table.querySelectorAll('tbody tr').forEach((tr) => {
            let col = 0;
            tr.querySelectorAll(':scope > td').forEach((td) => {
                const span = parseInt(td.getAttribute('colspan'), 10) || 1;
                if (span > 1) {
                    td.removeAttribute('data-label');
                    col += span;
                    return;
                }
                const label = headers[col] ?? '';
                col += 1;
                if (label === '') {
                    td.removeAttribute('data-label');
                } else {
                    td.setAttribute('data-label', label);
                }
            });
        });
    });
}

window.mergeFerroCartItem = function (item) {
    let cart = [];
    try {
        cart = JSON.parse(localStorage.getItem('ferro_cart') || '[]');
    } catch (_) {
        cart = [];
    }
    const idx = cart.findIndex((x) => Number(x.id) === Number(item.id));
    if (idx >= 0) {
        cart[idx].qty = Math.min(50, Number(cart[idx].qty || 0) + Number(item.qty || 1));
        if (item.currency) {
            cart[idx].currency = item.currency;
        }
    } else {
        cart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            currency: item.currency || 'EGP',
            qty: Math.min(50, Number(item.qty || 1)),
            image: item.image || '',
            url: item.url || '',
            category: item.category || '',
        });
    }
    localStorage.setItem('ferro_cart', JSON.stringify(cart));
    return cart.reduce((s, i) => s + Number(i.qty || 0), 0);
};

function ferroCartAddUrl() {
    const explicit = document.querySelector('meta[name="ferro-cart-add-url"]')?.getAttribute('content')?.trim();
    if (explicit) {
        return explicit;
    }
    return new URL('/api/cart/add', window.location.href).href;
}

function ferroXsrfTokenFromCookie() {
    const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);
    if (!match) {
        return '';
    }
    try {
        return decodeURIComponent(match[1]);
    } catch {
        return match[1];
    }
}

function ferroSyncCartBadges(totalQty) {
    document.querySelectorAll('[data-ferro-cart-badge]').forEach((badge) => {
        badge.textContent = String(totalQty);
        if (totalQty > 0) {
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
}
window.ferroSyncCartBadges = ferroSyncCartBadges;

function ferroPulseCartBadges() {
    document.querySelectorAll('[data-ferro-cart-badge]').forEach((badge) => {
        badge.classList.remove('ferro-cart-badge-pop');
        // Reflow so repeated adds retrigger animation
        void badge.offsetWidth;
        badge.classList.add('ferro-cart-badge-pop');
    });
}

window.ferroAddToCart = async function (productId, qty = 1) {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const xsrf = ferroXsrfTokenFromCookie();
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': token || '',
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };
    if (xsrf) {
        headers['X-XSRF-TOKEN'] = xsrf;
    }
    let res;
    try {
        res = await fetch(ferroCartAddUrl(), {
            method: 'POST',
            credentials: 'same-origin',
            headers,
            body: JSON.stringify({ product_id: productId, quantity: qty }),
        });
    } catch {
        window.showToast?.('Network error. Please try again.', 'error');
        return false;
    }
    const data = await res.json().catch(() => ({}));
    if (!data.success || !data.item) {
        const msg =
            data.message ||
            (res.status === 419 ? 'Session expired. Refresh the page and try again.' : '') ||
            'Could not add to cart';
        window.showToast?.(msg, 'error');
        return false;
    }
    const totalQty = window.mergeFerroCartItem(data.item);
    ferroSyncCartBadges(totalQty);
    ferroPulseCartBadges();
    const ar = document.documentElement.getAttribute('lang') === 'ar';
    window.showToast?.(ar ? 'أُضيف إلى سلتك!' : 'Added to your arsenal!', 'success');
    return true;
};

function ferroCartQtyFromStorage() {
    try {
        const cart = JSON.parse(localStorage.getItem('ferro_cart') || '[]');
        return cart.reduce((s, i) => s + Number(i.qty || 0), 0);
    } catch {
        return 0;
    }
}

function ferroFindAddToCartButton(ev) {
    for (const node of ev.composedPath()) {
        if (node instanceof Element && node.hasAttribute('data-ferro-add-to-cart')) {
            return node;
        }
    }
    return null;
}

document.addEventListener('click', (e) => {
    const addBtn = ferroFindAddToCartButton(e);
    if (!addBtn) {
        return;
    }
    e.preventDefault();
    const id = Number.parseInt(addBtn.getAttribute('data-ferro-add-to-cart') || '', 10);
    const q = Number.parseInt(addBtn.getAttribute('data-ferro-add-qty') || '1', 10);
    if (!Number.isFinite(id) || id <= 0 || typeof window.ferroAddToCart !== 'function') {
        return;
    }
    window.ferroAddToCart(id, Number.isFinite(q) && q > 0 ? q : 1);
});

// ── Intersection Observer — Reveal animations + cart badge ────────────────
document.addEventListener('DOMContentLoaded', () => {
    const io = new IntersectionObserver(
        (entries) => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('is-visible'); }),
        { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
    );
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));

    initAdminTableMobileLabels();

    const count = ferroCartQtyFromStorage();
    ferroSyncCartBadges(count);
});

// ── Abandoned cart beacon (fires on page unload from checkout) ────────────
if (window.location.pathname.startsWith('/checkout')) {
    window.addEventListener('beforeunload', () => {
        const email = document.querySelector('[data-cart-email]')?.dataset?.cartEmail?.trim();
        const cartData = window.__FERRO_CART__;
        if (!email || !cartData?.items?.length) {
            return;
        }
        const data = JSON.stringify({
            email,
            cart_items: cartData.items,
            cart_value: cartData.total,
        });
        navigator.sendBeacon('/cart/abandon', new Blob([data], { type: 'application/json' }));
    });
}

// ── Language direction handler ─────────────────────────────────────────────
// Ensures smooth transition when switching languages
document.querySelectorAll('.lang-toggle-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        // Add loading state
        e.currentTarget.style.opacity = '0.6';
        e.currentTarget.style.pointerEvents = 'none';
    });
});

