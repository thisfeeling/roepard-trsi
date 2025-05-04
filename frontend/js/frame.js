fetch("/trsi/backend/controllers/FrameController.php")
    .then(response => response.json())
    .then(data => {
        const ctx = document.getElementById('usuariosChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Usuarios registrados',
                    data: data.data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

function mostrarHoraColombia() {
    const horaColombia = moment().tz("America/Bogota").format("HH:mm:ss YYYY-MM-DD Z");
    document.getElementById("horaColombia").textContent = horaColombia;
  }

  // Mostrar la hora al cargar
  mostrarHoraColombia();

  // Actualizar cada segundo
  setInterval(mostrarHoraColombia, 1000);