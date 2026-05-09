// --- 1. INITIALISATION GÉNÉRALE ---
document.addEventListener('DOMContentLoaded', () => {
    // Affichage du nom d'utilisateur dans le Header
    const userData = localStorage.getItem('user');
    if (userData) {
        try {
            const user = JSON.parse(userData);
            const nameDisplay = document.getElementById('user-name-header'); 
            if (nameDisplay && user.name) {
                nameDisplay.innerText = "Bonjour, " + user.name.split(' ')[0];
            }
        } catch (e) { console.error("Erreur user data", e); }
    }
    
    // Lancement des fonctions de l'interface
    faireDefilerConseils();
    chargerQuartiers();
    chargerFeedActualites();
    mettreAJourProchainPassage();
    chargerStatistiquesReelles(); // ✅ Ajouté pour le ✅ et la barre %
    // On vérifie une fois au chargement
surveillerArriveeCamion();
  if (document.getElementById('liste-mes-signalements')) {
        chargerMesSignalements();
    }

// Puis on vérifie toutes les 10 secondes (pour le test)
setInterval(surveillerArriveeCamion, 10000);


});

// --- 2. MENU ET MODALES ---
function toggleMenu() {
    const menu = document.getElementById('side-menu');
    const overlay = document.getElementById('menu-overlay');
    if (menu && overlay) {
        menu.classList.toggle('active');
        overlay.style.display = menu.classList.contains('active') ? 'block' : 'none';
        document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : 'auto';
    }
}

function ouvrirModaleDeconnexion() {
    const modal = document.getElementById('logout-modal');
    if (modal) modal.style.display = 'flex';
}

function validerDeconnexion() {
    localStorage.clear();
    window.location.href = '/login'; 
}

function fermerModale() {
    const modal = document.getElementById('logout-modal');
    if (modal) modal.style.display = 'none';
}

// --- 3. STATISTIQUES RÉELLES (DOCKER) ---
async function chargerStatistiquesReelles() {
    const elResolved = document.getElementById('stat-resolved');
    const elPercent = document.getElementById('stat-percent');
    const elBar = document.getElementById('stat-bar');
    const token = localStorage.getItem('token');

    try {
        const response = await fetch('/api/reports', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        if (response.ok) {
            const reports = await response.json();
            const total = reports.length;
            const resolved = reports.filter(r => r.status === 'resolved').length;
            const percent = total > 0 ? Math.round((resolved / total) * 100) : 0;

            if (elResolved) elResolved.innerText = resolved;
            if (elPercent) elPercent.innerText = percent + "%";
            if (elBar) elBar.style.width = percent + "%";
        }
    } catch (e) { console.error("Erreur stats", e); }
}

// --- 4. FEED PHOTOS (DOCKER STORAGE) ---
async function chargerFeedActualites() {
    const feed = document.getElementById('latest-reports-feed');
    const token = localStorage.getItem('token');
    if (!feed) return;

    try {
        const response = await fetch('/api/reports', {
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const reports = await response.json();
        const recentWithImage = reports.filter(r => r.image).reverse().slice(0, 5);
        
        feed.innerHTML = ""; 
        if(recentWithImage.length === 0) {
            feed.innerHTML = "<p style='font-size:12px; color:#95a5a6;'>Aucune photo récente.</p>";
            return;
        }

        recentWithImage.forEach(report => {
            feed.innerHTML += `
                <div class="news-card" style="flex: 0 0 220px; background: white; border-radius: 20px; overflow: hidden; margin-right: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                    <img src="/storage/${report.image}" style="width: 100%; height: 130px; object-fit: cover;">
                    <div style="padding: 12px;">
                        <strong style="color: #2c3e50; font-size: 13px;">📍 ${report.location_name || 'Dakar'}</strong>
                    </div>
                </div>`;
        });
    } catch (e) { console.error("Erreur feed", e); }
}

// --- 5. CONSEILS ÉCOLOGIQUES ---
const tips = [
    "🌍 Un déchet bien trié = un environnement protégé.",
    "🗑️ Sortez vos poubelles uniquement avant le passage du camion.",
    "🚫 Évitez de jeter les déchets dans la rue, Dakar vous remercie !",
    "🔄 Le plastique est recyclable — pensez à le séparer du reste.",
    "⏰ Respecter les horaires réduit les nuisances sonores."
];

function faireDefilerConseils() {
    const tipText = document.getElementById('eco-tip-text');
    if (!tipText) return;
    let index = 0;
    tipText.innerText = tips[0];
    setInterval(() => {
        tipText.style.opacity = 0;
        setTimeout(() => {
            index = (index + 1) % tips.length;
            tipText.innerText = tips[index];
            tipText.style.opacity = 1;
        }, 500);
    }, 8000);
}

// --- 6. QUARTIERS ET PASSAGES ---
async function chargerQuartiers() {
    const container = document.getElementById('zones-container');
    if (!container) return;
    try {
        const response = await fetch('/api/zones');
        const zones = await response.json();
        container.innerHTML = ""; 
        zones.forEach(zone => {
            const statusColor = zone.status === 'clean' ? '#2ecc71' : '#e74c3c';
            container.innerHTML += `
                <div class="zone-card" style="background: white; padding: 18px; border-radius: 20px; margin-bottom: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); border-left: 6px solid ${statusColor};">
                    <strong style="font-size: 17px; color: #2c3e50; display: block;">${zone.name}</strong>
                    <a href="/carte?zone=${encodeURIComponent(zone.name)}" style="text-decoration: none; color: #27ae60; font-size: 13px; font-weight: 700;">Voir sur la carte 📍</a>
                </div>`;
        });
    } catch (error) { console.error("Erreur zones", error); }
}

async function mettreAJourProchainPassage() {
    const zoneNameDisplay = document.getElementById('info-zone-name');
    const zoneTimeDisplay = document.getElementById('info-zone-time');
    if (!zoneNameDisplay || !zoneTimeDisplay) return;

    try {
        const response = await fetch('/api/zones');
        const zones = await response.json();
        if (zones.length > 0) {
            zoneNameDisplay.innerText = zones[0].name;
            zoneTimeDisplay.innerText = "Aujourd'hui à " + (zones[0].passage_time || "18:30");
        }
    } catch (error) { zoneNameDisplay.innerText = "DAKAR"; }
}
// --- FONCTION DE SURVEILLANCE DU CAMION ---
async function surveillerArriveeCamion() {
    const alerteBox = document.getElementById('camion-alerte');
    if (!alerteBox) return;

    try {
        // On demande à Laravel s'il y a une alerte active
        const response = await fetch('/api/check-alerte');
        const data = await response.json();

        if (data.actif) {
            // Si une alerte est active, on affiche le bandeau orange
            alerteBox.style.display = 'block';
            console.log("🚚 ALERTE : Le camion arrive à " + data.zone);
            
            // On peut faire vibrer le téléphone si c'est sur mobile
            if (navigator.vibrate) navigator.vibrate([200, 100, 200]);
        } else {
            alerteBox.style.display = 'none';
        }
    } catch (e) {
        console.error("Erreur surveillance camion", e);
    }
}
async function chargerMesSignalements() {
    const container = document.getElementById('liste-mes-signalements');
    const token = localStorage.getItem('token');
    
    if (!container) return; // Sécurité si on n'est pas sur la page profil

    if (!token) {
        container.innerHTML = "<p style='text-align:center; padding:20px;'>Veuillez vous connecter pour voir vos signalements.</p>";
        return;
    }

    try {
        const response = await fetch('/api/my-reports', {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            const result = await response.json();
            // On récupère les données (souvent dans result.data avec Laravel)
            const reports = result.data || result; 

            if (reports.length === 0) {
                container.innerHTML = "<p style='text-align:center; color:#95a5a6; padding:20px;'>Vous n'avez pas encore fait de signalement.</p>";
                return;
            }

            container.innerHTML = ""; // On vide le message de chargement
            
            reports.forEach(report => {
                let statusBadge = report.status === 'pending' 
                    ? '<span style="background:#fff3cd; color:#856404; padding:4px 8px; border-radius:10px; font-size:11px;">⏳ En attente</span>' 
                    : '<span style="background:#d4edda; color:#155724; padding:4px 8px; border-radius:10px; font-size:11px;">✅ Terminé</span>';

                container.innerHTML += `
                    <div style="background: white; border-radius: 20px; padding: 15px; margin-bottom: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 10px;">
                            <strong style="color: #2c3e50; font-size: 14px;">📍 ${report.location_name || 'Dakar'}</strong>
                            ${statusBadge}
                        </div>
                        <p style="font-size: 13px; color: #7f8c8d; margin-bottom: 10px;">${report.description}</p>
                        ${report.image ? `<img src="/storage/${report.image}" style="width: 100%; border-radius: 12px; height: 150px; object-fit: cover;">` : ''}
                        <div style="font-size: 10px; color: #bdc3c7; margin-top: 8px;">Signalé le : ${new Date(report.created_at).toLocaleDateString()}</div>
                    </div>
                `;
            });
        } else {
            container.innerHTML = "<p style='text-align:center;'>Erreur lors de la récupération.</p>";
        }
    } catch (e) {
        console.error("Erreur profil :", e);
        container.innerHTML = "<p style='text-align:center;'>Impossible de joindre le serveur.</p>";
    }
}
