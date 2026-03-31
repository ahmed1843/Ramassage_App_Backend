<head>
    <meta charset="UTF-8">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Ton CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <div id="map" style="height: 400px;"></div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Tes scripts -->
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/carte.js') }}"></script>
<footer class="mobile-nav">
  <nav>
    <ul class="nav-list">
      <li class="nav-item"><a href="{{ url('/') }}">🏠<span>Accueil</span></a></li>
      <li class="nav-item"><a href="{{ url('/carte') }}" class="active" style="color: #27ae60 !important;">🗺️<span>Carte</span></a></li>
      <li class="nav-item"><a href="{{ url('/signalement') }}">📢<span>Signaler</span></a></li>
      <li class="nav-item"><a href="{{ url('/notifications') }}">🔔<span>Notifs</span></a></li>
      <li class="nav-item"><a href="{{ url('/profil') }}">👤<span>Profil</span></a></li>
    </ul>
  </nav>
</footer>


</body>