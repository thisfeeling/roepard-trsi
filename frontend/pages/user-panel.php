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
        header("Location: /trsi/index.php");
        exit();
    }
} else {
    // El usuario no está autenticado, redirige a la página de index.php
    header("Location: /trsi/index.php");
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
    <link href="/trsi/frontend/dist/bootstrap/css/bootstrap.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="/trsi/frontend/dist/fontawesome/css/fontawesome.min.css"> 
    <!-- Styles -->
    <link rel="stylesheet" href="/trsi/frontend/css/style.css">
    <!-- CSS de intl-tel-input -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/css/intlTelInput.css"/>
    <script>
        window.USER_ID = "<?php echo htmlspecialchars($user_id); ?>";
    </script>
</head>

<body style="background-color: var(--uam-white); color: var(--uam-black);">
    <!-- Navbar -->
    <?php include __DIR__ . '/../../frontend/components/navbar.php'; ?>

    <!-- Contenido principal -->
    <main class="container-fluid d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">
        <div class="uam-bar-commits my-5 mx-auto p-4 d-flex flex-column align-items-center" style="background: var(--uam-blue); border-radius: 18px; max-width: 1200px;">
            <h2 class="text-center mb-4" style="color: var(--uam-yellow); font-size: 2.5rem; font-weight: bold;">
                User Management Panel
            </h2>
            <div class="d-flex flex-column align-items-center w-100">
                <div class="user-card bg-white p-4 mb-5" style="border-radius: 16px; max-width: 500px; min-width: 320px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
                    <div class="d-flex flex-row align-items-center mb-3">
                        <div class="me-4">
                            <?php if (!empty($profile_picture)): ?>
                                <img id="userProfilePictureCard" src="/trsi/uploads/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile"
                                    style="width: 100px; height: 100px; border-radius: 50%; background: var(--uam-blue); object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--uam-blue); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: bold; font-size: 1.2rem;">
                                    Picture
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div><strong>ID:</strong> <?php echo htmlspecialchars($user_id); ?></div>
                            <div><strong>First Name:</strong> <?php echo htmlspecialchars($first_name); ?></div>
                            <div><strong>Last Name:</strong> <?php echo htmlspecialchars($last_name); ?></div>
                            <div><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></div>
                            <div><strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></a></div>
                            <div><strong>Phone:</strong> <?php echo htmlspecialchars($phone); ?></div>
                            <div><strong>Birthdate:</strong> <?php echo htmlspecialchars($birthdate); ?></div>
                            <div><strong>Country:</strong> <?php echo htmlspecialchars($country); ?></div>
                            <div><strong>City:</strong> <?php echo htmlspecialchars($city); ?></div>
                            <div><strong>Role:</strong> <span style="color: var(--uam-blue);"><?php echo htmlspecialchars($role_id == 1 ? 'user' : ($role_id == 2 ? 'admin' : 'supervisor')); ?></span></div>
                            <div><strong>Status:</strong> <span style="color: var(--uam-blue);"><?php echo htmlspecialchars($status_id == 1 ? 'active' : 'inactive'); ?></span></div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between w-100" style="max-width: 600px;">
                    <a href="/trsi/frontend/pages/services.php"
                       class="btn btn-uam d-flex align-items-center justify-content-center"
                       style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 150px; height: 50px; padding: 0;">
                        Regresar
                    </a>
                    <button type="button"
                            class="btn btn-uam d-flex align-items-center justify-content-center"
                            id="btnDetailUser"
                            style="font-size: 1.4rem; font-weight: bold; border-radius: 15px; width: 200px; height: 50px; padding: 0;">
                        Edit profile
                    </button>
                </div>
            </div>
        </div>
    </main>

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

    <!-- Modal de detalles de usuario -->
    <div class="modal fade" id="detalleUsuarioModal" tabindex="-1" aria-labelledby="detalleUsuarioLabel"
        aria-hidden="true" style="color: var(--uam-black);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleUsuarioLabel">Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formUpdateUsuario" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="modalUserUser_id" name="user_id" />
                        <input type="hidden" id="modalUserStatus" name="status_id"/>
                        <input type="hidden" id="modalUserRole" name="role_id"/>
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
                                <label for="modalUserBirthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-control" id="modalUserBirthdate" name="birthdate"
                                    required autocomplete="off" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="modalUserCurrentPassword" class="form-label">Contraseña actual</label>
                                <input type="password" class="form-control" id="modalUserCurrentPassword" name="current_password"
                                    placeholder="Introduce tu contraseña actual" required autocomplete="off" />
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="modalUserPassword" class="form-label">Nueva contraseña</label>
                                <input type="text" class="form-control" id="modalUserPassword" name="password"
                                    placeholder="Introduce la nueva contraseña" required autocomplete="off" />
                            </div>                              
                        </div>

                        <!-- Fila 4: 2 columnas -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="modalUserCountry" class="form-label">País</label>
                                <select class="form-select" id="modalUserCountry" name="country" default="Seleccione un país" required>
                                    <option value="">Seleccione un país</option>
                                    <option value="Colombia">Colombia</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3" id="ciudadColombiaDiv" style="display:none;">
                                <label for="modalUserCity" class="form-label">Ciudad</label>
                                <select class="form-select" id="modalUserCity" name="city" default="Seleccione una ciudad">
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
                            <div class="col-md-6 mb-3" id="ciudadOtroDiv" style="display:none;">
                                <label for="modalUserCityOtro" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="modalUserCityOtro" name="city_otro" placeholder="Ingrese su ciudad" />
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

    <!-- jQuery -->
    <script src="/trsi/frontend/dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="/trsi/frontend/dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="/trsi/frontend/dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="/trsi/frontend/dist/fontawesome/js/all.min.js"></script>
    <!-- user-panel JS -->
    <script src="/trsi/frontend/js/user-panel.js"></script>
    <!-- JS de intl-tel-input -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/intlTelInput.min.js"></script>
    <!-- Utils para validación y formato -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.1/js/utils.js"></script>
</body>

</html>