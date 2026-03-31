document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('report-form');
    const geoBtn = document.getElementById('geo-btn');
    const photoInput = document.getElementById('photo');
    const preview = document.getElementById('photo-preview');
    const previewContainer = document.getElementById('preview-container');
    
    let coords = { lat: null, lng: null };

    // --- 1. GÉRER LE CLIC SUR LE BOUTON GPS 📍 ---
    geoBtn.addEventListener('click', () => {
        geoBtn.innerHTML = "⌛";
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition((pos) => {
                coords.lat = pos.coords.latitude;
                coords.lng = pos.coords.longitude;
                document.getElementById('location').value = "📍 Position GPS capturée";
                geoBtn.innerHTML = "✅";
                geoBtn.style.backgroundColor = "#e8f5e9";
            }, (err) => {
                alert("Erreur GPS : " + err.message);
                geoBtn.innerHTML = "❌";
            });
        }
    });

    // --- 2. PRÉVISUALISATION DE LA PHOTO ---
    photoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // --- 3. ENVOI DU FORMULAIRE (FormData pour l'image) ---
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        // On utilise FormData car il y a un fichier (image)
        const formData = new FormData();
        formData.append('title', document.getElementById('location').value);
        formData.append('description', document.getElementById('description').value);
        formData.append('latitude', coords.lat);
        formData.append('longitude', coords.lng);
        formData.append('zone_id', 1); // Test
        if (photoInput.files[0]) {
            formData.append('image', photoInput.files[0]);
        }

        try {
            const response = await fetch('/api/reports', {
                method: 'POST',
                headers: {
                    // ⚠️ Ne PAS mettre 'Content-Type': 'application/json' ici ! 
                    // Le navigateur le fait tout seul pour FormData
                    'Authorization': `Bearer ${localStorage.getItem('token')}`
                },
                body: formData
            });

            if (response.ok) {
                document.getElementById('confirmation').style.display = 'block';
                form.reset();
                previewContainer.style.display = 'none';
                setTimeout(() => window.location.href = '/carte', 2000);
            } else {
                alert("Erreur lors de l'envoi");
            }
        } catch (error) {
            console.error(error);
        }
    });
});
