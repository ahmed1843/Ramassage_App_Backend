const BASE_URL = "/api"; // ✅ Utiliser un chemin relatif est plus sûr avec Laravel

async function apiFetch(endpoint, options = {}) {
    const token = localStorage.getItem('token');
    
    const defaultHeaders = {
        'Accept': 'application/json',
        // 'Content-Type' sera ajouté seulement si ce n'est pas un FormData
    };

    if (token) {
        defaultHeaders['Authorization'] = `Bearer ${token}`;
    }

    // 💡 ASTUCE : Si on envoie un FormData (photo), on laisse le navigateur 
    // gérer lui-même le Content-Type (ne pas mettre application/json)
    if (!(options.body instanceof FormData)) {
        defaultHeaders['Content-Type'] = 'application/json';
    }

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...options.headers 
        }
    };

    const response = await fetch(`${BASE_URL}${endpoint}`, config);
    
    if (response.status === 401) {
        localStorage.clear();
        window.location.href = '/login';
    }

    return response;
}
