<?php
// Verifica si session_start ya se inicializo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
};
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $first_name = $_SESSION['first_name'] ?? 'First name not available';
    $last_name = $_SESSION['last_name'] ?? 'Last name not available';
    $email = $_SESSION['email'] ?? 'Email not available';
    $name = $first_name . ' ' . $last_name;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>home</title>
    <!-- Bootstrap CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" href="../css/style.css">
    <!-- Icon of the page -->
    <link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon-16x16.png">
    <link rel="manifest" href="../site.webmanifest">
</head>

<body style="background-color: var(--olive-green) !important;">

    <!-- Incluir el navbar -->
    <?php
    include './navbar.php';
    ?>

    <!-- Contenido principal de la página -->
    <main class="container text-light">
        <h1 class="my-5">Bienvenido a Symphony Synapse</h1>
        <div class="container text-light d-flex justify-content-center">
            <div class="my-5">
                <p>
                    <strong>
                        ¿Eres dueño de un restaurante y buscas optimizar tus pedidos? Nuestra
                        plataforma está diseñada para facilitar el proceso de abastecimiento
                        de ingredientes frescos y productos esenciales.
                        Explora una amplia red de proveedores confiables, compara precios en
                        tiempo real y realiza pedidos directamente desde tu cuenta.
                        Te ayudamos a gestionar tus compras de manera eficiente para que puedas
                        concentrarte en lo más importante: ¡sorprender a tus clientes con la
                        mejor comida!
                        Simplifica tus pedidos y mejora la gestión de tu restaurante. 🍅🥩🛒
                        ¡Regístrate hoy y lleva tu negocio al siguiente nivel!
                    </strong>
                </p>
                <div class="d-flex justify-content-center mt-4 my-4">
                    <button type="button" class="btn btn-success" id="masSobreNosotrosModal" data-bs-toggle="modal" data-bs-target="#masSobreNosotrosModal">Conecta con nosotros</button>
                </div>
            </div>
            <div style="background-color: var(--ivory-cream); border-radius: 1000px;" class="container">
                <img src="../uploads/SYMPHONY.png" alt="S" width="300" height="300">
            </div>
        </div>
        <h2 class="my-5">¿Que Ofrecemos en SYMPHONY SYNAPSE?</h2>
        <div class="container text-light d-flex justify-content-center">
            <ul class="list-unstyled">
                <li class="mb-3"><strong>Acceso a múltiples proveedores confiables:</strong> Encuentra todo lo que necesitas, desde productos frescos hasta utensilios de cocina, en un solo lugar.</li>
                <li class="mb-3"><strong>Comparación de precios:</strong> Ahorra tiempo y dinero al elegir las mejores opciones para tu restaurante.</li>
                <li class="mb-3"><strong>Gestión eficiente de pedidos:</strong> Realiza, organiza y rastrea tus compras fácilmente desde nuestra plataforma.</li>
                <li class="mb-3"><strong>Soporte personalizado: </strong> Nuestro equipo está aquí para ayudarte a optimizar tus procesos y resolver cualquier duda.</li>
                <div class="d-flex justify-content-center mt-4 my-4">
                    <li class="mb-3"><strong>¡Todo lo que tu restaurante necesita, al alcance de un clic!</strong></li>
                </div>
            </ul>
        </div>
        <h2 class="my-5">¿POR QUÉ ELEGIRNOS?</h2>
        <div class="container text-light d-flex justify-content-center">
            <ul class="list-unstyled">
                <li class="mb-3"><strong>Ahorro de tiempo:</strong> Centralizamos todas tus compras para que dediques más tiempo a lo que importa: tu negocio.</li>
                <li class="mb-3"><strong>Personalización:</strong> Configura tus pedidos recurrentes y ajusta tus necesidades según la demanda de tu restaurante.</li>
                <li class="mb-3"><strong>Innovación constante:</strong> Actualizamos nuestra plataforma con nuevas herramientas para que siempre estés a la vanguardia.</li>
                <li class="mb-3"><strong>Red de apoyo:</strong> Únete a una comunidad de restauranteros para compartir experiencias, consejos y recomendaciones.</li>
                <li class="mb-3"><strong>Transparencia total:</strong> Seguimiento en tiempo real de tus pedidos, claridad en los precios y sin costos ocultos.</li>
                <li class="mb-3"><strong>Sostenibilidad:</strong> Trabajamos con proveedores comprometidos con prácticas responsables para ofrecerte opciones más sostenibles.</li>
            </ul>
        </div>
        <h2 class="my-5">Beneficios Clave</h2>
        <div class="container text-light d-flex justify-content-center">
            <ul class="list-unstyled">
                <li class="mb-3"><strong>Ahorro garantizado:</strong> Reduce costos al comparar múltiples opciones y elegir las mejores ofertas.</li>
                <li class="mb-3"><strong>Servicio rapido:</strong> Realiza pedidos en minutos y recibe tus productos a tiempo.</li>
                <li class="mb-3"><strong>Productos frescos y de calidad:</strong> Trabajamos con proveedores que garantizan estándares altos en cada entrega.</li>
                <li class="mb-3"><strong>Optimización:</strong> Herramientas para gestionar tus existencias y evitar desperdicios.</li>
            </ul>
        </div>
        <h2 class="my-5">PROBLEMAS QUE SOLUCIONAREMOS</h2>
        <div class="container text-light d-flex justify-content-center">
            <ul class="list-unstyled">
                <li class="mb-3"><strong>Falta de tiempo:</strong> Buscar múltiples proveedores y realizar pedidos manualmente consume horas valiosas.</li>
                <li class="mb-3"><strong>Altos costos:</strong> La falta de opciones para comparar precios lleva a gastos innecesarios.</li>
                <li class="mb-3"><strong>Ineficiencia operativa:</strong> Los errores en la gestión de inventarios o retrasos en los pedidos pueden afectar directamente el servicio al cliente.</li>
                <li class="mb-3"><strong>Limitada transparencia:</strong> Es difícil rastrear los pedidos o tener claridad sobre la calidad de los productos adquiridos.</li>
            </ul>
        </div>
    </main>

    <!-- Incluir el footer -->
    <?php
    include './footer.php';
    ?>

    <!-- jQuery -->
    <script src="../js/jquery.js"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <!-- main JS -->
    <script src="../js/main.js"></script>
</body>

</html>