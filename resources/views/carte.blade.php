<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    @include('partials.header', ['subtitle' => 'Carte des signalements'])

    <main>
        <div style="position: relative; margin: 10px;">
            <div id="map" style="height: 500px; border-radius: 20px; z-index: 1;"></div>
            <button onclick="declencherLocalisation()" style="position: absolute; bottom: 20px; right: 20px; width: 50px; height: 50px; background: white; border: 2px solid #27ae60; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.2); z-index: 1000; font-size: 22px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                🎯
            </button>
        </div>
    </main>

    <!-- 1. AJOUT DE L'OVERLAY (Indispensable pour le script) -->
    <div id="menu-overlay" onclick="toggleMenu()" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1999999;"></div>

    <!-- 2. INCLUSION DES COMPOSANTS (Avant les scripts) -->
    @include('partials.side-menu')
    @include('partials.logout-modal')
    @include('partials.footer')

    <!-- 3. SCRIPTS (À la fin) -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/carte.js') }}"></script>
</body>
