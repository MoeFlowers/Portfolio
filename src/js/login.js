function togglePassword() {
  const passwordField = document.getElementById("password");
  const buttonIcon = document.querySelector('button img'); // Selecciona el ícono

  if (passwordField.type === "password") {
    passwordField.type = "text"; // Cambia a texto
    buttonIcon.src = "assets/icons/eye-slash-solid.svg"; // Ícono de "ocultar contraseña"
    buttonIcon.alt = "Ocultar contraseña";
  } else {
    passwordField.type = "password"; // Cambia a contraseña
    buttonIcon.src = "assets/icons/eye-solid.svg"; // Ícono de "mostrar contraseña"
    buttonIcon.alt = "Mostrar contraseña";
  }
}
