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
            url: "../controllers/comparativaController.php",
            method: "GET",
            dataType: "json",
            success: function (data) {
                // Configuración común para ambos gráficos
                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    devicePixelRatio: Math.min(window.devicePixelRatio, 2), // Limitar a 2x para mejor rendimiento
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    },
                    elements: {
                        line: {
                            tension: 0.3,
                            borderWidth: 2,
                            borderJoinStyle: 'round',
                            fill: false
                        },
                        point: {
                            radius: 3,
                            hoverRadius: 5,
                            hitRadius: 10,
                            hoverBorderWidth: 2
                        },
                        bar: {
                            borderRadius: 4,
                            borderSkipped: false,
                            borderWidth: 1
                        }
                    },
                    layout: {
                        padding: {
                            left: 8,
                            right: 8,
                            top: 10,
                            bottom: 10
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'center',
                            labels: {
                                boxWidth: 12,
                                padding: 12,
                                font: {
                                    size: () => window.innerWidth < 768 ? 11 : 13
                                },
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: {
                                size: 12,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 12
                            },
                            padding: 10,
                            cornerRadius: 4,
                            displayColors: true,
                            mode: 'index',
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: true,
                                borderColor: 'rgba(0, 0, 0, 0.1)'
                            },
                            title: {
                                display: true,
                                text: 'Hora',
                                font: {
                                    size: () => window.innerWidth < 768 ? 11 : 13,
                                    weight: 'bold'
                                },
                                padding: { top: 10, bottom: 10 }
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                font: {
                                    size: () => window.innerWidth < 768 ? 9 : 11
                                },
                                maxTicksLimit: () => window.innerWidth < 768 ? 8 : 12,
                                padding: 5
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: true,
                                borderColor: 'rgba(0, 0, 0, 0.1)'
                            },
                            title: {
                                display: true,
                                text: 'Valores',
                                font: {
                                    size: () => window.innerWidth < 768 ? 11 : 13,
                                    weight: 'bold'
                                },
                                padding: { bottom: 10 }
                            },
                            ticks: {
                                font: {
                                    size: () => window.innerWidth < 768 ? 9 : 11
                                },
                                maxTicksLimit: 8,
                                padding: 5,
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : value.toFixed(2);
                                }
                            }
                        }
                    }
                };

                // Configuración de la cuadrícula
                const gridColor = 'rgba(0, 0, 0, 0.05)';
                const borderColor = 'rgba(0, 0, 0, 0.1)';

                // Gráfico de Conteo de Personas (Barras)
                const ctx1 = document.getElementById('conteopersonasChart').getContext('2d');
                
                // Función para manejar el redimensionamiento
                const handleChartResize = (chart) => {
                    if (!chart || !chart.canvas) return;
                    
                    const container = chart.canvas.parentElement?.parentElement;
                    if (!container) return;
                    
                    const containerWidth = container.offsetWidth;
                    const aspectRatio = 16/9;
                    
                    // Ajustar el padding del contenedor para mantener la relación de aspecto
                    chart.canvas.parentElement.style.paddingBottom = `${100/aspectRatio}%`;
                    
                    // Usar requestAnimationFrame para mejor rendimiento
                    requestAnimationFrame(() => {
                        if (chart && typeof chart.resize === 'function') {
                            chart.resize();
                        }
                    });
                };

                // Configuración común de datasets para el gráfico de barras
                const barDataset = {
                    label: 'Personas/5min',
                    data: data.people_count_5min,
                    borderColor: 'rgba(55, 60, 191, 0.8)',
                    backgroundColor: 'rgba(69, 49, 170, 0.7)',
                    hoverBackgroundColor: 'rgba(55, 60, 191, 0.9)',
                    hoverBorderColor: 'rgba(55, 60, 191, 1)',
                };

                const chart1 = new Chart(ctx1, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [barDataset]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            x: {
                                grid: {
                                    display: true,
                                    color: gridColor,
                                    drawBorder: true,
                                    borderColor: borderColor
                                },
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    },
                                    maxRotation: 45,
                                    minRotation: 45,
                                    padding: 10
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    color: gridColor,
                                    drawBorder: true,
                                    borderColor: borderColor
                                },
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    },
                                    padding: 10
                                }
                            }
                        },
                        plugins: {
                            ...commonOptions.plugins,
                            legend: {
                                ...commonOptions.plugins.legend,
                                align: 'center',
                                labels: {
                                    ...commonOptions.plugins.legend.labels,
                                    usePointStyle: true,
                                    padding: 20
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 10,
                                cornerRadius: 4,
                                displayColors: true,
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });

                // Gráfico de Potencia (Líneas)
                const ctx2 = document.getElementById('potenciaconsumidaChart').getContext('2d');
                
                // Configuración de colores para el gráfico de líneas
                const lineColors = [
                    'rgba(54, 162, 235, 1)',    // Azul
                    'rgba(255, 159, 64, 1)',    // Naranja
                    'rgba(153, 102, 255, 1)',   // Morado
                    'rgba(255, 99, 132, 1)',    // Rojo
                    'rgba(75, 192, 192, 1)',    // Verde agua
                    'rgba(255, 205, 86, 1)'     // Amarillo
                ];

                const chart2 = new Chart(ctx2, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'FPS',
                                data: data.fps,
                                borderColor: lineColors[0],
                                backgroundColor: lineColors[0].replace('1)', '0.1)'),
                                pointBackgroundColor: lineColors[0],
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: lineColors[0],
                                borderWidth: 2,
                                pointBorderWidth: 2,
                                pointHoverRadius: 6,
                                pointHoverBorderWidth: 2,
                                tension: 0.3
                            },
                            {
                                label: 'Potencia Panel',
                                data: data.panel_power,
                                borderColor: lineColors[1],
                                backgroundColor: lineColors[1].replace('1)', '0.1)'),
                                pointBackgroundColor: lineColors[1],
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: lineColors[1],
                                borderWidth: 2,
                                pointBorderWidth: 2,
                                pointHoverRadius: 6,
                                pointHoverBorderWidth: 2,
                                tension: 0.3
                            },
                            {
                                label: 'Batería (%)',
                                data: data.battery_percentage,
                                borderColor: lineColors[2],
                                backgroundColor: lineColors[2].replace('1)', '0.1)'),
                                pointBackgroundColor: lineColors[2],
                                pointBorderColor: '#fff',
                                pointHoverBackgroundColor: '#fff',
                                pointHoverBorderColor: lineColors[2],
                                borderWidth: 2,
                                pointBorderWidth: 2,
                                pointHoverRadius: 6,
                                pointHoverBorderWidth: 2,
                                tension: 0.3
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            x: {
                                grid: {
                                    display: true,
                                    color: gridColor,
                                    drawBorder: true,
                                    borderColor: borderColor
                                },
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    },
                                    maxRotation: 45,
                                    minRotation: 45,
                                    padding: 10
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    display: true,
                                    color: gridColor,
                                    drawBorder: true,
                                    borderColor: borderColor
                                },
                                ticks: {
                                    font: {
                                        size: window.innerWidth < 768 ? 10 : 12
                                    },
                                    padding: 10,
                                    callback: function(value) {
                                        // Verificar si this.scale está definido
                                        if (this && this.scale && this.scale.options && this.scale.options.ticks && this.scale.options.ticks.suffix) {
                                            return value + this.scale.options.ticks.suffix;
                                        }
                                        return value;
                                    }
                                }
                            }
                        },
                        plugins: {
                            ...commonOptions.plugins,
                            legend: {
                                ...commonOptions.plugins.legend,
                                align: 'center',
                                labels: {
                                    ...commonOptions.plugins.legend.labels,
                                    usePointStyle: true,
                                    padding: 20,
                                    generateLabels: function(chart) {
                                        const data = chart.data;
                                        if (data.labels.length && data.datasets.length) {
                                            return data.datasets.map((dataset, i) => ({
                                                text: dataset.label,
                                                fillStyle: dataset.borderColor,
                                                strokeStyle: dataset.borderColor,
                                                lineWidth: 2,
                                                pointStyle: 'circle',
                                                hidden: !chart.isDatasetVisible(i),
                                                lineCap: 'round',
                                                lineJoin: 'round'
                                            }));
                                        }
                                        return [];
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleFont: {
                                    size: 12,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 10,
                                cornerRadius: 4,
                                displayColors: true,
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += context.parsed.y;
                                            if (context.dataset.label.includes('Batería')) {
                                                label += '%';
                                            } else if (context.dataset.label.includes('Potencia')) {
                                                label += ' W';
                                            }
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });

                // Configurar el observador de redimensionamiento
                let resizeTimer;
                const resizeObserver = new ResizeObserver(entries => {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(() => {
                        if (chart1) handleChartResize(chart1);
                        if (chart2) handleChartResize(chart2);
                    }, 100);
                });

                // Función para actualizar los tamaños de los gráficos
                const updateChartSizes = () => {
                    try {
                        if (chart1 && typeof chart1.resize === 'function') {
                            handleChartResize(chart1);
                        }
                        if (chart2 && typeof chart2.resize === 'function') {
                            handleChartResize(chart2);
                        }
                    } catch (e) {
                        console.error('Error al actualizar los tamaños de los gráficos:', e);
                    }
                };

                // Observar cambios en el contenedor principal
                const chartContainer = document.querySelector('.chart-grid');
                if (chartContainer) {
                    resizeObserver.observe(chartContainer);
                }

                // Inicializar tamaños
                if (chart1) handleChartResize(chart1);
                if (chart2) handleChartResize(chart2);

                // Limpiar al desmontar
                window.addEventListener('beforeunload', () => {
                    resizeObserver.disconnect();
                });

                // Asegurarse de que los gráficos estén definidos antes de agregar event listeners
                if (chart1 && chart2) {
                    // Actualizar tamaños después de que se cargue todo el contenido
                    if (document.readyState === 'complete') {
                        setTimeout(updateChartSizes, 100);
                    } else {
                        window.addEventListener('load', function() {
                            setTimeout(updateChartSizes, 100);
                        });
                    }
                }
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















