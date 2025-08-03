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
            url: "../controllers/graficaspotenciaController.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                const ctx = document.getElementById('graficaspotenciaChart').getContext('2d');
                
                // Configuración del gráfico
                const config = {
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
                                label: 'Voltaje Panel',
                                data: data.panel_voltage,
                                borderColor: 'rgba(255, 99, 132, 1)',
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Corriente Panel',
                                data: data.panel_current,
                                borderColor: 'rgba(255, 159, 64, 1)',
                                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Potencia Panel',
                                data: data.panel_power,
                                borderColor: 'rgba(255, 205, 86, 1)',
                                backgroundColor: 'rgba(255, 205, 86, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Radiación Solar',
                                data: data.solar_radiation,
                                borderColor: 'rgba(75, 192, 192, 1)',
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            },
                            {
                                label: 'Batería (%)',
                                data: data.battery_percentage,
                                borderColor: 'rgba(153, 102, 255, 1)',
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        layout: {
                            padding: {
                                left: 5,
                                right: 5,
                                top: 10,
                                bottom: 5
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    padding: 10,
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Hora',
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    }
                                },
                                ticks: {
                                    maxRotation: 45,
                                    minRotation: 45,
                                    font: {
                                        size: window.innerWidth < 768 ? 8 : 10
                                    },
                                    maxTicksLimit: window.innerWidth < 768 ? 10 : 15
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Valores',
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    }
                                },
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 768 ? 8 : 10
                                    },
                                    maxTicksLimit: 8
                                }
                            }
                        }
                    }
                };

                // Crear instancia del gráfico
                const myChart = new Chart(ctx, config);

                // Manejar el redimensionamiento de la ventana
                let resizeTimeout;
                const handleResize = function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(function() {
                        myChart.update();
                    }, 200);
                };
                window.addEventListener('resize', handleResize);
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