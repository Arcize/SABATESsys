<div class="fullContainer">
    <div class="image-box">
        <div class="welcome-box">
            <h1>¡Bienvenido!</h1>
            <p>Crea tu cuenta en el Sistema de Reportes de SABATES</p>
        </div>
    </div>
    <div class="login-register-box">
        <div class="img-header">
        </div>
        <form action="?view=register_user" method="POST" class="login-register-form">
            <h2 class="login-register-header">Crea una Cuenta</h2>
            <?php
            if (isset($_SESSION['register_failed'])) {
                echo '<p class="alertSession">' . $_SESSION['register_failed'] . '</p>';
                unset($_SESSION['register_failed']);
            }
            ?>
            <div class="input-box">
                <input type="text" placeholder="Cédula" name="id" required class="no-spaces ci" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M160-80q-33 0-56.5-23.5T80-160v-440q0-33 23.5-56.5T160-680h200v-120q0-33 23.5-56.5T440-880h80q33 0 56.5 23.5T600-800v120h200q33 0 56.5 23.5T880-600v440q0 33-23.5 56.5T800-80H160Zm80-160h240v-18q0-17-9.5-31.5T444-312q-20-9-40.5-13.5T360-330q-23 0-43.5 4.5T276-312q-17 8-26.5 22.5T240-258v18Zm320-60h160v-60H560v60Zm-200-60q25 0 42.5-17.5T420-420q0-25-17.5-42.5T360-480q-25 0-42.5 17.5T300-420q0 25 17.5 42.5T360-360Zm200-60h160v-60H560v60ZM440-600h80v-200h-80v200Z" />
                </svg>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Usuario" name="username" required class="no-spaces" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6">
                    <path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z" />
                </svg>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Contraseña" name="password" id="password" required class="no-spaces" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                    <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                </svg>
            </div>
            <div class="password-box">
                <ul id="password-criteria">
                    <h6>La contraseña debe contener:</h6>
                    <li id="length" class="invalid">Al menos 8 caracteres</li>
                    <li id="uppercase" class="invalid">Al menos una letra mayúscula</li>
                    <li id="lowercase" class="invalid">Al menos una letra minúscula</li>
                    <li id="number" class="invalid">Al menos un número</li>
                </ul>
            </div>
            <button type="submit" class="btnIn">Registrarte</button>
            <div class="login-register">
                <p>¿Ya tienes cuenta? <a href="?view=login">Inicia sesión</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const lengthCriteria = document.getElementById('length');
        const uppercaseCriteria = document.getElementById('uppercase');
        const lowercaseCriteria = document.getElementById('lowercase');
        const numberCriteria = document.getElementById('number');

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
</script>

<style>
    .valid::before {
        content: '✔';
        color: green;
        margin-right: 5px;
    }

    .invalid::before {
        content: '✖';
        color: red;
        margin-right: 5px;
    }
</style>