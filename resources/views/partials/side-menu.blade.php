<!-- 🌑 Ombre du menu (Ajoutée ici pour être présente sur toutes les pages) -->
<div id="menu-overlay" onclick="toggleMenu()" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1999999;"></div>

<!-- 📱 Menu Latéral -->
<div id="side-menu" style="position: fixed; top: 0; right: -280px; width: 280px; height: 100%; background: white; z-index: 2000000; box-shadow: -10px 0 40px rgba(0,0,0,0.15); padding: 0; overflow: hidden; font-family: 'Segoe UI', sans-serif;">
    

    <!-- Header du menu -->
    <div style="background: linear-gradient(135deg, #27ae60, #2ecc71); padding: 30px 25px; color: white; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="margin: 0; font-size: 22px; font-weight: 900;">Menu</h2>
            <span id="user-greeting" style="font-size: 12px; opacity: 0.8; font-weight: bold;"></span>

        </div>
        <button onclick="toggleMenu()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; font-weight: bold;">✕</button>
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

        <!-- ⚙️ Dashboard Admin -->
        <div id="menu-admin-section" style="display: none; margin-bottom: 10px;">
            <a href="{{ url('/admin') }}" style="display: flex; align-items: center; gap: 15px; padding: 12px 15px; text-decoration: none; color: #27ae60; font-weight: 800; border: 2px solid #27ae60; border-radius: 15px; background: #f0f9f4;"><span>⚙️</span> Dashboard Admin</a>
        </div>

        <button onclick="ouvrirModaleDeconnexion()" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; background: white; color: #e74c3c; border: 2px solid #e74c3c; padding: 14px; border-radius: 18px; font-weight: 800; cursor: pointer;"><span>🚪</span> Déconnexion</button>
    </nav>
</div>
<script>
function toggleMenu() {
    const menu = document.getElementById('side-menu');
    const overlay = document.getElementById('menu-overlay');
    
    if (menu && overlay) {
        menu.classList.toggle('active');
        
        if (menu.classList.contains('active')) {
            overlay.style.display = 'block';
            document.body.style.overflow = 'hidden';
        } else {
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    } else {
        console.error("Menu ou Overlay introuvable dans le DOM");
    }
}
</script>

