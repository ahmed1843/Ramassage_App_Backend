// 1. Déclarations globales
let mainMap;
let markersGroup;

// 2. Fonction pour récupérer les signalements
async function actualiserCarte(map) {
    try {
        const response = await fetch('/api/reports'); 
        if (!response.ok) throw new Error("Erreur réseau");
        
        const reports = await response.json();
        markersGroup.clearLayers();

        reports.forEach(report => {
            const lat = parseFloat(report.latitude || report.lat);
            const lng = parseFloat(report.longitude || report.lng);
            if (!lat || !lng) return;

            let popupContent = `
                <div style="min-width:160px; font-family: sans-serif;">
                    <strong style="color: #27ae60; font-size: 14px;">${report.title || 'Signalement'}</strong><br>
                    <p style="margin: 5px 0; color: #555; font-size: 13px;">${report.description || ''}</p>
            `;

            if (report.image) {
                const imageUrl = `${window.location.origin}/storage/${report.image}`;
                popupContent += `
                    <img src="${imageUrl}" style="width:100%; border-radius:10px; margin-top:5px;" 
                         onerror="this.src='https://placeholder.com'">
                `;
            }

            popupContent += `
                    <hr style="border:0; border-top:1px solid #eee; margin:10px 0;">
                    <span style="font-size:11px; color:#999;">Statut: ${report.status}</span>
                </div>
            `;

            const marker = L.circleMarker([lat, lng], {
                radius: 10,
                fillColor: report.status === 'pending' ? '#e74c3c' : '#27ae60',
                color: "#fff",
                weight: 2,
                fillOpacity: 0.9
            }).bindPopup(popupContent);

            markersGroup.addLayer(marker);
        });
    } catch (e) { console.error("Erreur mise à jour carte:", e); }
}

// 3. Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    mainMap = L.map('map').setView([14.7167, -17.4677], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mainMap);

    markersGroup = L.layerGroup().addTo(mainMap);

    // ✅ On a supprimé la ligne addControl qui faisait planter le script

    actualiserCarte(mainMap);
    setInterval(() => actualiserCarte(mainMap), 30000);

    const urlParams = new URLSearchParams(window.location.search);
    const zoneRaw = urlParams.get('zone');
    if (zoneRaw) {
        const zoneClean = zoneRaw.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, ""); 
        const coordsZones = {
            'dakar': [14.7167, -17.4677],
            'medina': [14.6850, -17.4480],
            'plateau': [14.6667, -17.4333],
            'almadies': [14.7480, -17.5120],
            'grand yoff': [14.7300, -17.4500]
        };
        if (coordsZones[zoneClean]) {
            setTimeout(() => {
                mainMap.setView(coordsZones[zoneClean], 16);
                L.popup().setLatLng(coordsZones[zoneClean]).setContent(`📍 <b>Quartier : ${zoneRaw}</b>`).openOn(mainMap);
            }, 600);
        }
    }
});

// 4. Fonctions de localisation (Appelées par le bouton HTML)
function declencherLocalisation() {
    if (mainMap) {
        localiserUtilisateur(mainMap);
    }
}

function localiserUtilisateur(map) {
    if (!navigator.geolocation) {
        alert("Géolocalisation non supportée");
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;

            const userIcon = L.divIcon({
                className: 'user-location-icon',
                html: '<div class="pulse"></div>',
                iconSize: [20, 20]
            });

            L.marker([lat, lng], { icon: userIcon }).addTo(map).bindPopup("<b>Vous êtes ici</b>").openPopup();
            map.setView([lat, lng], 16);
        },
        (error) => {
            alert("Erreur : Activez le GPS et assurez-vous d'être en HTTPS.");
        },
        { enableHighAccuracy: true, timeout: 5000 }
    );
}
