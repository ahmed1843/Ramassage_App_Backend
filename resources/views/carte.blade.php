<head>
    <meta charset="UTF-8">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- Ton CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
  @include('partials.header', ['subtitle' => 'Mes dernières alertes'])


    <!-- ✅ Ajoute le <main> ici -->
    <main>
        <div id="map" style="height: 500px; border-radius: 20px; z-index: 1;"></div>
    </main>

    <!-- Scripts -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/carte.js') }}"></script>

    @include('partials.footer')
@include('partials.logout-modal')
@include('partials.side-menu')


</body>
