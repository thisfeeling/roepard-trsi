$(document).ready(function () {
    // Forzar recarga completa al retroceder
    window.addEventListener("pageshow", function (event) {
        if (event.persisted) {
            window.location.reload();
        }
    });

    // Manejar clics en botones
    $("#masSobreNosotrosModal").on("click", function () {
        $(".modal").modal("hide");
        setTimeout(function () {
            window.location.href = "../views/about.php";
        }, 300);
    });

    $("#btnGestionUsuarios").on("click", function () {
        $(".modal").modal("hide");
        setTimeout(function () {
            window.location.href = "../views/manage-users.php";
        }, 300);
    });
});
