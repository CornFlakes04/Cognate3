(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('bg-white shadow-sm').css('top', '0px');
        } else {
            $('.sticky-top').removeClass('bg-white shadow-sm').css('top', '-150px');
        }
    });


    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        loop: true,
        dots: true,
        items: 1
    });

    //smtp
    // Robust no-redirect contact handler
    (function () {
        const form = document.getElementById('contactForm');
        if (!form) { console.warn('[contact] #contactForm not found'); return; }

        // Ensure/attach a status element
        let status = document.getElementById('contactStatus');
        if (!status) {
            status = document.createElement('p');
            status.id = 'contactStatus';
            status.className = 'text-center mt-3';
            form.appendChild(status);
        }

        // Helper
        function setStatus(msg, cls) {
            status.hidden = false;
            status.className = 'text-center mt-3 ' + (cls || 'text-muted');
            status.textContent = msg;
            // ensure it’s not visually invisible on light bg
            if (!cls) status.classList.add('text-dark');
        }


        // Your Formspree endpoint
        const ENDPOINT = 'https://formspree.io/f/mkgqbkwz'; // <- replace

        // Absolute guarantee: no native submit
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            try {
                const name = (document.getElementById('name')?.value || '').trim();
                const email = (document.getElementById('email')?.value || '').trim();
                const subject = (document.getElementById('subject')?.value || '').trim();
                const message = (document.getElementById('message')?.value || '').trim();

                if (!name || !email || !subject || !message) {
                    return setStatus('Please fill out all fields.', 'text-danger');
                }

                setStatus('Sending…', 'text-muted');

                const fd = new FormData();
                fd.set('name', name);
                fd.set('email', email);
                fd.set('subject', subject);
                fd.set('message', message);
                fd.set('_replyto', email);
                fd.set('_subject', 'Nexora Tech — Contact Form');



                const res = await fetch(ENDPOINT, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: fd
                });

                if (!res.ok) {
                    const text = await res.text().catch(() => '');
                    console.error('[contact] Formspree error', res.status, text);
                    return setStatus(
                        res.status === 404 ? 'Form not found. Check your Formspree URL/activation.'
                            : 'Failed to send. Please try again later.',
                        'text-danger'
                    );
                }

                setStatus('Thanks! Your message has been sent.', 'text-success');
                form.reset();
                status.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } catch (err) {
                console.error('[contact] submit error', err);
                setStatus('Network or script error. Check console.', 'text-danger');
            }
        });

        // Quick sanity log to confirm this block actually ran
        console.log('[contact] handler ready');
    })();

    // Video Modal
    document.addEventListener('DOMContentLoaded', () => {
        const modalEl = document.getElementById('projectVideoModal');
        const video = document.getElementById('projectVideo');
        const source = document.getElementById('projectVideoSource');
        const title = document.getElementById('projectVideoTitle');

        modalEl.addEventListener('show.bs.modal', (ev) => {
            const trigger = ev.relatedTarget; // the <a> clicked
            const path = trigger?.getAttribute('data-video') || '';
            const label = trigger?.getAttribute('data-title') || 'Project Video';
            title.textContent = label;

            // Set source and load
            source.src = path;
            video.load();

            // Try autoplay (user click usually allows it)
            const p = video.play();
            if (p && typeof p.catch === 'function') { p.catch(() => {/* ignore */ }); }
        });

        modalEl.addEventListener('hidden.bs.modal', () => {
            // Stop and release the file
            video.pause();
            video.currentTime = 0;
            source.src = '';
            video.load();
        });
    });

    // Products: sort + filter (works with or without #sortSelect)
    (function () {
        const grid = document.getElementById('productGrid');
        const select = document.getElementById('sortSelect');       // may be null (you commented it out)
        const filtersWrap = document.getElementById('filters');
        const clearBtn = document.getElementById('clearFilters');    // may be null if you commented it out too
        if (!grid || !filtersWrap) return;                           // don’t require select anymore

        // Snapshot original columns (Bootstrap cols) to restore “Featured” order
        const cols = Array.from(grid.children);
        cols.forEach((col, i) => col.dataset.originalIndex = i);

        // --- Helpers ---
        const getItem = col => col.querySelector('.product-item');

        function getPrice(col) {
            const item = getItem(col);
            if (!item) return Number.POSITIVE_INFINITY;
            const n = parseFloat(item.dataset.price);
            return isNaN(n) ? Number.POSITIVE_INFINITY : n;
        }
        function getAdded(col) {
            const item = getItem(col);
            if (!item || !item.dataset.added) return -Infinity;
            const t = Date.parse(item.dataset.added);
            return isNaN(t) ? -Infinity : t;
        }
        function getCats(col) {
            const item = getItem(col);
            if (!item) return [];
            return (item.dataset.cats || '')
                .split(',')
                .map(s => s.trim().toLowerCase())
                .filter(Boolean);
        }

        // Active categories from checkboxes in #filters
        const checks = Array.from(filtersWrap.querySelectorAll('input[type="checkbox"]'));
        function activeCats() {
            return new Set(checks.filter(c => c.checked).map(c => c.value.toLowerCase()));
        }
        function matchesCats(col, act) {
            if (act.size === 0) return true;
            const cats = getCats(col);
            return cats.some(c => act.has(c));
        }

        // Main: apply current sort + filter
        function applySortFilter() {
            const mode = select?.value || '0';   // default to “Featured” when select is missing
            const act = activeCats();

            const ordered = cols.slice();
            if (mode === '1') {
                ordered.sort((a, b) => getPrice(a) - getPrice(b));       // Low → High
            } else if (mode === '2') {
                ordered.sort((a, b) => getPrice(b) - getPrice(a));       // High → Low
            } else if (mode === '3') {
                ordered.sort((a, b) => getAdded(b) - getAdded(a));       // Newest
            } else {
                ordered.sort((a, b) => a.dataset.originalIndex - b.dataset.originalIndex); // Featured
            }

            ordered.forEach(col => {
                grid.appendChild(col);
                col.style.display = matchesCats(col, act) ? '' : 'none';
            });
        }

        // Events (guarded for optional elements)
        select?.addEventListener('change', applySortFilter);
        checks.forEach(c => c.addEventListener('change', applySortFilter));
        clearBtn?.addEventListener('click', () => {
            checks.forEach(c => (c.checked = false));
            // If you also want to reset sort when Clear is present:
            if (select) select.value = '0';
            applySortFilter();
        });

        // Initial render
        applySortFilter();
    })();


    // Product details modal (unified): populate on show + make whole card clickable
    (function () {
        const modalEl = document.getElementById('productModal');
        if (!modalEl) return;

        const el = {
            title: document.getElementById('productModalTitle'),
            img: document.getElementById('productModalImg'),
            cat: document.getElementById('productModalCat'),
            price: document.getElementById('productModalPrice'),
            desc: document.getElementById('productModalDesc'),
            cta: document.getElementById('productModalCTA'),
        };

        const peso = n => '₱' + Number(n).toLocaleString('en-PH');

        function getTitle(item) {
            const h4 = item.querySelector('h4');
            if (h4 && h4.textContent.trim()) return h4.textContent.trim();
            const img = item.querySelector('img');
            if (img?.alt?.trim()) return img.alt.trim();
            const lab = item.querySelector('.product-overlay small');
            if (lab?.textContent?.trim()) return lab.textContent.trim();
            return item.dataset.title || 'Product';
        }
        function getCat(item) {
            const cats = (item.dataset.cats || '').trim();
            if (cats) return cats;
            const lab = item.querySelector('.product-overlay small');
            return lab ? lab.textContent.trim() : '';
        }
        function buildInquiryURL(title, sku) {
            const url = new URL('contact.html', location.href);
            const subject = `Inquiry — ${title}${sku ? ` [${sku}]` : ''}`;
            url.searchParams.set('subject', subject);
            if (sku) url.searchParams.set('sku', sku);
            url.searchParams.set('product', title);
            return url.pathname + url.search;
        }
        function populate(item) {
            const imgEl = item.querySelector('img');
            const title = getTitle(item);
            const cat = getCat(item);
            const price = item.dataset.price
                ? peso(item.dataset.price)
                : (item.querySelector('.h5')?.textContent?.trim() || '');
            const desc = item.dataset.desc || 'No description available.';
            const sku = item.dataset.sku || '';

            el.title.textContent = title;
            if (imgEl?.src) { el.img.src = imgEl.src; el.img.alt = title; }
            else { el.img.removeAttribute('src'); el.img.alt = ''; }
            el.cat.textContent = cat;
            el.price.textContent = price;
            el.desc.textContent = desc;
            el.cta.href = buildInquiryURL(title, sku);
        }

        // A) Single source of truth: populate when the modal is about to show
        modalEl.addEventListener('show.bs.modal', (ev) => {
            const trigger = ev.relatedTarget;                     // img or Details button
            const item = trigger?.closest('.product-item')
                || document.querySelector('.product-item.__opening'); // programmatic fallback
            if (item) populate(item);
        });

        // B) Whole card click opens modal (except real buttons/links)
        document.addEventListener('click', (e) => {
            const item = e.target.closest('.product-item');
            if (!item) return;
            if (e.target.closest('.btn, a, [data-bs-toggle]')) return;

            const Modal = window.bootstrap?.Modal;
            if (!Modal) { console.error('[product] Bootstrap JS not loaded'); return; }

            // Mark the item so the show handler can find it (fallback path)
            document.querySelectorAll('.product-item.__opening').forEach(x => x.classList.remove('__opening'));
            item.classList.add('__opening');
            new Modal(modalEl).show();
        });

        // C) Inject subject/SKU when clicking "Inquire" on a card
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a.btn-inquire');
            if (!link) return;
            const item = link.closest('.product-item');
            if (!item) return;
            const title = getTitle(item);
            const sku = item.dataset.sku || '';
            link.href = buildInquiryURL(title, sku);
        });
    })();


    // Show product name on hover by injecting it into the overlay
    (function () {
        const items = document.querySelectorAll('.product-item');
        if (!items.length) return;

        // Same title derivation used by the modal (kept local to avoid coupling)
        function getTitle(item) {
            const h4 = item.querySelector('h4');
            if (h4 && h4.textContent.trim()) return h4.textContent.trim();
            const img = item.querySelector('img');
            if (img?.alt?.trim()) return img.alt.trim();
            const lab = item.querySelector('.product-overlay small');
            if (lab?.textContent?.trim()) return lab.textContent.trim();
            return item.dataset.title || 'Product';
        }

        items.forEach(item => {
            const overlay = item.querySelector('.product-overlay');
            if (!overlay) return;

            // Avoid duplicates if scripts re-run
            if (!overlay.querySelector('.product-name')) {
                const name = document.createElement('div');
                name.className = 'product-name';
                name.textContent = getTitle(item);
                overlay.insertBefore(name, overlay.firstElementChild || null);
            }

            // Optional: native tooltip as fallback
            const t = getTitle(item);
            if (!item.title) item.title = t;
            const img = item.querySelector('img');
            if (img && !img.title) img.title = t;
        });
    })();


    // Contact form: populate subject/product from URL params
    (function () {
        const qs = new URLSearchParams(location.search);
        const subject = qs.get('subject');
        const product = qs.get('product');
        const sku = qs.get('sku');

        const subjEl = document.querySelector('#subject, [name="subject"]');
        if (subjEl && subject) subjEl.value = subject;

        const prodEl = document.querySelector('#product, [name="product"]');
        if (prodEl && (product || sku)) {
            prodEl.value = [product, sku ? `[${sku}]` : null].filter(Boolean).join(' ');
        }
    })();



})(jQuery);