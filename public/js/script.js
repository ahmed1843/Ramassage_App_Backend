// --- 1. CONFIGURATION DES CONSEILS ---
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
    }, 10000);
}

// --- 2. GESTION DE L'AFFICHAGE DYNAMIQUE ---
function gererAffichageUtilisateur() {
    const authSection = document.getElementById('auth-section');
    const userData = localStorage.getItem('user');
    const footerNav = document.querySelector('.mobile-nav');
    
    const elementsDashboard = [
        document.getElementById('eco-tip-container'),
        document.getElementById('search-zone'),
        document.getElementById('zones-container'),
        document.getElementById('stats-section'),
        document.querySelector('.next-pickup')
    ];

    if (userData && authSection) {
        // --- MODE CONNECTÉ ---
        const user = JSON.parse(userData);
        const mots = user.name ? user.name.trim().split(/\s+/) : ["Utilisateur"];
        const initiales = (mots.length > 1) ? (mots[0][0] + mots[mots.length-1][0]).toUpperCase() : mots[0].substring(0, 2).toUpperCase();
        const couleurs = ['#2ecc71', '#3498db', '#9b59b6', '#e67e22', '#1abc9c'];
        const couleurUser = couleurs[user.name ? user.name.length % couleurs.length : 0];

  authSection.innerHTML = `
    <div style="display:grid; grid-template-columns: 40px 1fr 40px; align-items:center; background:white; padding:15px; border-radius:20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom:10px;">
        <div></div>
        <a href="/profil" style="display:flex; flex-direction:column; align-items:center; gap:8px; text-decoration:none;">
            <!-- ... reste du code ... -->
        </a>
        <button onclick="confirmerDeconnexion()" ...>🚪</button>
    </div>`;
        elementsDashboard.forEach(el => { if(el) el.style.display = (el.id === 'eco-tip-container') ? 'flex' : 'block'; });
        if(footerNav) footerNav.style.display = 'block';

        afficherToutesLesZones();
        faireDefilerConseils();

    } else if (authSection) {
        // --- MODE DÉCONNECTÉ ---
        authSection.innerHTML = `
            <div style="text-align:center; padding:40px 20px; background:white; border-radius:25px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom:20px;">
                <div style="font-size:60px; margin-bottom:20px;">🌍</div>
                <h2 style="color:#2c3e50; margin-bottom:10px;">Dakar Propre</h2>
                <p style="color:#7f8c8d; margin-bottom:30px; font-size:15px;">Connectez-vous pour suivre les camions et protéger votre quartier.</p>
                <button onclick="window.location.href='/login'" style="width:100%; background:#2ecc71; color:white; border:none; padding:16px; border-radius:15px; font-weight:bold; font-size:16px; cursor:pointer; box-shadow: 0 4px 15px rgba(46,204,113,0.3);">Accéder à mon espace</button>
                <p style="margin-top:15px; font-size:13px; color:#95a5a6;">Pas encore inscrit ? <a href="/register" style="color:#2ecc71; font-weight:bold; text-decoration:none;">Créer un compte</a></p>
            </div>`;
        
        elementsDashboard.forEach(el => { if(el) el.style.display = 'none'; });
        if(footerNav) footerNav.style.display = 'none';
    }
}

// --- 3. RÉCUPÉRATION DES DONNÉES API ---
async function afficherToutesLesZones() {
    const container = document.getElementById('zones-container');
    if (!container) return;
    try {
        const response = await fetch('/api/zones'); 
        const json = await response.json();
        const zones = json.data || json;
        if (zones.length > 0) {
            container.innerHTML = zones.map(zone => `
                <section class="zone-card bg-white p-4 rounded-xl shadow-sm border-l-4 border-primary mb-3">
                    <h2 class="font-bold text-lg">${zone.nom || zone.name}</h2>
                    <p class="text-gray-600 text-sm">${zone.description || 'Quartier de Dakar'}</p>
                </section>`).join('');
            activerLaRecherche();
        }
    } catch (e) { container.innerHTML = "<p>Zones indisponibles.</p>"; }
}

function activerLaRecherche() {
    const searchInput = document.getElementById('search-zone');
    if (!searchInput) return;
    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
        const zones = document.querySelectorAll('.zone-card');
        zones.forEach(zone => {
            const text = zone.innerText.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            zone.style.display = text.includes(term) ? "block" : "none";
        });
    });
}

// --- 4. INITIALISATION ---
document.addEventListener('DOMContentLoaded', () => {
    gererAffichageUtilisateur();
});

function confirmerDeconnexion() { 
    const modal = document.getElementById('logout-modal');
    if(modal) modal.style.display = 'flex'; 
}
function validerDeconnexion() {
    localStorage.removeItem('user');
    window.location.href = '/';
}
function fermerModal() {
    document.getElementById('logout-modal').style.display = 'none';
}
