$(document).ready(function () {
  // Repositorio
  const owner = "thisfeeling";
  const repo = "roepard-trsi";
  const apiUrl = `https://api.github.com/repos/${owner}/${repo}/commits`;

  $.getJSON(apiUrl, function (data) {
    const rows = data.map(commit => [
      commit.sha.substring(0, 7), // ID corto del commit
      commit.commit.message,
      commit.commit.author.name,
      new Date(commit.commit.author.date).toLocaleString(),
      `<a href="${commit.html_url}" target="_blank" class="btn btn-sm btn-outline-light">Ver</a>`
    ]);

    $('#tablaCommits').DataTable({
      data: rows,
      columns: [
        { title: "ID" },
        { title: "Mensaje" },
        { title: "Autor" },
        { title: "Fecha" },
        { title: "Acción" }
      ],
      columnDefs: [{ className: 'dt-center', targets: '_all' }],
      // Idioma
      language: {
        url: '/trsi/frontend/dist/datatables/plugins/lang/en-GB.json'
      }
    });
  });
});
