<?php
session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit;
}
$userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
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
  <title>Nexora Tech - Services</title>
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
            <?php if (isset($_SESSION['loggedin'])): ?>
                <a href="dashboard.php" class="nav-item nav-link">Dashboard</a>
            <?php endif; ?>               
            <a href="index.php" class="nav-item nav-link">Home</a>
            <a href="about.php" class="nav-item nav-link">About</a>
            <a href="service.php" class="nav-item nav-link active">Services</a>
            <a href="project.php" class="nav-item nav-link">Projects</a>
            <a href="contact.php" class="nav-item nav-link">Contact</a>
          </div>
          
          <?php if (isset($_SESSION['loggedin'])): ?>
              <a href="logout.php" class="btn btn-primary rounded-pill py-2 px-4 ms-lg-5">Logout</a>
          <?php else: ?>
              <a href="login.php" class="btn btn-primary rounded-pill py-2 px-4 ms-lg-5">Login</a>
          <?php endif; ?>
        </div>
      </nav>
    </div>
  </div>
  <div class="container-fluid bg-primary hero-header min-vh-50 d-flex align-items-center">
    <div class="container">
    </div>
  </div>
  <div class="container-fluid py-5">
    <div class="container py-5">
      <div class="row g-5 align-items-center">

        <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
          <h1 class="mb-5">Our Creative <span class="text-uppercase text-primary bg-light px-2">Services</span></h1>
          <p>Our creative services are centered around designing and delivering smart, connected solutions
            that make everyday life easier and business operations more efficient. By harnessing the power
            of IoT, we turn raw data into meaningful insights and seamless control across devices.</p>
          <p class="mb-5">From intelligent home systems to custom automation for businesses, we tailor solutions
            to your needs—combining reliability, user-friendly design, and forward-thinking tech.</p>

          <div class="d-flex align-items-center bg-light">
            <div class="btn-square flex-shrink-0 bg-primary d-flex align-items-center justify-content-center"
              style="width:100px;height:100px;">
              <i class="fa fa-phone fa-2x text-white"></i>
            </div>
            <div class="px-3">
              <h3 class="mb-1">+0123456789</h3>
              <span>Call us 24/7 for a free consultation</span>
            </div>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="row g-4">

            <div class="col-md-6 wow fadeIn" data-wow-delay="0.2s">
              <div class="service-item h-100 d-flex flex-column justify-content-center bg-primary p-4 rounded">
                <div class="mb-3">
                  <i class="fa fa-microchip fa-2x text-white"></i>
                </div>
                <h3 class="mb-2 text-white">Hardware Engineering</h3>
                <p class="mb-0 text-white-50">Sensor/MCU selection, prototyping, and firmware. Deliverables include cost
                  model and implementation roadmap.</p>
              </div>
            </div>

            <div class="col-md-6 wow fadeIn" data-wow-delay="0.4s">
              <div class="service-item h-100 d-flex flex-column justify-content-center bg-light p-4 rounded">
                <div class="mb-3">
                  <i class="fa fa-shield-alt fa-2x text-primary"></i>
                </div>
                <h3 class="mb-2">Security</h3>
                <p class="mb-0">Threat modeling, device identity, key rotation, and hardening. Deliverables: security
                  configs & SOPs.</p>
              </div>
            </div>

            <div class="col-md-6 wow fadeIn" data-wow-delay="0.6s">
              <div class="service-item h-100 d-flex flex-column justify-content-center bg-light p-4 rounded">
                <div class="mb-3">
                  <i class="fa fa-tools fa-2x text-primary"></i>
                </div>
                <h3 class="mb-2">Managed IoT Operations</h3>
                <p class="mb-0">24/7 monitoring, fleet health, OTA updates, and incident response with monthly health
                  reports.</p>
              </div>
            </div>

            <div class="col-md-6 wow fadeIn" data-wow-delay="0.8s">
              <div class="service-item h-100 d-flex flex-column justify-content-center bg-primary p-4 rounded">
                <div class="mb-3">
                  <i class="fa fa-industry fa-2x text-white"></i>
                </div>
                <h3 class="mb-2 text-white">Commercial</h3>
                <p class="mb-0 text-white-50">Retail/facility deployments, dashboards, and integrations (ERP/BI) for
                  measurable business impact.</p>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="container-xxl pb-5">

  </div>
  <div class="container-fluid bg-dark text-white-50 footer pt-5">
    <div class="container py-5">
      <div class="row g-5 text-center text-lg-start">

        <div class="col-lg-4">
          <h1 class="text-white mb-3">NEXORA</h1>
          <p class="mb-0">
            We deliver smart IoT solutions that simplify life and work by connecting devices and data seamlessly for
            smarter living.
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
</body>

</html>