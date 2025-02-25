// Login Form Submission
document.addEventListener("DOMContentLoaded", function() {
    // Get modal elements
    const loginBtn = document.getElementById('loginBtn');
    const registerBtn = document.getElementById('registerBtn');
    const loginModal = document.getElementById('loginModal');
    const registerModal = document.getElementById('registerModal');
    const closeLogin = document.getElementById('closeLogin');
    const closeRegister = document.getElementById('closeRegister');
    const loginForm = document.getElementById('login-form');

    // Login button click handler
    if (loginBtn) {
        loginBtn.addEventListener('click', function() {
            loginModal.style.display = "block";
        });
    }

    // Register button click handler
    if (registerBtn) {
        registerBtn.addEventListener('click', function() {
            registerModal.style.display = "block";
        });
    }

    // Close button handlers
    if (closeLogin) {
        closeLogin.addEventListener('click', function() {
            loginModal.style.display = "none";
        });
    }

    if (closeRegister) {
        closeRegister.addEventListener('click', function() {
            registerModal.style.display = "none";
        });
    }

    // Click outside modal to close
    window.addEventListener('click', function(event) {
        if (event.target === loginModal) {
            loginModal.style.display = "none";
        }
        if (event.target === registerModal) {
            registerModal.style.display = "none";
        }
    });

    // Login form submission
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(loginForm);
            
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    }
});

// Logout Button
document.getElementById('logout-btn').addEventListener('click', function () {
    fetch('logout.php')
    .then(() => {
        window.location.href = 'login.html';
    });
});

// Register form submission
const registerForm = document.getElementById('register-form');
if (registerForm) {
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(registerForm);
        
        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = data.redirect;
            } else {
                alert(data.message || 'Registration failed');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Registration failed');
        });
    });
}