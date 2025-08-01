<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>login</title>
    <!-- Bootstrap CSS -->
    <link href="../dist/bootstrap/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="../dist/fontawesome/css/fontawesome.min.css" />
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/variables.css" />
    <!-- Custom JS -->
    <script src="../js/app.js"></script>
    <!-- Icono -->
    <link rel="icon" href="../favicon.ico" type="image/x-icon" />
</head>

<body style="background-color: var(--uam-white)">
    <!-- Navbar -->
    <?php include __DIR__ . '/../components/navbar.php'; ?>

    <main style="background-color: var(--uam-white)">
        <div class="d-flex justify-content-center align-items-center" style="min-height: 0vh">
            <!-- Form Card -->
            <div class="card shadow-lg p-4" style="
            max-width: 30rem;
            background-color: var(--uam-blue);
            color: var(--uam-white);
          ">
                <div class="card-header text-center border-0 pb-0" style="background-color: transparent">
                    <h2 style="color: var(--uam-yellow)">Formulario de login</h2>
                </div>
                <div class="card-body">
                    <div class="login-form-container mx-auto" style="
                font-weight: bold;
                background-color: var(--uam-blue);
                color: var(--uam-white);
                max-width: 300px;
                max-height: 250px;
              ">
                        <form id="LoginForm" class="needs-validation" style="
                  background-color: var(--uam-blue);
                  color: var(--uam-white);
                " novalidate>
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label">Email, Username or Phone</label>
                                <input style="color: var(--uam-black)" type="email" class="form-control custom-input"
                                    id="email" name="email" placeholder="Email/Username/Phone" required
                                    autocomplete="email" />
                                <div class="invalid-feedback">
                                    Porfavor ingree un input valido.
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input style="color: var(--uam-black)" type="password" class="form-control custom-input"
                                    id="password" name="password" placeholder="Password" required
                                    autocomplete="current-password" />
                                <div class="invalid-feedback">
                                    Porfavor ingree un input valido.
                                </div>
                            </div>
                            <!-- Submit -->
                            <div class="d-grid">
                                <button style="
                      background-color: var(--uam-yellow);
                      color: var(--uam-blue);
                    " type="submit" class="btn btn-primary custom-button">
                                    Iniciar sesión
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de login -->
        <div class="modal fade" id="LoginModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true"
            data-bs-theme="white">
            <div class="modal-dialog">
                <div class="modal-content bg-white text-dark" style="background-color: var(--olive-green)">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">Titulo</h5>
                        <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Contenido</div>
                </div>
            </div>
        </div>
    </main>

    <!-- jQuery -->
    <script src="../dist/jquery/js/jquery.min.js"></script>
    <!-- Bootstrap JS With Popper-->
    <script src="../dist/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- Chart JS -->
    <script src="../dist/chart/js/chart.umd.min.js"></script>
    <!-- FontAwesome JS -->
    <script src="../dist/fontawesome/js/all.min.js"></script>
    <!-- main JS -->
    <script src="../js/login.js"></script>
</body>

</html>