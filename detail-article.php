<?php

session_start();

require_once('inc/fonction.php');
include_once('inc/fichier.php');
include_once('inc/pdo.php');


$sql = "SELECT * FROM blog_articles";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$articles  = $query->fetchAll();
debug($articles);

$sql = "SELECT * FROM blog_comments";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$comments  = $query->fetchAll();
debug($comments);

$sql = "SELECT * FROM blog_users";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$users  = $query->fetchAll();
debug($users);



if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $article = getArticleById($id);

    if (empty($article)) {
        die('404');
    }
}else{
    die('404');
}





require_once('inc/header.php'); ?>
<div id="contenerArticle">
    <div class="blocArticle">
        <h1><?php echo $article['title']; ?></h1>
        <div class="minibloc">
            <img src="asset/img/<?php echo $article['image']; ?>" alt="<?php $article['title']; ?>">
            <p><?php echo $article['content']; ?></p>
        </div>
        <h4>Cette article a été créé le <?php echo $article['created_at']; ?></h4>
        <?php if (islogged()) { ?>
            <div class="separator"></div>
            <form class="monForm" action="" method="POST" novalidate>
                <?php echo label('commentaire','Commentaire') ?>
                <textarea placeholder="Laissez un commentaire" name="commentaire"></textarea>
                <input type="submit" name="submitted" value="Envoyer">
            </form>
        <?php } ?>
        <?php foreach ($comments as $key => $comment) { ?>
            <?php if ($article['id'] === $comment['id_article']) { ?>
                <div class="minibloc2">
                    <ul>
                        <?php foreach ($users as $key => $user) { ?>
                            <?php if ($comment['id_article'] === $user['id']) { ?>
                                <p><?php echo $user['pseudo']; ?>:&nbsp;</p>
                            <?php } ?>
                        <?php } ?>
                        <li><?php echo $comment['content']; ?></li>
                    </ul>
                </div>
            <?php } ?>
        <?php } ?>
    </div>
</div>



<?php if (isloggedAdmin()) { ?>
    <a href="edit.php?id=<?php echo $id; ?>">EDIT</a>
<?php } ?>





<?php require_once('inc/footer.php');