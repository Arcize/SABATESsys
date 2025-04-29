document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const form = document.querySelector('form[action="?view=register_user"]');
    const lengthCriteria = document.getElementById('length');
    const uppercaseCriteria = document.getElementById('uppercase');
    const lowercaseCriteria = document.getElementById('lowercase');
    const numberCriteria = document.getElementById('number');

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;

            // Verificar longitud
            if (password.length >= 8) {
                lengthCriteria.classList.remove('invalid');
                lengthCriteria.classList.add('valid');
            } else {
                lengthCriteria.classList.remove('valid');
                lengthCriteria.classList.add('invalid');
            }

            // Verificar mayúsculas
            if (/[A-Z]/.test(password)) {
                uppercaseCriteria.classList.remove('invalid');
                uppercaseCriteria.classList.add('valid');
            } else {
                uppercaseCriteria.classList.remove('valid');
                uppercaseCriteria.classList.add('invalid');
            }

            // Verificar minúsculas
            if (/[a-z]/.test(password)) {
                lowercaseCriteria.classList.remove('invalid');
                lowercaseCriteria.classList.add('valid');
            } else {
                lowercaseCriteria.classList.remove('valid');
                lowercaseCriteria.classList.add('invalid');
            }

            // Verificar números
            if (/\d/.test(password)) {
                numberCriteria.classList.remove('invalid');
                numberCriteria.classList.add('valid');
            } else {
                numberCriteria.classList.remove('valid');
                numberCriteria.classList.add('invalid');
            }
        });
    }

    if (form) {
        form.addEventListener('submit', function(event) {
            if (lengthCriteria.classList.contains('invalid') ||
                uppercaseCriteria.classList.contains('invalid') ||
                lowercaseCriteria.classList.contains('invalid') ||
                numberCriteria.classList.contains('invalid')) {
                event.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, asegúrese de que la contraseña cumpla con todos los requisitos.',
                    customClass: {
                        popup: 'custom-swal-font'
                    }
                });
            }
        });
    }
});