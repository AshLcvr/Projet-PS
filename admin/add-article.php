<?php
session_start();
require('inc/fonction.php');
require('../inc/pdo.php');

$title = 'Ajout d\'un article';
$errors= [];
$titre = '';
$content = '';
$image = '';


if(!empty($_POST['submitted'])) {
    $titre = trim(strip_tags($_POST['titre']));
    $content = trim(strip_tags($_POST['content']));

    $errors = validateText($errors,$titre, 'titre', 2, 255);
    $errors = validateText($errors,$content, 'content', 2, 1500);

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
        // Taille du fichier
        $sizeMax = 2000000; // 2mo
        if($file_size > $sizeMax || filesize($file_tmp) > $sizeMax) {
            $errors['image'] = 'Votre fichier est trop gros (max 2mo).';
        } else {
            // Type du fichier.
            $allowedMimeType = array('image/png','image/jpeg','image/jpg');
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file_tmp);
            if(!in_array($mime, $allowedMimeType)) {
                $errors['image'] = 'Veuillez télécharger une image du type jpeg ou .png';
            }
        }
    }

    if(count($errors) === 0) {
        // upload
        $point = strrpos($file_name, '.');
        $extension = substr($file_name,$point, strlen($file_name) - $point);
        $newfile = time() . '-' . generateRandomString(12).$extension;
        if(!is_dir('upload')) {
            mkdir('upload');
        }

        $sql = "INSERT INTO blog_articles (title, content, created_at, status, user_id, image) VALUES (:title, :content, NOW(), 'publish', 1, :image )";
        $query = $pdo->prepare($sql);
        $query ->bindValue(':title', $titre, PDO::PARAM_STR);
        $query ->bindValue(':content', $content, PDO::PARAM_STR);
        $query ->bindValue(':image', $newfile, PDO::PARAM_STR);
        $query -> execute();
        header('Location: index.php');
    }

debug($errors);

}

require('inc/header.php');

?>

<section id="formulaires">
    <form method="post" action="" novalidate class="monForm" enctype="multipart/form-data">

        <div class="bloc">
        <?= label('titre', 'Titre de l\'article:');
        echo inputTextAdd('titre', $titre);
        echo spanError('titre', $errors); ?>
        </div>

        <div class="bloc">
            <?=
            label('content', 'Contenu de l\'article');
            ?>
            <textarea class="content_admin" name="content" id="content" ></textarea>
            <?= spanError('content', $errors);?>
        </div>


        <?= label('image', 'Image:')
        ?>
        <input type="file" name="image" id="image" >
        <?= spanError('image', $errors) ?>

        <input type="submit" name="submitted">
    </form>
</section>
