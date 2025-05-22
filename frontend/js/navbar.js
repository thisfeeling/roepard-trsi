function mostrarHoraColombia() {
    // Obtener la hora actual en Colombia
    const horaColombia = moment().tz("America/Bogota").format("HH:mm:ss YYYY-MM-DD Z");
    document.getElementById("uam-date").textContent = horaColombia;
  }

  // Mostrar la hora al cargar
  mostrarHoraColombia();

  // Actualizar cada segundo
  setInterval(mostrarHoraColombia, 1000);

  $(document).ready(function() {
    // Manejo del clic en el botón de logout
    $('#logout-button').on('click', function(e) {
        e.preventDefault(); // Previene el comportamiento por defecto del enlace o botón

        // Enviar petición AJAX para cerrar sesión
        $.ajax({
            url: '/trsi/backend/api/logout.php',
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    // Redirige al login o página principal
                    window.location.href = '/trsi/frontend/pages/login.php';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error en la petición: ' + error);
            }
        });
    });
  });

 