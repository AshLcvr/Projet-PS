<?php
session_start();
include('../inc/pdo.php');
include('inc/fonction.php');

$sql = "SELECT * FROM blog_comments WHERE status='new'";
$query = $pdo->prepare($sql);
$query->execute();
$comments  = $query->fetchAll();

$nbArticles = 4;
$ordre = 'DESC';
$page = 1;

$offset = $nbArticles * $page - $nbArticles;

$sql = "SELECT * FROM blog_users ORDER BY created_at $ordre LIMIT $nbArticles OFFSET $offset";
$query = $pdo->prepare($sql);
$query->execute();
$users  = $query->fetchAll();

$sql = "SELECT * FROM blog_articles ORDER BY created_at $ordre LIMIT $nbArticles OFFSET $offset";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$articles  = $query->fetchAll();

$title = 'Admin Dashboard';

include('inc/header.php');
?>

<div id="contenerGlobal" class="container-fluid">
        <div class="contenerArticle">
            <div class="titre_article"><h1> Derniers articles : </h1></div>
            <?php foreach ($articles as $key => $article) { ?>
                <a class="bloc" href="edit-articles.php?id=<?php echo $article['id']; ?>">
                    <h2><?php echo $article['title']; ?></h2>
                    <div class="minibloc">
                        <img src="asset/img/<?php echo $article['image']; ?>" alt="<?php $article['title']; ?>">
                        <p>Cet article a été créé le <?php echo $article['created_at']; ?></p>
                    </div>
                </a>
            <?php } ?>
        </div>


    <div class="contenerComment">
        <div><h1>Derniers commentaires:</h1></div>
        <?php foreach ($comments as $key => $comment) { ?>
            <div class="bloc">
                <div class="minibloc">
                    <p><?php echo $comment['content']; ?></p>
                    <p> créer le : <?php echo $comment['created_at']; ?></p>
                    <p> status: <?php echo $comment['status']; ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="contenerUsers">
        <div><h1> Dernières inscriptions : </h1></div>
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