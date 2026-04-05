// --- 1. CONFIGURATION ET CONSEILS ÉCO ---
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

// --- 2. CHARGEMENT DES QUARTIERS ET RECHERCHE ---
async function chargerQuartiers() {
    const container = document.getElementById('zones-container');
    if (!container) return;

    try {
        const response = await fetch('/api/zones');
        const zones = await response.json();

        if (zones.length === 0) {
            container.innerHTML = "<p style='text-align:center; color:#999;'>Aucune zone répertoriée.</p>";
            return;
        }

        container.innerHTML = ""; 

        for (const zone of zones) {
            const statusColor = zone.status === 'clean' ? '#2ecc71' : '#e74c3c';
            const statusLabel = zone.status === 'clean' ? 'Propre' : 'Critique';

            let infoHoraire = "⏰ Horaire non défini";
            try {
                const schedRes = await fetch(`/api/schedules/zone/${zone.id}`);
                if (schedRes.ok) {
                    const sched = await schedRes.json();
                    if (sched && sched.pickup_time) {
                        infoHoraire = `🗓️ ${sched.day_of_week} à ${sched.pickup_time.substring(0, 5)}`;
                    }
                }
            } catch (e) { }

            const zoneCard = `
                <div class="zone-card" style="background: white; padding: 18px; border-radius: 20px; margin-bottom: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.04); border-left: 6px solid ${statusColor};">
                    <div style="display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <strong style="font-size: 17px; color: #2c3e50; display: block;">${zone.name}</strong>
                            <div style="font-size: 13px; color: #27ae60; font-weight: bold; margin-top: 4px;">${infoHoraire}</div>
                        </div>
                        <span style="background: ${statusColor}15; color: ${statusColor}; padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase;">
                            ${statusLabel}
                        </span>
                    </div>
                    <div style="margin-top: 15px; border-top: 1px solid #f8f9fa; padding-top: 12px;">
                        <a href="/carte?zone=${encodeURIComponent(zone.name)}" style="text-decoration: none; display: flex; align-items: center; gap: 8px; color: #27ae60; font-size: 13px; font-weight: 700;">
                            <span>Voir sur la carte</span>
                            <span style="font-size: 16px;">📍</span>
                        </a>
                    </div>
                </div>`;
            container.innerHTML += zoneCard;
        }
        activerLaRecherche();
    } catch (error) {
        container.innerHTML = "<p style='text-align:center;'>Erreur de chargement.</p>";
    }
}

function activerLaRecherche() {
    const searchInput = document.getElementById('search-zone');
    if (!searchInput) return;

    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "").trim();
        const cards = document.querySelectorAll('.zone-card');
        
        cards.forEach(card => {
            const text = card.innerText.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            card.style.display = text.includes(term) ? "block" : "none";
        });
    });
}

// --- 3. CHARGEMENT DU FEED ACTUALITÉS ---
async function chargerFeedActualites() {
    const feed = document.getElementById('latest-reports-feed');
    if (!feed) return;

    try {
        const response = await fetch('/api/reports');
        const reports = await response.json();

        const recentWithImage = reports
            .filter(r => r.image)
            .reverse()
            .slice(0, 5);

        if (recentWithImage.length === 0) {
            feed.innerHTML = "<p style='color:#999; font-size:13px;'>Aucune photo récente.</p>";
            return;
        }

        feed.innerHTML = ""; 

        recentWithImage.forEach(report => {
            const isUrgent = report.status === 'pending';
            const statusLabel = isUrgent ? '🔴 Urgent' : '🟢 Réglé';
            const statusColor = isUrgent ? '#e74c3c' : '#2ecc71';

            const card = `
                <div class="news-card" style="flex: 0 0 260px; scroll-snap-align: start; background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #f0f0f0; display: flex; flex-direction: column; margin-right: 15px;">
                    <div style="width: 100%; height: 160px; position: relative;">
                        <img src="/storage/${report.image}" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; top: 12px; left: 12px; background: white; color: ${statusColor}; padding: 6px 12px; border-radius: 12px; font-size: 11px; font-weight: 800; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            ${statusLabel}
                        </div>
                    </div>
                    <div style="padding: 16px;">
                        <strong style="color: #2c3e50; font-size: 14px;">${report.location_name || 'Lieu inconnu'}</strong>
                        <p style="color: #7f8c8d; font-size: 12px; margin: 5px 0;">Signalé récemment</p>
                    </div>
                </div>`;
            feed.innerHTML += card;
        });
    } catch (e) {
        console.error("Erreur feed actus", e);
    }
}

// --- 4. INITIALISATION ---
document.addEventListener('DOMContentLoaded', () => {
    // Profil utilisateur
    const userData = localStorage.getItem('user');
    if (userData) {
        const user = JSON.parse(userData);
        const nameDisplay = document.getElementById('user-name-display');
        const avatarDisplay = document.getElementById('user-avatar');
        if (nameDisplay) nameDisplay.innerText = (user.name || "Citoyen").split(' ')[0];
        if (avatarDisplay) avatarDisplay.innerText = (user.name ? user.name.charAt(0) : "👤").toUpperCase();
    }

    faireDefilerConseils();
    chargerQuartiers();
    chargerFeedActualites();
});
