<!DOCTYPE html>
<html lang="fr">
<head>
<script src="{{ asset('js/auth-guard.js') }}"></script>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Déchets - Accueil</title>
    
    <!-- ✅ On garde uniquement le CSS en haut -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        /* ... Ton style actuel (inchangé) ... */
        footer.mobile-nav { position: fixed; bottom: 0; left: 0; right: 0; width: 100%; height: 85px; background-color: white; border-top: 1px solid #eeeeee; box-shadow: 0 -5px 25px rgba(0,0,0,0.1); z-index: 999999; display: flex; align-items: center; }
        .nav-list { display: flex !important; width: 100% !important; justify-content: space-around !important; list-style: none !important; padding: 0 !important; margin: 0 !important; }
        .nav-item a { text-decoration: none !important; color: #7f8c8d !important; display: flex !important; flex-direction: column !important; align-items: center !important; gap: 4px !important; font-size: 26px !important; }
        main { padding-bottom: 120px !important; }
    </style>
</head>

<body>
    <header style="padding: 20px; text-align: center;">
        <h1 style="color: #27ae60; font-weight: 900;">New Déchets</h1>
    </header>

    <main class="p-5 flex flex-col gap-6" style="padding: 20px;">
        <!-- Élément CRUCIAL pour le JS -->
        <section id="auth-section"></section>

        <!-- Conseil Éco -->
        <div id="eco-tip-container" style="background: #e8f5e9; border-left: 5px solid #2ecc71; padding: 15px; border-radius: 15px; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">🌱</span>
            <div>
                <strong style="color: #2e7d32; font-size: 13px; display: block;">Conseil Éco-Citoyen</strong>
                <p id="eco-tip-text" style="margin: 0; font-size: 14px; color: #1b5e20;">Chargement...</p>
            </div>
        </div>

        <input type="text" id="search-zone" placeholder="Rechercher une zone..." style="width:100%; padding:12px; border-radius:10px; border:1px solid #ddd;">

        <section id="zones-container">
            <p style="text-align:center;">Chargement des zones...</p>
        </section>
    </main>

<footer class="mobile-nav">
  <nav>
    <ul class="nav-list">
   <li class="nav-item"><a href="{{ url('/') }}" class="active" style="color: #27ae60 !important;">🏠<span>Accueil</span></a></li>

      <!-- ✅ ON AJOUTE LA CARTE ICI -->
      <li class="nav-item"><a href="{{ url('/carte') }}">🗺️<span>Carte</span></a></li>
      <li class="nav-item"><a href="{{ url('/signalement') }}">📢<span>Signaler</span></a></li>
      <li class="nav-item"><a href="{{ url('/notifications') }}">🔔<span>Notifs</span></a></li>
      <li class="nav-item"><a href="{{ url('/profil') }}">👤<span>Profil</span></a></li>
    </ul>
  </nav>
</footer>



    <!-- ✅ UN SEUL APPEL JS ICI (Important) -->
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
