<?php
include __DIR__ . "/../layouts/header.php";
?>
<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
?>
<div class="wrapper">

    <?php include __DIR__ . "/../layouts/sidebar.php"; ?>

    <div class="main-content">

        <?php include __DIR__ . "/../layouts/navbar.php"; ?>

        <div class="container-fluid mt-4">

            <div class="card shadow">

                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

                    <h4 class="mb-0">
                        <i class="fa fa-folder-open"></i>
                        Drug Categories
                    </h4>

                    <a href="/MEDItrack/public/categories/create.php" class="btn btn-success">

                        <i class="fa fa-plus"></i>

                        Add Category

                    </a>

                </div>

                <div class="card-body">
<?php if(isset($_SESSION['success'])): ?>

<div class="alert alert-success alert-dismissible fade show">

    <i class="fa fa-check-circle"></i>

    <?= $_SESSION['success']; ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>


<?php if(isset($_SESSION['error'])): ?>

<div class="alert alert-danger alert-dismissible fade show">

    <i class="fa fa-exclamation-circle"></i>

    <?= $_SESSION['error']; ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>
                    <table id="categoryTable"
                           class="table table-bordered table-striped table-hover">

                        <thead>

                        <tr>

                            <th>#</th>

                            <th>Code</th>

                            <th>Category Name</th>

                            <th>Description</th>

                            <th>Total Drugs</th>

                            <th width="170">Action</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php

                        $no = 1;

                        foreach($categories as $category):

                        ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td><?= htmlspecialchars($category['category_id']); ?></td>

                            <td><?= htmlspecialchars($category['category_name']); ?></td>

                            <td><?= htmlspecialchars($category['description']); ?></td>

                            <td>

                                <span class="badge bg-success">

                                    <?= $category['total_drugs']; ?>

                                </span>

                            </td>

                            <td>

                                <a href="edit.php?id=<?= $category['category_id']; ?>"

                                   class="btn btn-warning btn-sm">

                                    <i class="fa fa-edit"></i>

                                </a>

                                <a href="delete.php?id=<?= $category['category_id']; ?>"
   class="btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this category?');">

    <i class="fa fa-trash"></i>

</a>

                            </td>

                        </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php
include __DIR__ . "/../layouts/footer.php";
?>