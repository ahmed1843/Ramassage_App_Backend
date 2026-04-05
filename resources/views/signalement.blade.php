<!DOCTYPE html>
<html lang="fr">
<head>
<script src="{{ asset('js/auth-guard.js') }}"></script>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Signalement - New Déchets</title>
  
  <!-- ✅ ON GARDE UNIQUEMENT CES DEUX LIGNES (asset) -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/signalement.css') }}">
  
  <!-- ❌ ON SUPPRIME LES ANCIENNES LIGNES SANS ASSET ICI -->
</head>
<body>
@include('partials.header', ['subtitle' => 'Mes dernières alertes'])


  <main style="padding: 20px;">
    <!-- ✅ AJOUT DE enctype POUR LES PHOTOS -->
    <form class="report-form" id="report-form" enctype="multipart/form-data" style="background: white; padding: 20px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <label style="display:block; margin-bottom:8px; font-weight:bold;">Description du problème :</label>
      <textarea id="description" required placeholder="Décrivez le dépôt sauvage..." style="width: 100%; height: 100px; padding: 12px; border-radius: 12px; border: 1px solid #eee; margin-bottom: 20px;"></textarea>

      <label style="display:block; margin-bottom:8px; font-weight:bold;">Localisation :</label>
      <div style="display: flex; gap: 10px; margin-bottom: 20px;">
        <input type="text" id="location" placeholder="Ex: Quartier Médina" required style="flex: 1; padding: 12px; border-radius: 12px; border: 1px solid #eee;" />
        <button type="button" id="geo-btn" title="Ma position actuelle" style="padding: 12px; background: #f8f9fa; border: 1px solid #eee; border-radius: 12px; cursor: pointer;">📍</button>
      </div>

      <label style="display:block; margin-bottom:8px; font-weight:bold;">Ajouter une photo :</label>
      <input type="file" id="photo" accept="image/*" style="width: 100%; margin-bottom: 10px;" />
      
    <div id="preview-container" style="display: none; margin-top: 10px;">
    <img id="photo-preview" src="" style="width: 100%; border-radius: 10px;">
</div>

      <button type="submit" style="width: 100%; padding: 16px; background: #2ecc71; color: white; border: none; border-radius: 15px; font-weight: bold; cursor: pointer; margin-top: 10px;">Envoyer le signalement</button>
    </form>

    <div id="confirmation" style="display:none; margin-top: 20px; padding: 15px; background: #e8f5e9; color: #2e7d32; border-radius: 15px; text-align: center; font-weight: bold;">
      ✅ Signalement envoyé avec succès !
    </div>
  </main>

@include('partials.footer')



  <style>
      main { padding-bottom: 110px !important; }
      .nav-list { display: flex !important; flex-direction: row !important; }
  </style>

    <script src="{{ asset('js/config.js') }}"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <!-- ✅ AJOUTE CETTE LIGNE ICI -->
  <script src="{{ asset('js/signalement.js') }}"></script>
@include('partials.logout-modal')
@include('partials.side-menu')

</body>

</body>
</html>
