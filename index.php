<?php session_start(); ?>
<?php
// after you start session and know $userId
require __DIR__ . '/db.php';
$userId = isset($_SESSION['id']) ? (int)$_SESSION['id'] : 0;
$stmt = $pdo->prepare('SELECT theme FROM users WHERE id = ?');
$stmt->execute([$userId]);
$theme = $stmt->fetchColumn() ?: 'system';

// Map 'system' to the user’s OS at runtime via CSS; keep attribute as-is
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= htmlspecialchars($theme) ?>">


<head>
  <meta charset="utf-8">
  <title>Nexora Tech</title>
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
            <a href="index.php" class="nav-item nav-link active">Home</a>
            <a href="about.php" class="nav-item nav-link">About</a>
            <a href="service.php" class="nav-item nav-link">Services</a>
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
  <div class="container-fluid pb-5 hero-header bg-light mb-5">
    <div class="container py-5">
      <div class="row g-5 align-items-center mb-5">
        <div class="col-lg-6">
          <h1 class="display-1 mb-4 animated slideInRight">We Make Your <span class="text-primary">Business</span>
            Better</h1>
        </div>
        <div class="col-lg-6">
          <div class="owl-carousel header-carousel animated fadeIn">
            <img class="img-fluid" src="img/Slide1.jpg" alt="">
            <img class="img-fluid" src="img/Slide2.jpg" alt="">
            <img class="img-fluid" src="img/Slide3.jpg" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid py-5">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6">
          <div class="row">
            <div class="col-6 wow fadeIn" data-wow-delay="0.1s">
              <img class="img-fluid w-100" src="img/about-1.jpg" alt="">
            </div>
            <div class="col-6 wow fadeIn" data-wow-delay="0.3s">
              <img class="img-fluid h-75 w-100" src="img/about-2.jpg" alt="">
              <div class="h-25 d-flex align-items-center text-center bg-primary px-4">
                <h4 class="text-white lh-base mb-0">Nexora Tech</h4>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
          <h1 class="mb-5"><span class="text-uppercase text-primary bg-light px-2">History</span> of Our
            Creation</h1>
          <p class="mb-4">We specialize in creating smart, connected solutions that make everyday life easier.</p>
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
            We deliver smart IoT solutions that simplify life and work.
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