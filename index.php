<?php

session_start();

require_once('inc/fonction.php');
include_once('inc/fichier.php');
include_once('inc/pdo.php');

$title= 'Le blog de ouf';
$error = array();
$commentaire = '';


$sql = "SELECT * FROM blog_comments";
$query = $pdo->prepare($sql);
$query->execute();
$comments  = $query->fetchAll();

$sql = "SELECT * FROM blog_users";
$query = $pdo->prepare($sql);
$query->execute();
$users  = $query->fetchAll();




$nbArticles = 6;
$ordre = 'DESC';
$page = 1;



if(!empty($_POST['submittedd'])){


    if (!empty($_POST['tri']) && $_POST['tri'] !== 'Nombre de rêves par page :'){
        $nbArticles = $_POST['tri'];
    }
    if(!empty($_POST['ordre']) &&  $_POST['ordre'] !== 'Ordre d\'affichage :'){
        $ordre = $_POST['ordre'];

   

   
    }
    // if no error
    if (count($error) == 0) {

    }
}

$offset = $nbArticles * $page - $nbArticles;

$sql = "SELECT * FROM blog_articles ORDER BY created_at $ordre LIMIT $nbArticles OFFSET $offset";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$articles  = $query->fetchAll();


$sql = "SELECT COUNT(id) FROM blog_articles";
$query = $pdo->prepare($sql);
$query->execute();
$count = $query->fetchColumn();




require_once('inc/header.php'); ?>


<section id="homepage" class="wrap">
    <h1 class="main_title">Bienvenue sur Blog2Ouf !</h1>
    <form action="" method="post" class="tribox">
        <select class="tri" name="tri">
            <option value="6"> Nombre d'articles': </option>
            <option value="2" > 2 </option>
            <option value="4" > 4 </option>
            <option value="6" > 6 </option>
            <option value="8" > 8 </option>
            <option value="<?= $count ?>" > Voir tout </option>
        </select>
        <select class="tri" name="ordre">
            <option value="DESC"> Ordre d'affichage : </option>
            <option value="DESC"> Les plus récents </option>
            <option value="ASC"> Les plus anciens </option>
        </select>
        <input type="submit" name="submittedd" value="Trier">
    </form>
    <div class="center">
        <?php pagination($page, $nbArticles, $count); ?>
    </div>


    <div id="contenerArticles">
        <?php foreach ($articles as $key => $article) { ?>
            <?php if ($article['status'] === 'publish') { ?>
                <div class="bloc bloc<?php echo $key; ?>">
                    <a class="minibloc1" href="detail-article.php?id=<?php echo $article['id']; ?>">
                        <img src="admin/upload/<?php echo $article['image']; ?>.jpg" alt="<?php $article['title']; ?>">
                        <h2><?php echo $article['title']; ?></h2>
                    </a>
                    <h4>Cette article a été créé le <?php echo $article['created_at']; ?></h4>
                    <?php if (!$article['created_at'] === $article['modified_at']) { ?>
                        <p><?php echo $article['modified']; ?></p>
                    <?php } ?>
                    <?php foreach ($comments as $key => $comment) { ?>
                        <?php if ($article['id'] === $comment['id_article'] && $comment['status'] === 'publish') { ?>
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
            <?php } ?>
        <?php } ?>
    </div>
    <div class="center">
        <?php pagination($page, $nbArticles, $count); ?>
    </div>
</section>



<?php require_once('inc/footer.php');

