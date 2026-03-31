const BASE_URL = "/api"; // ✅ Utiliser un chemin relatif est plus sûr avec Laravel

async function apiFetch(endpoint, options = {}) {
    const token = localStorage.getItem('token');
    
    const defaultHeaders = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    };

    if (token) {
        defaultHeaders['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        ...options,
        headers: {
            ...defaultHeaders,
            ...options.headers 
        }
    };

    const response = await fetch(`${BASE_URL}${endpoint}`, config);
    
    // Si le token est expiré (401), on déconnecte
    if (response.status === 401) {
        localStorage.clear();
        // ✅ CHANGEMENT ICI : login.html -> /login
        window.location.href = '/login';
    }

    return response;
}
