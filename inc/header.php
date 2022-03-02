<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0", maximum-scale=1.0, minimum-scale=1.0>
    <title><?php if(!empty($title)) {echo $title;}else{ echo 'renseigne le titre'; }; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville&family=Quicksand:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="./asset/css/style.css">
  </head>
  <body>
      <div id="content">
        <header>
            <nav>
                <img src="asset/img/logo1mini.png" alt="">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (islogged()) { ?>
                        <li><a href="index.php">Bienvenue <?php echo $_SESSION['user']['pseudo']; ?></a></li>
                        <li><a href="logout.php">Déconnexion</a></li>
                            <?php if(isloggedAdmin()) {  ?>
                                <li><a href="admin/index.php">Admin</a></li>
                            <?php } ?>
                        <?php } else{ ?>
                        <li><a href="register.php">Inscription</a></li>
                        <li><a href="login.php">Connexion</a></li>
                    <?php }
                    debug($_SESSION);?>
                </ul>
            </nav>
        </header>
