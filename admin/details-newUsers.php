<?php
session_start();
include('../inc/pdo.php');
include('inc/fonction.php');

$nbArticles = 4;
$ordre = 'DESC';

$sql = "SELECT * FROM blog_users ORDER BY created_at $ordre LIMIT $nbArticles";
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
                        <th>DELETE</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Created_at</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $key => $user) { ?>
                        <tr>
                            <th><a href="admin/delete-articles.php?id=<?php echo $id; ?>">DELETE</a></th>
                            <th><?php echo $user['pseudo']; ?></th>
                            <th><?php echo $user['email']; ?></th>
                            <th><?php echo $user['created_at']; ?></th>
                            <th><?php echo $user['role']; ?></th>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                    <th>DELETE</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Created_at</th>
                        <th>Role</th>
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