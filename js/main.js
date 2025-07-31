$(document).ready(function () {
    // Forzar recarga completa al retroceder
    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    // Manejo del clic en el botón de gestión de usuarios
    $("#btnGestionUsuarios").on("click", function () {
        $(".modal").modal("hide"); // Ocultar modales abiertos
        setTimeout(function () {
            window.location.href = "/trsi/frontend/pages/users.php"; // Redirigir a la página de gestión de usuarios
        }, 300);
    });
});
