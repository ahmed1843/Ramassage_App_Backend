<!DOCTYPE html>
<html lang="fr">
<head>
   {{-- <script src="{{ asset('js/auth-guard.js') }}"></script> --}}

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Notifications - New Déchets</title>
    
    <!-- ✅ Tailwind Fixé -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

   
</head>

<body class="bg-gray-50 text-gray-900">

    <!-- ✅ Header Fixe en haut -->
  @include('partials.header', ['subtitle' => 'Mes dernières alertes'])


    <main class="p-4 max-w-md mx-auto">
        <div class="flex justify-between items-center mb-6 mt-2">
            <h2 class="text-gray-500 font-bold text-sm uppercase">Dernières Alertes</h2>
            <button onclick="marquerToutLu()" class="text-blue-500 text-xs font-bold bg-blue-50 px-3 py-1 rounded-full">
                Tout lire
            </button>
        </div>

        <!-- ✅ Liste des Notifications -->
        <div id="notifications-list" class="flex flex-col gap-4">
            <div class="flex justify-center py-10">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500"></div>
            </div>
        </div>
    </main>

     @include('partials.footer')

    <!-- SCRIPTS -->
    <script src="{{ asset('js/config.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

   <!-- ... (Tout ton code HTML reste identique jusqu'au script) ... -->

<script>
    async function chargerMesNotifications() {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch('/api/notifications', {
                headers: { 
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
            
            const notifications = await response.json();
            const list = document.getElementById('notifications-list');
            list.innerHTML = '';

            if (!notifications || notifications.length === 0) {
                list.innerHTML = `
                    <div class="text-center p-12 bg-white rounded-3xl shadow-sm border border-dashed border-gray-200">
                        <span class="text-4xl">📭</span>
                        <p class="text-gray-400 mt-4 font-medium text-sm">Aucune nouvelle alerte.</p>
                    </div>`;
                return;
            }

            notifications.forEach(n => {
                // ✅ ADAPTATION : On lit n.message et n.title directement (pas de .data)
                const message = n.message || "";
                const title = n.title || "Notification";
                
                let icon = '🔔';
                let borderColor = 'border-yellow-400';
                let statusBg = 'bg-yellow-50';
                
                // On détecte le statut par le contenu du message pour garder les couleurs
                if(message.includes('🚚')) { 
                    icon = '🚚'; borderColor = 'border-blue-500'; statusBg = 'bg-blue-50'; 
                } else if(message.includes('✅')) { 
                    icon = '✅'; borderColor = 'border-green-500'; statusBg = 'bg-green-50'; 
                }

                list.innerHTML += `
                    <div class="notif-card bg-white p-4 rounded-2xl shadow-sm border-l-4 ${borderColor} mb-4">
                        <div class="flex gap-4 items-start">
                            <div class="p-2 ${statusBg} rounded-xl text-xl">${icon}</div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800">${title}</p>
                                <p class="text-gray-600 text-xs mt-1 font-medium">${message}</p>
                                <p class="text-[10px] text-gray-400 mt-2 uppercase font-bold">
                                    Dakar • ${new Date(n.created_at).toLocaleTimeString('fr-SN', {hour:'2-digit', minute:'2-digit'})}
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            });
        } catch (error) {
            console.error("Erreur:", error);
            document.getElementById('notifications-list').innerHTML = '<p class="text-center text-red-500 text-xs">Erreur de connexion au serveur.</p>';
        }
    }

    function marquerToutLu() {
        alert("Toutes les notifications ont été lues.");
    }

    window.onload = chargerMesNotifications;
</script>
@include('partials.logout-modal')
@include('partials.side-menu')
</body>
</html>
