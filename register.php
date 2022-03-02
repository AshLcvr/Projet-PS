<?php
session_start();
require('inc/pdo.php');
require('inc/fonction.php');

if(isLogged()) {
    header('Location: index.php');
}
$errors = [];
if(!empty($_POST['submitted'])) {
    $pseudo    = trim(strip_tags($_POST['pseudo']));
    $mail      = trim(strip_tags($_POST['mail']));
    $password  = trim(strip_tags($_POST['password']));
    $password2 = trim(strip_tags($_POST['password2']));

    $errors = validateText($errors, $pseudo,'pseudo', 3, 140);
    if(empty($errors['pseudo'])) {
        $sql = "SELECT id FROM user WHERE pseudo = :pseudo";
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
        $sql = "SELECT id FROM user WHERE email = :mail";
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
        $sql = "INSERT INTO user (pseudo, email,password, token, created_at, role) VALUES (:pseudo, :mail, :password, '$token', NOW(), 'abonne')";
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
        <form class="wrapform" action="" method="post" novalidate>
            <?= label('psuedo', 'Pseudo:');
            echo inputTextAdd('pseudo', $titre);
            echo spanError('pseudo', $errors); ?>

            <label for="mail">E-mail *</label>
            <input type="email" name="mail" id="mail" value="<?php valueNoReset('mail'); ?>">
            <?= spanError('mail', $errors); ?>

            <label for="password">Mot de passe *</label>
            <input type="password" name="password" id="password">
            <?= spanError('password', $errors); ?>

            <label for="password2">Confirmation mot de passe *</label>
            <input type="password" name="password2" id="password2"><br>

            <input type="submit" name="submitted" value="Inscrivez-Vous">
        </form>
    </div>
<?php include('inc/footer.php');