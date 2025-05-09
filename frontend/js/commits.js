$(document).ready(function () {
  const owner = "thisfeeling";
  const repo = "roepard-trsi";
  const apiUrl = `https://api.github.com/repos/${owner}/${repo}/commits`;

  $.getJSON(apiUrl, function (data) {
    const rows = data.map(commit => [
      commit.commit.message,
      commit.commit.author.name,
      new Date(commit.commit.author.date).toLocaleString(),
      `<a href="${commit.html_url}" target="_blank" class="btn btn-sm btn-outline-light">Ver</a>`
    ]);

    $('#tablaCommits').DataTable({
      data: rows,
      columns: [
        { title: "Mensaje" },
        { title: "Autor" },
        { title: "Fecha" },
        { title: "Ver" }
      ],
      columnDefs: [{ className: 'dt-center', targets: '_all' }],
      // Idioma
      language: {
        url: '/trsi/frontend/dist/datatables/plugins/lang/en-GB.json'
      }
    });
  });
});
