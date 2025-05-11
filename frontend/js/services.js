
async function mostrarVersionGitHub() {
    const tagUrl = "https://api.github.com/repos/thisfeeling/roepard-trsi/tags";
    const commitUrl = "https://api.github.com/repos/thisfeeling/roepard-trsi/commits/main";
    let version = "Rev-";
    try {
        // Primero intenta obtener el último tag
        const tagResp = await fetch(tagUrl);
        const tags = await tagResp.json();
        if (tags.length > 0) {
            version += tags[0].name + "-" + tags[0].commit.sha.substring(0, 7);
        } else {
            // Si no hay tags, usa el último commit de main
            const commitResp = await fetch(commitUrl);
            const commit = await commitResp.json();
            version += "main-" + commit.sha.substring(0, 7);
        }
    } catch (e) {
        version += "desconocido";
    }
    document.getElementById("github-version").textContent = version;
}
mostrarVersionGitHub();
