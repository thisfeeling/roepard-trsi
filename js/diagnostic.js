$(document).ready(function () {
    // Función para mostrar un modal con un mensaje
    function showModal(message) {
        $('#modalMessageContent').text(message); // Establecer el contenido del modal
        $('#modalMessage').modal('show'); // Mostrar el modal
        // Remover la clase de fondo modal si queda atascada
        document.addEventListener('hidden.bs.modal', function () {
            document.body.classList.remove('modal-open'); // Remover clase de modal abierto
            document.querySelectorAll('.modal-backdrop').forEach(function (el) {
                el.remove(); // Remover el fondo modal
            });
        });
    }

    // Manejo del clic en el botón para verificar el estado de la base de datos
    $('#btn-db').on('click', function() {
        $.ajax({
            url: '../api/diagnostic.php',
            method: 'POST',
            data: { action: 'db' },
            success: function(response) {
                // console.log('Respuesta del servidor:', response); // Para debug
                // Mostrar el estado de la conexión a la base de datos
                let html = response.db_status ? 
                    '<span style="color: #28a745;">✅ Conectada</span>' : 
                    '<span style="color: #dc3545;">🗄️ Desconectada</span>';
                // Si la conexión es exitosa, mostrar los permisos de la base de datos
                if (response.db_status) {
                    html += '<ul style="list-style:none; padding-left:0; margin-top: 10px;">';
                    html += `<li>Permiso SELECT: ${response.perm_select ? '✅' : '❌'}</li>`;
                    html += `<li>Permiso INSERT: ${response.perm_insert ? '✅' : '❌'}</li>`;
                    html += `<li>Permiso UPDATE: ${response.perm_update ? '✅' : '❌'}</li>`;
                    html += `<li>Permiso DELETE: ${response.perm_delete ? '✅' : '❌'}</li>`;
                    html += '</ul>';
                }
                $('#db-result').html(html); // Mostrar resultado en el DOM
            },
            error: function(xhr, status, error) {
                // Manejo de errores en la petición
                // console.error('Error en la petición:', error); // Para debug
                $('#db-result').html('<span style="color: #dc3545;">Error al verificar</span>');
                showModal('Error: ' + error);
                return;
            }
            
        });
    });

    // Manejo del clic en el botón para verificar el estado del Jetson
    $('#btn-jetson').on('click', function() {
        $.ajax({
            url: '../api/diagnostic.php',
            method: 'POST',
            data: { action: 'jetson' },
            success: function(response) {
                // Mostrar el estado del Jetson
                $('#jetson-result').html(response.jetson_status ? 
                    '<span style="color: #28a745;">🤖 Disponible</span>' : 
                    '<span style="color: #dc3545;">🤖 Sin respuesta</span>');
            },
            error: function(xhr, status, error) {
                // Manejo de errores en la petición
                $('#jetson-result').html('<span style="color: #dc3545;">Error al verificar</span>');
                showModal('Error: ' + error);
                return;
            }
        });
    });
});
