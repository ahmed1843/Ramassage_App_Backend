<!DOCTYPE html>
<html lang="fr">
<head>
<script src="{{ asset('js/auth-guard.js') }}"></script>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil - New Déchets</title>
  
  <!-- ✅ Toujours utiliser asset() -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
</head>

<body>
  <header style="padding: 20px; text-align: center;">
    <h1 style="color: #27ae60; font-weight: 900;">Profil Utilisateur</h1>
  </header>

  <main class="p-5" style="padding: 20px;">
    <!-- RÉSUMÉ RAPIDE -->
    <section style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; margin-bottom: 25px;">
      <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Mon Profil</h2>
      <p style="margin: 5px 0;"><strong>Nom :</strong> <span id="user-name-display" style="color: #27ae60;">Chargement...</span></p>
      <p style="margin: 5px 0;"><strong>Email :</strong> <span id="user-email-display">Chargement...</span></p>
    </section>

    <!-- FORMULAIRE DE MISE À JOUR -->
    <section class="user-info" style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <h2 style="font-size: 16px; font-weight: bold; margin-bottom: 20px; color: #2c3e50;">Informations personnelles</h2>
      <form id="profil-form">
        <label style="display:block; margin-bottom:8px;">Nom complet :</label>
        <input type="text" id="display-name" placeholder="Votre nom" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #eee; margin-bottom: 20px;"/>

        <label style="display:block; margin-bottom:8px;">Quartier :</label>
        <select id="user-quartier" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #eee; margin-bottom: 20px;">
          <option value="medina">Médina</option>
          <option value="plateau">Plateau</option>
          <option value="ouakam">Ouakam</option>
          <option value="ngor">Ngor</option>
        </select>
        <button type="submit" style="width: 100%; padding: 15px; background: #2ecc71; color: white; border: none; border-radius: 15px; font-weight: bold; cursor: pointer;">Mettre à jour le profil</button>
      </form>
    </section>

    <!-- ZONE DES SIGNALEMENTS -->
    <section class="my-reports-section" style="margin-top: 35px;">
      <h2 style="color: #27ae60; margin-bottom: 15px; font-size: 18px;">Mes derniers signalements</h2>
      <div id="my-reports-list">
        <p style="text-align: center; color: #95a5a6;">Chargement de vos activités...</p>
      </div>
    </section>

    <div style="margin-top: 40px;">
      <button id="logout-btn" style="width: 100%; padding: 15px; background: #fff; color: #e74c3c; border: 2px solid #e74c3c; border-radius: 15px; font-weight: bold; cursor: pointer;">Se déconnecter</button>
    </div>
  </main>
<footer class="mobile-nav">
  <nav>
    <ul class="nav-list">
      <li class="nav-item"><a href="{{ url('/') }}">🏠<span>Accueil</span></a></li>
      <!-- ✅ ON AJOUTE LA CARTE ICI -->
      <li class="nav-item"><a href="{{ url('/carte') }}">🗺️<span>Carte</span></a></li>
      <li class="nav-item"><a href="{{ url('/signalement') }}">📢<span>Signaler</span></a></li>
      <li class="nav-item"><a href="{{ url('/notifications') }}">🔔<span>Notifs</span></a></li>
      <li class="nav-item"><a href="{{ url('/profil') }}">👤<span>Profil</span></a></li>
    </ul>
  </nav>
</footer>


  <style>
      main { padding-bottom: 120px !important; }
      .nav-list { display: flex !important; justify-content: space-around !important; list-style: none !important; padding: 0 !important; margin: 0 !important; }
      .nav-item a { text-decoration: none !important; color: #7f8c8d !important; display: flex !important; flex-direction: column !important; align-items: center !important; gap: 4px !important; font-size: 26px !important; }
      .nav-item a.active { color: #27ae60 !important; }
      .nav-item a span { font-size: 11px !important; font-weight: 700 !important; text-transform: uppercase; }
  </style>
  <!-- ✅ Scripts JS -->
  <script src="{{ asset('js/config.js') }}"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <script src="{{ asset('js/profil.js') }}"></script> <!-- 👈 INDISPENSABLE -->

</body>
</html>
