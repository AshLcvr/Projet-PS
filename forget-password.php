<?php
require('inc/pdo.php');
require('inc/fonction.php');

$errors = [];
if(!empty($_POST['submitted'])) {
    $mail = trim(strip_tags($_POST['mail']));
    $sql = "SELECT * FROM blog_users WHERE email = :mail";
    $query = $pdo->prepare($sql);
    $query->bindValue(':mail', $mail, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch();
    if(!empty($user)) {
        $urlBase = urlRemovelast( "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
        $href = $urlBase . '/modif-password.php?email=' . urlencode($mail) . '&token=' . urlencode($user['token']);
        echo '<a href="'.$href.'">Un mail vient de vous être envoyé.. Ou pas.</a> ';
        die();
    } else {
        $errors['mail'] = 'Ce mail n\'existe pas';
    }
}
include('inc/header.php'); ?>
    <div class="wrap">
        <h2>Mot de passe oublié</h2>
        <form class="center monForm" action="" method="post" novalidate>
            <div class="bloc">
                <?= label('mail', 'Email :') ?>
                <input type="email" name="mail" id="mail" value="<?php valueNoReset('mail'); ?>">
                <?= spanError('mail', $errors); ?>
            </div>
            <input type="submit" name="submitted" value="Envoyer">
        </form>
    </div>
<?php include('inc/footer.php');