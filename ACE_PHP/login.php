<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Nexora Tech — Login</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">

  <style>
    :root{
      /* Nexora brand system (tunable) */
      --nx-primary: #11a79a;
      --nx-primary-700: #0b7f73;
      --nx-primary-900: #075a52;
      --nx-accent:  #21c7b7;
      --nx-bg: #070f10;          /* deep teal-black */
      --nx-fg: #e8f6f3;          /* on-dark text */
      --nx-muted: #98b8b3;
      --nx-danger: #ef4444;
      --glass: rgba(10,18,18,.55);
      --glass-strong: rgba(8,14,14,.72);
      --ring: rgba(17,167,154,.35);
      --border: rgba(255,255,255,.08);

      /* 🔁 Background image: change here only */
      --nx-bg-image: image-set(
        url("img/nexora-dark-teal-grid.webp") type("image/webp") 1x,
        url("img/nexora-dark-teal-grid@2x.webp") type("image/webp") 2x,
        url("img/nexora-dark-teal-grid.jpg") type("image/jpeg") 1x
      );
      /* If you prefer no photo, use:
         --nx-bg-image: linear-gradient(135deg,#052b2a 0%,#071214 100%);
      */
    }

    html,body{height:100%;}
    body{
      font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji", sans-serif;
      color: var(--nx-fg);
      background: var(--nx-bg);
      overflow: hidden;
    }

    /* Background image + vignette + brand gradient wash */
    .hero{
      position:fixed; inset:0;
      background:
        radial-gradient(1200px 600px at 70% 15%, rgba(17,167,154,.18), transparent 60%),
        radial-gradient(1200px 600px at 25% 85%, rgba(33,199,183,.14), transparent 60%),
        var(--nx-bg-image) center/cover no-repeat;
      filter: saturate(110%);
    }
    .hero::after{ /* dark vignette for contrast */
      content:""; position:absolute; inset:0;
      background: radial-gradient(1200px 600px at 50% 40%, transparent 40%, rgba(0,0,0,.45) 100%), linear-gradient(to bottom, rgba(0,0,0,.35), rgba(0,0,0,.55));
    }

    /* Glass card */
    .login-wrap{
      position:relative; z-index:1;
      min-height:100dvh; display:grid; place-items:center; padding:2rem;
    }
    .card-glass{
      width:min(440px, 92vw);
      background: linear-gradient(180deg, var(--glass-strong), var(--glass));
      backdrop-filter: blur(16px) saturate(120%);
      -webkit-backdrop-filter: blur(16px) saturate(120%);
      border:1px solid var(--border);
      border-radius:24px;
      box-shadow: 0 20px 60px rgba(0,0,0,.45), inset 0 1px 0 rgba(255,255,255,.04);
      padding:28px 26px;
    }

    /* Brand header */
    .brand{
      display:flex; flex-direction:column; align-items:center; gap:.5rem; margin-bottom:1rem;
    }
    .brand-badge{
      width:74px; height:74px; border-radius:50%;
      display:grid; place-items:center;
      background: radial-gradient(60% 60% at 50% 35%, rgba(255,255,255,.06), transparent),
                  linear-gradient(145deg, rgba(17,167,154,.22), rgba(7,90,82,.22));
      border:1px solid var(--border);
      box-shadow: inset 0 0 0 2px rgba(255,255,255,.02), 0 8px 24px rgba(0,0,0,.35);
    }
    .brand-badge img{ width:44px; height:44px; object-fit:contain; opacity:.95; }
    .brand h1{
      font-family: "Space Grotesk", system-ui, sans-serif;
      font-size: clamp(22px, 2.2vw, 28px);
      letter-spacing:.4px; margin:0;
    }

    /* Inputs */
    .form-label{ color: var(--nx-muted); font-weight:500; }
    .form-control{
      background: rgba(255,255,255,.06);
      border:1px solid rgba(255,255,255,.14);
      color:#fff; padding:.8rem .95rem;
      border-radius:14px;
      transition: box-shadow .15s ease, border-color .15s ease, background .2s ease;
    }
    .form-control::placeholder{ color: #b5c7c4; }
    .form-control:focus{
      background: rgba(255,255,255,.09);
      border-color: var(--nx-primary);
      box-shadow: 0 0 0 .25rem var(--ring);
      color:#fff;
    }

    /* Password toggle button merged cleanly with input */
    .input-group .btn-toggle{
      border:1px solid rgba(255,255,255,.14);
      border-left:none; background: rgba(255,255,255,.06);
      color:#cfe7e3; padding:0 14px; border-radius:0 14px 14px 0;
    }
    .input-group .form-control{ border-right:none; border-radius:14px 0 0 14px; }
    .btn-toggle:hover{ color:#fff; background: rgba(255,255,255,.10); }

    /* Primary button */
    .btn-nx{
      --bs-btn-color:#031110;
      --bs-btn-bg: var(--nx-primary);
      --bs-btn-border-color: var(--nx-primary);
      --bs-btn-hover-bg: var(--nx-primary-700);
      --bs-btn-hover-border-color: var(--nx-primary-700);
      --bs-btn-focus-shadow-rgb: 17,167,154;
      border-radius:14px; padding:.9rem 1rem; font-weight:700;
      letter-spacing:.2px;
      box-shadow: 0 6px 18px rgba(17,167,154,.35);
      transition: transform .06s ease;
    }
    .btn-nx:active{ transform: translateY(1px); }

    /* Translucent error that matches the glass */
    .alert-glass{
      background: rgba(239,68,68,.12);
      color: #ffd6d6;
      border: 1px solid rgba(239,68,68,.25);
      border-radius:14px;
      backdrop-filter: blur(10px);
    }

    /* Helper links */
    .helper{ display:flex; justify-content:space-between; align-items:center; gap:1rem; }
    .helper a{ color: var(--nx-accent); text-decoration:none; }
    .helper a:hover{ text-decoration:underline; }

    @media (prefers-reduced-motion: reduce){
      *{ transition:none !important; animation-duration:.01ms !important; }
    }
/* === Light-mode form + card === */

/* Card: from dark-glass to soft white panel */
.card-glass{
  background: rgba(255,255,255,.92);
  border: 1px solid rgba(0,0,0,.06);
  box-shadow: 0 16px 40px rgba(0,0,0,.08);
  -webkit-backdrop-filter: blur(8px) saturate(110%);
  backdrop-filter: blur(8px) saturate(110%);
}

/* Labels */
.form-label{ color:#3b4a47; }

/* Inputs */
.form-control{
  background:#ffffff;
  color:#0f2a28;
  border:1px solid #d7e0de;          /* subtle neutral border */
  border-radius:12px;
  box-shadow:none;
}
.form-control::placeholder{ color:#8aa19c; }
.form-control:focus{
  background:#ffffff;
  border-color: var(--nx-primary);
  box-shadow: 0 0 0 .2rem rgba(17,167,154,.18);
  color:#0f2a28;
}

/* Password toggle next to input */
.input-group .form-control{ border-right:none; border-radius:12px 0 0 12px; }
.input-group .btn-toggle{
  background:#ffffff;
  color:#5b6f6b;
  border:1px solid #d7e0de;
  border-left:none;
  border-radius:0 12px 12px 0;
}
.input-group .btn-toggle:hover{ background:#f7fbfa; color:#2a3a37; }

/* Checkbox + helper link tones */
.form-check-input{
  border-color:#bcd0cc;
}
.form-check-input:checked{
  background-color: var(--nx-primary);
  border-color: var(--nx-primary);
}
.helper a{ color: var(--nx-primary-700); }

/* Alert: lighter red on white */
.alert-glass{
  background: rgba(239,68,68,.08);
  color:#7d2121;
  border:1px solid rgba(239,68,68,.25);
}

/* Primary button keeps brand teal, soften shadow for light bg */
.btn-nx{
  box-shadow: 0 6px 16px rgba(17,167,154,.22);
}
/* Make the "Nexora Tech" title readable on light card */
.brand h1{
  color:#0f2a28 !important;        /* dark ink on light */
  text-shadow:none;                 /* remove glow */
}

/* (Optional) use brand teal instead of ink) */
.brand h1.brand-teal{ color: var(--nx-primary-900) !important; }
/* === Mobile polish (≤480px) === */
@media (max-width: 480px){
  /* allow scrolling when keyboard opens */
  body{ overflow:auto; }

  /* spacing that respects notches */
  .login-wrap{
    place-items: start;
    padding: max(16px, env(safe-area-inset-top)) 16px 24px;
  }

  /* tighter, lighter card */
  .card-glass{
    width: 100%;
    padding: 20px 16px;
    border-radius: 16px;
    box-shadow: 0 10px 24px rgba(0,0,0,.12);
  }

  /* compact brand */
  .brand-badge{ width:60px; height:60px; }
  .brand h1{ font-size: 20px; }

  /* tap-friendly controls + avoid iOS zoom */
  .form-control{
    min-height: 48px;
    font-size: 16px;    /* ≥16px prevents iOS auto-zoom */
  }
  .input-group .btn-toggle{
    min-height: 48px;
    padding: 0 12px;
  }
  .btn-nx{
    padding: .85rem 1rem;
    font-size: 16px;
    border-radius: 12px;
  }

  /* stack helper links nicely */
  .helper{ flex-direction: column; align-items: flex-start; gap: .5rem; }

  /* optional: lighten background processing for low-end phones */
  .hero{ filter: saturate(105%); }
}
/* centered helper row for the create-account link */
.helper.alt{
  justify-content: center;
  gap: .5rem;
  color: var(--nx-muted);
}
.helper .link-create{
  color: var(--nx-primary-700);
  font-weight: 600;
  text-decoration: none;
}
.helper .link-create:hover{ text-decoration: underline; }

  </style>
</head>
<body>
  <div class="hero" aria-hidden="true"></div>

  <main class="login-wrap">
    <section class="card-glass">
      <header class="brand">
        <div class="brand-badge">
          <!-- Use your local logo path -->
          <img src="img/nexora_brand_logo.png" alt="Nexora logo">
        </div>
        <h1>Nexora Tech</h1>
      </header>

      <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-glass mb-3" role="alert">
          <strong>Login failed.</strong> Incorrect username or password.
        </div>
      <?php endif; ?>

      <form action="authenticate.php" method="post" novalidate>
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input class="form-control" type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="username">
        </div>

        <div class="mb-2">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <input class="form-control" type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
            <button class="btn btn-toggle" type="button" id="togglePassword" aria-label="Show/Hide password">
              <i class="fa fa-eye" aria-hidden="true"></i>
            </button>
          </div>
        </div>

        <div class="helper mt-2 mb-3">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
            <label class="form-check-label" for="remember" style="color:var(--nx-muted)">Remember me</label>
          </div>
          <a href="#">Forgot password?</a>
        </div>

        <div class="d-grid">
          <button class="btn btn-nx btn-lg" type="submit">Login</button>
            <!-- Add under the submit button -->
            <div class="helper alt mt-3">
            <span>Don’t have an account?</span>
            <a href="register.php" class="link-create">Create one</a>
            </div>          
        </div>
      </form>
    </section>
  </main>

  <script>
    const toggle = document.getElementById('togglePassword');
    const pwd = document.getElementById('password');
    const icon = toggle.querySelector('i');
    toggle.addEventListener('click', () => {
      const show = pwd.type === 'password';
      pwd.type = show ? 'text' : 'password';
      icon.classList.toggle('fa-eye-slash', show);
      icon.classList.toggle('fa-eye', !show);
      toggle.setAttribute('aria-pressed', String(show));
    });
  </script>
</body>
</html>
