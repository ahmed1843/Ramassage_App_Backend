<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil - New Déchets</title>
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
</head>

<body>
  @include('partials.header', ['subtitle' => 'Mon espace'])

  <main class="p-5" style="padding: 20px;">
    
    <!-- 1. RÉSUMÉ ET INFOS FIXES -->
    <section style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; margin-bottom: 25px;">
      <h2 style="font-size: 18px; font-weight: bold; margin-bottom: 15px;">Mon Profil</h2>
      <p style="margin: 5px 0;"><strong>Nom :</strong> <span id="user-name-display" style="color: #27ae60;">Chargement...</span></p>
      <p style="margin: 5px 0;"><strong>Email :</strong> <span id="user-email-display">Chargement...</span></p>
    </section>

    <!-- 2. BOUTON ADMIN (Conditionnel) -->
    <section id="admin-section" style="display: none; margin-bottom: 25px; text-align: center;">
      <a href="{{ url('/admin') }}" style="display: block; background: #27ae60; color: white; padding: 15px; border-radius: 15px; text-decoration: none; font-weight: bold; box-shadow: 0 4px 10px rgba(39, 174, 96, 0.3);">
        ⚙️ Accéder au Dashboard Admin
      </a>
    </section>

    <!-- 3. FORMULAIRE DE MISE À JOUR (REMIS ICI) -->
    <section class="user-info" style="background: white; padding: 25px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px;">
      <h2 style="font-size: 16px; font-weight: bold; margin-bottom: 20px; color: #2c3e50;">Modifier mes informations</h2>
      <form id="profil-form">
        <label style="display:block; margin-bottom:8px; font-size: 12px; font-weight: bold;">NOM COMPLET :</label>
        <input type="text" id="display-name" required style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #edf2f7; background: #f8fafc; margin-bottom: 20px;"/>

        <label style="display:block; margin-bottom:8px; font-size: 12px; font-weight: bold;">MA ZONE / QUARTIER :</label>
        <select id="user-quartier" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid #edf2f7; background: #f8fafc; margin-bottom: 20px;">
          <option value="medina">Médina</option>
          <option value="plateau">Plateau</option>
          <option value="almadies">Almadies</option>
          <option value="ouakam">Ouakam</option>
        </select>
        <button type="submit" id="btn-update" style="width: 100%; padding: 15px; background: #2ecc71; color: white; border: none; border-radius: 15px; font-weight: bold; cursor: pointer;">Sauvegarder les changements</button>
      </form>
    </section>

    <!-- 4. LISTE DES SIGNALEMENTS -->
    <section class="my-reports-section">
      <h2 style="color: #27ae60; margin-bottom: 15px; font-size: 18px; font-weight: bold;">Mes derniers signalements</h2>
      <div id="mes-signalements-liste">
        <p style="text-align: center; color: #95a5a6;">Chargement de vos activités...</p>
      </div>
    </section>

  </main>

  @include('partials.footer')
  @include('partials.side-menu')
  @include('partials.logout-modal')

  <script src="{{ asset('js/config.js') }}"></script>
  <script src="{{ asset('js/script.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const userData = localStorage.getItem('user');
    const token = localStorage.getItem('token');

    if (!userData || !token) {
        window.location.href = '/login';
        return;
    }

    const user = JSON.parse(userData);
    
    // Remplissage initial
    document.getElementById('user-name-display').innerText = user.name;
    document.getElementById('user-email-display').innerText = user.email;
    document.getElementById('display-name').value = user.name;
    if(user.zone) document.getElementById('user-quartier').value = user.zone.toLowerCase();

    if (user.role === 'admin') {
        document.getElementById('admin-section').style.display = 'block';
    }

    // --- FONCTION MISE À JOUR DU PROFIL ---
    document.getElementById('profil-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const btn = document.getElementById('btn-update');
        const newName = document.getElementById('display-name').value;
        const newZone = document.getElementById('user-quartier').value;

        btn.innerText = "Mise à jour...";
        btn.disabled = true;

        try {
            const response = await fetch('/api/user/update', {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: newName, zone: newZone })
            });

            if (response.ok) {
                const updatedData = await response.json();
                // On met à jour le localStorage
                user.name = newName;
                user.zone = newZone;
                localStorage.setItem('user', JSON.stringify(user));
                alert("✅ Profil mis à jour !");
                location.reload();
            }
        } catch (err) { alert("Erreur lors de la mise à jour"); }
        btn.innerText = "Sauvegarder les changements";
        btn.disabled = false;
    });

    // --- CHARGEMENT DES SIGNALEMENTS ---
    const liste = document.getElementById('mes-signalements-liste');
    try {
        const response = await fetch('/api/reports', { 
            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
        });
        const result = await response.json();
        const allReports = Array.isArray(result) ? result : (result.data || []);

        const myReports = allReports.filter(r => String(r.user_id) === String(user.id));

        if (myReports.length > 0) {
            liste.innerHTML = "";
            myReports.reverse().forEach(report => {
                let couleur = report.status === 'resolved' ? "#27ae60" : "#f39c12";
                let photoUrl = report.image ? `/storage/${report.image}` : '';
                liste.innerHTML += `
                    <div style="background: white; padding: 15px; border-radius: 15px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-left: 5px solid ${couleur};">
                        <div style="display: flex; gap: 15px; align-items: center;">
                            ${photoUrl ? `<img src="${photoUrl}" style="width: 60px; height: 60px; border-radius: 12px; object-fit: cover;">` : ''}
                            <div style="flex: 1;">
                                <div style="display: flex; justify-content: space-between;">
                                    <small style="color: #95a5a6; font-size: 10px;">ID #${report.id}</small>
                                    <span style="color: ${couleur}; font-size: 10px; font-weight: bold;">${report.status}</span>
                                </div>
                                <p style="margin: 5px 0; font-weight: bold; color: #2c3e50; font-size: 14px;">${report.description}</p>
                                <div style="font-size: 11px; color: #bdc3c7;">📍 ${report.location_name || 'Dakar'}</div>
                            </div>
                        </div>
                    </div>`;
            });
           } else {
            // Voici le petit logo et le message quand c'est vide
            liste.innerHTML = `
                <div style="text-align: center; padding: 40px 20px; background: #f9fbf9; border-radius: 20px; border: 2px dashed #e0e0e0; margin-top: 10px;">
                    <div style="font-size: 50px; margin-bottom: 15px;">🍃</div>
                    <h3 style="color: #2c3e50; font-size: 16px; margin-bottom: 5px;">Tout est propre !</h3>
                    <p style="color: #95a5a6; font-size: 13px; line-height: 1.4;">
                        Vous n'avez pas encore envoyé de signalement.<br>
                        Dakar vous remercie pour votre engagement.
                    </p>
                    <a href="/signalement" style="display: inline-block; margin-top: 15px; color: #2ecc71; font-weight: bold; text-decoration: none; font-size: 13px;">
                        + Faire mon premier signalement
                    </a>
                </div>`;
        }

    } catch (e) { console.error(e); }
});
</script>
</body>
</html>
