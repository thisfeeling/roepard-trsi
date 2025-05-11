function mostrarHoraColombia() {
    const horaColombia = moment().tz("America/Bogota").format("HH:mm:ss YYYY-MM-DD Z");
    document.getElementById("uam-date").textContent = horaColombia;
  }

  // Mostrar la hora al cargar
  mostrarHoraColombia();

  // Actualizar cada segundo
  setInterval(mostrarHoraColombia, 1000);