const loginForm = document.getElementById('login-form');

if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const errorMsg = document.getElementById('login-error');

        try {
            // ✅ On utilise un chemin relatif pour éviter les erreurs de port
            const response = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, password })
            });

            const data = await response.json();

            if (response.ok) {
                // 1. SAUVEGARDE
                localStorage.setItem('token', data.token);
                localStorage.setItem('user', JSON.stringify(data.user));

                // 2. ✅ REDIRECT VERS L'ACCUEIL LARAVEL (/)
                window.location.href = '/';
            } else {
                if(errorMsg) {
                    errorMsg.innerText = "❌ " + (data.message || "Identifiants incorrects");
                    errorMsg.style.display = 'block';
                }
            }
        } catch (error) {
            console.error("Erreur Login:", error);
            alert("Serveur inaccessible");
        }
    });
}

// ✅ On sécurise le bouton d'affichage du mot de passe
const togglePassword = document.getElementById('togglePassword');
if (togglePassword) {
    togglePassword.addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.textContent = type === 'password' ? '👁️' : '🙈';
    });
}
