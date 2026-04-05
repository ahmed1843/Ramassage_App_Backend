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
/// --- FOCUS SUR UNE ZONE DEPUIS L'ACCUEIL (VERSION ACCENTS CORRIGÉS) ---
const urlParams = new URLSearchParams(window.location.search);
const zoneRaw = urlParams.get('zone');

if (zoneRaw) {
    // 1. On nettoie le nom : minuscule + on enlève les accents
    const zoneClean = zoneRaw.toLowerCase()
        .normalize("NFD").replace(/[\u0300-\u036f]/g, ""); 

    console.log("Recherche du quartier nettoyé :", zoneClean);

    const coordsZones = {
        'dakar': [14.7167, -17.4677],
        'medina': [14.6850, -17.4480], // "medina" sans accent ici
        'plateau': [14.6667, -17.4333],
        'almadies': [14.7480, -17.5120],
        'grand yoff': [14.7300, -17.4500]
    };

    if (coordsZones[zoneClean]) {
        setTimeout(() => {
            map.setView(coordsZones[zoneClean], 16);
            L.popup()
                .setLatLng(coordsZones[zoneClean])
                .setContent(`📍 <b>Quartier : ${zoneRaw}</b>`)
                .openOn(map);
        }, 600);
    }
}


});
