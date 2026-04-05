<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Dakar Propre</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8 font-sans">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-green-700">Gestion des Signalements 🇸🇳</h1>
            <button onclick="location.reload()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                Rafraîchir
            </button>
        </div>
        
        <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-green-600 text-white font-bold uppercase text-xs">
                        <th class="p-4">ID</th>
                        <th class="p-4 text-center">Photo</th>
                        <th class="p-4">Quartier</th>
                        <th class="p-4">Description</th>
                        <th class="p-4 text-center">Statut</th>
                        <th class="p-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="reports-list">
                    <!-- Les lignes s'afficheront ici via JS -->
                </tbody>
            </table>
        </div>
    </div>

<script>
    window.onload = function() {
        chargerSignalements();
    };

    async function chargerSignalements() {
        try {
            const response = await fetch('/api/admin/reports');
            const reports = await response.json();
            const tableBody = document.getElementById('reports-list');
            tableBody.innerHTML = ''; 

            if (reports.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-gray-500 italic">Aucun signalement en base.</td></tr>';
                return;
            }

            reports.forEach(report => {
                let statusBadge = '';
                let actionButtons = '';

                // ✅ CORRECTION : On utilise report.image car c'est le nom dans ta base
                const imageUrl = report.image 
                    ? `/storage/${report.image}` 
                    : 'https://placeholder.com';

                if (report.status === 'resolved') {
                    statusBadge = '<span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">Réglé</span>';
                    actionButtons = '<span class="text-green-600 italic text-sm font-medium">Ramassé ✅</span>';
                } 
                else if (report.status === 'in_progress') {
                    statusBadge = '<span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800">En cours</span>';
                    actionButtons = `<button onclick="changerStatut(${report.id}, 'resolved')" class="bg-green-500 hover:bg-green-600 text-white text-xs px-4 py-2 rounded shadow transition">✅ Terminer</button>`;
                } 
                else { 
                    statusBadge = '<span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800">En attente</span>';
                    actionButtons = `<button onclick="changerStatut(${report.id}, 'in_progress')" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-4 py-2 rounded shadow transition">🚚 Lancer</button>`;
                }

                tableBody.innerHTML += `
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-4 font-bold text-gray-700">#${report.id}</td>
                        <td class="p-4 text-center">
                            <img src="${imageUrl}" 
                                 alt="Déchet" 
                                 class="w-12 h-12 rounded shadow-sm object-cover cursor-pointer hover:scale-110 transition mx-auto border border-gray-200"
                                 onclick="window.open('${imageUrl}', '_blank')">
                        </td>
                        <td class="p-4 font-medium">${report.zone ? report.zone.name : 'Dakar'}</td>
                        <td class="p-4 text-gray-600">${report.description || 'N/A'}</td>
                        <td class="p-4 text-center">${statusBadge}</td>
                        <td class="p-4 text-center">${actionButtons}</td>
                    </tr>
                `;
            });
        } catch (error) {
            console.error("Erreur détaillée :", error);
        }
    }

    async function changerStatut(id, nouveauStatut) {
        if (!confirm("Voulez-vous changer le statut ?")) return;

        try {
            const response = await fetch(`/api/admin/reports/${id}/status`, {
                method: 'PATCH',
                headers: { 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: nouveauStatut })
            });

            if (response.ok) {
                chargerSignalements();
            } else {
                alert("Erreur lors de la mise à jour");
            }
        } catch (error) {
            console.error("Erreur lors du changement de statut:", error);
        }
    }
</script>
</body>
</html>
