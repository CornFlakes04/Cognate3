<?php
// Start the session and check if the user is logged in.
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
    
}
$userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;

// NEW: Dynamic Greeting Logic
date_default_timezone_set('Asia/Manila'); // Set timezone to Philippines
$hour = date('G');
if ($hour >= 5 && $hour < 12) {
    $greeting = "Good Morning";
} elseif ($hour >= 12 && $hour < 18) {
    $greeting = "Good Afternoon";
} else {
    $greeting = "Good Evening";
}
?>
<?php
// after you start session and know $userId
require __DIR__ . '/db.php';

$stmt = $pdo->prepare('SELECT theme FROM users WHERE id = ?');
$stmt->execute([$userId]);
$theme = $stmt->fetchColumn() ?: 'system';

// Map 'system' to the user’s OS at runtime via CSS; keep attribute as-is
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($theme) ?>">


<head>
  <meta charset="utf-8">
  <title>Nexora Tech - Dashboard</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <link href="img/favicon.ico" rel="icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Space+Grotesk&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link rel="stylesheet" href="css/theme.css">

  <!-- (Added) Small style touch for the preferences card; safe to keep inline -->
  <style>
    .prefs-note{color:var(--muted);}
  </style>
</head>

<body>
  <div id="spinner"
    class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
  <div class="container-fluid sticky-top">
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light border-bottom border-2 border-white">
        <a href="index.php" class="navbar-brand">
          <h1>Nexora Tech</h1>
        </a>
        <button type="button" class="navbar-toggler ms-auto me-0" data-bs-toggle="collapse"
          data-bs-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <div class="navbar-nav ms-auto">
            <a href="dashboard.php" class="nav-item nav-link active">Dashboard</a>            
            <a href="index.php" class="nav-item nav-link">Home</a>
            <a href="about.php" class="nav-item nav-link">About</a>
            <a href="service.php" class="nav-item nav-link">Services</a>
            <a href="project.php" class="nav-item nav-link">Projects</a>
            <a href="contact.php" class="nav-item nav-link">Contact</a>
          </div>
          <a href="logout.php" class="btn btn-primary rounded-pill py-2 px-4 ms-lg-5">Logout</a>
        </div>
      </nav>
    </div>
  </div>
  
  <div class="container my-5">
    <div class="p-5 bg-light rounded-3 wow fadeIn" data-wow-delay="0.1s">
        <h1 class="display-4"><?= $greeting ?>, <span class="text-primary"><?=htmlspecialchars($_SESSION['name'])?></span>!</h1>
        <p class="lead">This is your personal dashboard. Use the quick links below to navigate the site.</p>
        <hr class="my-4">
        
        <?php
        $quotes = [
            "The best way to predict the future is to invent it. - Alan Kay",
            "It's not a bug, it's an undocumented feature. - Anonymous",
            "Walking on water and developing software from a specification are easy if both are frozen. - Edward V. Berard",
            "Code is like humor. When you have to explain it, it’s bad. - Cory House",
            "Talk is cheap. Show me the code. - Linus Torvalds",
            "Measuring programming progress by lines of code is like measuring aircraft building progress by weight. - Bill Gates",
            "Any sufficiently advanced technology is indistinguishable from magic. - Arthur C. Clarke",
            "First, solve the problem. Then, write the code. - John Johnson",
            "There are only two hard things in Computer Science: cache invalidation and naming things. - Phil Karlton"
        ];
        $randomQuote = $quotes[array_rand($quotes)];
        ?>
        <div class="alert alert-info" role="alert">
          <h4 class="alert-heading">Quote of the Day</h4>
          <p class="mb-0">"<?= $randomQuote ?>"</p>
        </div>

        <!-- (Added) User Preferences: Theme -->
        <div class="card mt-4">
          <div class="card-body">
            <h5 class="card-title mb-3"><i class="bi bi-palette me-2"></i>User Preferences</h5>
            <div class="row g-3 align-items-end">
              <div class="col-sm-6 col-md-4">
                <label for="prefTheme" class="form-label">Theme</label>
                <select id="prefTheme" class="form-select">
                  <option value="system" <?= $theme==='system'?'selected':''; ?>>System (follow device)</option>
                  <option value="light"  <?= $theme==='light' ?'selected':''; ?>>Light</option>
                  <option value="dark"   <?= $theme==='dark'  ?'selected':''; ?>>Dark</option>
                </select>
              </div>
              <div class="col-sm-auto">
                <button id="saveTheme" class="btn btn-primary">Save</button>
              </div>
              <div class="col-12">
                <small class="prefs-note" id="themeMsg" role="status"></small>
              </div>
            </div>
          </div>
        </div>
        <!-- /User Preferences -->
    </div>
  </div>
  <div class="container pb-5">
    <div class="text-center wow fadeIn" data-wow-delay="0.1s">
        <h2 class="mb-5">Dashboard Links</h2>
    </div>
    <div class="row g-4 justify-content-center">
        <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.2s">
            <div class="card h-100 text-center">
                <div class="card-body d-flex flex-column">
                    <i class="fa fa-user-circle fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">My Profile</h5>
                    <ul class="list-group list-group-flush mt-3">
                        <li class="list-group-item"><strong>Username:</strong> <?=htmlspecialchars($_SESSION['name'])?></li>
                        <li class="list-group-item"><strong>User ID:</strong> <?=htmlspecialchars($_SESSION['id'])?></li>
                        <li class="list-group-item">
                            <strong>Last Login:</strong>
                            <?php
                            if (isset($_SESSION['last_login']) && $_SESSION['last_login'] !== null) {
                                $date = new DateTime($_SESSION['last_login']);
                                echo $date->format('F j, Y, g:i A');
                            } else {
                                echo 'This is your first login!';
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.4s">
            <div class="card h-100 text-center">
                <div class="card-body d-flex flex-column">
                    <i class="fa fa-cogs fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Our Services</h5>
                    <p class="card-text">Discover the creative services that we offer to our clients.</p>
                    <a href="service.php" class="btn btn-primary mt-auto">Go to Services</a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 wow fadeIn" data-wow-delay="0.6s">
            <div class="card h-100 text-center">
                <div class="card-body d-flex flex-column">
                    <i class="fa fa-project-diagram fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">View Projects</h5>
                    <p class="card-text">See a gallery of our latest and most innovative projects.</p>
                    <a href="project.php" class="btn btn-primary mt-auto">Go to Projects</a>
                </div>
            </div>
        </div>
    </div>
  </div>
  
  
  <div class="container-fluid bg-dark text-white-50 footer pt-5">
    <div class="container py-5">
      <div class="row g-5 text-center text-lg-start">
        <div class="col-lg-4">
          <h1 class="text-white mb-3">NEXORA</h1>
          <p class="mb-0">
            We deliver smart IoT solutions that simplify life and work by connecting devices and data seamlessly.
          </p>
        </div>
        <div class="col-lg-4">
          <h5 class="text-white mb-4">Get In Touch</h5>
          <p><i class="fa fa-map-marker-alt me-3"></i>123 Street, Pasig City, Philippines</p>
          <p><i class="fa fa-phone-alt me-3"></i>+63 912 345 6789</p>
          <p><i class="fa fa-envelope me-3"></i>nexora@tech.com</p>
        </div>
        <div class="col-lg-4">
          <h5 class="text-white mb-4">Popular Link</h5>
          <a class="btn btn-link" href="about.php">About Us</a>
          <a class="btn btn-link" href="contact.php">Contact Us</a>
          <a class="btn btn-link" href="project.php">Projects</a>
        </div>
      </div>
    </div>
  </div>
  <a href="#!" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/waypoints/waypoints.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <script src="js/main.js"></script>

  <!-- (Added) Theme preference save/apply -->
  <script>
    (function(){
      const sel = document.getElementById('prefTheme');
      const btn = document.getElementById('saveTheme');
      const msg = document.getElementById('themeMsg');

      if(!sel || !btn) return;

      function apply(val){
        document.documentElement.setAttribute('data-theme', val);
      }

      btn.addEventListener('click', async function(){
        const val = sel.value;
        apply(val); // instant switch
        if(msg){ msg.textContent = 'Saving…'; }

        try{
          const r = await fetch('update_theme.php', {
            method:'POST',
            headers:{'Content-Type':'application/json'},
            body: JSON.stringify({ theme: val })
          });
          if(!r.ok) throw new Error('HTTP ' + r.status);
          if(msg){ msg.textContent = 'Saved.'; }
        }catch(e){
          if(msg){ msg.textContent = 'Saved locally. Server save failed or endpoint missing.'; }
          // still keep the applied theme visually
        }
      });
    })();
  </script>
</body>

</html>
