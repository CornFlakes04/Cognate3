<?php /* register.php */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Create Account — Nexora Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
  <style>
    :root{
      --nx-primary:#11a79a; --nx-primary-700:#0b7f73;
      --nx-fg:#e8f6f3;                 /* on-dark default */
      --nx-muted:#98b8b3;
      --border:#d7e0de;

      /* Background to match login */
      --nx-bg:#070f10;                 /* deep teal-black */
      --nx-bg-image: image-set(
        url("img/nexora-dark-teal-grid.webp") type("image/webp") 1x,
        url("img/nexora-dark-teal-grid@2x.webp") type("image/webp") 2x,
        url("img/nexora-dark-teal-grid.jpg") type("image/jpeg") 1x
      );
    }

    /* Page + background layer */
    html, body { height: 100%; }
    body{
      font-family:Inter,system-ui,sans-serif;
      background: var(--nx-bg);
      color: var(--nx-fg);
      overflow:hidden;
    }
    .hero{
      position:fixed; inset:0;
      background:
        radial-gradient(1200px 600px at 70% 15%, rgba(17,167,154,.18), transparent 60%),
        radial-gradient(1200px 600px at 25% 85%, rgba(33,199,183,.14), transparent 60%),
        var(--nx-bg-image) center/cover no-repeat;
      filter: saturate(110%);
      z-index:0;
    }
    .hero::after{
      content:""; position:absolute; inset:0;
      background:
        radial-gradient(1200px 600px at 50% 40%, transparent 40%, rgba(0,0,0,.45) 100%),
        linear-gradient(to bottom, rgba(0,0,0,.35), rgba(0,0,0,.55));
    }

    /* Foreground */
    .wrap{min-height:100dvh;display:grid;place-items:center;padding:24px; position:relative; z-index:1;}
    .card-pane{
      width:min(440px,92vw);
      background:#fff;                 /* light card on dark bg */
      color:#0f2a28;
      border:1px solid rgba(0,0,0,.06);
      border-radius:16px;
      box-shadow:0 16px 40px rgba(0,0,0,.18);
      padding:24px 20px;
    }
    .brand{display:flex;flex-direction:column;align-items:center;gap:.5rem;margin-bottom:.5rem;}
    .brand-badge{width:64px;height:64px;border-radius:50%;display:grid;place-items:center;background:#eef6f5;border:1px solid #e1efec;}
    .brand h1{font-family:"Space Grotesk",system-ui,sans-serif;font-size:24px;margin:0;color:#0f2a28;}
    .form-label{color:#3b4a47;font-weight:600;}
    .form-control{
      background:#fff;color:#0f2a28;border:1px solid var(--border);
      border-radius:12px;min-height:48px;font-size:16px;
    }
    .form-control:focus{border-color:var(--nx-primary);box-shadow:0 0 0 .2rem rgba(17,167,154,.18);}
    .btn-nx{
      background:var(--nx-primary);border-color:var(--nx-primary);color:#031110;
      font-weight:700;border-radius:12px;padding:.85rem 1rem;
      box-shadow:0 6px 16px rgba(17,167,154,.22);
    }
    .btn-nx:hover{background:#0b7f73;border-color:#0b7f73;}
    .helper{display:flex;justify-content:center;gap:.5rem;color:#5b6f6b;margin-top:.5rem;}
    .error{background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.25);color:#7d2121;border-radius:12px;padding:10px 12px;}
    @media (max-width:480px){ .card-pane{padding:18px 14px;border-radius:14px;} .brand h1{font-size:20px;} body{overflow:auto;} }
  </style>
</head>
<body>
  <!-- background layer -->
  <div class="hero" aria-hidden="true"></div>

  <main class="wrap">
    <section class="card-pane">
      <header class="brand">
        <div class="brand-badge"><img src="img/nexora_brand_logo.png" alt="Nexora" style="width:40px;height:40px;object-fit:contain"></div>
        <h1>Create Account</h1>
      </header>

      <?php if (!empty($_GET['err'])): ?>
        <div class="error mb-3">
          <?php echo htmlspecialchars($_GET['err']); ?>
        </div>
      <?php endif; ?>

      <form action="process_register.php" method="post" novalidate>
        <div class="mb-3">
          <label class="form-label" for="username">Username</label>
          <input class="form-control" id="username" name="username" maxlength="50" required placeholder="Choose a username" autocomplete="username">
        </div>
        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input class="form-control" id="email" name="email" type="email" maxlength="100" required placeholder="you@example.com" autocomplete="email">
        </div>
        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input class="form-control" id="password" name="password" type="password" required minlength="8" placeholder="At least 8 characters" autocomplete="new-password">
        </div>
        <div class="mb-3">
          <label class="form-label" for="confirm">Confirm Password</label>
          <input class="form-control" id="confirm" name="confirm" type="password" required minlength="8" placeholder="Retype your password" autocomplete="new-password">
        </div>
        <div class="d-grid">
          <button class="btn btn-nx" type="submit">Create account</button>
        </div>
      </form>

      <div class="helper">
        <span>Already have an account?</span>
        <a href="login.php">Log in</a>
      </div>
    </section>
  </main>
</body>
</html>
