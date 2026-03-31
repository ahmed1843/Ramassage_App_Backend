<!DOCTYPE html>
<html lang="fr">
<head>
<script src="{{ asset('js/auth-guard.js') }}"></script>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - New Déchets</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
  <header>
    <h1>Mes Notifications</h1>
  </header>

<main class="p-5">
    <!-- ✅ Garde tes réglages en haut si tu veux, mais ajoute ceci en bas -->
    <section style="margin-top: 30px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
            <h2 style="font-size: 18px; font-weight: bold; color: #2c3e50;">Mes Alertes</h2>
            <button onclick="marquerToutLu()" style="background: none; border: none; color: #3498db; font-size: 12px; cursor: pointer;">Tout marquer comme lu</button>
        </div>

        <!-- ✅ C'EST CET ID QUE LE JS CHERCHE -->
        <div id="notifications-list" class="flex flex-col gap-3">
            <p style="text-align: center; color: #95a5a6; padding: 20px;">Chargement de vos alertes...</p>
        </div>
    </section>
</main>

<!-- ✅ N'OUBLIE PAS LE SCRIPT À LA FIN -->
<script src="{{ asset('js/notifications.js') }}"></script>


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
    /* On s'assure que le contenu ne passe pas sous le menu */
    main { padding-bottom: 110px !important; }
    
    /* On force l'affichage en ligne du menu si jamais un autre CSS l'empêche */
    .nav-list { display: flex !important; flex-direction: row !important; }
</style>



  <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
