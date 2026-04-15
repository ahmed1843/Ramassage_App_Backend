// --- 1. MENU LATÉRAL ET NAVIGATION ---
function toggleMenu() {
    const menu = document.getElementById('side-menu');
    const overlay = document.getElementById('menu-overlay');
    
    if (menu && overlay) {
        menu.classList.toggle('active');
        
        // On affiche ou cache l'overlay (fond sombre)
        if (menu.classList.contains('active')) {
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Empêche de défiler derrière
        } else {
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
}

// --- 2. GESTION DE LA DÉCONNEXION ---
function ouvrirModaleDeconnexion() {
    // 1. On ferme d'abord le menu latéral pour plus de clarté
    const menu = document.getElementById('side-menu');
    const overlay = document.getElementById('menu-overlay');
    if (menu) menu.classList.remove('active');
    if (overlay) overlay.style.display = 'none';

    // 2. On affiche la modale de confirmation
    const modal = document.getElementById('logout-modal');
    if (modal) {
        modal.style.display = 'flex';
    } else {
        validerDeconnexion(); // Sécurité : déconnexion directe si pas de modale
    }
}

function validerDeconnexion() {
    localStorage.clear(); // Vide les infos user et le token
    window.location.href = '/login'; 
}

function fermerModale() {
    const modal = document.getElementById('logout-modal');
    if (modal) modal.style.display = 'none';
}

// --- 3. CONSEILS ÉCOLOGIQUES (DÉFILANT) ---
const tips = [
    "🌍 Un déchet bien trié = un environnement protégé.",
    "🗑️ Sortez vos poubelles uniquement avant le passage du camion.",
    "🚫 Évitez de jeter les déchets dans la rue, Dakar vous remercie !",
    "🔄 Le plastique est recyclable — pensez à le séparer du reste.",
    "⏰ Respecter les horaires réduit les nuisances sonores.",
    "🧴 Utilisez des sacs réutilisables pour vos courses au marché."
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

// --- 4. CHARGEMENT DES DONNÉES DEPUIS L'API ---
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
    } catch (error) { console.error("Erreur chargement zones", error); }
}

async function chargerFeedActualites() {
    const feed = document.getElementById('latest-reports-feed');
    if (!feed) return;

    try {
        const response = await fetch('/api/reports');
        const reports = await response.json();
        const recentWithImage = reports.filter(r => r.image).reverse().slice(0, 5);
        feed.innerHTML = ""; 

        recentWithImage.forEach(report => {
            feed.innerHTML += `
                <div class="news-card" style="flex: 0 0 260px; background: white; border-radius: 24px; overflow: hidden; margin-right: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);">
                    <img src="/storage/${report.image}" style="width: 100%; height: 160px; object-fit: cover;">
                    <div style="padding: 16px;">
                        <strong style="color: #2c3e50;">${report.location_name || 'Dakar'}</strong>
                    </div>
                </div>`;
        });
    } catch (e) { console.error("Erreur chargement feed", e); }
}

// --- 5. INITIALISATION GÉNÉRALE ---
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
});
