$(document).ready(function () {
  // Definición de variables para el propietario y repositorio de GitHub
  const owner = "thisfeeling";
  const repo = "roepard-trsi";
  const apiUrl = `https://api.github.com/repos/${owner}/${repo}/commits`;

  // Llamada a la API de GitHub para obtener los commits
  $.getJSON(apiUrl, function (data) {
    // Mapeo de los datos de commits a un formato adecuado para la tabla
    const rows = data.map(commit => [
      commit.sha.substring(0, 7), // ID corto del commit
      commit.commit.message, // Mensaje del commit
      commit.commit.author.name, // Nombre del autor
      new Date(commit.commit.author.date).toLocaleString(), // Fecha del commit
      `<a href="${commit.html_url}" target="_blank" class="btn btn-sm btn-outline-light">Ver</a>` // Enlace para ver el commit
    ]);

    // Inicialización de la tabla con DataTables
    $('#tablaCommits').DataTable({
      data: rows, // Datos a mostrar en la tabla
      columns: [
        { title: "ID" },
        { title: "Mensaje" },
        { title: "Autor" },
        { title: "Fecha" },
        { title: "Acción" }
      ],
      columnDefs: [{ className: 'dt-center', targets: '_all' }], // Centrar contenido de todas las columnas
      // Configuración del idioma para DataTables
      language: {
        url: '../dist/datatables/plugins/lang/en-GB.json'
      }
    });
  });
});
