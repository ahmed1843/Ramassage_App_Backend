<!DOCTYPE html>
<html lang="fr">
<head>
    <script src="{{ asset('js/auth-guard.js') }}"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Déchets - Accueil</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

   
</head>

<body>
@include('partials.header', ['subtitle' => 'Mes dernières alertes'])

<!-- 🚪 MODALE DE DÉCONNEXION (Cachée par défaut) -->
<div id="logout-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:2000000; align-items:center; justify-content:center; backdrop-filter: blur(5px);">
    <div style="background:white; width:85%; max-width:320px; padding:30px; border-radius:25px; text-align:center; animation: zoomIn 0.3s ease;">
        <div style="font-size:50px; margin-bottom:15px;">👋</div>
        <h2 style="color:#2c3e50; margin-bottom:10px;">Déconnexion</h2>
        <p style="color:#7f8c8d; margin-bottom:25px; font-size:15px;">Voulez-vous vraiment vous déconnecter ?</p>
        <button onclick="validerDeconnexion()" style="width:100%; padding:15px; background:#e74c3c; color:white; border:none; border-radius:15px; margin-bottom:10px; font-weight:bold; cursor:pointer;">Oui, me déconnecter</button>
        <button onclick="fermerModale()" style="width:100%; padding:15px; background:#f1f2f6; color:#7f8c8d; border:none; border-radius:15px; font-weight:bold; cursor:pointer;">Annuler</button>
    </div>
</div>





    <!-- 📊 ÉTAPE 2 : STATISTIQUES FLOTTANTES -->
    <div style="display: flex; justify-content: space-around; margin: -30px 20px 20px 20px; position: relative; z-index: 10;">
        <div style="background: white; padding: 15px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; flex: 1; margin: 0 5px;">
            <span style="font-size: 20px;">✅</span>
            <div style="font-weight: 900; color: #27ae60; font-size: 18px;">124</div>
            <div style="font-size: 10px; color: #7f8c8d; text-transform: uppercase; font-weight: bold;">Signalements réglés</div>
        </div>
        <div style="background: white; padding: 15px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; flex: 1; margin: 0 5px;">
            <span style="font-size: 20px;">🚚</span>
            <div style="font-weight: 900; color: #27ae60; font-size: 18px;">8</div>
            <div style="font-size: 10px; color: #7f8c8d; text-transform: uppercase; font-weight: bold;">Camions actifs</div>
        </div>
    </div>

    <main class="p-5 flex flex-col gap-6" style="padding: 20px;">
        <section id="auth-section"></section>

        <!-- Conseil Éco -->
        <div id="eco-tip-container" style="background: #e8f5e9; border-left: 5px solid #2ecc71; padding: 15px; border-radius: 15px; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">🌱</span>
            <div>
                <strong style="color: #2e7d32; font-size: 13px; display: block;">Conseil Éco-Citoyen</strong>
                <p id="eco-tip-text" style="margin: 0; font-size: 14px; color: #1b5e20;">Chargement...</p>
            </div>
        </div>

        <input type="text" id="search-zone" placeholder="Rechercher une zone (ex: Médina)..." style="width:100%; padding:15px; border-radius:15px; border:1px solid #eee; box-shadow: 0 4px 6px rgba(0,0,0,0.05); outline: none;">

        <section id="zones-container">
            <p style="text-align:center; color: #95a5a6;">Chargement des zones...</p>
        </section>
    </main>
<!-- 📸 SECTION ACTUALITÉS (Style Instagram) -->
<section style="margin-top: 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h2 style="font-size: 18px; font-weight: 800; color: #2c3e50; margin: 0;">Récemment signalés 📸</h2>
        <a href="{{ url('/carte') }}" style="color: #27ae60; font-size: 13px; font-weight: 700; text-decoration: none;">Voir tout</a>
    </div>

    <!-- Conteneur horizontal (Scroll) -->
    <div id="latest-reports-feed" style="display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px; scroll-snap-type: x mandatory; -webkit-overflow-scrolling: touch;">
        <p style="color: #95a5a6; font-size: 14px;">Chargement des actus...</p>
    </div>
</section>

<style>
    /* Pour cacher la barre de scroll tout en gardant le balayage */
    #latest-reports-feed::-webkit-scrollbar { display: none; }
    #latest-reports-feed { -ms-overflow-style: none; scrollbar-width: none; }
</style>



@include('partials.footer')


    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
@include('partials.logout-modal')
@include('partials.side-menu')


</body>
</html>
