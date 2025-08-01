$(document).ready(function () {
    // Manejo del envío del formulario de inicio de sesión
    $('#LoginForm').submit(function (event) {
        event.preventDefault(); // Prevenir el comportamiento por defecto del formulario
        // console.log("Login process started");

        // Obtener los valores de los campos de entrada
        var username = $.trim($('input[name="email"]').val()); // 'email' es el campo combinado para email, username o phone
        var password = $.trim($('input[name="password"]').val()); // Obtener la contraseña

        console.log("Username: " + username);
        console.log("Password: " + password);

        // Llamar a la función para hacer el login
        LoginUser(username, password);
    });
});

// Función para loguear al usuario
function LoginUser(username, password) {
    // Verificar si los campos están vacíos
    if (!username || !password) {
        var $modal = $('#LoginModal');
        $modal.find('.modal-title').text("Error");
        $modal.find('.modal-body').text("Por favor, completa todos los campos.");
        $modal.modal('show');
        return; // Salir de la función si hay campos vacíos
    }

    // Enviar datos de inicio de sesión al servidor
    $.ajax({
        url: '../api/auth_user.php', // URL de la API de autenticación
        method: 'POST', // Método HTTP para la petición
        data: { username: username, password: password }, // Datos a enviar en la petición
        dataType: 'json', // Tipo de dato esperado en la respuesta
        success: function (message) {
            var $modal = $('#LoginModal');
            if (message.status === "success") {
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text("Login successful.");
                $modal.modal('show');

                // Redirigir al usuario después de cerrar el modal
                $modal.on('hidden.bs.modal', function () {
                    window.location.href = "../pages/services.php";
                });
            } else {
                // Manejo de errores en el inicio de sesión
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text(message.message || "Error desconocido.");
                $modal.modal('show');
            }
        },
        error: function(xhr, status, error) {
            // Manejo de errores en la petición
            var $modal = $('#LoginModal');
            $modal.find('.modal-title').text("Error");
            $modal.find('.modal-body').text("Ocurrió un error en la conexión. Inténtalo de nuevo.");
            $modal.modal('show');
        }
    });
}
