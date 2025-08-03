$(document).ready(function () {
    async function mostrarVersionGitHub() {
        // URL de la API de GitHub para obtener el último tag y el último commit
        const tagUrl = "https://api.github.com/repos/thisfeeling/roepard-trsi/tags";
        const commitUrl = "https://api.github.com/repos/thisfeeling/roepard-trsi/commits/main";
        let version = "";
        try {
            // Primero intenta obtener el último tag
            const tagResp = await fetch(tagUrl);
            const tags = await tagResp.json();
            if (tags.length > 0) {
                version += tags[0].name + "-" + tags[0].commit.sha.substring(0, 7);
            } else {
                // Si no hay tags, usa el último commit de main
                const commitResp = await fetch(commitUrl);
                const commit = await commitResp.json();
                version += "main-" + commit.sha.substring(0, 7);
            }
        } catch (e) {
            version += "desconocido"; // Manejo de errores
        }
        document.getElementById("github-version").textContent = version; // Mostrar la versión en el DOM
    }

    mostrarVersionGitHub(); // Mostrar la versión en el DOM
    // Mostrar el modal al hacer clic en el botón Sistema
    $('#btnSistema').click(function () {
        $('#sistemaModal').modal('show');
    });

    // Cargar información del sistema
    function cargarInfoSistema() {

        // Obtener información del navegador
        const userAgent = navigator.userAgent;
        const screenSize = `${window.screen.width}x${window.screen.height}`;

        // Mostrar información del navegador
        $('#navegador-info').text(navigator.userAgentData?.brands[0]?.brand || 'Navegador no detectado');
        $('#pantalla-info').text(screenSize);
    }

    // Cargar información al abrir el modal
    $('#sistemaModal').on('shown.bs.modal', function () {
        cargarInfoSistema();
    });

    // Función para verificar el estado de la base de datos
    function verificarEstadoDB() {
        // Mostrar indicador de carga
        $('#db-status-text').text('Verificando...');
        $('#db-status-indicator').removeClass('d-none').addClass('spinner-border spinner-border-sm text-warning');
        
        $.ajax({
            url: '../api/diagnostic.php',
            method: 'POST',
            data: { action: 'db' },
            dataType: 'json',
            success: function(response) {
                // Ocultar spinner
                $('#db-status-indicator').addClass('d-none');
                
                if (response.db_status) {
                    // Conexión exitosa
                    $('#db-status-text')
                        .removeClass('text-danger')
                        .addClass('text-success fw-bold')
                        .html('<i class="fas fa-check-circle me-1"></i> Conectado');
                    
                    // Mostrar permisos en la consola (opcional)
                    // console.log('Permisos de la base de datos:', {
                    //     SELECT: response.perm_select,
                    //     INSERT: response.perm_insert,
                    //     UPDATE: response.perm_update,
                    //     DELETE: response.perm_delete
                    // });
                } else {
                    // Error en la conexión
                    $('#db-status-text')
                        .removeClass('text-success')
                        .addClass('text-danger fw-bold')
                        .html('<i class="fas fa-times-circle me-1"></i> Error de conexión');
                }
            },
            error: function(xhr, status, error) {
                // console.error('Error al verificar la base de datos:', error);
                $('#db-status-indicator').addClass('d-none');
                $('#db-status-text')
                    .removeClass('text-success')
                    .addClass('text-danger fw-bold')
                    .html('<i class="fas fa-exclamation-triangle me-1"></i> Error al verificar');
            }
        });
    }
    
    // Verificar el estado de la base de datos al cargar la página
    verificarEstadoDB();
    
    // Verificar el estado de la base de datos cada 5 minutos
    setInterval(verificarEstadoDB, 5 * 60 * 1000);
    
    // Verificar el estado de la base de datos cuando se abre el modal
    $('#sistemaModal').on('shown.bs.modal', function() {
        verificarEstadoDB();
    });
});