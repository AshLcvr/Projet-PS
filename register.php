<?php
session_start();
require('inc/pdo.php');
require('inc/fonction.php');

if(isLogged()) {
    header('Location: index.php');
}
$title = 'Inscription';
$errors = [];
$pseudo = '';
$mail = '';
$password = '';
$password2 = '';
if(!empty($_POST['submitted'])) {
    $pseudo    = trim(strip_tags($_POST['pseudo']));
    $mail      = trim(strip_tags($_POST['mail']));
    $password  = trim(strip_tags($_POST['password']));
    $password2 = trim(strip_tags($_POST['password2']));

    $errors = validateText($errors, $pseudo,'pseudo', 3, 140);
    if(empty($errors['pseudo'])) {
        $sql = "SELECT id FROM blog_users WHERE pseudo = :pseudo";
        $query = $pdo->prepare($sql);
        $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $query->execute();
        $pseudoExist = $query->fetch();
        if(!empty($pseudoExist)) {
            $errors['pseudo'] = 'Pseudo déjà pris';
        }
    }

    $errors = validateEmail($errors, $mail, 'mail');
    if(empty($errors['mail'])) {
        $sql = "SELECT id FROM blog_users WHERE email = :mail";
        $query = $pdo->prepare($sql);
        $query->bindValue(':mail', $mail, PDO::PARAM_STR);
        $query->execute();
        $emailExist = $query->fetch();
        if(!empty($emailExist)) {
            $errors['mail'] = 'E-mail déjà pris';
        }
    }

    if(!empty($password) && !empty($password2)) {
        if(mb_strlen($password) < 6) {
            $errors['password'] = 'Votre mot de passe est trop court(min 6)';
        }  elseif ($password !== $password2) {
            $errors['password'] = 'Vos mot de passe sont différents';
        }
    } else {
        $errors['password'] = 'Veuillez renseigner les deux mots de passe';
    }

    if(count($errors) === 0) {
        $hashpassword = password_hash($password, PASSWORD_DEFAULT);
        $token = generateRandomString(70);
        $sql = "INSERT INTO blog_users (pseudo, email,password, token, created_at, role) VALUES (:pseudo, :mail, :password, '$token', NOW(), 'abonné')";
        $query = $pdo->prepare($sql);
        $query->bindValue(':pseudo', $pseudo, PDO::PARAM_STR);
        $query->bindValue(':mail', $mail, PDO::PARAM_STR);
        $query->bindValue(':password', $hashpassword, PDO::PARAM_STR);
        $query->execute();
        header('Location: login.php');
    }

}

include('inc/header.php'); ?>

    <div class="wrap">
        <h2>Inscription</h2>
        <form class="center monForm" action="" method="post" novalidate>

            <div class="bloc">
                <?= label('pseudo', 'Pseudo:');
                echo inputTextAdd('pseudo', $pseudo);
                echo spanError('pseudo', $errors); ?>
            </div>
            <div class="bloc">
                <?= label('mail', 'Email :') ?>
                <input type="email" name="mail" id="mail" value="<?php valueNoReset('mail'); ?>">
                <?= spanError('mail', $errors); ?>
            </div>
            <div class="bloc">
                <?= label('password', 'Mot de passe :') ?>
                <input type="password" name="password" id="password">
                <?= spanError('password', $errors); ?>
            </div>
            <div class="bloc">
                <?= label('password2', 'Confirmation mot de passe :') ?>
                <input type="password" name="password2" id="password2"><br>
            </div>

            <input type="submit" name="submitted" value="Inscrivez-Vous">
        </form>
    </div>
<?php include('inc/footer.php');