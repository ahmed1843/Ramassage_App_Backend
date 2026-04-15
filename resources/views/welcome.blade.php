<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Déchets - Accueil</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>


<body>
@include('partials.header', ['subtitle' => 'Ensemble pour Dakar'])


<!-- 🌑 OVERLAY MENU -->
<div id="menu-overlay" onclick="toggleMenu()" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; backdrop-filter: blur(2px);"></div>

<!-- 📊 STATISTIQUES FLOTTANTES (Réelles) -->
<div style="display: flex; justify-content: space-around; margin: -30px 20px 20px 20px; position: relative; z-index: 10;">
    <div style="background: white; padding: 15px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; flex: 1; margin: 0 5px;">
        <span style="font-size: 20px;">✅</span>
        <div id="stat-resolved" style="font-weight: 900; color: #27ae60; font-size: 18px;">...</div>
        <div style="font-size: 10px; color: #7f8c8d; text-transform: uppercase; font-weight: bold;">Réglés</div>
    </div>
    <div style="background: white; padding: 15px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; flex: 1; margin: 0 5px;">
        <span style="font-size: 20px;">🚚</span>
        <div id="stat-trucks" style="font-weight: 900; color: #27ae60; font-size: 18px;">8</div>
        <div style="font-size: 10px; color: #7f8c8d; text-transform: uppercase; font-weight: bold;">Camions</div>
    </div>
</div>

<!-- 🚚 BLOC PASSAGE DYNAMIQUE + ALERTE -->
<div style="margin: 20px; background: white; padding: 25px; border-radius: 30px; border: 2px solid #27ae60; box-shadow: 0 15px 35px rgba(39, 174, 96, 0.1);">
    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
        <div style="background: #e8f5e9; padding: 10px; border-radius: 12px; font-size: 25px;">🚚</div>
        <div>
            <span id="info-zone-name" style="font-size: 11px; color: #7f8c8d; font-weight: 800; text-transform: uppercase;">CHARGEMENT...</span>
            <h2 style="margin: 0; font-size: 17px; color: #2c3e50;">Prochain Ramassage</h2>
        </div>
    </div>
    
    <div id="info-zone-time" style="background: #f1f8f5; padding: 15px; border-radius: 20px; text-align: center; font-size: 16px; font-weight: 900; color: #1e8449;">
        Recherche de l'horaire...
    </div>

    <button onclick="activerAlerteCamion()" id="btn-alerte" style="width: 100%; margin-top: 15px; background: white; color: #27ae60; border: 1.5px solid #27ae60; padding: 12px; border-radius: 15px; font-weight: 800; font-size: 12px; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: 0.3s;">
        <span id="alerte-icon">🔔</span> <span id="alerte-text">M'alerter avant le passage</span>
    </button>
</div>

<!-- 🏆 OBJECTIF PROPRETÉ DYNAMIQUE -->
<div style="margin: 25px 20px; background: white; padding: 20px; border-radius: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <span style="font-weight: 800; color: #2c3e50; font-size: 13px;">Objectif Dakar Propre</span>
        <span id="stat-percent" style="color: #27ae60; font-weight: 900; font-size: 14px;">0%</span>
    </div>
    <div style="width: 100%; height: 10px; background: #f0f0f0; border-radius: 10px; overflow: hidden;">
        <div id="stat-bar" style="width: 0%; height: 100%; background: linear-gradient(90deg, #2ecc71, #27ae60); border-radius: 10px; transition: width 1.5s ease-in-out;"></div>
    </div>
    <p style="margin-top: 10px; font-size: 11px; color: #7f8c8d; line-height: 1.4;">
        Basé sur les signalements réglés en temps réel.
    </p>
</div>


<main style="padding: 0 20px 120px 20px;">
    <!-- 🌱 CONSEIL ÉCO -->
    <div id="eco-tip-container" style="background: #e8f5e9; border-left: 5px solid #2ecc71; padding: 15px; border-radius: 15px; display: flex; align-items: center; gap: 10px; margin-bottom: 25px;">
        <span style="font-size: 24px;">🌱</span>
        <div>
            <strong style="color: #2e7d32; font-size: 13px;">Conseil Éco-Citoyen</strong>
            <p id="eco-tip-text" style="margin: 0; font-size: 14px; color: #1b5e20;">Chargement...</p>
        </div>
    </div>

    <!-- 📸 ACTUALITÉS -->
    <section style="margin-bottom: 30px; padding: 0; background: transparent; box-shadow: none;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2 style="font-size: 18px; font-weight: 800; color: #2c3e50;">Dernières photos 📸</h2>
            <a href="{{ url('/carte') }}" style="color: #27ae60; font-size: 13px; font-weight: 700; text-decoration: none;">Voir tout</a>
        </div>
        <div id="latest-reports-feed" style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px;">
            <p style="color: #95a5a6;">Chargement...</p>
        </div>
    </section>


    <!-- 🔍 RECHERCHE & ZONES -->
    <input type="text" id="search-zone" placeholder="Rechercher une zone..." style="width:100%; padding:15px; border-radius:15px; border:1px solid #eee; margin-bottom: 20px; outline: none; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">
    <section id="zones-container" style="background: transparent; padding: 0; box-shadow: none;"></section>
</main>
<div style="margin-top: 30px; padding: 0 15px 100px 15px;"> <!-- 100px de padding bas pour pas cacher derrière le menu -->
    <h3 style="font-size: 16px; color: #2c3e50; font-weight: 800; margin-bottom: 15px;">BESOIN D'AIDE ? 🆘</h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
        <!-- Bouton Police -->
        <a href="tel:17" style="text-decoration: none; background: white; padding: 12px; border-radius: 15px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <span style="font-size: 20px;">🚓</span>
            <div>
                <div style="font-size: 12px; font-weight: 900; color: #e74c3c;">Police</div>
                <div style="font-size: 14px; color: #7f8c8d;">17</div>
            </div>
        </a>

        <!-- Bouton Pompiers -->
        <a href="tel:18" style="text-decoration: none; background: white; padding: 12px; border-radius: 15px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <span style="font-size: 20px;">🚒</span>
            <div>
                <div style="font-size: 12px; font-weight: 900; color: #e67e22;">Pompiers</div>
                <div style="font-size: 14px; color: #7f8c8d;">18</div>
            </div>
        </a>

        <!-- Service Client Ramassage (Ton projet) -->
        <a href="tel:800101010" style="text-decoration: none; background: #e8f6ef; border: 1px solid #27ae60; padding: 12px; border-radius: 15px; display: flex; align-items: center; gap: 10px; grid-column: span 2;">
            <span style="font-size: 20px;">📞</span>
            <div>
                <div style="font-size: 12px; font-weight: 900; color: #27ae60;">Service de Salubrité (Vert)</div>
                <div style="font-size: 14px; color: #2c3e50;">Appel gratuit : 800 10 10 10</div>
            </div>
        </a>
    </div>
</div>


@include('partials.footer')
@include('partials.side-menu')

<script src="{{ asset('js/config.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
