<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Incluir el archivo de conexión a la base de datos
require_once __DIR__ . '/../core/DBConfig.php';
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
    $stmt = $db->prepare("SELECT first_name, last_name, email, password, role_id, status_id FROM users WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    // Si el usuario existe, obtenemos sus datos
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $first_name = $user['first_name'];
        $last_name = $user['last_name'];
        $email = $user['email'];
        $password = $user['password'];
        $role_id = $user['role_id'];
        $status_id = $user['status_id'];
        $name = $first_name . ' ' . $last_name;
        // Verifica el rol del usuario
        if ($role_id === 2) { // Verifica si el usuario es admin (role_id = 2)
            // Se queda en manage-users.php
        } else {
            // Redirige a index.php si no es admin
            header('Location: ../index.html');
            exit();
        }
    } else {
        // Si no se encuentra el usuario en la base de datos
        header("Location: ../index.html");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de inicio de sesión
    header("Location: ../index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <title>Gestor de Usuarios</title>
    <!-- Bootstrap 5 CSS -->
    <link href="../dist/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

    <!-- DataTables Bootstrap 5 CSS -->
    <link href="../dist/datatables/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="../dist/fontawesome/css/fontawesome.min.css" />

    <!-- Variables y estilos -->
    <link rel="stylesheet" href="../css/variables.css" />
    <link rel="stylesheet" href="../css/style.css" />
    <!-- CSS de intl-tel-input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.css" />
    <!-- Icono -->
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <style>
    /* DataTables modo claro solo para la tabla de commits */
    #tablaUsuarios,
    #tablaUsuarios thead th,
    #tablaUsuarios tbody td {
        background: #fff !important;
        color: #111 !important;
    }

    #tablaUsuarios thead th {
        border-bottom: 2px solid #dee2e6 !important;
    }

    #tablaUsuarios tbody tr {
        border-bottom: 1px solid #dee2e6 !important;
    }

    #tablaUsuarios {
        border: 1px solid #dee2e6 !important;
        border-radius: 6px;
    }

    /* Paginación y controles */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background: #f8f9fa !important;
        color: #111 !important;
        border: 1px solid #dee2e6 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #e9ecef !important;
        color: #111 !important;
    }

    /* Filtros y otros controles */
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select {
        background: #fff !important;
        color: #111 !important;
        border: 1px solid #dee2e6 !important;
    }

    /* Responsive sólo para la tabla */
    .table-responsive {
        background: #fff !important;
        border-radius: 18px;
    }
    </style>
</head>

<body class="bg-white text-light">

    <!-- Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main class="container-fluid d-flex flex-column align-items-center justify-content-center"
        style="min-height: 80vh;">
        <div class="uam-bar-commits my-5 mx-auto p-4 d-flex flex-column align-items-center"
            style="background: var(--uam-blue); border-radius: 18px; max-width: 1200px;">
            <h2 class="text-center mb-4" style="color: var(--uam-yellow); font-size: 2.5rem; font-weight: bold;">
                Administrador de Usuarios
            </h2>
            <div class="bg-white text-dark p-4 mb-4" style="border-radius: 18px; width: 100%; max-width: 1100px;">
                <div class="table-responsive">
                    <table id="tablaUsuarios" class="table table-bordered table-hover" style="width:100%">
                        <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Rol</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Telefono</th>
                                <th>Pais</th>
                                <th>Ciudad</th>
                                <th>Fecha de Nacimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-between w-100" style="max-width: 1100px;">
                <a href="./services.php" class="btn btn-uam d-flex align-items-center justify-content-center"
                    style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 180px; height: 50px; padding: 0;">
                    Regresar
                </a>
                <button type="button" class="btn btn-uam d-flex align-items-center justify-content-center"
                    style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 220px; height: 50px; padding: 0;"
                    id="btnRecargarUsuarios">
                    Recargar Lista
                </button>
                <button type="button" class="btn btn-uam d-flex align-items-center justify-content-center"
                    data-bs-toggle="modal" data-bs-target="#crearUsuarioModal"
                    style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 220px; height: 50px; padding: 0;">
                    Crear nuevo usuario
                </button>
            </div>
        </div>
    </main>

    <!-- Modal para mensajes -->
    <div class="modal fade" id="modalMessage" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalMessageContent"></div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar usuario -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-light">
                    <h5 class="modal-title text-light" id="confirmDeleteModalLabel">Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-light">
                    ¿Estás seguro de que deseas eliminar este usuario?
                </div>
                <div class="modal-footer text-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de detalles de usuario -->
    <div class="modal fade" id="detalleUsuarioModal" tabindex="-1" aria-labelledby="detalleUsuarioLabel"
        aria-hidden="true" style="color: var(--uam-black);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleUsuarioLabel">Detalles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formUpdateUsuario" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="modalUserUser_id" name="user_id" />
                        <input type="hidden" name="admin_edit" value="1">
                        <div class="form-group" id="currentPasswordGroup">
                            <label for="modalUserCurrentPassword">Contraseña actual</label>
                            <input type="password" id="modalUserCurrentPassword" name="current_password"
                                class="form-control">
                        </div>
                        <!-- Fila 1: 3 columnas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="modalUserExistingPicture" class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" accept="image/png,image/jpeg,image/heic"
                                    class="form-control" id="modalUserExistingPicture">
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
                                <div class="form-group">
                                    <label for="modalUserPhone">Teléfono</label>
                                    <input type="tel" id="modalUserPhone" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: 3 columnas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="modalUserPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="modalUserPassword" name="password"
                                    placeholder="Enter a password" required autocomplete="off" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="modalUserCountry" class="form-label">País</label>
                                <select class="form-select" id="modalUserCountry" name="country" required>
                                    <option value="">Seleccione un país</option>
                                    <option value="Colombia">Colombia</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="ciudadColombiaDiv" style="display:none;">
                                <label for="modalUserCity" class="form-label">Ciudad</label>
                                <select class="form-select" id="modalUserCity" name="city" required>
                                    <option value="">Seleccione una ciudad</option>
                                    <option value="Arauca">Arauca</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Barranquilla">Barranquilla</option>
                                    <option value="Bogotá">Bogotá</option>
                                    <option value="Bucaramanga">Bucaramanga</option>
                                    <option value="Cali">Cali</option>
                                    <option value="Cartagena">Cartagena</option>
                                    <option value="Cúcuta">Cúcuta</option>
                                    <option value="Florencia">Florencia</option>
                                    <option value="Ibagué">Ibagué</option>
                                    <option value="Leticia">Leticia</option>
                                    <option value="Manizales">Manizales</option>
                                    <option value="Medellín">Medellín</option>
                                    <option value="Mitú">Mitú</option>
                                    <option value="Mocoa">Mocoa</option>
                                    <option value="Montería">Montería</option>
                                    <option value="Neiva">Neiva</option>
                                    <option value="Pasto">Pasto</option>
                                    <option value="Pereira">Pereira</option>
                                    <option value="Popayán">Popayán</option>
                                    <option value="Puerto Carreño">Puerto Carreño</option>
                                    <option value="Quibdó">Quibdó</option>
                                    <option value="Riohacha">Riohacha</option>
                                    <option value="San Andrés">San Andrés</option>
                                    <option value="San José del Guaviare">San José del Guaviare</option>
                                    <option value="Santa Marta">Santa Marta</option>
                                    <option value="Sincelejo">Sincelejo</option>
                                    <option value="Tunja">Tunja</option>
                                    <option value="Valledupar">Valledupar</option>
                                    <option value="Villavicencio">Villavicencio</option>
                                    <option value="Yopal">Yopal</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="ciudadOtroDiv" style="display:none;">
                                <label for="modalUserCityOtro" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="modalUserCityOtro" name="city_otro"
                                    placeholder="Ingrese su ciudad" />
                            </div>
                        </div>

                        <!-- Fila 4: 3 columnas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="modalUserBirthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="modalUserBirthdate" name="birthdate"
                                    required autocomplete="off" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="modalUserStatus" class="form-label">Status</label>
                                <select class="form-select" id="modalUserStatus" name="status_id" required>
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                    <option value="3">Filed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="modalUserRole" class="form-label">Role</label>
                                <select class="form-select" id="modalUserRole" name="role_id" required>
                                    <option value="1">User</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Supervisor</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnUpdateUser">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para crear usuario  -->
    <div class="modal fade" id="crearUsuarioModal" tabindex="-1" aria-labelledby="crearUsuarioLabel" aria-hidden="true"
        style="color: var(--uam-black);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="crearUsuarioLabel">Crear nuevo usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCrearUsuario" enctype="multipart/form-data" method="post">
                    <div class="modal-body">
                        <input type="hidden" id="modalUserUser_id" name="user_id" />
                        <input type="hidden" name="admin_edit" value="1">
                        <!-- Fila 1: 3 columnas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="createUserProfilePicture" class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" accept="image/png,image/jpeg,image/heic"
                                    class="form-control" id="createUserProfilePicture">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="createUserFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="createUserFirstName" name="first_name"
                                    placeholder="Enter a first name" required autocomplete="on" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="createUserLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="createUserLastName" name="last_name"
                                    placeholder="Enter a last name" required autocomplete="on" />
                            </div>
                        </div>

                        <!-- Fila 2: 3 columnas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="createUserUsername" class="form-label">Username</label>
                                <input type="text" class="form-control" id="createUserUsername" name="username"
                                    placeholder="Enter an username" required autocomplete="on" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="createUserEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="createUserEmail" name="email"
                                    placeholder="Enter an email" required autocomplete="on" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label for="createUserPhone">Teléfono</label>
                                    <input type="tel" id="createUserPhone" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Fila 3: 3 columnas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="createUserPassword" class="form-label">Password</label>
                                <input type="password" class="form-control" id="createUserPassword" name="password"
                                    placeholder="Enter a password" required autocomplete="off" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="createUserCountry" class="form-label">País</label>
                                <select class="form-select" id="createUserCountry" name="country" required>
                                    <option value="">Seleccione un país</option>
                                    <option value="Colombia">Colombia</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="CreateciudadColombiaDiv" style="display:none;">
                                <label for="createUserCity" class="form-label">Ciudad</label>
                                <select class="form-select" id="createUserCity" name="city">
                                    <option value="">Seleccione una ciudad</option>
                                    <option value="Arauca">Arauca</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Barranquilla">Barranquilla</option>
                                    <option value="Bogotá">Bogotá</option>
                                    <option value="Bucaramanga">Bucaramanga</option>
                                    <option value="Cali">Cali</option>
                                    <option value="Cartagena">Cartagena</option>
                                    <option value="Cúcuta">Cúcuta</option>
                                    <option value="Florencia">Florencia</option>
                                    <option value="Ibagué">Ibagué</option>
                                    <option value="Leticia">Leticia</option>
                                    <option value="Manizales">Manizales</option>
                                    <option value="Medellín">Medellín</option>
                                    <option value="Mitú">Mitú</option>
                                    <option value="Mocoa">Mocoa</option>
                                    <option value="Montería">Montería</option>
                                    <option value="Neiva">Neiva</option>
                                    <option value="Pasto">Pasto</option>
                                    <option value="Pereira">Pereira</option>
                                    <option value="Popayán">Popayán</option>
                                    <option value="Puerto Carreño">Puerto Carreño</option>
                                    <option value="Quibdó">Quibdó</option>
                                    <option value="Riohacha">Riohacha</option>
                                    <option value="San Andrés">San Andrés</option>
                                    <option value="San José del Guaviare">San José del Guaviare</option>
                                    <option value="Santa Marta">Santa Marta</option>
                                    <option value="Sincelejo">Sincelejo</option>
                                    <option value="Tunja">Tunja</option>
                                    <option value="Valledupar">Valledupar</option>
                                    <option value="Villavicencio">Villavicencio</option>
                                    <option value="Yopal">Yopal</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3" id="CreateciudadOtroDiv" style="display:none;">
                                <label for="CreateUserCityOtro" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="CreateUserCityOtro" name="city_otro"
                                    placeholder="Ingrese su ciudad" />
                            </div>
                        </div>

                        <!-- Fila 4: 3 columnas -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="createUserBirthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="createUserBirthdate" name="birthdate"
                                    required autocomplete="on" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="createUserStatus" class="form-label">Status</label>
                                <select class="form-select" id="createUserStatus" name="status_id" required
                                    autocomplete="off">
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                    <option value="3">Filed</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btnCrearUsuario">Crear usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </main>

    <!-- jQuery -->
    <script src="../dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS Bundle -->
    <script src="../dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart.js -->
    <script src="../dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome -->
    <script src="../dist/fontawesome/js/all.min.js"></script>
    <!-- DataTables JS -->
    <script src="../dist/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../dist/datatables/js/dataTables.bootstrap5.min.js"></script>
    <!-- JS personalizado -->
    <script src="../js/users.js"></script>
    <!-- JS de intl-tel-input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>
    <!-- Utils para validación y formato -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"></script>
</body>

</html>