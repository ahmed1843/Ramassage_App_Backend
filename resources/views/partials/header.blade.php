<header style="
    position: relative;
    background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
    padding: 25px 20px 40px 20px;
    border-bottom-left-radius: 40px;
    border-bottom-right-radius: 40px;
    color: white;
    overflow: hidden;
">
    <!-- Conteneur Principal -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 10;">
        
        <!-- 📍 GAUCHE : Titre et Sous-titre -->
        <div style="text-align: left;">
            <h1 style="font-size: 24px; font-weight: 900; margin: 0; line-height: 1.1;">
                Dakar <br> <span style="color: #d1f2eb;">Plus Propre</span>
            </h1>
            <p style="font-size: 13px; opacity: 0.9; margin-top: 5px; font-weight: 500;">
                {{ $subtitle ?? 'Mes dernières alertes' }}
            </p>
            <!-- 🎖️ Badge Admin -->
            <span id="admin-badge" style="display: none; background: #f1c40f; color: #2c3e50; font-size: 9px; font-weight: 900; padding: 2px 8px; border-radius: 6px; text-transform: uppercase; margin-top: 8px;">Admin</span>
        </div>

        <!-- 👤 DROITE : Bloc Utilisateur + Menu -->
        <div style="display: flex; align-items: center; gap: 12px;">
            <!-- Badge Profil -->
            <a href="{{ url('/profil') }}" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: white; background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(5px);">
                <div style="width: 28px; height: 28px; background: white; color: #27ae60; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px;">👤</div>
                <span id="user-greeting" style="font-weight: 800; font-size: 13px; text-transform: uppercase;">Profil</span>
            </a>
            
            <!-- Bouton Menu ☰ -->
            <button onclick="toggleMenu()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 40px; height: 40px; border-radius: 12px; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                ☰
            </button>
        </div>
    </div>
</header>
