// 1. Déclaration sécurisée du groupe de marqueurs
if (typeof markersGroup === 'undefined') {
    var markersGroup; 
}

async function actualiserCarte(map) {
    try {
        const response = await fetch('/api/reports'); 

        if (!response.ok) throw new Error("Erreur réseau ou route introuvable");
        
        const reports = await response.json();

        // On vide les anciens marqueurs pour éviter les doublons
        markersGroup.clearLayers();

        reports.forEach(report => {
            const lat = parseFloat(report.latitude || report.lat);
            const lng = parseFloat(report.longitude || report.lng);

            if (!lat || !lng) return;

            // --- CONSTRUCTION DU CONTENU DU POPUP ---
            let popupContent = `
                <div style="min-width:160px; font-family: sans-serif;">
                    <strong style="color: #27ae60; font-size: 14px;">${report.title || 'Sans titre'}</strong><br>
                    <p style="margin: 5px 0; color: #555; font-size: 13px;">${report.description || 'Pas de description'}</p>
            `;

            // ✅ CORRECTION ICI : Ajout du $ et de l'IP correcte (127.0.0.1:8000)
            if (report.image) {
             const imageUrl = `http://127.0.0.1:8000/storage/${report.image}`;
                
                console.log("Lien généré pour l'ID " + report.id + " :", imageUrl);

                popupContent += `
                    <img src="${imageUrl}" 
                         style="width:100%; border-radius:10px; margin-top:5px; border: 1px solid #eee; display:block;" 
                         onerror="this.src='https://placeholder.com'">
                `;
            }

            popupContent += `
                    <hr style="border:0; border-top:1px solid #eee; margin:10px 0;">
                    <span style="font-size:11px; color:#999;">Statut: ${report.status}</span>
                </div>
            `;

            // 3. Création du marqueur
            const marker = L.circleMarker([lat, lng], {
                radius: 10,
                fillColor: report.status === 'pending' ? 'red' : 'green',
                color: "#fff",
                weight: 2,
                fillOpacity: 0.9
            }).bindPopup(popupContent);

            markersGroup.addLayer(marker);
        });

        console.log(`Carte mise à jour : ${reports.length} points affichés.`);

    } catch (e) { 
        console.error("Erreur mise à jour carte :", e); 
    }
}

// 2. Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // On centre sur Dakar
    const map = L.map('map').setView([14.7167, -17.4677], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    markersGroup = L.layerGroup().addTo(map);

    // Premier chargement
    actualiserCarte(map);

    // Rafraîchissement auto toutes les 30 secondes
    setInterval(() => actualiserCarte(map), 30000);
});
