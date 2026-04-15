<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Signalement - New Déchets</title>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/signalement.css') }}">
    
    <style>
        /* Correction pour le défilement et le footer */
        body { 
            margin: 0; 
            padding: 0; 
            overflow-x: hidden; 
            background-color: #f8f9fa;
        }
        main { 
            padding-bottom: 120px !important; /* Laisse de la place pour le footer */
            min-height: 100vh;
        }
        /* Style pour que le bouton localisation soit visible */
        #geo-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
    </style>
</head>
<body>
    @include('partials.header', ['subtitle' => 'Signaler un problème'])

    <main style="padding: 20px;">
        <form class="report-form" id="form-signalement" enctype="multipart/form-data" style="background: white; padding: 25px; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
            
            <label style="display:block; margin-bottom:10px; font-weight:800; color: #2c3e50;">Description :</label>
            <textarea name="description" id="description" required placeholder="Que se passe-t-il ?" style="width: 100%; height: 120px; padding: 15px; border-radius: 15px; border: 1px solid #edf2f7; background: #f8fafc; margin-bottom: 20px; font-family: inherit;"></textarea>

            <label style="display:block; margin-bottom:10px; font-weight:800; color: #2c3e50;">Localisation :</label>
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <input type="text" name="location_name" id="location" placeholder="Ex: Rue 10, Médina" required style="flex: 1; padding: 15px; border-radius: 15px; border: 1px solid #edf2f7; background: #f8fafc;" />
                <button type="button" id="geo-btn" onclick="obtenirLocalisation()" style="width: 55px; background: white; border: 1px solid #2ecc71; border-radius: 15px; cursor: pointer; color: #2ecc71;">📍</button>
            </div>

            <label style="display:block; margin-bottom:10px; font-weight:800; color: #2c3e50;">Photo du dépôt :</label>
            <input type="file" name="image" id="photo" accept="image/*" style="width: 100%; margin-bottom: 20px;" />
            
            <div id="preview-container" style="display: none; margin-bottom: 20px;">
                <img id="photo-preview" src="" style="width: 100%; border-radius: 15px; border: 2px solid #2ecc71;">
            </div>

            <button type="button" id="btn-envoyer" onclick="envoyerSignalement()" style="width: 100%; padding: 18px; background: #2ecc71; color: white; border: none; border-radius: 18px; font-weight: 900; font-size: 16px; cursor: pointer; box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);">
                Envoyer le signalement
            </button>
        </form>

        <div id="confirmation" style="display:none; margin-top: 20px; padding: 20px; background: #d4edda; color: #155724; border-radius: 20px; text-align: center; font-weight: bold; border: 1px solid #c3e6cb;">
            🎉 Signalement enregistré !
        </div>
    </main>

    @include('partials.footer')
    @include('partials.side-menu')

    <!-- Scripts -->
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        // Fonction simple pour la localisation (même si bloquée, elle ne fera pas planter la page)
        function obtenirLocalisation() {
            const input = document.getElementById('location');
            input.value = "Localisation en cours...";
            
            navigator.geolocation.getCurrentPosition(
                (pos) => { input.value = `Lat: ${pos.coords.latitude.toFixed(4)}, Long: ${pos.coords.longitude.toFixed(4)}`; },
                (err) => { input.value = "Dakar (Position manuelle)"; alert("GPS bloqué par le navigateur (HTTP)."); }
            );
        }

        // Prévisualisation de la photo
        document.getElementById('photo').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                document.getElementById('preview-container').style.display = 'block';
                document.getElementById('photo-preview').src = URL.createObjectURL(file);
            }
        };
    </script>
</body>
</html>
