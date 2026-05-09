// 1. Déclarations globales
let mainMap;
let markersGroup;
let truckMarker = null;

// Icône du camion (Utilisation d'un lien direct pour le test)
const truckIcon = L.icon({
    iconUrl: 'https://flaticon.com', 
    iconSize: [40, 40],
    iconAnchor: [20, 20]
});

// 2. Fonction pour récupérer les signalements (Points Rouges/Verts)
async function actualiserSignalements() {
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
                popupContent += `<img src="${imageUrl}" style="width:100%; border-radius:10px; margin-top:5px;" onerror="this.src='https://placeholder.com'">`;
            }

            popupContent += `<hr style="border:0; border-top:1px solid #eee; margin:10px 0;"><span style="font-size:11px; color:#999;">Statut: ${report.status}</span></div>`;

            const marker = L.circleMarker([lat, lng], {
                radius: 10,
                fillColor: report.status === 'resolved' ? '#27ae60' : '#e74c3c',
                color: "#fff",
                weight: 2,
                fillOpacity: 0.9
            }).bindPopup(popupContent);

            markersGroup.addLayer(marker);
        });
    } catch (e) { console.error("Erreur signalements:", e); }
}

// 3. Fonction pour suivre le CAMION en temps réel
async function actualiserPositionCamion() {
    try {
        const response = await fetch('/api/check-alerte');
        const data = await response.json();

        if (data.actif && data.lat && data.lng) {
            const pos = [data.lat, data.lng];

            if (!truckMarker) {
                truckMarker = L.marker(pos, { icon: truckIcon }).addTo(mainMap)
                    .bindPopup("<b>🚚 Le camion arrive !</b>").openPopup();
            } else {
                truckMarker.setLatLng(pos); // Le camion glisse sur la carte
            }
        } else if (truckMarker) {
            mainMap.removeLayer(truckMarker);
            truckMarker = null;
        }
    } catch (e) { console.error("Erreur tracking camion:", e); }
}

// 4. Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    // Initialisation de la carte unique
    mainMap = L.map('map').setView([14.7167, -17.4677], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mainMap);

    markersGroup = L.layerGroup().addTo(mainMap);

    // Chargement initial
    actualiserSignalements();
    actualiserPositionCamion();

    // Intervalles de mise à jour
    setInterval(actualiserSignalements, 30000); // Signalements toutes les 30s
    setInterval(actualiserPositionCamion, 3000); // Camion toutes les 3s (plus fluide)

    // Gestion du zoom par quartier (URL Params)
    const urlParams = new URLSearchParams(window.location.search);
    const zoneRaw = urlParams.get('zone');
    if (zoneRaw) {
        const coordsZones = {
            'dakar': [14.7167, -17.4677],
            'medina': [14.6850, -17.4480],
            'plateau': [14.6667, -17.4333],
            'almadies': [14.7480, -17.5120]
        };
        const zoneClean = zoneRaw.toLowerCase();
        if (coordsZones[zoneClean]) {
            setTimeout(() => {
                mainMap.setView(coordsZones[zoneClean], 16);
            }, 600);
        }
    }
});

// 5. Géolocalisation Utilisateur
function declencherLocalisation() {
    if (!navigator.geolocation) return alert("Géolocalisation non supportée");
    
    navigator.geolocation.getCurrentPosition((position) => {
        const pos = [position.coords.latitude, position.coords.longitude];
        L.marker(pos).addTo(mainMap).bindPopup("<b>Vous êtes ici</b>").openPopup();
        mainMap.setView(pos, 16);
    }, () => alert("Erreur GPS"), { enableHighAccuracy: true });
}
