<?php
require('../inc/pdo.php');
require('inc/fonction.php');

session_start();

if(!empty($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $article = getArticleById($id);
    $status = 'draft';
    if(empty($article)) {
        die('404');
    }
} else {
    die('404');
}
$sql = "UPDATE blog_articles SET status = :status WHERE id = :id";
$query = $pdo->prepare($sql);
$query->bindValue(':id',$id, PDO::PARAM_INT);
$query->bindValue(':status',$status, PDO::PARAM_STR);
$query->execute();
debug($article);

header('Location: ../index.php');