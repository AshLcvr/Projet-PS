<?php
require('inc/pdo.php');
require('inc/fonction.php');
require('inc/request.php');

$errors = [];

if(!empty($_GET['email']) && !empty($_GET['token'])) {
    $email = urldecode($_GET['email']);
    $token = urldecode($_GET['token']);

    $sql = "SELECT * FROM user WHERE email = :email AND token = :token";
    $query = $pdo->prepare($sql);
    $query->bindValue(':email', $email, PDO::PARAM_STR);
    $query->bindValue(':token', $token, PDO::PARAM_STR);
    $query-> execute();
    $user = $query->fetch();
    if(empty($user)) {
        header('Location: index.php');
    }
} else {
    header('Location: index.php');
}


if(!empty($_POST['submitted'])) {

    $password  = trim(strip_tags($_POST['password']));
    $password2 = trim(strip_tags($_POST['password2']));

    // password // => 6 caractères au minimum, identiques et renseigné
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
        $sql = "UPDATE user SET password = :hash, token = :token WHERE id = :id";
        $query = $pdo->prepare($sql);
        $query->bindValue(':hash', $hashpassword, PDO::PARAM_STR);
        $query->bindValue(':token', $token, PDO::PARAM_STR);
        $query->bindValue(':id', $user['id'], PDO::PARAM_INT);
        $query-> execute();

        // UPDATE
        header('Location: login.php');
    }
}


include('inc/header.php'); ?>
    <div class="wrap">
        <h2>Modification  du profil</h2>
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
    </div>
<?php include('inc/footer.php');