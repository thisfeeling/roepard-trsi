<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>terms</title>
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

    <main style="background-color: var(--olive-green) !important;">
        <nav id="navbar-example2" class="navbar bg-body-tertiary px-3 mb-3" style="background-color: var(--olive-green) !important;">
            <a class="navbar-brand text-light my-4" href="#scrollspyHeading1">Terms and Conditions</a>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading1">First</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading2">Second</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">More</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#scrollspyHeading3">Third</a></li>
                        <li><a class="dropdown-item" href="#scrollspyHeading4">Fourth</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#scrollspyHeading5">Fifth</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example bg-body-tertiary p-3 rounded-2" tabindex="0">
            <h4 id="scrollspyHeading1">Uso del Software Symphony Synapse</h4>
            <p>
                El software Symphony Synapse está diseñado para proporcionar a los restaurantes una herramienta eficiente para la gestión de inventarios, pedidos y abastecimiento de productos. Al utilizar nuestra plataforma, el usuario acepta que el software debe ser utilizado exclusivamente para fines comerciales dentro de la industria restaurantera. Cualquier uso no autorizado o abuso de las funcionalidades del sistema, como la manipulación de inventarios sin el respaldo adecuado, la introducción de datos falsificados o la distribución no permitida del software a terceros, puede resultar en la suspensión temporal o permanente de la cuenta del usuario. El propietario del restaurante es responsable de la seguridad y la precisión de los datos ingresados, así como de los registros realizados en el sistema. Symphony Synapse se compromete a garantizar la máxima seguridad posible, sin embargo, la seguridad de la cuenta y la protección contra el acceso no autorizado dependen también de las prácticas de seguridad implementadas por el usuario, incluyendo el uso adecuado de contraseñas.
            </p>
            <h4 id="scrollspyHeading2">Política de Confidencialidad y Protección de Datos</h4>
            <p>
                Symphony Synapse valora la privacidad de los usuarios y se compromete a proteger la información confidencial proporcionada a través del software. Los datos recopilados, tales como información de contacto, inventarios, y registros de pedidos, se almacenan en servidores seguros y solo serán utilizados para fines relacionados con la mejora del servicio y la funcionalidad de la plataforma. Nos comprometemos a no vender ni compartir la información personal de los usuarios con terceros, excepto en los casos donde la ley nos lo exija. Los usuarios tienen derecho a acceder, modificar o eliminar sus datos personales en cualquier momento a través del panel de administración. Sin embargo, al eliminar los datos, el usuario acepta que algunas funcionalidades del sistema pueden verse afectadas si los datos necesarios para su operación son eliminados.
            </p>
            <h4 id="scrollspyHeading3">Responsabilidad por la Exactitud del Inventario y los Pedidos</h4>
            <p>
                El usuario es el único responsable de mantener la precisión y actualidad de los registros de inventario dentro de Symphony Synapse. El software facilita la gestión, pero no garantiza la precisión de los datos ingresados, ya que depende de la correcta manipulación y actualización del inventario por parte del usuario. En caso de discrepancias entre los datos del sistema y el inventario real, Symphony Synapse no se hará responsable de las pérdidas o inconvenientes derivados de errores en la actualización de datos. El software no será responsable por la falta de existencias de productos que no hayan sido correctamente registrados o pedidos a tiempo. Se recomienda que el administrador realice auditorías periódicas de inventario y registros de pedidos para garantizar la eficiencia del sistema.
            </p>
            <h4 id="scrollspyHeading4">Modificaciones y Actualizaciones del Software</h4>
            <p>
                Symphony Synapse se reserva el derecho de realizar modificaciones, actualizaciones o mejoras al software en cualquier momento, sin previo aviso, con el fin de mejorar su funcionalidad, seguridad o adaptabilidad. Estas actualizaciones pueden incluir nuevos módulos, funcionalidades adicionales, o cambios en la interfaz de usuario. El usuario acepta que, al continuar utilizando el software, está de acuerdo con las modificaciones que puedan surgir. Algunas actualizaciones pueden requerir que el usuario instale nuevas versiones del software o realice ajustes en su infraestructura tecnológica. Aunque nos esforzamos por mantener el sistema libre de fallos, no garantizamos la ausencia de errores en las actualizaciones o en las versiones anteriores del software.
            </p>
            <h4 id="scrollspyHeading5">Condiciones de Cancelación y Terminación del Servicio</h4>
            <p>
                El usuario tiene derecho a cancelar su suscripción a Symphony Synapse en cualquier momento, mediante una solicitud formal a través del sistema o contactando al soporte técnico. La cancelación no exime al usuario de las obligaciones financieras pendientes, como los pagos por suscripciones no reembolsables o cargos adicionales por servicios premium. En caso de que el software sea descontinuado o que el servicio sea suspendido por causas atribuibles a Symphony Synapse, el usuario recibirá una notificación con la mayor antelación posible y se ofrecerá una solución alternativa o reembolso según corresponda. Si el usuario viola los términos y condiciones establecidos, Symphony Synapse se reserva el derecho de suspender o terminar el acceso a la cuenta sin previo aviso y sin obligación de reembolsar pagos ya efectuados.
            </p>
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