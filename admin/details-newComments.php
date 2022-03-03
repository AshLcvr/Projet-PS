<?php
session_start();
include('../inc/pdo.php');
include('inc/fonction.php');

$sql = "SELECT * FROM blog_comments WHERE status='new'";
$query = $pdo->prepare($sql);
$query->execute();
$newcomments  = $query->fetchAll();

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
                        <th>PUBLISH/DELETE</th>
                        <th>User</th>
                        <th>created_at</th>
                        <th>modified_at</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($newcomments as $key => $comment) { ?>
                        <tr>
                            <th><a href="admin/publish-comments.php?id=<?php echo $id; ?>">PUBLISH</a> / <a href="admin/delete-comments.php?id=<?php echo $id; ?>">DELETE</a></th>
                            <?php foreach ($users as $key => $user) { ?>
                                <?php if ($user['id'] === $comment['user_id']) { ?>
                                    <th><?php echo $user['pseudo']; ?></th>
                                <?php } ?>
                            <?php } ?>
                            <th><?php echo $comment['created_at']; ?></th>
                            <th><?php echo $comment['modified_at']; ?></th>
                            <th><?php echo $comment['status']; ?></th>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>PUBLISH/DELETE</th>
                        <th>User</th>
                        <td>created_at</td>
                        <td>modified_at</td>
                        <td>status</td>
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