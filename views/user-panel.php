<?php
// Incluir el archivo de conexión a la base de datos
require_once '../backend/DBConfig.php';
// Crear una instancia de la clase de conexión
$auth = new DBConfig();
$db = $auth->getConnection();
// Verifica si la sesión está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Verifica si el usuario está autenticado
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // Recupera el ID del usuario desde la sesión
    $user_id = $_SESSION['user_id']; // Asumiendo que 'user_id' está guardado en la sesión
    // Consulta para obtener los datos del usuario
    $stmt = $db->prepare("SELECT profile_picture, first_name, last_name, email, phone, username, country, city, birthdate, role_id, status_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Si el usuario existe, obtenemos sus datos
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $profile_picture = $user['profile_picture'];
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $email = $user['email'];
        $phone = $user['phone'];
        $username = $user['username'];
        $country = $user['country'];
        $city = $user['city'];
        $birthdate = $user['birthdate'];
        $role_id = $user['role_id'];
        $status_id = $user['status_id'];
        $name = $first_name . ' ' . $last_name;
        // Si se encuentra autenticado , mantiene en la pagina
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: ../index.php");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de index.php
    header("Location: ../index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>user-panel</title>
    <!-- Bootstrap CSS -->
    <link href="../dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body style="background-color: var(--olive-green) !important; color: var(--soft-green);">
    <!-- Incluir el navbar -->
    <?php include './navbar.php'; ?>

    <!-- Contenido principal -->
    <main class="container my-5">

        <!-- Modal alert -->
        <div class="modal fade" id="manageUsersModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
            data-bs-target="#detalleUsuarioModal" data-bs-theme="white" style="z-index: 1051 !important;">
            <div class="modal-dialog">
                <div class="modal-content bg-white text-dark" style="background-color: var(--olive-green);">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">title</h5>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        content
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación para eliminar usuario -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header text-dark">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-dark">
                        ¿Estás seguro de que deseas eliminar tu cuenta?
                    </div>
                    <div class="modal-footer text-dark">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de detalles de usuario -->
        <div class="modal fade" id="detalleUsuarioModal" tabindex="-1" aria-labelledby="detalleUsuarioLabel"
            aria-hidden="true" style="background-color: var(--soft-green); color: var(--dark-green);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleUsuarioLabel">Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formUsuario" enctype="multipart/form-data" method="post">
                        <div class="modal-body">
                            <input type="hidden" id="modalUserUser_id" name="user_id" />

                            <!-- Fila 1: 3 columnas -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserExistingPicture" class="form-label">Profile Picture</label>
                                    <input type="file" name="profile_picture" accept="image/png,image/jpeg,image/heic" class="form-control" id="modalUserExistingPicture">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="modalUserFirstName" name="first_name"
                                        placeholder="Enter a first name" required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="modalUserLastName" name="last_name"
                                        placeholder="Enter a last name" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 2: 3 columnas -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="modalUserUsername" name="username"
                                        placeholder="Enter an username" required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="modalUserEmail" name="email"
                                        placeholder="Enter an email" required autocomplete="off" />
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modalUserPhone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="modalUserPhone" name="phone"
                                        placeholder="Enter a phone" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 3: 2 columnas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserPassword" class="form-label">Password</label>
                                    <input type="text" class="form-control" id="modalUserPassword" name="password"
                                        placeholder="Enter a password" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserCountry" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="modalUserCountry" name="country"
                                        placeholder="Enter a country" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 4: 2 columnas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserCity" class="form-label">City</label>
                                    <input type="text" class="form-control" id="modalUserCity" name="city"
                                        placeholder="Enter a city" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserBirthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control" id="modalUserBirthdate" name="birthdate"
                                        required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 5: 2 columnas -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserStatus" class="form-label">Status</label>
                                    <select class="form-select" id="modalUserStatus" name="status_id" disabled required>
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                        <option value="3">Filed</option>
                                    </select>
                                    <input type="hidden" id="status_id_hidden" name="status_id"
                                        value="<?php echo htmlspecialchars($status_id); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserRole" class="form-label">Role</label>
                                    <select class="form-select" id="modalUserRole" name="role_id" disabled required>
                                        <option value="1">User</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Supervisor</option>
                                    </select>
                                    <input type="hidden" id="role_id_hidden" name="role_id"
                                        value="<?php echo htmlspecialchars($role_id); ?>">
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btnUpdateUser">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Card del usuario -->
                <div class="card text-center shadow-lg"
                    style="background-color: var(--soft-green) !important; color: var(--dark-green) !important;">
                    <div class="card-header bg-white text-dark"
                        style="background-color: var(--soft-green) !important; color: var(--dark-green) !important;">
                        <h5>User Management Panel</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Imagen de perfil -->
                            <img src="../uploads/<?php echo htmlspecialchars($profile_picture); ?>"
     alt="Profile picture" class="rounded-circle img-fluid mb-3"
     style="width: 250px; height: 250px; object-fit: cover;">

                            
                            <!-- Información del usuario -->
                            <div class="col-md-8 text-start">
                                <input type="hidden" id="UserUser_id" value="<?php echo htmlspecialchars($user_id); ?>">
                                <p><strong>ID:</strong> <?php echo htmlspecialchars($user_id); ?></p>
                                <p><strong>Status:</strong> <?php echo htmlspecialchars($status_id); ?></p>
                                <p><strong>Role:</strong> <?php echo htmlspecialchars($role_id); ?></p>
                                <p><strong>First name:</strong> <?php echo htmlspecialchars($first_name); ?></p>
                                <p><strong>Last name:</strong> <?php echo htmlspecialchars($last_name); ?></p>
                                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></p>
                                <p><strong>Location:</strong> <?php echo htmlspecialchars("$city, $country"); ?></p>
                                <p><strong>Birthdate:</strong> <?php echo htmlspecialchars($birthdate); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light"
                        style="background-color: var(--soft-green) !important; color: var(--dark-green) !important;">
                        <button type="button" class="btn btn-primary me-2" id="btnDetailUser">Edit profile</button>
                        <button type="button" class="btn btn-danger me-2" id="btnDeleteUser">Delete account</button>
                        <a href="../backend/LogoutController.php" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="../dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="../dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- ChartJS-->
    <script src="../dist/chart/js/chart.umd.min.js"></script>
    <!-- user-panel JS -->
    <script src="../js/user-panel.js"></script>
</body>

</html>