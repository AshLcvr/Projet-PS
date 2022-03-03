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

$sql = "SELECT * FROM blog_users";
$query = $pdo->prepare($sql);
$query->execute();
$users  = $query->fetchAll();


$title = 'Admin Dashboard';

include('inc/header.php');
?>

<h1> Bienvenue sur le Back-Office</h1>
<!-- Begin Page Content -->
<div class="container-fluid">

<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Tous les articles</h1>
<p class="mb-4">Voici tous les articles qui ont été postés.</p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Mes Articles</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>EDIT/DELETE</th>
                        <th>Titre</th>
                        <th>Created_at</th>
                        <th>Modified_at</th>
                        <th>Status</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $key => $article) { ?>
                        <tr>
                            <th><a href="admin/edit-articles.php?id=<?php echo $id; ?>">EDIT</a> / <a href="admin/delete-articles.php?id=<?php echo $id; ?>">DELETE</a></th>
                            <th><?php echo $article['title']; ?></th>
                            <th><?php echo $article['created_at']; ?></th>
                            <th><?php echo $article['modified_at']; ?></th>
                            <th><?php echo $article['status']; ?></th>
                            <?php foreach ($users as $key => $user) { ?>
                                <?php if ($user['id'] === $article['user_id']) { ?>
                                    <th><?php echo $user['pseudo']; ?></th>
                                <?php } ?>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>EDIT/DELETE</th>
                        <td>Titre</td>
                        <td>Created_at</td>
                        <td>Modified_at</td>
                        <td>Status</td>
                        <td>User</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

<?php
include('inc/footer.php');