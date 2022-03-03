<?php

session_start();

require_once('inc/fonction.php');
include_once('inc/fichier.php');
include_once('inc/pdo.php');

$error = array();


$sql = "SELECT * FROM blog_comments";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$comments  = $query->fetchAll();


$sql = "SELECT * FROM blog_users";
$query = $pdo->prepare($sql);
// proctection injection sql
$query->execute();
$users  = $query->fetchAll();


if (!empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $article = getArticleById($id);

    if (empty($article)) {
        die('404');
    }
}else{
    die('404');
}


if (!empty($_POST['submitted']) && islogged()) {


    $commentaire = trim(strip_tags($_POST['commentaire']));
    $status = 'new';
    $id_article = $id;
    $user_id = $_SESSION['user']['id'];


    if (count($error) == 0) {
        $sql = "INSERT INTO blog_comments ( id_article, content, user_id, created_at, modified_at, status) VALUES (:id_article, :content, :user_id, NOW(), NOW(), :status)";
        $query = $pdo->prepare($sql);
        $query->bindValue(':id_article',$id_article, PDO::PARAM_INT);
        $query->bindValue(':content',$commentaire, PDO::PARAM_STR);
        $query->bindValue(':user_id',$user_id, PDO::PARAM_INT);
        $query->bindValue(':status',$status, PDO::PARAM_STR);
        $query->execute();
        //header('Location: index.php');
    }

}
debug($article);
require_once('inc/header.php'); ?>
<div id="contenerArticle">
    <div class="blocArticle">
        <h1><?php echo $article['title']; ?></h1>
        <div class="minibloc">
            <img src="admin/upload/<?php echo $article['image']; ?>" alt="<?php $article['title']; ?>">
            <p><?php echo $article['content']; ?></p>
        </div>
        <h4>Cet article a été créé le <?php echo $article['created_at']; ?></h4>
        <?php if(($article['modified_at']) !== NULL){ echo '<h4>Modifié le '.$article['modified_at'].'</h4>';} ?>
        <?php if (islogged()) { ?>

            <div class="separator"></div>
            <form class="monForm" action="" method="POST" novalidate>
                <?php echo label('commentaire','Commentaires') ?>
                <textarea placeholder="Laissez un commentaire" name="commentaire"></textarea>
                <input type="submit" name="submitted" value="Envoyer">
            </form>
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
</div>



<?php if (isloggedAdmin()) { ?>
    <a href="admin/edit-articles.php?id=<?php echo $id; ?>">EDIT</a>
    <a href="admin/delete-articles.php?id=<?php echo $id; ?>">delete</a>
<?php } ?>





<?php require_once('inc/footer.php');
