$(document).ready(function() {
    $('#LoginForm').submit(function (event) {
        event.preventDefault();
        console.log("Login process started");

        // Obtener los valores de los campos de entrada
        var username = $.trim($('input[name="email"]').val()); // 'email' es el campo combinado para email, username o phone
        var password = $.trim($('input[name="password"]').val());

        // console.log("Username: " + username);
        // console.log("Password: " + password);

        // Llamar a la función para hacer el login
        LoginUser(username, password);
    });
});

// Función para loguear al usuario
function LoginUser(username, password) {
    $.ajax({
        url: '/trsi/backend/controllers/AuthController.php',
        method: 'POST',
        data: { username: username, password: password },
        dataType: 'json',
        success: function (response) {
            // console.log("Response: ", response);

            // Verificación de acceso y manejo del modal
            var $modal = $('#LoginModal');
            if (response.status == "success") {
                // console.log("Successful login.");
                // console.log("Redirecting to home...");

                // Configurar el contenido del modal
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text("Login successful.");

                // Mostrar el modal
                $modal.modal('show');

                // Redirigir después de cerrar el modal
                $modal.on('hidden.bs.modal', function () {
                    window.location.href = "/trsi/index.php"; // Redirige a la página principal
                });
            } else {
                // console.log("Error: " + response.message);

                // Mostrar un modal para el error
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text("Incorrect Credentials.");
                $modal.modal('show');
            }
        },
        error: function(xhr, status, error) {
            // console.log("AJAX Error: " + error);
        }
    });
};
