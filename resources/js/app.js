import './bootstrap';

// ===== THEME TOGGLE (Animated Sun/Moon) =====
function initTheme() {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark' || (!saved && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

function toggleTheme() {
    const html = document.documentElement;
    html.classList.toggle('dark');
    localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
    
    // Show toast on toggle
    const isDark = html.classList.contains('dark');
    showToast(isDark ? 'Mode gelap aktif' : 'Mode terang aktif', 'info', 2000);
}

// Init on load
initTheme();
window.toggleTheme = toggleTheme;

// ===== MOBILE MENU (animated) =====
window.toggleMobileMenu = function() {
    const menu = document.getElementById('mobile-menu');
    const btn = document.getElementById('mobile-menu-btn');
    if (!menu) return;
    
    const isOpen = menu.classList.contains('open');
    
    if (isOpen) {
        menu.classList.remove('open');
        if (btn) btn.setAttribute('aria-expanded', 'false');
    } else {
        menu.classList.add('open');
        if (btn) btn.setAttribute('aria-expanded', 'true');
    }
};

// Close mobile menu on link click
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            const menu = document.getElementById('mobile-menu');
            if (menu) menu.classList.remove('open');
        });
    });
});

// ===== CATALOG FILTER =====
// Fixed: pass event explicitly instead of relying on implicit `event` global
window.filterCatalog = function(filter, clickedChip) {
    const cards = document.querySelectorAll('[data-category-card]');
    const chips = document.querySelectorAll('.filter-chip');

    chips.forEach(c => c.classList.remove('active'));
    if (clickedChip) clickedChip.classList.add('active');

    cards.forEach(card => {
        if (filter === 'all') {
            card.style.display = '';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
            return;
        }
        const hasAc = card.dataset.hasAc;
        const bathroom = card.dataset.bathroom;

        let show = false;
        if (filter === 'ac') show = hasAc === '1';
        if (filter === 'non-ac') show = hasAc === '0';
        if (filter === 'km-dalam') show = bathroom === 'inside';
        if (filter === 'km-luar') show = bathroom === 'outside';

        if (show) {
            card.style.display = '';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        } else {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.95)';
            setTimeout(() => { card.style.display = 'none'; }, 200);
        }
    });
};

// ===== NAVBAR SCROLL EFFECT (with debounce via rAF) =====
let lastScrollY = 0;
let scrollTicking = false;

function onScroll() {
    const nav = document.getElementById('navbar');
    if (!nav) return;
    
    const scrollY = window.scrollY;
    
    // Add shadow on scroll
    if (scrollY > 20) {
        nav.classList.add('shadow-theme');
    } else {
        nav.classList.remove('shadow-theme');
    }
    
    // Hide on scroll down, show on scroll up (only on mobile)
    if (window.innerWidth < 768) {
        if (scrollY > lastScrollY && scrollY > 100) {
            nav.style.transform = 'translateY(-100%)';
        } else {
            nav.style.transform = 'translateY(0)';
        }
    }
    lastScrollY = scrollY;
    scrollTicking = false;
}

window.addEventListener('scroll', () => {
    if (!scrollTicking) {
        requestAnimationFrame(onScroll);
        scrollTicking = true;
    }
}, { passive: true });

// ===== SCROLL REVEAL (IntersectionObserver) =====
document.addEventListener('DOMContentLoaded', () => {
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length === 0) return;
    
    // Respect prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        reveals.forEach(el => el.classList.add('visible'));
        return;
    }
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    reveals.forEach(el => observer.observe(el));
});

// ===== COUNTER ANIMATION =====
window.animateCounter = function(el, target, duration = 1500) {
    // Respect prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        el.textContent = target.toLocaleString('id-ID');
        return;
    }

    let start = 0;
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3); // ease out cubic
        const current = Math.round(start + (target - start) * eased);
        el.textContent = current.toLocaleString('id-ID');
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
};

// ===== SMOOTH FLASH MESSAGE DISMISS =====
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-dismiss]').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.closest('[data-flash]');
            if (target) {
                target.style.opacity = '0';
                target.style.transform = 'translateY(-10px)';
                setTimeout(() => target.remove(), 300);
            }
        });
    });
    
    // Auto-dismiss after 5s
    document.querySelectorAll('[data-flash]').forEach(flash => {
        setTimeout(() => {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-10px)';
            setTimeout(() => flash.remove(), 300);
        }, 5000);
    });
});

// ===== TOAST NOTIFICATION SYSTEM =====
const toastIcons = {
    success: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    warning: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>',
    error: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    info: '<svg class="toast-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
};

function showToast(message, type = 'info', duration = 4000) {
    const container = document.getElementById('toast-container');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `${toastIcons[type] || toastIcons.info}<span>${message}</span>`;
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('toast-out');
        setTimeout(() => toast.remove(), 300);
    }, duration);
}
window.showToast = showToast;

// ===== NOTIFICATION BELL =====
let notifDropdownOpen = false;
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const baseUrl = document.querySelector('meta[name="base-url"]')?.content || '';

window.toggleNotifDropdown = function() {
    const dropdown = document.getElementById('notif-dropdown');
    if (!dropdown) return;
    
    notifDropdownOpen = !notifDropdownOpen;
    dropdown.classList.toggle('hidden', !notifDropdownOpen);
    
    if (notifDropdownOpen) {
        fetchRecentNotifications();
    }
};

// Close dropdown on outside click
document.addEventListener('click', (e) => {
    const bell = document.getElementById('notif-bell-desktop');
    const dropdown = document.getElementById('notif-dropdown');
    if (dropdown && bell && !bell.contains(e.target)) {
        dropdown.classList.add('hidden');
        notifDropdownOpen = false;
    }
});

async function fetchNotifCount() {
    try {
        const res = await fetch(`${baseUrl}/notifications/unread-count`, { credentials: 'same-origin' });
        if (!res.ok) return;
        const data = await res.json();
        updateBadge(data.count);
    } catch (e) { /* silent */ }
}

function updateBadge(count) {
    ['notif-badge', 'notif-badge-mobile'].forEach(id => {
        const badge = document.getElementById(id);
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
}

async function fetchRecentNotifications() {
    const list = document.getElementById('notif-list');
    if (!list) return;
    
    try {
        const res = await fetch(`${baseUrl}/notifications/recent`, { credentials: 'same-origin' });
        if (!res.ok) return;
        const notifs = await res.json();
        
        if (notifs.length === 0) {
            list.innerHTML = '<p class="text-center text-theme-muted text-sm py-6">Tidak ada notifikasi</p>';
            return;
        }
        
        list.innerHTML = notifs.map(n => `
            <div class="notif-item ${n.read ? '' : 'unread'}">
                <div class="notif-title">${n.title}</div>
                <div class="notif-msg">${n.message}</div>
                <div class="notif-time">${n.time}</div>
            </div>
        `).join('');
    } catch (e) {
        list.innerHTML = '<p class="text-center text-red-400 text-sm py-6">Gagal memuat</p>';
    }
}

window.markAllRead = async function() {
    try {
        await fetch(`${baseUrl}/notifications/mark-read`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
        });
        updateBadge(0);
        fetchRecentNotifications();
        showToast('Semua notifikasi ditandai dibaca', 'success', 2000);
    } catch (e) { /* silent */ }
};

// ===== NOTIFICATION POLLING with Page Visibility API =====
// Stops polling when tab is inactive to save bandwidth & battery
let notifInterval = null;

function startNotifPolling() {
    if (notifInterval) return; // already running
    fetchNotifCount();
    notifInterval = setInterval(fetchNotifCount, 30000);
}

function stopNotifPolling() {
    if (notifInterval) {
        clearInterval(notifInterval);
        notifInterval = null;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const hasNotifBell = document.getElementById('notif-badge') || document.getElementById('notif-badge-mobile');
    
    if (hasNotifBell) {
        // Start polling only if tab is visible
        if (!document.hidden) {
            startNotifPolling();
        }

        // React to visibility changes
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopNotifPolling();
            } else {
                startNotifPolling();
            }
        });
    }
    
    // Staggered animation for catalog cards
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    document.querySelectorAll('[data-category-card]').forEach((card, i) => {
        if (!prefersReducedMotion) {
            card.classList.add('stagger-item');
            card.style.animationDelay = `${i * 0.1}s`;
        }
    });
});
