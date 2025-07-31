
async function injectHTML(selector, url) {
    const resp = await fetch(url);
    if (!resp.ok) return;
    document.querySelector(selector).innerHTML = await resp.text();
}

document.addEventListener('DOMContentLoaded', () => {
    injectHTML('#navbar-layout', '../components/navbar.php');
});