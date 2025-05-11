<?php
// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/../../backend/core/DBConfig.php';
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
    $stmt = $db->prepare("SELECT first_name, last_name, email, role_id, status_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Si el usuario existe, obtenemos sus datos
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $email = $user['email'];
        $role_id = $user['role_id'];
        $status_id = $user['status_id'];
        $name = $first_name . ' ' . $last_name;
        // Verifica el rol del usuario
        if ($role_id === 2) { // Verifica si el usuario es admin (role_id = 2)
            // Se queda en manage-users.php
        } else {
            // Redirige a index.php si no es admin
            header('Location: /trsi/index.php');
            exit();
        }
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: /trsi/index.php");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de inicio de sesión
    header("Location: /trsi/index.php");
    exit();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>manage-users</title>
    <!-- Bootstrap CSS -->
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
</head>

<body style="background-color: var(--olive-green) !important;">

    <!-- Incluir el navbar -->
    <?php
    include './navbar.php';
    ?>

    <main>
        <div class="d-flex justify-content-center text-light">
            <!-- Contenido de la página -->
            <h2>User Management</h2>
        </div>

        <div class="container-fluid my-4">
            <div class="" id="ListUsers">

            </div>
        </div>

        <div class="d-flex justify-content-center">
            <button type="button" class="btn btn-success my-4" data-bs-toggle="modal"
                data-bs-target="#crearUsuarioModal">
                Create New User
            </button>
        </div>

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
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar este usuario?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de detalles de usuario -->
        <div class="modal fade" id="detalleUsuarioModal" tabindex="-1" aria-labelledby="detalleUsuarioLabel"
            aria-hidden="true" style="background-color: var(--soft-green); color: var(--dark-green);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleUsuarioLabel">Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formUsuario">
                        <div class="modal-body">

                            <input type="hidden" id="modalUserUser_id" name="user_id" />

                            <!-- Fila 1 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserProfilePicture" class="form-label">Profile Picture</label>
                                    <input type="text" class="form-control" id="modalUserProfilePicture"
                                        name="profile_picture" placeholder="Enter a profile picture" required
                                        autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 2 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="modalUserFirstName" name="first_name"
                                        placeholder="Enter a first name" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="modalUserLastName" name="last_name"
                                        placeholder="Enter a last name" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 3 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="modalUserUsername" name="username"
                                        placeholder="Enter an username" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="modalUserEmail" name="email"
                                        placeholder="Enter an email" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 4 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserPhone" class="form-label">Phone</label>
                                    <input type="email" class="form-control" id="modalUserPhone" name="phone"
                                        placeholder="Enter a phone" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserPassword" class="form-label">Password</label>
                                    <input type="text" class="form-control" id="modalUserPassword" name="password"
                                        placeholder="Enter a password" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 5 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserCountry" class="form-label">Country</label>
                                    <input type="email" class="form-control" id="modalUserCountry" name="country"
                                        placeholder="Enter a country" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserCity" class="form-label">City</label>
                                    <input type="text" class="form-control" id="modalUserCity" name="city"
                                        placeholder="Enter a city" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 6 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserBirthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control" id="modalUserBirthdate" name="birthdate"
                                        required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 7 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserStatus" class="form-label">Status</label>
                                    <select class="form-select" id="modalUserStatus" name="status_id" required
                                        autocomplete="off">
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                        <option value="3">Filed</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modalUserRole" class="form-label">Role</label>
                                    <select class="form-select" id="modalUserRole" name="role_id" required
                                        autocomplete="off">
                                        <option value="1">User</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Supervisor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btnActualizarUsuario">Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para crear usuario  -->
        <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioLabel"
            aria-hidden="true" style="background-color: var(--soft-green); color: var(--dark-green);">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="crearUsuarioLabel">Create New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="formCrearUsuario">
                        <div class="modal-body">
                            <!-- Fila 1 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="createUserProfilePicture" class="form-label">Profile Picture</label>
                                    <input type="text" class="form-control" id="createUserProfilePicture"
                                        name="profile_picture" placeholder="Enter a profile picture" required
                                        autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 2 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="createUserFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="createUserFirstName" name="first_name"
                                        placeholder="Enter a first name" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="createUserLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="createUserLastName" name="last_name"
                                        placeholder="Enter a last name" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 3 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="createUserUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="createUserUsername" name="username"
                                        placeholder="Enter an username" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="createUserEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="createUserEmail" name="email"
                                        placeholder="Enter an email" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 4 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="createUserPhone" class="form-label">Phone</label>
                                    <input type="email" class="form-control" id="createUserPhone" name="phone"
                                        placeholder="Enter a phone" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="createUserPassword" class="form-label">Password</label>
                                    <input type="text" class="form-control" id="createUserPassword" name="password"
                                        placeholder="Enter a password" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 5 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="createUserCountry" class="form-label">Country</label>
                                    <input type="email" class="form-control" id="createUserCountry" name="country"
                                        placeholder="Enter a country" required autocomplete="off" />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="createUserCity" class="form-label">City</label>
                                    <input type="text" class="form-control" id="createUserCity" name="city"
                                        placeholder="Enter a city" required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 6 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="createUserBirthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control" id="createUserBirthdate" name="birthdate"
                                        required autocomplete="off" />
                                </div>
                            </div>

                            <!-- Fila 7 -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="createUserStatus" class="form-label">Status</label>
                                    <select class="form-select" id="createUserStatus" name="status_id" required
                                        autocomplete="off">
                                        <option value="1">Active</option>
                                        <option value="2">Inactive</option>
                                        <option value="3">Filed</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="createUserRole" class="form-label">Role</label>
                                    <select class="form-select" id="createUserRole" name="role_id" required
                                        autocomplete="off">
                                        <option value="1">User</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Supervisor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="btnCrearUsuario">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- manage-users JS-->
    <script src="/trsi/frontend/js/manage-users.js"></script>
</body>

</html>