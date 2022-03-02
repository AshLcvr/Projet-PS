<?php
session_start();
require('inc/pdo.php');
require('inc/fonction.php');

if(isLogged()) {
    header('Location: index.php');
}
$login = '';
$password = '';
$errors = [];
if(!empty($_POST['submitted'])) {
    $login    = trim(strip_tags($_POST['login']));
    $password = trim(strip_tags($_POST['password']));
    $sql = "SELECT * FROM blog_users WHERE pseudo = :login OR email = :login";
    $query = $pdo->prepare($sql);
    $query->bindValue(':login', $login, PDO::PARAM_STR);
    $query-> execute();
    $user = $query->fetch();
    if(!empty($user)) {
        if(password_verify($password, $user['password'] )) {
            $_SESSION['user'] = array(
                'id'     => $user['id'],
                'pseudo' => $user['pseudo'],
                'email'  => $user['email'],
                'role'   => $user['role'],
                'ip'     => $_SERVER['REMOTE_ADDR']
            );
            if($user['role'] === 'admin'){
                header('Location: admin/index.php');
            }else{
                header('Location: index.php');
            }

        } else {
            $errors['login'] = 'Error credentials';
        }
    } else {
        $errors['login'] = 'Error credentials';
    }
}
include('inc/header.php'); ?>
    <div class="wrap">
        <h2>Connexion</h2>
        <form class="monForm center" action="" method="post" novalidate>
            <div class="bloc">
                <?= label('login', 'Pseudo ou mail:');
                echo inputTextAdd('login', $login);
                echo spanError('login', $errors); ?>
            </div>

            <div class="bloc">
                <?= label('password', 'Mot de passe :') ?>
                <input type="password" name="password" id="password">
                <?= spanError('password', $errors); ?>
            </div>

            <input type="submit" name="submitted" value="Connectez-Vous">
            <p><a href="forget-password.php">Mot de passe oublié</a></p>
        </form>
    </div>
<?php include('inc/footer.php');