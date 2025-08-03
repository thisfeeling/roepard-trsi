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

    // Verificar sesión al cargar la página
    $.get("../api/check_session.php", function (resp) {
        if (!resp.logged) {
            // Si no está logueado, redirigir a login
            window.location.href = "../pages/login.php";
            return; // Salir si no hay sesión
        }

        // Si está logueado, hacer la petición para los datos
        $.ajax({
            url: "../controllers/datostiemporealController.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                // Mapeo de fuentes de energía a valores numéricos
                const powerSourceMap = {
                    "solar": 1,
                    "battery": 2,
                    "external": 3
                };

                const power_source_numeric = data.power_source.map(
                    value => powerSourceMap[value] || 0
                );

                // Inicialización del gráfico de líneas
                const ctx = document.getElementById('datosentiemporealChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'FPS',
                                data: data.fps,
                                borderColor: 'rgba(54, 162, 235, 1)',
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Personas contadas en 5 minutos',
                                data: data.people_count_5min,
                                borderColor: 'rgb(55, 60, 191)',
                                backgroundColor: 'rgba(69, 49, 170, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            },
                            // ... resto de los datasets ...
                            {
                                label: 'Batería (%)',
                                data: data.battery_percentage,
                                borderColor: 'rgba(153, 102, 255, 1)',
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Fuente de energía',
                                data: power_source_numeric,
                                borderColor: 'rgb(56, 208, 10)',
                                backgroundColor: 'rgba(19, 175, 29, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Hora'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Valores'
                                },
                                ticks: {
                                    callback: function (value) {
                                        const labels = {
                                            1: "Solar",
                                            2: "Batería",
                                            3: "Externa"
                                        };
                                        return labels[value] || value;
                                    }
                                }
                            }
                        }
                    }
                });
            },
            error: function (xhr, status, error) {
                // console.error("Error cargando los datos:", error);
                showModal('Error: ' + error);
                return;
            }
        });
    }, "json").fail(function () {
        // console.error("Error verificando la sesión");
        showModal('Error: ' + error);
        return;
    });
});