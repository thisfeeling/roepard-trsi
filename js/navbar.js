function mostrarHoraColombia() {
    // Obtener la hora actual en Colombia
    const horaColombia = moment().tz("America/Bogota").format("HH:mm:ss YYYY-MM-DD Z");
    const uamDate = document.getElementById("uam-date");
    if (uamDate) {
        uamDate.textContent = horaColombia;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    mostrarHoraColombia();
    setInterval(mostrarHoraColombia, 1000);
});

$(document).ready(function () {
    // Manejo del clic en el botón de logout
    $('#logout-button').on('click', function (event) {
        event.preventDefault(); // Previene el comportamiento por defecto del enlace o botón

        // Enviar petición AJAX para cerrar sesión
        $.ajax({
            url: '../api/logout.php',
            method: 'POST',
            success: function (response) {
                if (response.success) {
                    // Redirige a la página principal
                    window.location.href = '../index.html';
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                alert('Error en la petición: ' + error);
            }
        });
    });
});

