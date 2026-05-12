// ===== THEME TOGGLE =====
const btn = document.getElementById('toggleBtn');

if (btn) {
    // jib theme mn localStorage
    if (localStorage.getItem('theme') === 'light') {
        document.body.classList.add('light');
        btn.textContent = '☀️ Light';
    }

    btn.addEventListener('click', function () {
        document.body.classList.toggle('light');

        if (document.body.classList.contains('light')) {
            localStorage.setItem('theme', 'light');
            btn.textContent = '☀️ Light';
        } else {
            localStorage.setItem('theme', 'dark');
            btn.textContent = '🌙 Dark';
        }
    });
}