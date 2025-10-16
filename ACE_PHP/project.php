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
  <title>Nexora Tech - Projects</title>
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
          <h1>Nexora Tech | <span class="text-primary">Projects</span></h1>
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
            <a href="service.php" class="nav-item nav-link">Services</a>
            <a href="project.php" class="nav-item nav-link active">Projects</a>
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
  <div class="container-fluid mt-5">
    <div class="container mt-5">
      <div class="row g-0">
        <div class="col-lg-5 wow fadeIn" data-wow-delay="0.1s">
          <div class="d-flex flex-column justify-content-center bg-primary h-100 p-5">
            <h1 class="text-white mb-5">Our Latest <span
                class="text-uppercase text-primary bg-light px-2">Projects</span></h1>
            <h4 class="text-white mb-0"><span class="display-1">6</span> of our latest projects</h4>
          </div>
        </div>

        <div class="col-lg-7">
          <div class="row g-0">

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.2s">
              <div class="project-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/project-1.jpg" alt="">
                <a class="project-overlay text-decoration-none" href="#" data-bs-toggle="modal"
                  data-bs-target="#projectVideoModal" data-video="img/data.mp4" data-title="Data">
                  <h4 class="text-white">Data</h4>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
              <div class="project-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/project-2.jpg" alt="">
                <a class="project-overlay text-decoration-none" href="#" data-bs-toggle="modal"
                  data-bs-target="#projectVideoModal" data-video="img/bionic.mp4" data-title="Bionic">
                  <h4 class="text-white">Bionic</h4>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.4s">
              <div class="project-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/project-3.jpg" alt="">
                <a class="project-overlay text-decoration-none" href="#" data-bs-toggle="modal"
                  data-bs-target="#projectVideoModal" data-video="img/listen.mp4" data-title="Listen">
                  <h4 class="text-white">Listen</h4>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
              <div class="project-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/project-4.jpg" alt="">
                <a class="project-overlay text-decoration-none" href="#" data-bs-toggle="modal"
                  data-bs-target="#projectVideoModal" data-video="img/vision.mp4" data-title="Vision">
                  <h4 class="text-white">Vision</h4>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.6s">
              <div class="project-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/project-5.jpg" alt="">
                <a class="project-overlay text-decoration-none" href="#" data-bs-toggle="modal"
                  data-bs-target="#projectVideoModal" data-video="img/control.mp4" data-title="Control">
                  <h4 class="text-white">Control</h4>
                </a>
              </div>
            </div>

            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.7s">
              <div class="project-item position-relative overflow-hidden">
                <img class="img-fluid w-100" src="img/project-6.jpg" alt="">
                <a class="project-overlay text-decoration-none" href="#" data-bs-toggle="modal"
                  data-bs-target="#projectVideoModal" data-video="img/sensors.mp4" data-title="Sensors">
                  <h4 class="text-white">Sensors</h4>
                </a>
              </div>
            </div>

          </div>
        </div>

      </div>
    </div>
  </div>
  <div class="modal fade" id="projectVideoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content bg-dark">
        <div class="modal-header border-0">
          <h5 class="modal-title text-white" id="projectVideoTitle">Project Video</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          <div class="ratio ratio-16x9">
            <video id="projectVideo" class="w-100 h-100" controls preload="metadata" playsinline>
              <source id="projectVideoSource" src="" type="video/mp4">
              Your browser does not support HTML5 video.
            </video>
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
          <div class="d-flex justify-content-center justify-content-lg-start pt-2">
            <a class="btn btn-outline-primary btn-square border-2 me-2" href="#"><i class="fab fa-facebook-f"></i></a>
            <a class="btn btn-outline-primary btn-square border-2 me-2" href="#"><i class="fab fa-instagram"></i></a>
          </div>
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