import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Add this to your main JavaScript file
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Or if you're using fetch API
function fetchWithCsrf(url, options = {}) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    return fetch(url, {
        ...options,
        headers: {
            ...options.headers,
            'X-CSRF-TOKEN': csrfToken,
        },
        credentials: 'include', // Changed from 'same-origin' to 'include' for cross-domain requests
    });
}

// Add a specific login function that uses fetchWithCsrf
function loginWithCsrf(url, formData) {
    return fetchWithCsrf(url, {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.json();
        }
    });
}


