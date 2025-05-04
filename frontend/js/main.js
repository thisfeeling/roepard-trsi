$(document).ready(function () {
    // Forzar recarga completa al retroceder
    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    $("#btnGestionUsuarios").on("click", function () {
        $(".modal").modal("hide");
        setTimeout(function () {
            window.location.href = "/trsi/frontend/pages/manage-users.php";
        }, 300);
    });
});
