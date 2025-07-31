$(document).ready(function () {
    // Verificar sesión al cargar la página
    $.get("../api/check_session.php", function (resp) {
        if (!resp.logged) {
            // Si no está logueado, redirigir a login
            window.location.href = "../pages/login.php";
        } else {
            // Si está logueado, redirigir a services
            window.location.href = "../pages/services.php";
        }
    }, "json").fail(function () {
        // En caso de error en la solicitud, redirigir a login
        // window.location.href = "../pages/login.php";
    });
});