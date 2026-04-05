<div id="logout-modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.6); z-index:3000000; align-items:center; justify-content:center; backdrop-filter: blur(5px);">
    <div style="background:white; width:85%; max-width:320px; padding:30px; border-radius:25px; text-align:center;">
        <div style="font-size:50px; margin-bottom:15px;">👋</div>
        <h2 style="color:#2c3e50; margin-bottom:10px;">Déconnexion</h2>
        <p style="color:#7f8c8d; margin-bottom:25px; font-size:15px;">Voulez-vous vraiment vous déconnecter ?</p>
        <button onclick="validerDeconnexion()" style="width:100%; padding:15px; background:#e74c3c; color:white; border:none; border-radius:15px; margin-bottom:10px; font-weight:bold; cursor:pointer;">Oui, me déconnecter</button>
        <button onclick="fermerModale()" style="width:100%; padding:15px; background:#f1f2f6; color:#7f8c8d; border:none; border-radius:15px; font-weight:bold; cursor:pointer;">Annuler</button>
    </div>
</div>
