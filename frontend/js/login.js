$(document).ready(function() {
    $('#LoginForm').submit(function (event) {
        event.preventDefault();
        // console.log("Login process started");

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
    // Verificar si los campos están vacíos
    if (!username || !password) {
        var $modal = $('#LoginModal');
        $modal.find('.modal-title').text("Error");
        $modal.find('.modal-body').text("Por favor, completa todos los campos.");
        $modal.modal('show');
        return; // Salir de la función si hay campos vacíos
    }

    $.ajax({
        url: '/trsi/backend/api/auth.php',
        method: 'POST',
        data: { username: username, password: password },
        dataType: 'json',
        success: function (message) {
            var $modal = $('#LoginModal');
            if (message.status === "success") {
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text("Login successful.");
                $modal.modal('show');

                $modal.on('hidden.bs.modal', function () {
                    window.location.href = "/trsi/index.php";
                });
            } else {
                // Asegúrate de que el mensaje de error se maneje correctamente
                $modal.find('.modal-title').text("Login response: ");
                $modal.find('.modal-body').text(message.message || "Error desconocido.");
                $modal.modal('show');
            }
        },
        error: function(xhr, status, error) {
            var $modal = $('#LoginModal');
            $modal.find('.modal-title').text("Error");
            $modal.find('.modal-body').text("Ocurrió un error en la conexión. Inténtalo de nuevo.");
            $modal.modal('show');
        }
    });
}
