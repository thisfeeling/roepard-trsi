fetch("/trsi/backend/controllers/graficaspotenciaController.php")
.then(response => response.json())
    .then(data => {
        // Inicialización del gráfico de barras
        const ctx = document.getElementById('graficaspotenciaChart').getContext('2d');
        new Chart(ctx, {
            type: 'line', // Tipo de gráfico
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'FPS',
                        data: data.fps,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Voltaje Panel',
                        data: data.panel_voltage,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Corriente Panel',
                        data: data.panel_current,
                        borderColor: 'rgba(255, 159, 64, 1)',
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Potencia Panel',
                        data: data.panel_power,
                        borderColor: 'rgba(255, 205, 86, 1)',
                        backgroundColor: 'rgba(255, 205, 86, 0.2)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Radiación Solar',
                        data: data.solar_radiation,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2,
                        fill: true,
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
                        }
                    }
                }
            }
        });
    })
    .catch(error => console.error("Error cargando los datos:", error));