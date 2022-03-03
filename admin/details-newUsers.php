<?php
session_start();
include('../inc/pdo.php');
include('inc/fonction.php');

$nbArticles = 4;
$ordre = 'DESC';

$sql = "SELECT * FROM blog_users ORDER BY created_at $ordre LIMIT $nbArticles";
$query = $pdo->prepare($sql);
$query->execute();
$users  = $query->fetchAll();


$title = 'Admin Dashboard';

include('inc/header.php');
?>

<h1> Bienvenue sur le Back-Office</h1>
<div id="contenerGlobal">
<div class="contenerUsers">
        <?php foreach ($users as $key => $user) { ?>
                <a class="bloc" href="edit-users.php?id=<?php echo $user['id']; ?>">
                    <p><?php echo $user['pseudo']; ?></p>
                    <p><?php echo $user['created_at']; ?></p>
                </a>
        <?php } ?>
    </div>
</div>

<?php
include('inc/footer.php');