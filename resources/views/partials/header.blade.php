<header style="
    position: relative;
    background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
    padding: 25px 20px 40px 20px;
    border-bottom-left-radius: 40px;
    border-bottom-right-radius: 40px;
    color: white;
    overflow: hidden;
    z-index: 1000;
">
   

    <!-- Conteneur Principal -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; position: relative; z-index: 10; margin-top: 10px;">
        
        <!-- 📍 GAUCHE : Logo et Titres -->
        <div style="text-align: left;">
            <img src="{{ asset('Logo de MMD Smart Clean.png') }}" alt="Logo MMD" style="height: 85px; margin-bottom: 10px; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.2));">
            
            <h1 style="font-size: 22px; font-weight: 900; margin: 0; line-height: 1.1;">
                Dakar <br> <span style="color: #d1f2eb;">Plus Propre</span>
            </h1>
            <p style="font-size: 12px; opacity: 0.8; margin-top: 5px; font-weight: 500;">
                {{ $subtitle ?? 'Mes dernières alertes' }}
            </p>
        </div>

        <!-- 👤 DROITE : Profil + Menu -->
        <div style="display: flex; align-items: center; gap: 10px; padding-top: 40px;">
            <a href="{{ url('/profil') }}" style="display: flex; align-items: center; gap: 6px; text-decoration: none; color: white; background: rgba(255,255,255,0.15); padding: 8px 12px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(5px);">
                <span style="font-size: 14px;">👤</span>
                <span style="font-weight: 800; font-size: 12px; text-transform: uppercase;">Profil</span>
            </a>
            
            <button onclick="toggleMenu()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 40px; height: 40px; border-radius: 12px; font-size: 22px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                ☰
            </button>
        </div>
    </div>
</header>
