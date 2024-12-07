<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DataTables Example</title>

    <!-- Подключение Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/style.css" />
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="text-decoration-none">
                <h2>KSP Dictionary</h2>
            </a>
            <div class="d-flex gap-3">
                <button class="btn btn-success mb-3" id="createCache">
                    Create Cache
                </button>
                <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addModal">
                    Add KEY
                </button>
            </div>
        </div>

        <table id="example" class="table table-striped cell-border" style="width: 100%">
            <thead>
                <tr>
                    <th>id</th>
                    <th>APP</th>
                    <th>Namespace</th>
                    <th>KEY</th>
                    <th>en</th>
                    <th>he</th>
                    <th>ru</th>
                    <th>Comment</th>
                    <th>Code</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Модальное окно -->
    <div class="modal fade " id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Add KEY</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addDataForm">
                        <!-- Первый ряд: 3 select и 2 input -->
                        <div class="row mb-4">
                            <div class="col">
                                <label for="appSelect" class="form-label">App</label>
                                <select class="form-select" id="appSelect" name="app" required>
                                    <option value="">Choose...</option>
                                    <option value="m-action">m-action</option>
                                    <option value="cart">cart</option>
                                    <option value="account">account</option>
                                    <option value="checkout">checkout</option>
                                    <option value="mobile">mobile</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="namespace" class="form-label">Namespace</label>
                                <input type="text" class="form-control" id="namespace" name="namespace" required />
                            </div>
                            <div class="col">
                                <label for="key" class="form-label">KEY</label>
                                <input type="text" class="form-control" id="key" name="key" required />
                            </div>
                        </div>

                        <!-- Разделитель -->
                        <hr class="my-4">

                        <!-- Второй ряд: 3 textarea -->
                        <div class="row mb-4">
                            <div class="col">
                                <label for="en" class="form-label">English</label>
                                <textarea class="form-control" id="en" name="en" rows="5" required></textarea>
                            </div>
                            <div class="col">
                                <label for="he" class="form-label">Hebrew</label>
                                <textarea class="form-control rtl" id="he" name="he" rows="5" required></textarea>
                            </div>
                            <div class="col">
                                <label for="ru" class="form-label">Russian</label>
                                <textarea class="form-control" id="ru" name="ru" rows="5" required></textarea>
                            </div>
                        </div>

                        <!-- Разделитель -->
                        <hr class="my-4">

                        <!-- Третий ряд: 2 input -->
                        <div class="row mb-4">
                            <div class="col">
                                <label for="comment" class="form-label">Comment</label>
                                <input type="text" class="form-control" id="comment" name="comment" />
                            </div>
                            <div class="col">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code" />
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2" id="submitButton">ADD
                                KEY</button>
                            <button type=" button" class="btn btn-danger me-2" id="deleteButton"
                                style="display: none;">Delete KEY</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно для подтверждения удаления -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- toast -->
    <div class="toast align-items-center position-absolute bg-success text-white" id="toast" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Hello, world! This is a toast message.
            </div>
            <button type="button" class="btn-close btn-close-white btn-close-sm me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>

    <!-- Подключение jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Custom JS -->
    <script src="assets/script.js"></script>
</body>

</html>