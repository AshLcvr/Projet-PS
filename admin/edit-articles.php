<?php
session_start();
require('../inc/pdo.php');
require('inc/fonction.php');
require('../inc/request.php');

$selects = ['publish' => 'Publier', 'draft' => 'Brouillon',];
$titre = '';
$content = '';
$status = '';
$errors= [];
$id = $_GET['id'];
$article = getFetchof('blog_articles', 'id', $id);

if(!empty($_POST['submitted'])){

    $titre = trim(strip_tags($_POST['title']));
    $content = trim(strip_tags($_POST['content']));
    $status = trim(strip_tags($_POST['status']));
    $errors = validateText($errors, $titre, 'titre', 2, 255);
    $errors = validateText($errors, $content, 'content', 2, 1500);

    if($_FILES['image']['error'] > 0) {
        if($_FILES['image']['error'] != 4) {
            $errors['image'] = 'Error: ' . $_FILES['image']['error'];
        } else {
            $errors['image'] = 'Veuillez renseigner une image';
        }
    } else {
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp  = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $sizeMax = 2000000;
        if($file_size > $sizeMax || filesize($file_tmp) > $sizeMax) {
            $errors['image'] = 'Votre fichier est trop gros (max 2mo).';
        } else {
            $allowedMimeType = array('image/png','image/jpeg','image/jpg');
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file_tmp);
            if(!in_array($mime, $allowedMimeType)) {
                $errors['image'] = 'Veuillez télécharger une image du type jpeg ou .png';
            }
        }
    }

    if(count($errors) === 0) {
        $point = strrpos($file_name, '.');
        $extension = substr($file_name,$point, strlen($file_name) - $point);
        $newfile = time() . '-' . generateRandomString(12).$extension;
        if(!is_dir('upload')) {
            mkdir('upload');
        }
        $sql = "UPDATE blog_articles SET title = :title , content = :content, modified_at= NOW(); status = :status ,image= :image WHERE id = $id";
        $query = $pdo -> prepare($sql);
        $query ->bindValue(':title', $titre, PDO::PARAM_STR);
        $query ->bindValue(':content', $content, PDO::PARAM_STR);
        $query ->bindValue(':status', $status, PDO::PARAM_STR);
        $query ->bindValue(':image', $newfile, PDO::PARAM_STR);
        $query -> execute();
        header('Location: ../detail-article.php?id='.$id);
    }
}
debug($article);

include('inc/header.php'); ?>

    <div class="wrap">
        <form class="monForm center" action="" method="POST" novalidate enctype="multipart/form-data">

            <div class="bloc">
                <?php
                echo label('title', 'Titre:');
                echo inputTextEdit('title', $titre, $article);
                echo spanError('title',$errors);?>
            </div>

            <div class="bloc">
                <?php
                echo label('content', 'Content:');
                echo inputTextEdit('content', $content, $article);
                echo  spanError('content',$errors);?>
            </div>

            <div class="bloc">
                <?php
                echo label('status', 'status:');
                echo inputSelectEdit($status, $article, 'status', $selects);
                echo  spanError('content',$errors);
                ?>

            </div>

            <div class="bloc">
                <?= label('image', 'Image:')
                ?>
                <input type="file" name="image" id="image" >
                <?= spanError('image', $errors) ?>
            </div>
            <img src="upload/<?php echo $article['image']?>" alt="Image originale de l'article">

            <input type="submit" name="submitted">
        </form>
    </div>

<?php
include('inc/footer.php');
