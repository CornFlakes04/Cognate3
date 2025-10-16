(function () {
  // Configure once and reuse
  const BASE = '/Cognate3/';              // adjust if needed
  const REDIRECT_AFTER_LOGOUT = BASE + 'login.html';
  const TOKEN_KEY = 'nexora_token';
  const LAST_USER_KEY = 'nexora_last_username';

  function show(el) { el && el.classList.remove('d-none'); }
  function hide(el) { el && el.classList.add('d-none'); }

  function setupAuthNav(opts = {}) {
    const loginSel  = opts.loginSelector  || '#loginLink';
    const logoutSel = opts.logoutSelector || '#logoutBtn';
    const loginLink = document.querySelector(loginSel);
    const logoutBtn = document.querySelector(logoutSel);
    const hasToken  = !!localStorage.getItem(TOKEN_KEY);

    if (hasToken) {
      hide(loginLink); show(logoutBtn);
      logoutBtn && logoutBtn.addEventListener('click', async () => {
        try {
          // Optional server logout:
          // await fetch(BASE + 'api/auth/logout.php', { method: 'POST' });
        } catch (_) {}
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(LAST_USER_KEY);
        location.replace(REDIRECT_AFTER_LOGOUT);
      }, { once: true });
    } else {
      show(loginLink); hide(logoutBtn);
    }
  }

  // Expose a tiny API if you want to call it manually
  window.NexoraAuth = {
    setupAuthNav,
    isLoggedIn: () => !!localStorage.getItem(TOKEN_KEY),
    requireAuth(redirectTo = BASE + 'login.html') { // use on protected pages
      if (!localStorage.getItem(TOKEN_KEY)) location.replace(redirectTo);
    }
  };

  // Auto-run for any page that has the elements
  document.addEventListener('DOMContentLoaded', () => setupAuthNav());
})();

(function () {
  const TOKEN_KEY = 'nexora_token';
  // Intercept clicks on links with data-requires-auth
  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-requires-auth]');
    if (!a) return;
    const hasToken = !!localStorage.getItem(TOKEN_KEY);
    if (!hasToken) {
      e.preventDefault();
      const next = encodeURIComponent(a.getAttribute('href') || 'project.html');
      // Send them to login with a return target
      location.href = `login.html?next=${next}`;
    }
  });
})();

(function () {
  const TOKEN_KEY = 'nexora_token';

  document.addEventListener('click', (e) => {
    const a = e.target.closest('a[data-requires-auth]');
    if (!a) return;

    if (!localStorage.getItem(TOKEN_KEY)) {
      e.preventDefault();
      const next = encodeURIComponent(a.getAttribute('href') || 'project.html');
      // set a one-time flash message for the login page
      sessionStorage.setItem('flash', 'Please log in to view Projects.');
      location.href = `login.html?next=${next}`;
    }
  });
})();
