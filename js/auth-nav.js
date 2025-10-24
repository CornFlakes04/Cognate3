// /Cognate3/js/auth-nav.js
(function () {
  var BASE = '/Cognate3/';
  var TOKEN_KEY = 'nexora_token';
  var LAST_USER_KEY = 'nexora_last_username';

  // After logout, always go home
  var REDIRECT_AFTER_LOGOUT = BASE + 'index.html';
  var ENDPOINT_LOGOUT = BASE + 'api/auth/logout.php'; // optional server endpoint

  function show(el) { if (el) el.classList.remove('d-none'); }
  function hide(el) { if (el) el.classList.add('d-none'); }
  function hasValidToken() {
    try {
      var v = localStorage.getItem(TOKEN_KEY);
      return !!(v && v !== 'null' && v !== 'undefined');
    } catch (e) { return false; }
  }
  function clearClientAuth() {
    try {
      localStorage.removeItem(TOKEN_KEY);
    } catch (_) {}
    try {
      localStorage.removeItem(LAST_USER_KEY);
    } catch (_) {}
    try {
      sessionStorage.clear();
    } catch (_) {}
  }

  function wireLogout(el) {
    if (!el || el._wiredLogout) return;
    el._wiredLogout = true;

    el.addEventListener('click', function (e) {
      // Always take control so we can clear client, call server, then go home
      e.preventDefault();

      // 1) Clear client
      clearClientAuth();

      // 2) Tell server to end PHP session (ignore errors)
      try {
        fetch(ENDPOINT_LOGOUT, {
          method: 'POST',
          credentials: 'include',
          cache: 'no-store',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ action: 'logout' })
        }).catch(function() {});
      } catch (_) {}

      // 3) Hard redirect to index with cache-bust
      var bust = (REDIRECT_AFTER_LOGOUT.indexOf('?') >= 0 ? '&' : '?') + 'ts=' + Date.now();
      location.replace(REDIRECT_AFTER_LOGOUT + bust);
    });
  }

  function pickSingleLogoutElement() {
    // Prefer #logoutLink (anchor), fall back to #logoutBtn (button)
    var logoutLink = document.querySelector('#logoutLink');
    var logoutBtn  = document.querySelector('#logoutBtn');
    var chosen = logoutLink || logoutBtn;

    // Hide any extras so you don't see two
    [logoutLink, logoutBtn].forEach(function (el) {
      if (!el) return;
      if (el !== chosen) hide(el);
    });

    return chosen;
  }

  function setupAuthNav(opts) {
    opts = opts || {};
    var loginSel = opts.loginSelector || '#loginLink';
    var loginLink = document.querySelector(loginSel);

    // Start hidden
    hide(loginLink);
    hide(document.querySelector('#logoutLink'));
    hide(document.querySelector('#logoutBtn'));

    if (hasValidToken()) {
      // Logged in → show exactly ONE logout control
      var logoutEl = pickSingleLogoutElement();
      if (logoutEl) {
        show(logoutEl);
        wireLogout(logoutEl);
      }
    } else {
      // Logged out → show login only
      show(loginLink);
    }
  }

  function enableRequiresAuthIntercept() {
    // Only gates links that explicitly declare data-requires-auth
    document.addEventListener('click', function (e) {
      var a = e.target && e.target.closest ? e.target.closest('a[data-requires-auth]') : null;
      if (!a) return;
      if (!hasValidToken()) {
        e.preventDefault();
        var href = a.getAttribute('href') || location.href;
        var next = encodeURIComponent(href);
        try { sessionStorage.setItem('flash', 'Please log in to continue.'); } catch (_) {}
        location.href = BASE + 'login.html?next=' + next;
      }
    });
  }

  // Public helpers (optional)
  window.NexoraAuth = {
    setupAuthNav: setupAuthNav,
    isLoggedIn: hasValidToken,
    requireAuth: function (redirectTo) {
      redirectTo = redirectTo || (BASE + 'login.html');
      if (!hasValidToken()) location.replace(redirectTo);
    }
  };

  // Initial run
  document.addEventListener('DOMContentLoaded', function () {
    setupAuthNav();
    enableRequiresAuthIntercept();
  });

  // Re-run on back/forward cache restore so UI stays correct
  window.addEventListener('pageshow', function (ev) {
    if (ev.persisted) setupAuthNav();
  });
})();
