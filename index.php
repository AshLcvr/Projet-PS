<?php

session_start();

require_once('inc/fonction.php');
include_once('inc/fichier.php');
include_once('inc/pdo.php');


$error = array();
$commentaire = '';


$sql = "SELECT * FROM blog_articles";
$query = $pdo->prepare($sql);
$query->execute();
$articles  = $query->fetchAll();


$sql = "SELECT * FROM blog_comments";
$query = $pdo->prepare($sql);
$query->execute();
$comments  = $query->fetchAll();


$sql = "SELECT * FROM blog_users";
$query = $pdo->prepare($sql);
$query->execute();
$users  = $query->fetchAll();


if (!empty($_POST['submitted'])) {
    $commentaire = trim(strip_tags($_POST['commentaire']));


    // Validation
    if (!islogged()) {
        header('Location: connexion.php'); 
    }

   

   
    
    // if no error
    if (count($error) == 0) {

    }

}



require_once('inc/header.php'); ?>

<div id="contenerArticles">

    <?php foreach ($articles as $key => $article) { ?>
        <div class="bloc bloc<?php echo $key; ?>">
            <a class="minibloc1" href="detail-article.php?id=<?php echo $article['id']; ?>">
                <img src="asset/img/<?php echo $article['image']; ?>" alt="<?php $article['title']; ?>">
                <h2><?php echo $article['title']; ?></h2>
            </a>
            <h4>Cette article a été créé le <?php echo $article['created_at']; ?></h4>
            <?php if (!$article['created_at'] === $article['modified_at']) { ?>
                <p><?php echo $article['modified']; ?></p>
            <?php } ?>
            <?php if (islogged()) { ?>
                <div class="separator"></div>
                <form class="monForm" action="" method="POST" novalidate>
                    <?php echo label('commentaire','Commentaire') ?>
                    <textarea placeholder="Laissez un commentaire" name="commentaire"></textarea>
                    <input type="submit" name="submitted" value="Envoyer">
                </form>
            <?php } ?>
            <?php foreach ($comments as $key => $comment) { ?>
                <div class="minibloc2">
                    <ul>
                        <?php if ($article['id'] === $comment['id_article']) { ?>
                            <?php foreach ($users as $key => $user) { ?>
                                <?php if ($comment['id_article'] === $user['id']) { ?>
                                    <p><?php echo $user['pseudo']; ?>:&nbsp;</p>
                                <?php } ?>
                            <?php } ?>
                            <li><?php echo $comment['content']; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            
        </div>
    <?php } ?>


</div>


<?php require_once('inc/footer.php');

