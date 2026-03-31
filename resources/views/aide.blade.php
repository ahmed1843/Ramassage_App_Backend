<!DOCTYPE html>
<html lang="fr">
<head>
<script src="{{ asset('js/auth-guard.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide & Contact - New Déchets</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        /* On garde ton style spécifique */
        main { padding-bottom: 120px !important; padding: 20px; }
        footer.mobile-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; height: 85px; border-top: 1px solid #eee; display: flex; align-items: center; z-index: 1000; }
        .nav-list { display: flex; width: 100%; justify-content: space-around; list-style: none; padding: 0; margin: 0; }
        .nav-item a { text-decoration: none; display: flex; flex-direction: column; align-items: center; gap: 4px; color: #7f8c8d; }
        .nav-item a.active { color: #27ae60; }
        .nav-item span:first-child { font-size: 26px; }
        .nav-item span:last-child { font-size: 11px; font-weight: 700; text-transform: uppercase; }
    </style>
</head>
<body style="background: #f8f9fa;">

    <header style="padding: 20px; text-align: center; background: white;">
        <h1 style="color: #27ae60; font-weight: 900;">Aide & Contact</h1>
    </header>

    <main class="p-5 flex flex-col gap-6">
        <!-- Ton contenu original corrigé -->
        <section style="background: #2ecc71; color: white; padding: 20px; border-radius: 20px; text-align: center; box-shadow: 0 10px 20px rgba(46,204,113,0.2); margin-bottom: 20px;">
            <span style="font-size: 40px;">📞</span>
            <h2 style="margin: 10px 0 5px 0;">Assistance UCG</h2>
            <p style="font-size: 14px; opacity: 0.9; margin-bottom: 15px;">Une urgence ? Appelez-nous directement.</p>
            <a href="tel:+221338000000" style="display: inline-block; background: white; color: #2ecc71; padding: 12px 25px; border-radius: 12px; font-weight: bold; text-decoration: none; font-size: 18px;">
                Appeler le 33 800 00 00
            </a>
        </section>

        <section style="margin-bottom: 20px;">
            <h3 style="font-weight: bold; margin-bottom: 15px;">❓ Foire aux questions</h3>
            <div style="display: flex; flex-direction: column; gap: 10px;">
                <details style="background: white; padding: 15px; border-radius: 15px; cursor: pointer; border: 1px solid #eee;">
                    <summary style="font-weight: bold; color: #2c3e50;">🚛 Camion non passé ?</summary>
                    <p style="font-size: 13px; color: #7f8c8d; margin-top: 10px;">Vérifiez l'état de la zone dans vos statistiques ou faites un signalement.</p>
                </details>
            </div>
        </section>

        <section style="background: white; padding: 20px; border-radius: 20px; border: 1px solid #eee;">
            <h3 style="font-weight: bold; margin-bottom: 15px;">📩 Nous écrire</h3>
            <form style="display: flex; flex-direction: column; gap: 12px;">
                <input type="text" placeholder="Sujet" style="padding: 12px; border-radius: 10px; border: 1px solid #ddd;">
                <textarea placeholder="Votre message..." style="padding: 12px; border-radius: 10px; border: 1px solid #ddd; height: 100px;"></textarea>
                <button type="button" onclick="alert('Message envoyé !')" style="background: #2ecc71; color: white; border: none; padding: 15px; border-radius: 12px; font-weight: bold; cursor: pointer;">Envoyer</button>
            </form>
        </section>
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
</body>
</html>
