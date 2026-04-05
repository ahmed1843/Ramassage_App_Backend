<!DOCTYPE html>
<html lang="fr">
<head>
  <script src="{{ asset('js/auth-guard.js') }}"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil - New Déchets</title>
  
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
</head>

<body>
@include('partials.header', ['subtitle' => 'Mes dernières alertes'])

  <main class="p-5" style="padding: 20px;">
    <!-- RÉSUMÉ RAPIDE -->
    <section style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; margin-bottom: 25px;">
      <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Mon Profil</h2>
      <p style="margin: 5px 0;"><strong>Nom :</strong> <span id="user-name-display" style="color: #27ae60;">Chargement...</span></p>
      <p style="margin: 5px 0;"><strong>Email :</strong> <span id="user-email-display">Chargement...</span></p>
    </section>

    <!-- SECTION ADMIN (Cachée par défaut) -->
    <section id="admin-section" style="display: none; margin-bottom: 25px; text-align: center;">
      <a href="{{ url('/admin') }}" style="display: block; background: #27ae60; color: white; padding: 15px; border-radius: 15px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3);">
        ⚙️ Accéder au Dashboard Admin
      </a>
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

  
  </main>

@include('partials.footer')


  <!-- ✅ Scripts JS -->
  <script src="{{ asset('js/config.js') }}"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <script src="{{ asset('js/profil.js') }}"></script>

  <script>
    // ✅ VERIFICATION DU RÔLE ADMIN EN JAVASCRIPT
    document.addEventListener('DOMContentLoaded', () => {
        const userData = localStorage.getItem('user');
        if (userData) {
            const user = JSON.parse(userData);
            // Si l'utilisateur est admin, on affiche le bouton
            if (user.role === 'admin') {
                document.getElementById('admin-section').style.display = 'block';
            }
        }
    });
  </script>
@include('partials.logout-modal')
@include('partials.side-menu')
</body>
</html>
