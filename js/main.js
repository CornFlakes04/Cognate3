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


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        items: 1,
        autoplay: true,
        smartSpeed: 1000,
        animateIn: 'fadeIn',
        animateOut: 'fadeOut',
        dots: true,
        loop: true,
        nav: false
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


})(jQuery);