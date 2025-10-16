<?php /* success.php */ ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Account Created — Nexora Tech</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
  <style>
    :root{
      --nx-primary:#11a79a; --nx-primary-700:#0b7f73;
      --nx-fg:#e8f6f3; --nx-muted:#98b8b3;

      /* same background as login/register */
      --nx-bg:#070f10;
      --nx-bg-image: image-set(
        url("img/nexora-dark-teal-grid.webp") type("image/webp") 1x,
        url("img/nexora-dark-teal-grid@2x.webp") type("image/webp") 2x,
        url("img/nexora-dark-teal-grid.jpg") type("image/jpeg") 1x
      );
    }

    html,body{height:100%;}
    body{
      font-family:Inter,system-ui,sans-serif;
      background:var(--nx-bg);
      color:var(--nx-fg);
      overflow:hidden;
    }
    .hero{
      position:fixed; inset:0; z-index:0;
      background:
        radial-gradient(1200px 600px at 70% 15%, rgba(17,167,154,.18), transparent 60%),
        radial-gradient(1200px 600px at 25% 85%, rgba(33,199,183,.14), transparent 60%),
        var(--nx-bg-image) center/cover no-repeat;
      filter:saturate(110%);
    }
    .hero::after{
      content:""; position:absolute; inset:0;
      background:
        radial-gradient(1200px 600px at 50% 40%, transparent 40%, rgba(0,0,0,.45) 100%),
        linear-gradient(to bottom, rgba(0,0,0,.35), rgba(0,0,0,.55));
    }

    .wrap{min-height:100dvh; display:grid; place-items:center; padding:24px; position:relative; z-index:1;}
    .card-pane{
      width:min(440px,92vw);
      background:#fff; color:#0f2a28;
      border:1px solid rgba(0,0,0,.06);
      border-radius:16px;
      box-shadow:0 16px 40px rgba(0,0,0,.18);
      padding:26px 22px;
      text-align:center;
    }

    .check{
      width:84px; height:84px; margin:0 auto 10px;
      display:block; border-radius:50%;
      background: radial-gradient(60% 60% at 50% 35%, #e9fbf7, #d1f6ef);
      box-shadow:0 10px 28px rgba(17,167,154,.25), inset 0 0 0 6px rgba(17,167,154,.18);
      position:relative;
    }
    .check svg{
      position:absolute; inset:0; margin:auto; width:44px; height:44px;
      stroke:#11a79a; stroke-width:6; fill:none; stroke-linecap:round; stroke-linejoin:round;
      stroke-dasharray:48; stroke-dashoffset:48; animation:draw .7s ease forwards .2s;
    }
    @keyframes draw{ to{ stroke-dashoffset:0; } }

    h2{ font-family:"Space Grotesk",system-ui,sans-serif; margin:6px 0 4px; }
    p.muted{ color:#5b6f6b; margin-bottom:18px; }

    .btn-nx{
      background:var(--nx-primary); border-color:var(--nx-primary);
      color:#031110; font-weight:700; border-radius:12px; padding:.85rem 1rem;
      box-shadow:0 6px 16px rgba(17,167,154,.22);
    }
    .btn-nx:hover{ background:#0b7f73; border-color:#0b7f73; }

    @media (max-width:480px){
      body{overflow:auto;}
      .card-pane{padding:20px 16px; border-radius:14px;}
    }
  </style>
</head>
<body>
  <div class="hero" aria-hidden="true"></div>

  <main class="wrap">
    <section class="card-pane">
      <span class="check">
        <svg viewBox="0 0 52 52" aria-hidden="true">
          <path d="M14 27 l8 8 l16 -16"></path>
        </svg>
      </span>

      <h2>Account created</h2>
      <p class="muted">
        Welcome, <strong><?php echo htmlspecialchars($_GET['u'] ?? ''); ?></strong>.
      </p>

      <a class="btn btn-nx btn-lg" href="login.php">Go to Login</a>

      <!-- Optional: auto-redirect after 3s -->
      <!-- <script> setTimeout(()=>location.href='login.php', 3000); </script> -->
    </section>
  </main>
</body>
</html>
