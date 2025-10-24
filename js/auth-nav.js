// /Cognate3/js/auth-nav.js
(function () {
  var BASE = '/Cognate3/';
  var TOKEN_KEY = 'nexora_token';
  var LAST_USER_KEY = 'nexora_last_username';
  var REDIRECT_AFTER_LOGOUT = BASE + 'login.html';

  function show(el) { if (el) el.classList.remove('d-none'); }
  function hide(el) { if (el) el.classList.add('d-none'); }

  function hasValidToken() {
    try {
      var v = localStorage.getItem(TOKEN_KEY);
      return !!(v && v !== 'null' && v !== 'undefined');
    } catch (e) { return false; }
  }

  function setupAuthNav(opts) {
    opts = opts || {};
    var loginSel  = opts.loginSelector  || '#loginLink';
    var logoutSel = opts.logoutSelector || '#logoutBtn, #logoutLink';

    var loginLink = document.querySelector(loginSel);
    var logoutEl  = document.querySelector(logoutSel);

    // Start hidden; reveal based on auth state
    hide(loginLink);
    hide(logoutEl);

    if (hasValidToken()) {
      // Logged in → show logout only
      show(logoutEl);

      if (logoutEl) {
        // Reset any previous listener if the script is injected twice
        var clone = logoutEl.cloneNode(true);
        logoutEl.parentNode.replaceChild(clone, logoutEl);
        logoutEl = clone;

        logoutEl.addEventListener('click', function (e) {
          // For <button>, prevent navigation; for <a>, let it navigate to server logout if present
          if (logoutEl.tagName !== 'A') e.preventDefault();

          try {
            localStorage.removeItem(TOKEN_KEY);
            localStorage.removeItem(LAST_USER_KEY);
            sessionStorage.clear();
          } catch (_) {}

          if (logoutEl.tagName !== 'A') {
            location.replace(REDIRECT_AFTER_LOGOUT);
          }
          // if it's an <a href="/Cognate3/api/auth/logout.php">, server handles redirect
        }, { once: true });
      }
    } else {
      // Not logged in → show login only
      show(loginLink);
    }
  }

  function enableRequiresAuthIntercept() {
    document.addEventListener('click', function (e) {
      var a = e.target && e.target.closest ? e.target.closest('a[data-requires-auth]') : null;
      if (!a) return;
      if (!hasValidToken()) {
        e.preventDefault();
        var href = a.getAttribute('href') || 'project.html';
        var next = encodeURIComponent(href);
        try { sessionStorage.setItem('flash', 'Please log in to continue.'); } catch (_) {}
        location.href = BASE + 'login.html?next=' + next;
      }
    });
  }

  window.NexoraAuth = {
    setupAuthNav: setupAuthNav,
    isLoggedIn: hasValidToken,
    requireAuth: function (redirectTo) {
      redirectTo = redirectTo || (BASE + 'login.html');
      if (!hasValidToken()) location.replace(redirectTo);
    }
  };

  document.addEventListener('DOMContentLoaded', function () {
    setupAuthNav();
    enableRequiresAuthIntercept();
  });
})();
