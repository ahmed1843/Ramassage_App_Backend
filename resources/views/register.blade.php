<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>S'enregistrer - New Déchets</title>
  
  <!-- ✅ Utilisation de asset() -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body style="background: #f8f9fa;">
  <header style="padding: 20px; text-align: center;">
    <h1 style="color: #27ae60; font-weight: 900;">Créer un compte</h1>
  </header>

<main class="p-5" style="padding: 20px; max-width: 450px; margin: 0 auto; padding-bottom: 120px;">
    <form id="register-form" class="auth-form" style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
      
      <label>Prénom :</label>
      <input type="text" id="prenom" required placeholder="Votre prénom" style="width:100%; padding:12px; border-radius:10px; border:1px solid #eee; margin-bottom:15px;">
      <span id="error-prenom" style="color: #e74c3c; font-size: 11px; display: none;">⚠️ Lettres uniquement.</span>

      <label>Nom :</label>
      <input type="text" id="nom" required placeholder="Votre nom" style="width:100%; padding:12px; border-radius:10px; border:1px solid #eee; margin-bottom:15px;">
      <span id="error-nom" style="color: #e74c3c; font-size: 11px; display: none;">⚠️ Lettres uniquement.</span>

      <label>Téléphone :</label>
      <input type="tel" id="telephone" required placeholder="77 000 00 00" style="width:100%; padding:12px; border-radius:10px; border:1px solid #eee; margin-bottom:15px;">
      
      <label>Email :</label>
      <input type="email" id="email" required placeholder="ex: momo@mail.com" style="width:100%; padding:12px; border-radius:10px; border:1px solid #eee; margin-bottom:15px;">

      <label>Mot de passe :</label>
      <div style="position: relative; width: 100%; margin-bottom: 5px;">
          <input type="password" id="password" required minlength="8" placeholder="••••••••" style="width: 100%; padding: 12px; padding-right: 45px; border-radius: 10px; border: 1px solid #eee; box-sizing: border-box;">
          <span id="togglePassword" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer;">👁️</span>
      </div>

      <!-- ✅ INDISPENSABLE pour éviter l'erreur ligne 55/58 de register.js -->
      <div id="password-strength-container" style="display: none; margin-bottom: 15px;">
          <div style="display: flex; gap: 4px; height: 4px; margin-bottom: 5px;">
              <div id="bar-1" style="flex: 1; background: #eee;"></div>
              <div id="bar-2" style="flex: 1; background: #eee;"></div>
              <div id="bar-3" style="flex: 1; background: #eee;"></div>
          </div>
          <span id="strength-text" style="font-size: 11px; font-weight: bold; color: #95a5a6;">Faible</span>
      </div>

      <!-- ✅ INDISPENSABLE pour éviter l'erreur ligne 122 (status 422) -->
      <div id="error-message" style="display:none; color: red; font-size: 13px; margin-bottom: 10px; text-align: center;"></div>

      <button type="submit" style="width: 100%; background: #2ecc71; color: white; border: none; padding: 15px; border-radius: 15px; font-weight: bold; cursor: pointer;">S'enregistrer</button>
    </form>
    
    <div id="register-confirmation" style="display:none; text-align: center; margin-top: 20px; color: #2ecc71; font-weight: bold;">
      ✅ Enregistrement réussi !
    </div>
</main>

  <script src="{{ asset('js/config.js') }}"></script>
  <script src="{{ asset('js/register.js') }}"></script>
</body>
</html>
