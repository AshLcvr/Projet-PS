
<!DOCTYPE html>
          <html lang="en">

          <head>
              <meta charset="utf-8">
              <meta content="width=device-width, initial-scale=1.0" name="viewport">

              <title>Blog2Ouf - Homepage</title>
              <meta content="" name="description">
              <meta content="" name="keywords">

              <!-- Favicons -->
              <link href="assets/img/favicon.png" rel="icon">
              <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

              <!-- Google Fonts -->
              <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Roboto:300,300i,400,400i,500,500i,700,700i&display=swap" rel="stylesheet">


              <!-- Template Main CSS File -->
              <link href="assets/css/styleperso.css" rel="stylesheet">
              <link href="assets/css/style.css" rel="stylesheet">


              <!-- =======================================================
              * Template Name: Moderna - v4.8.0
              * Template URL: https://bootstrapmade.com/free-bootstrap-template-corporate-moderna/
              * Author: BootstrapMade.com
              * License: https://bootstrapmade.com/license/
              ======================================================== -->
          </head>

          <body>

          <header id="header" class="fixed-top d-flex align-items-center ">
              <div class="header_container ">
                  <img src="assets/img/logo1mini.png" style="border-radius: 50%">
                  <div class="logo">
                      <h1 class="text-light"><a href="index.html"><span>Blog2Ouf</span></a></h1>
                  </div>

                  <nav id="navbar" class="navbar">
                      <ul>
                          <li><a href="index.php">Home</a></li>
                          <?php if(isloggedAdmin()) {  ?>
                              <li><a href="admin/index.php">Admin</a></li>
                          <?php } ?>
                          <?php if (islogged()) {
                              $sql = "SELECT * FROM blog_users WHERE email = :mail";
                              $query = $pdo->prepare($sql);
                              $query->bindValue(':mail', $_SESSION['user']['email'], PDO::PARAM_STR);
                              $query->execute();
                              $user = $query->fetch();?>
                              <li><a href="modif-user.php?email=<?= urlencode($user['email']);?>&token=<?=urlencode($user['token']);?>"> Mon Profil</a></li>
                              <li><a href="logout.php">Déconnexion</a></li>
                          <?php } else{ ?>
                              <li><a href="register.php">Inscription</a></li>
                              <li><a href="login.php">Connexion</a></li>
                          <?php } ?>
                      </ul>
                      <i class="bi bi-list mobile-nav-toggle"></i>
                  </nav>
              </div>
          </header>