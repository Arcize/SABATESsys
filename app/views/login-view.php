<div class="fullContainer">
    <div class="image-box">
        <div class="welcome-box">
            <h1>¡Bienvenido!</h1>
            <p>Sistema de Reportes de SABATES</p>
        </div>
    </div>
    <div class="login-register-box">
        <div class="img-header">
            <img src="public\img\Banner_SABATES.png" alt="">
        </div>
        <form action="?view=login_user" method="POST" class="login-register-form">
            <h2 class="login-register-header">Iniciar Sesión</h2>
            <?php
            if (isset($_SESSION['register_success'])) {
                echo '<p class="alertSession">' . $_SESSION['register_success'] . '</p>';
                unset($_SESSION['register_success']);
            }
            if (isset($_SESSION['login_failed'])) {
                echo '<p class="alertSession">' . $_SESSION['login_failed'] . '</p>';
                unset($_SESSION['login_failed']);
            }
            ?>
            <div class="input-box">
                <input type="text" placeholder="Cédula" name="cedula" required class="no-spaces ci" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6e6e6e">
                    <path d="M160-80q-33 0-56.5-23.5T80-160v-440q0-33 23.5-56.5T160-680h200v-120q0-33 23.5-56.5T440-880h80q33 0 56.5 23.5T600-800v120h200q33 0 56.5 23.5T880-600v440q0 33-23.5 56.5T800-80H160Zm80-160h240v-18q0-17-9.5-31.5T444-312q-20-9-40.5-13.5T360-330q-23 0-43.5 4.5T276-312q-17 8-26.5 22.5T240-258v18Zm320-60h160v-60H560v60Zm-200-60q25 0 42.5-17.5T420-420q0-25-17.5-42.5T360-480q-25 0-42.5 17.5T300-420q0 25 17.5 42.5T360-360Zm200-60h160v-60H560v60ZM440-600h80v-200h-80v200Z" />
                </svg>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Contraseña" name="password" required class="no-spaces" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#6e6e6e">
                    <path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z" />
                </svg>
            </div>
            <div class="forgot">
                <a href="?view=forgotPassword">¿Olvidaste la Contraseña?</a>
            </div>
            <button type="submit" class="btnIn">Iniciar Sesión</button>
            <div class="login-register">
                <p>¿No tienes cuenta? <a href="?view=register">Regístrate</a></p>
            </div>
        </form>
    </div>
</div>