function togglePassword() {
    const passwordField = document.getElementById('password');
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
}

// Lógica del formulario con SweetAlert2
document.getElementById('loginForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Evita el envío tradicional del formulario

    const formData = new FormData(this);
    const response = await fetch('../src/controllers/login.php', {
        method: 'POST',
        body: formData,
    });

    const result = await response.json();

    if (result.status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Inicio Exitoso',
            text: result.message,
        }).then(() => {
            // Redirigir al dashboard después del éxito
            window.location.href = 'pages/dashboard.php';
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: result.message,
        });
    }
});



document.querySelector("form").addEventListener("submit", function (event) {
    const usuario = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    // Validación de campos vacíos
    if (usuario === "" || password === "") {
        event.preventDefault();  // Evita el envío del formulario
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor complete ambos campos.'
        });
    }
});

