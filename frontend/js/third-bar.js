fetch("/trsi/backend/controllers/graficasdeconteoController.php")
.then(response => response.json())
    .then(data => {
        const powerSourceMap = {
            "solar": 1,
            "battery": 2,
            "external": 3,
        };

        const power_source_numeric = data.power_source.map(
            value => powerSourceMap[value] || 0
        );
        const ctx = document.getElementById('graficasdeconteoChart').getContext('2d');
        new Chart(ctx, {
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
                        fill: true,
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
    })
    .catch(error => console.error("Error cargando los datos:", error));