<?php
session_start();
include('../inc/pdo.php');
include('inc/fonction.php');


$nbArticles = 4;
$ordre = 'DESC';
$sql = "SELECT * FROM blog_articles ORDER BY created_at $ordre LIMIT $nbArticles";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$articles  = $query->fetchAll();


$title = 'Admin Dashboard';

include('inc/header.php');
?>

<h1> Bienvenue sur le Back-Office</h1>
<div id="contenerGlobal">
    <div class="contenerArticle">
        <?php foreach ($articles as $key => $article) { ?>
            <a class="bloc" href="../detail-article.php?id=<?php echo $article['id']; ?>">
                <h1><?php echo $article['title']; ?></h1>
                <img src="asset/img/<?php echo $article['image']; ?>" alt="<?php $article['title']; ?>">
                <h4>Cette article a été créé le <?php echo $article['created_at']; ?></h4>
            </a>
        <?php } ?>
    </div>
</div>

<?php
include('inc/footer.php');