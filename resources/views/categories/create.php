<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="wrapper">

    <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">

        <?php include __DIR__ . '/../layouts/navbar.php'; ?>

        <div class="container mt-4">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h4>
                        <i class="fa fa-plus-circle"></i>
                        Add Drug Category
                    </h4>

                </div>

                <div class="card-body">

                    <?php if(isset($_SESSION['error'])): ?>

                        <div class="alert alert-danger">

                            <?= $_SESSION['error']; ?>

                        </div>

                        <?php unset($_SESSION['error']); ?>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Category Code

                            </label>

                            <input
                                type="text"
                                name="category_code"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Category Name

                            </label>

                            <input
                                type="text"
                                name="category_name"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"></textarea>

                        </div>

                        <button
                            class="btn btn-success">

                            <i class="fa fa-save"></i>

                            Save Category

                        </button>

                        <a href="index.php"
                           class="btn btn-secondary">

                            Cancel

                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>