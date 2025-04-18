$(document).ready(function() {
    $('#LoginForm').submit(function (event) {
        event.preventDefault();
        console.log("Login process started");
        // Obtener los valores de los campos de entrada
        // Eliminar espacios en blanco al inicio y al final
        var username = $.trim($('input[name="email"]').val());
        var password = $.trim($('input[name="password"]').val());
        console.log("Email: " + username);
        console.log("Password: " + password);
        LoginUser(username, password);
    });
});

// Funcion para logear el usuario
function LoginUser(username, password) {
    $.ajax({
        url: '../backend/AuthController.php',
        method: 'POST',
        data: { username: username, password: password },
        dataType: 'json',
        success: function (response) {
            console.log("Response: ");
            console.log(response);
            // Verificación de acceso modal
            var $modal = $('#LoginModal');
            if (response.status == "success") {
                console.log("Successful login.");
                console.log("Redirecting to home...");
                // Configurar el contenido del modal
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text("Successful login.");
                $modal.on('hidden.bs.modal', function () {
                    window.location.href = "../index.php";
                });
                // Mostrar el modal
                $modal.modal('show');
            } else {
                console.log("Error: " + response.message);
                // Mostrar un modal para errores
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text("Incorrect Credentials.");
                $modal.modal('show');
            }
        }
    });
};
