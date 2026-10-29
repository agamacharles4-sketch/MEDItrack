<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="wrapper">

<?php include __DIR__ . '/../layouts/sidebar.php'; ?>

<div class="main-content">

<?php include __DIR__ . '/../layouts/navbar.php'; ?>

<div class="container mt-4">

<div class="card shadow">

<div class="card-header bg-warning">

<h4>

<i class="fa fa-edit"></i>

Edit Category

</h4>

</div>

<div class="card-body">
<?php if(isset($_SESSION['success'])): ?>

<div class="alert alert-success">

<?= $_SESSION['success']; ?>

</div>

<?php unset($_SESSION['success']); ?>

<?php endif; ?>


<?php if(isset($_SESSION['error'])): ?>

<div class="alert alert-danger">

<?= $_SESSION['error']; ?>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>
<?php if(isset($_SESSION['error'])): ?>

<div class="alert alert-danger">

<?= $_SESSION['error']; ?>

</div>

<?php unset($_SESSION['error']); ?>

<?php endif; ?>

<form method="POST">

<div class="mb-3">

<label>Category Code</label>

<input
type="text"
name="category_code"
class="form-control"
value="<?= htmlspecialchars($category['category_id']); ?>"
required>

</div>

<div class="mb-3">

<label>Category Name</label>

<input
type="text"
name="category_name"
class="form-control"
value="<?= htmlspecialchars($category['category_name']); ?>"
required>

</div>

<div class="mb-3">

<label>Description</label>

<textarea
name="description"
class="form-control"
rows="4"><?= htmlspecialchars($category['description']); ?></textarea>

</div>

<a href="delete.php?id=<?= $category['category_id']; ?>"
   
class="btn btn-danger btn-sm"
   
onclick="return confirm('Are you sure you want to delete this category?');">

<i class="fa fa-trash"></i>

</a>

<a href="index.php" class="btn btn-secondary">

Cancel

</a>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>