<!-- 🌑 Voile noir -->
<div id="menu-overlay" onclick="toggleMenu()" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 1999999; backdrop-filter: blur(4px);"></div>

<!-- 📱 Menu Latéral avec tes 5 liens -->
<div id="side-menu" style="display: none; position: fixed; top: 15px; right: 15px; bottom: 15px; width: 280px; background: white; z-index: 2000000; border-radius: 30px; box-shadow: -10px 10px 40px rgba(0,0,0,0.15); padding: 0; overflow: hidden; font-family: 'Segoe UI', sans-serif;">
    
    <!-- Header du menu -->
    <div style="background: linear-gradient(135deg, #27ae60, #2ecc71); padding: 30px 25px; color: white; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="margin: 0; font-size: 22px; font-weight: 900;">Menu</h2>
        <button onclick="toggleMenu()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer;">✕</button>
    </div>

    <nav style="padding: 15px;">
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 5px;">
            <li><a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #2c3e50; font-weight: 700; border-radius: 15px;"><span>🏠</span> Accueil</a></li>
            <li><a href="{{ url('/carte') }}" style="display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #2c3e50; font-weight: 700; border-radius: 15px;"><span>🗺️</span> Carte</a></li>
            <li><a href="{{ url('/signalement') }}" style="display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #2c3e50; font-weight: 700; border-radius: 15px;"><span>📢</span> Signaler</a></li>
            <li><a href="{{ url('/notifications') }}" style="display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #2c3e50; font-weight: 700; border-radius: 15px;"><span>🔔</span> Notifs</a></li>
            <li><a href="{{ url('/profil') }}" style="display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #2c3e50; font-weight: 700; border-radius: 15px;"><span>👤</span> Profil</a></li>
        </ul>

        <div style="height: 1px; background: #eee; margin: 15px 0;"></div>

        <!-- ⚙️ Dashboard Admin (affiché par le JS plus bas) -->
        <div id="menu-admin-section" style="display: none; margin-bottom: 10px;">
            <a href="{{ url('/admin') }}" style="display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #27ae60; font-weight: 800; border: 2px solid #27ae60; border-radius: 15px; background: #f0f9f4;"><span>⚙️</span> Dashboard Admin</a>
        </div>

        <button onclick="ouvrirModaleDeconnexion()" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; background: white; color: #e74c3c; border: 2px solid #e74c3c; padding: 14px; border-radius: 18px; font-weight: 800; cursor: pointer;"><span>🚪</span> Déconnexion</button>
    </nav>
</div>

<script>
// 1. GESTION DE L'AFFICHAGE DYNAMIQUE (PRÉNOM ET BADGE)
document.addEventListener('DOMContentLoaded', () => {
    const userData = localStorage.getItem('user');
    const greeting = document.getElementById('user-greeting');
    const badge = document.getElementById('admin-badge');
    const adminSection = document.getElementById('menu-admin-section');

    if (userData) {
        const user = JSON.parse(userData);
        
        // Affichage du Prénom (ex: AWA)
        if (greeting) {
            let fullName = user.name || user.email || "PROFIL";
            let firstName = fullName.split(' ')[0].split('@')[0];
            greeting.innerText = firstName.toUpperCase();
        }

        // Affichage des privilèges Admin
        if (user.role === 'admin') {
            if (badge) badge.style.display = 'inline-block';
            if (adminSection) adminSection.style.display = 'block';
        }
    }
});

// 2. FONCTION POUR OUVRIR/FERMER LE MENU
function toggleMenu() {
    const menu = document.getElementById('side-menu');
    const overlay = document.getElementById('menu-overlay');
    if(!menu) return;

    const isHidden = (menu.style.display === "none" || menu.style.display === "");

    if (isHidden) {
        menu.style.display = "block";
        overlay.style.display = "block";
    } else {
        menu.style.display = "none";
        overlay.style.display = "none";
    }
}

// 3. FONCTIONS DÉCONNEXION
function ouvrirModaleDeconnexion() {
    toggleMenu(); // On ferme le menu latéral
    const modal = document.getElementById('logout-modal');
    if(modal) modal.style.display = "flex";
}

function validerDeconnexion() {
    localStorage.clear();
    window.location.href = '/login';
}

function fermerModale() {
    const modal = document.getElementById('logout-modal');
    if(modal) modal.style.display = "none";
}
</script>
