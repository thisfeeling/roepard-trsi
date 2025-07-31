fetch("../controllers/datostiemporealController.php")
    .then(response => response.json()) // Convertir la respuesta a JSON
    .then(data => {
        // Mapeo de fuentes de energía a valores numéricos
        const powerSourceMap = {
            "solar": 1,
            "battery": 2,
            "external": 3,
        };

        const power_source_numeric = data.power_source.map(
            value => powerSourceMap[value] || 0 // Asignar valor numérico o 0 si no está definido
        );

        // Inicialización del gráfico de líneas
        const ctx = document.getElementById('datosentiemporealChart').getContext('2d');
        new Chart(ctx, {
            type: 'line', // Tipo de gráfico
            data: {
                labels: data.labels, // Etiquetas del eje X
                datasets: [
                    {
                        label: 'FPS', // Etiqueta del conjunto de datos
                        data: data.fps, // Datos para el gráfico
                        borderColor: 'rgba(54, 162, 235, 1)', // Color de la línea
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de fondo
                        borderWidth: 2, // Ancho de la línea
                        fill: false, // No llenar el área bajo la línea
                        tension: 0.3 // Curvatura de la línea
                    },
                    // Otros conjuntos de datos para diferentes métricas
                    {
                        label: 'Personas contadas en 5 minutos',
                        data: data.people_count_5min,
                        borderColor: 'rgb(55, 60, 191)',
                        backgroundColor: 'rgba(69, 49, 170, 0.2)',
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
                responsive: true, // Hacer que el gráfico sea responsivo
                maintainAspectRatio: false, // No mantener la relación de aspecto
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Hora' // Título del eje X
                        }
                    },
                    y: {
                        beginAtZero: true, // Comenzar el eje Y en cero
                        title: {
                            display: true,
                            text: 'Valores' // Título del eje Y
                        },
                        ticks: {
                            callback: function (value) {
                                // Personalizar etiquetas del eje Y
                                const labels = {
                                    1: "Solar",
                                    2: "Batería",
                                    3: "Externa"
                                };
                                return labels[value] || value; // Retornar etiqueta personalizada o valor
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(error => console.error("Error cargando los datos:", error)); // Manejo de errores en la carga de datos
