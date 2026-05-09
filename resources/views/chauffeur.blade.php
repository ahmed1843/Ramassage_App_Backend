<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SND - Espace Chauffeur</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body style="background: #f4f7f6; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: sans-serif;">

    <div style="width: 90%; max-width: 400px; background: white; padding: 30px; border-radius: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.1); text-align: center;">
        <div style="font-size: 50px; margin-bottom: 10px;">🚚</div>
        <h1 style="color: #2c3e50; font-size: 22px; font-weight: 800;">Espace Chauffeur</h1>
        <p style="color: #7f8c8d; font-size: 13px; margin-bottom: 25px;">Gérez les alertes de ramassage</p>

        <!-- SELECTION DE LA ZONE -->
        <div style="text-align: left; margin-bottom: 20px;">
            <label style="font-weight: bold; color: #27ae60; font-size: 12px; text-transform: uppercase;">Zone de service :</label>
            <select id="zone-select" style="width: 100%; padding: 12px; border-radius: 12px; border: 2px solid #f0f0f0; font-size: 16px;">
                <option value="Médina">Médina</option>
                <option value="Plateau">Plateau</option>
                <option value="Almadies">Almadies</option>
            </select>
        </div>

        <!-- BOUTONS D'ACTION -->
        <button id="btn-alerte" onclick="envoyerAlerte(true)" style="width: 100%; padding: 20px; background: #e67e22; color: white; border: none; border-radius: 15px; font-size: 16px; font-weight: 900; cursor: pointer; margin-bottom: 10px;">
            📢 ACTIVER L'ALERTE
        </button>

        <button id="btn-stop" onclick="envoyerAlerte(false)" style="width: 100%; padding: 12px; background: #95a5a6; color: white; border: none; border-radius: 15px; font-size: 13px; font-weight: bold; cursor: pointer;">
            🏁 RAMASSAGE TERMINÉ
        </button>

        <div id="status-msg" style="margin-top: 15px; padding: 10px; border-radius: 10px; font-weight: bold; font-size: 13px; display: none;"></div>

        <!-- SIMULATEUR POUR DÉMO PC -->
        <div style="margin-top: 30px; background: #f9f9f9; padding: 15px; border-radius: 20px; border: 1px dashed #ddd;">
            <p style="font-size: 11px; font-weight: bold; color: #7f8c8d; margin-bottom: 10px;">SIMULATEUR DE TRAJET (DÉMO)</p>
            <input type="range" min="0" max="100" value="0" style="width: 100%; accent-color: #27ae60;" oninput="simulerMouvement(this.value)">
            <p style="font-size: 10px; color: #bdc3c7; margin-top: 5px;">Faites glisser pour déplacer le camion sur la carte</p>
        </div>
    </div>

    <script>
        // 1. Alerte ON/OFF
        async function envoyerAlerte(statut) {
            const zoneName = document.getElementById('zone-select').value;
            const btnAlerte = document.getElementById('btn-alerte');
            const msgBox = document.getElementById('status-msg');

            try {
                const response = await fetch('/api/alerte-chauffeur', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ zone_name: zoneName, actif: statut })
                });

                if (response.ok) {
                    msgBox.style.display = "block";
                    if (statut) {
                        btnAlerte.style.background = "#27ae60";
                        btnAlerte.innerText = "ALERTE EN COURS...";
                        msgBox.style.background = "#e8f5e9"; msgBox.style.color = "#27ae60";
                        msgBox.innerText = "🔔 Habitants prévenus !";
                        // Optionnel: démarrer le vrai tracking GPS si on est sur mobile
                        // démarrerTracking(); 
                    } else {
                        btnAlerte.style.background = "#e67e22";
                        btnAlerte.innerText = "📢 ACTIVER L'ALERTE";
                        msgBox.style.background = "#eee"; msgBox.style.color = "#7f8c8d";
                        msgBox.innerText = "✅ Alerte coupée";
                    }
                }
            } catch (e) { alert("Erreur serveur"); }
        }

        // 2. Simulateur de mouvement
        async function simulerMouvement(val) {
            const zoneName = document.getElementById('zone-select').value;
            // Coordonnées de départ (Dakar)
            const startLat = 14.7167, startLng = -17.4677;
            // On fait varier légèrement pour la démo
            const newLat = startLat + (val * 0.0002); 
            const newLng = startLng + (val * 0.0002);

            await fetch('/api/update-truck-gps', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ zone_name: zoneName, lat: newLat, lng: newLng })
            });
        }
    </script>
</body>
</html>
