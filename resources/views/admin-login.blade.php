<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin - Dakar Propre</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- ✅ Correction du lien Tailwind -->
</head>
<body class="bg-slate-900 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-xl shadow-2xl w-96">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-2">Administration</h2>
        <p class="text-gray-500 text-center text-sm mb-6">Connectez-vous pour gérer les déchets</p>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email Admin</label>
                <input id="email" type="email" placeholder="Fatou212@gmail.com" 
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input id="password" type="password" placeholder="••••••••" 
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-green-500 outline-none">
            </div>
            
            <button onclick="connexion()" 
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg transition duration-300">
                Se connecter
            </button>
        </div>

        <p id="error-msg" class="text-red-500 text-sm mt-4 text-center hidden"></p>
    </div>

    <script>
        async function connexion() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorElement = document.getElementById('error-msg');

            try {
                // 🚀 CORRECTION : Utilise l'URL complète avec le port 8000
                const res = await fetch('http://127.0.0', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'Accept': 'application/json' 
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await res.json();

                if (res.ok) {
                    // 🛡️ On vérifie le rôle renvoyé par Laravel
                    if (data.user && data.user.role === 'admin') {
                        localStorage.setItem('admin_token', data.token);
                        localStorage.setItem('is_admin', 'true'); 
                        window.location.href = '/admin-dashboard';
                    } else {
                        errorElement.innerText = "Accès refusé : Vous n'êtes pas administrateur.";
                        errorElement.classList.remove('hidden');
                    }
                } else {
                    errorElement.innerText = data.message || "Identifiants incorrects.";
                    errorElement.classList.remove('hidden');
                }
            } catch (err) {
                console.error(err);
                alert("Erreur : Vérifie que PHP Artisan Serve est lancé sur le port 8000.");
            }
        }
    </script>
</body>
</html>
