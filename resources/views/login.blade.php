<!DOCTYPE html>
<html lang="fr">
<head>


  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion - New Déchets</title>
  
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">


  <style>
    /* Style pour le footer mobile */
    footer.mobile-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        width: 100%;
        height: 85px;
        background-color: white;
        border-top: 1px solid #eeeeee;
        box-shadow: 0 -5px 25px rgba(0,0,0,0.1);
        z-index: 999999;
        display: flex; 
        align-items: center;
    }
    footer.mobile-nav nav { width: 100%; }
    .nav-list {
        display: flex !important;
        width: 100% !important;
        justify-content: space-around !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .nav-item a {
        text-decoration: none !important;
        color: #7f8c8d !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;
        gap: 4px !important;
        font-size: 26px !important;
    }
    .nav-item a span {
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase;
    }
    main { padding-bottom: 120px !important; }
  </style>
</head>

<body>
  <header style="padding: 20px; text-align: center;">
    <h1 style="color: #27ae60; font-weight: 900;">Connexion</h1>
  </header>

  <main class="p-5" style="padding: 20px; max-width: 400px; margin: 0 auto;">
    <!-- Formulaire de connexion -->
    <form id="login-form" style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
      <label style="display:block; margin-bottom:8px; font-weight:bold;">Email :</label>
      <input type="email" id="email" required placeholder="votre@mail.com" style="width: 100%; padding: 14px; margin-bottom: 20px; border-radius: 12px; border: 1px solid #eee; box-sizing: border-box;">

      <label style="display:block; margin-bottom:8px; font-weight:bold;">Mot de passe :</label>
      <div style="position: relative; width: 100%;">
          <input type="password" id="password" required placeholder="••••••••" 
                 style="width: 100%; padding: 14px; padding-right: 45px; border-radius: 12px; border: 1px solid #eee; box-sizing: border-box;">
          <span id="togglePassword" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 18px;">👁️</span>
      </div>

      <div id="login-error" style="display:none; color: #e74c3c; font-size: 13px; margin: 15px 0; text-align: center;">
        ❌ Email ou mot de passe incorrect.
      </div>

      <button type="submit" style="width: 100%; background: #2ecc71; color: white; border: none; padding: 16px; border-radius: 15px; font-weight: bold; cursor: pointer; margin-top: 10px;">
        Se connecter
      </button>
    </form>

    <!-- Navigation Inscription -->
    <div style="background:white; padding:25px; border-radius:20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-top:20px; text-align:center;">
        <p style="font-size:14px; color:#7f8c8d; margin-bottom:15px;">Nouveau sur New Déchets ?</p>
        <button onclick="window.location.href='/register'" style="width:100%; background:white; color:#2ecc71; border: 2px solid #2ecc71; padding:14px; border-radius:15px; font-weight:bold; cursor:pointer;">
            Créer un compte
        </button>
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

  <script src="{{ asset('js/config.js') }}"></script>
  <script src="{{ asset('js/script.js') }}"></script>
  <script src="{{ asset('js/login.js') }}"></script> <!-- 👈 INDISPENSABLE -->
</body>
</html>
