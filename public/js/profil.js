let tousLesRapports = [];

// 1. Lancement au chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('token');
    const userData = localStorage.getItem('user');

    if (token && userData) {
        const user = JSON.parse(userData);
        // On vérifie si les éléments existent avant de remplir (sécurité Blade)
        if(document.getElementById('user-name-display')) {
            document.getElementById('user-name-display').innerText = user.name;
            document.getElementById('user-email-display').innerText = user.email;
            document.getElementById('display-name').value = user.name;
        }
        
        chargerMesSignalements(token);
    } else {
        // ✅ CHANGEMENT : redirection vers la route Laravel /login
        window.location.href = '/login';
    }

    // --- FORMULAIRE MISE À JOUR ---
    const updateForm = document.getElementById('profil-form');
    if (updateForm) {
        updateForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const nom = document.getElementById('display-name').value;
            const quartier = document.getElementById('user-quartier').value;
            const token = localStorage.getItem('token');
            await mettreAJourProfil(nom, quartier, token);
        });
    }

    // --- DÉCONNEXION ---
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', () => {
            localStorage.clear();
            // ✅ CHANGEMENT : retour à l'accueil Laravel /
            window.location.href = '/';
        });
    }

    // --- GESTION DES FILTRES ---
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.style.background = "#fff";
                b.style.color = b.style.borderColor; 
            });
            this.style.background = "#333";
            this.style.color = "#fff";
            
            const statut = this.getAttribute('data-status');
            afficherSignalementsFiltrés(statut);
        });
    });
});

// --- FONCTION API ---
async function mettreAJourProfil(nom, quartier, token) {
    try {
        const response = await fetch('/api/user/update', { // ✅ URL Relative
            method: 'PUT',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ name: nom, quartier: quartier })
        });

        if (response.ok) {
            const user = JSON.parse(localStorage.getItem('user'));
            user.name = nom;
            localStorage.setItem('user', JSON.stringify(user));
            if(document.getElementById('user-name-display')) {
                document.getElementById('user-name-display').innerText = nom;
            }
            alert("✅ Profil mis à jour !");
        } else {
            alert("❌ Erreur serveur.");
        }
    } catch (e) { console.error(e); }
}

// --- CHARGEMENT DES SIGNALEMENTS ---
async function chargerMesSignalements(token) {
    const container = document.getElementById('my-reports-list');
    try {
        const response = await fetch('/api/my-reports', { // ✅ URL Relative
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const res = await response.json();
        tousLesRapports = res.data || res || [];
        afficherSignalementsFiltrés('all');
    } catch (e) { 
        if(container) container.innerHTML = "<p class='text-center'>Impossible de charger vos signalements.</p>";
    }
}

function afficherSignalementsFiltrés(filtre) {
    const container = document.getElementById('my-reports-list');
    if (!container) return;
    container.innerHTML = "";

    const liste = (filtre === 'all') ? tousLesRapports : tousLesRapports.filter(r => {
        const s = r.status ? r.status.toLowerCase() : "";
        if (filtre === 'pending') return s.includes('pend') || s.includes('attente');
        if (filtre === 'in_progress') return s.includes('prog') || s.includes('cours');
        if (filtre === 'resolved') return s.includes('resol') || s.includes('termin');
        return s === filtre;
    });

    if (liste.length === 0) {
        container.innerHTML = "<p class='text-center text-gray-500 py-4'>Aucun signalement trouvé.</p>";
        return;
    }

liste.forEach(report => {
    let progress = "33%", color = "#f1c40f", statusText = "En attente";
    const s = report.status ? report.status.toLowerCase() : "";
    
    if (s.includes('prog') || s.includes('cours')) { 
        progress = "66%"; color = "#3498db"; statusText = "En cours"; 
    } else if (s.includes('resol') || s.includes('termin')) { 
        progress = "100%"; color = "#27ae60"; statusText = "Terminé"; 
    }

    // ✅ CORRECTION 1 : Ton Resource envoie 'titre' (mappé depuis title)
    const titre = report.titre || "Signalement";

    // ✅ CORRECTION 2 : Ton Resource envoie 'photo_url' déjà formaté avec asset()
    let imageHtml = "";
    if (report.photo_url) {
        imageHtml = `
            <div style="margin-top:10px; width:100%; border-radius:12px; overflow:hidden; border:1px solid #eee;">
                <img src="${report.photo_url}" style="width:100%; display:block;" alt="Photo du signalement">
            </div>`;
    }

    container.innerHTML += `
        <div style="background:#fff; padding:15px; border-radius:12px; margin-bottom:15px; border-left:5px solid ${color}; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <p style="font-weight:bold; margin:0; color:#333;">📍 ${titre}</p>
                <span style="font-size:10px; font-weight:bold; color:${color}; text-transform:uppercase; border:1px solid ${color}; padding:2px 6px; border-radius:10px;">${statusText}</span>
            </div>
            <p style="font-size:13px; color:#666; margin:10px 0;">${report.description || 'Pas de description'}</p>
            
            ${imageHtml} <!-- ✅ L'image s'affiche maintenant ! -->

            <div style="width:100%; background:#eee; height:6px; border-radius:10px; overflow:hidden; margin-top:10px;">
                <div style="width:${progress}; background:${color}; height:100%; transition: width 0.5s;"></div>
            </div>
        </div>`;
});

}
