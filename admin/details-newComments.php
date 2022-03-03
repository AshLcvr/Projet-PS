<?php
session_start();
include('../inc/pdo.php');
include('inc/fonction.php');

$sql = "SELECT * FROM blog_comments WHERE status='new'";
$query = $pdo->prepare($sql);
$query->execute();
$newcomments  = $query->fetchAll();


$title = 'Admin Dashboard';

include('inc/header.php');
?>

<h1> Bienvenue sur le Back-Office</h1>
<div id="contenerGlobal">
    <div class="contenerComment">
        <?php foreach ($newcomments as $key => $comment) { ?>
            <div class="bloc">
                <a href="delete-comments.php?id=<?php echo $comment['id']; ?>">delete</a>
                    <div class="minibloc">
                        <p><?php echo $comment['content']; ?></p>
                        <p> créer le : <?php echo $comment['created_at']; ?></p>
                        <p> status: <?php echo $comment['status']; ?></p>
                    </div>
                <a href="publish-comments.php?id=<?php echo $comment['id']; ?>">publier</a>
            </div>
        <?php } ?>
    </div>
</div>

<?php
include('inc/footer.php');