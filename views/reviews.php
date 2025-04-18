<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>reviews</title>
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

    <!-- Contenido princiapl de la pagina -->
    <main class="container text-light">
        <div class="container text-light d-flex justify-content-center">
            <h2 class="my-5">Testimonios de nuestros clientes</h2>
        </div>
        <!-- Person 1 -->
        <div class="container text-light d-flex justify-content-center my-5">
            <div class="container">
                <img src="../uploads/Person1.png" alt="Person 1" >
            </div>
            <div class="my-5">
                <p>
                    <strong>– Carla Gómez, Dueña de Bistro Delicias</strong>
                    <li class="mb-3">"Gracias a la comparación de precios en tiempo real, 
                    hemos logrado ahorrar un 15% en nuestras compras mensuales.
                    Además, la calidad de los proveedores es excelente. ¡Muy recomendado!"</li>
                    
                </p>
            </div>
        </div>
        <!-- Person 2 -->
        <div class="container text-light d-flex justify-content-center my-5">
            <div class="my-5">
                <p>
                    <strong>– Juan Martínez, Gerente de La Parrilla Familiar</strong>
                    <li class="mb-3">"La plataforma es súper fácil de usar y el soporte personalizado ha sido increíble. Siempre están disponibles para resolver nuestras dudas y ayudarnos a optimizar procesos. ConectaRestaurantes es justo lo que necesitábamos."</li>
                    
                </p>
            </div>
            <div class="container my-5">
                <img src="../uploads/Person2.png" alt="Person 2" >
            </div>
        </div>
        <!-- Person 3 -->
        <div class="container text-light d-flex justify-content-center my-5">
            <div class="container">
                <img src="../uploads/Person3.png" alt="Person 3" >
            </div>
            <div class="my-5">
                <p>
                    <strong>– Luis Fernández, Administrador de Pizza Express</strong>
                    <li class="mb-3">"ConectaRestaurantes nos ha facilitado mucho el abastecimiento. La variedad de productos y la confiabilidad de los proveedores han sido un gran apoyo para nuestro negocio. Sin duda, una herramienta imprescindible."</li>
                    
                </p>
            </div>
        </div>
        <!-- Person 4 -->
        <div class="container text-light d-flex justify-content-center my-5">
            <div class="my-5">
                <p>
                    <strong>– Ana López, Propietaria de Sweet & Coffee</strong>
                    <li class="mb-3">"Antes era un caos gestionar nuestros pedidos, pero desde que usamos ConectaRestaurantes todo está más organizado. El sistema de rastreo nos da tranquilidad y evitamos retrasos con los proveedores.</li>
                    
                </p>
            </div>
            <div class="container my-5">
                <img src="../uploads/Person4.png" alt="Person 4" >
            </div>
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
</body>
</html>