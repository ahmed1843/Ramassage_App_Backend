<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Calendrier - Ramassage Déchets</title>
  
  <!-- ✅ Toujours asset() pour le CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/calendrier.css') }}">

  <style>
    main { padding: 20px; padding-bottom: 110px !important; }
    .calendar { width: 100%; border-collapse: collapse; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .calendar th { background: #27ae60; color: white; padding: 15px; text-align: left; }
    .calendar td { padding: 15px; border-bottom: 1px solid #eee; color: #2c3e50; }
    
    /* Style du menu bas identique aux autres pages */
    footer.mobile-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; height: 85px; border-top: 1px solid #eee; display: flex; align-items: center; z-index: 1000; }
    .nav-list { display: flex; width: 100%; justify-content: space-around; list-style: none; padding: 0; margin: 0; }
    .nav-item a { text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 4px; color: #7f8c8d; }
    .nav-item span:first-child { font-size: 26px; }
    .nav-item span:last-child { font-size: 11px; font-weight: 700; text-transform: uppercase; }
  </style>
</head>

<body style="background: #f8f9fa;">
    <header style="padding: 20px; text-align: center; background: white;">
        <h1 style="color: #27ae60; font-weight: 900;">Planning de Ramassage</h1>
    </header>

    <main>
        <div id="zone-info" style="text-align:center; margin-bottom:15px; font-weight:bold; color:#27ae60; font-size: 18px;"></div>
        
        <table class="calendar">
          <thead>
            <tr>
              <th>Jour</th>
              <th>Heure de passage</th>
            </tr>
          </thead>
          <tbody id="calendar-body">
            <tr><td colspan="2" style="text-align:center; padding: 30px;">Chargement du planning...</td></tr>
          </tbody>
        </table>
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

    <!-- ✅ SCRIPTS JS -->
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <script src="{{ asset('js/calendrier.js') }}"></script>
</body>
</html>
