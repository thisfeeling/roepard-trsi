fetch("/trsi/backend/controllers/graficasdeconteoController.php")
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
        // Inicialización del gráfico de barras
        const ctx = document.getElementById('graficasdeconteoChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar', // Tipo de gráfico
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'FPS',  // Etiqueta del conjunto de datos
                        data: data.fps, // Datos para el gráfico
                        borderColor: 'rgba(54, 162, 235, 1)', // Color de la línea
                        backgroundColor: 'rgba(54, 162, 235, 0.2)', // Color de fondo
                        borderWidth: 2, // Ancho de la línea
                        fill: true, // No llenar el área bajo la línea
                        tension: 0.3 // Curvatura de la línea
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
                    {
                        label: 'Batería (%)',
                        data: data.battery_percentage,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderWidth: 2,
                        fill: true,
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
                responsive: true,  // Hacer que el gráfico sea responsivo
                maintainAspectRatio: false, // Mantener la proporción del gráfico
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Hora' // Título del eje X
                        }
                    },
                    y: {
                        beginAtZero: true,
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
                                return labels[value] || value; // Devolver la etiqueta o el valor si no está en el mapa
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(error => console.error("Error cargando los datos:", error)); // Manejo de errores