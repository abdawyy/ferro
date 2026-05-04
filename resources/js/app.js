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

function ferroCartQtyFromStorage() {
    try {
        const cart = JSON.parse(localStorage.getItem('ferro_cart') || '[]');
        return cart.reduce((s, i) => s + Number(i.qty || 0), 0);
    } catch {
        return 0;
    }
}

// ── Intersection Observer — Reveal animations + cart badge ────────────────
document.addEventListener('DOMContentLoaded', () => {
    const io = new IntersectionObserver(
        (entries) => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('is-visible'); }),
        { threshold: 0.08, rootMargin: '0px 0px -40px 0px' }
    );
    document.querySelectorAll('.reveal, .reveal-stagger').forEach(el => io.observe(el));

    initAdminTableMobileLabels();

    const badge = document.getElementById('cart-badge');
    const count = ferroCartQtyFromStorage();
    if (badge && count > 0) {
        badge.textContent = String(count);
        badge.classList.remove('hidden');
    }
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

