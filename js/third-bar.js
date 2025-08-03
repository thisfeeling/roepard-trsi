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
            url: "../controllers/graficasdeconteoController.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                // Mapeo de fuentes de energía a valores numéricos
                const powerSourceMap = {
                    "solar": 1,
                    "battery": 2,
                    "external": 3,
                };

                const power_source_numeric = data.power_source.map(
                    value => powerSourceMap[value] || 0 // Asignar valor numérico o 0 si no está definido
                );
                // Configuración del gráfico
                const ctx = document.getElementById('graficasdeconteoChart').getContext('2d');
                
                const config = {
                    type: 'bar',
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
                                label: 'Personas/5min',
                                data: data.people_count_5min,
                                borderColor: 'rgb(55, 60, 191)',
                                backgroundColor: 'rgba(69, 49, 170, 0.2)',
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
                                    maxTicksLimit: 8,
                                    callback: function(value, index, values) {
                                        // Solo mostrar etiquetas personalizadas para la fuente de energía
                                        if (this.scale && this.scale.id === 'y' && this.chart && this.chart.scales.y === this.scale) {
                                            const labels = {
                                                1: "Solar",
                                                2: "Batería",
                                                3: "Externa"
                                            };
                                            return labels[value] !== undefined ? labels[value] : value;
                                        }
                                        return value;
                                    }
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
