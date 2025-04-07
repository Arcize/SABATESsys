<div class="fullContainer">
    <div class="wrapper">
        <form action="?view=login_user" method="POST">
            <h2>Iniciar Sesión</h2>
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
                <input type="text" placeholder="Usuario" name="username" required class="no-spaces" />
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#f6f6f6"><path d="M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Z"/></svg>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Contraseña" name="password" required class="no-spaces"/>
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-80q-33 0-56.5-23.5T160-160v-400q0-33 23.5-56.5T240-640h40v-80q0-83 58.5-141.5T480-920q83 0 141.5 58.5T680-720v80h40q33 0 56.5 23.5T800-560v400q0 33-23.5 56.5T720-80H240Zm240-200q33 0 56.5-23.5T560-360q0-33-23.5-56.5T480-440q-33 0-56.5 23.5T400-360q0 33 23.5 56.5T480-280ZM360-640h240v-80q0-50-35-85t-85-35q-50 0-85 35t-35 85v80Z"/></svg>
            </div>
            <div class="forgot">
                <a href="#">¿Olvidaste la Contraseña?</a>
            </div>
            <button type="submit" class="btnIn">Iniciar Sesión</button>
            <div class="login-register">
                <p>¿No tienes cuenta? <a href="?view=register">Regístrate</a></p>
            </div>
        </form>
    </div>
</div>