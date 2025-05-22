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
                <input type="text" placeholder="Cédula" name="id" id="cedula" required class="no-spaces ci" required/>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6e6e6e">
                    <path d="M160-80q-33 0-56.5-23.5T80-160v-440q0-33 23.5-56.5T160-680h200v-120q0-33 23.5-56.5T440-880h80q33 0 56.5 23.5T600-800v120h200q33 0 56.5 23.5T880-600v440q0 33-23.5 56.5T800-80H160Zm80-160h240v-18q0-17-9.5-31.5T444-312q-20-9-40.5-13.5T360-330q-23 0-43.5 4.5T276-312q-17 8-26.5 22.5T240-258v18Zm320-60h160v-60H560v60Zm-200-60q25 0 42.5-17.5T420-420q0-25-17.5-42.5T360-480q-25 0-42.5 17.5T300-420q0 25 17.5 42.5T360-360Zm200-60h160v-60H560v60ZM440-600h80v-200h-80v200Z" />
                </svg>
                
                <div class="ciError inputErrorLoginRegister">
                    
                </div>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Contraseña" name="password" id="password" required class="no-spaces" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6e6e6e">
                    <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                </svg>
                <svg class="clue" xmlns="http://www.w3.org/2000/svg" height="36px" viewBox="0 -960 960 960" width="36px" fill="#6e6e6e">
                    <path d="M478-240q21 0 35.5-14.5T528-290q0-21-14.5-35.5T478-340q-21 0-35.5 14.5T428-290q0 21 14.5 35.5T478-240Zm-36-154h74q0-33 7.5-52t42.5-52q26-26 41-49.5t15-56.5q0-56-41-86t-97-30q-57 0-92.5 30T342-618l66 26q5-18 22.5-39t53.5-21q32 0 48 17.5t16 38.5q0 20-12 37.5T506-526q-44 39-54 59t-10 73Zm38 314q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
                </svg>
                <div class="criteria">
                    <h6>La contraseña debe contener:</h6>
                    <ul id="password-criteria">
                        <li id="length">Al menos 8 caracteres</li>
                        <li id="uppercase" >Al menos una letra mayúscula</li>
                        <li id="lowercase" >Al menos una letra minúscula</li>
                        <li id="number">Al menos un número</li>
                    </ul>
                </div>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Confirme su Contraseña" name="passwordConfirm" id="passwordConfirm" required class="no-spaces" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6e6e6e">
                    <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                </svg>
            </div>
            <button type="submit" class="btnIn">Registrarte</button>
            <div class="login-register">
                <p>¿Ya tienes cuenta? <a href="?view=login">Inicia sesión</a></p>
            </div>
        </form>
    </div>
</div>

<script>
    cedulaInput = document.getElementById('cedula');
    cedulaInput.addEventListener('input', async function() {
        
        const cedula = document.getElementById('cedula').value;
        const response = await fetch(`?view=employee&action=get_Cedula&cedula=${cedula}`);
        const data = await response.json();

        if (data.exist) {
            document.querySelector('.ciError').innerHTML = "La cédula ya existe";
            document.querySelector('.btnIn').disabled = true;
        } else {
            document.querySelector('.ciError').innerHTML = "";
            document.querySelector('.btnIn').disabled = false;
        }
    });

    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('passwordConfirm');
    const submitBtn = document.querySelector('.btnIn');

    function checkPasswordsMatch(showError = false) {
        const errorDiv = passwordConfirm.parentElement.querySelector('.inputErrorLoginRegister') || document.createElement('div');
        errorDiv.className = 'inputErrorLoginRegister';
        if (password.value !== passwordConfirm.value && showError && passwordConfirm.value !== "") {
            errorDiv.textContent = "Las contraseñas no coinciden";
            submitBtn.disabled = true;
        } else {
            errorDiv.textContent = "";
            // Solo habilita si no hay otros errores
            if (document.querySelector('.ciError').textContent === "") {
                submitBtn.disabled = false;
            }
        }
        if (!passwordConfirm.parentElement.querySelector('.inputErrorLoginRegister')) {
            passwordConfirm.parentElement.appendChild(errorDiv);
        }
    }

    // Solo muestra el error al escribir en el segundo campo
    password.addEventListener('input', function() {
        checkPasswordsMatch(false);
    });
    passwordConfirm.addEventListener('input', function() {
        checkPasswordsMatch(true);
    });
</script>