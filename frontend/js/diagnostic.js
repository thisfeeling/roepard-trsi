$(document).ready(function() {
    $('#btn-db').on('click', function() {
        $.ajax({
            url: '/trsi/backend/api/diagnostic.php',
            method: 'POST',
            data: { action: 'db' },
            success: function(response) {
                console.log('Respuesta del servidor:', response); // Para debug
                
                let html = response.db_status ? 
                    '<span style="color: #28a745;">✅ Conectada</span>' : 
                    '<span style="color: #dc3545;">🗄️ Desconectada</span>';
    
                if (response.db_status) {
                    html += '<ul style="list-style:none; padding-left:0; margin-top: 10px;">';
                    html += `<li>Permiso SELECT: ${response.perm_select ? '✅' : '❌'}</li>`;
                    html += `<li>Permiso INSERT: ${response.perm_insert ? '✅' : '❌'}</li>`;
                    html += `<li>Permiso UPDATE: ${response.perm_update ? '✅' : '❌'}</li>`;
                    html += `<li>Permiso DELETE: ${response.perm_delete ? '✅' : '❌'}</li>`;
                    html += '</ul>';
                }
                $('#db-result').html(html);
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición:', error); // Para debug
                $('#db-result').html('<span style="color: #dc3545;">Error al verificar</span>');
            }
        });
    });

    $('#btn-jetson').on('click', function() {
        $.ajax({
            url: '/trsi/backend/api/diagnostic.php',
            method: 'POST',
            data: { action: 'jetson' },
            success: function(response) {
                $('#jetson-result').html(response.jetson_status ? 
                    '<span style="color: #28a745;">🤖 Disponible</span>' : 
                    '<span style="color: #dc3545;">🤖 Sin respuesta</span>');
            },
            error: function() {
                $('#jetson-result').html('<span style="color: #dc3545;">Error al verificar</span>');
            }
        });
    });
});
