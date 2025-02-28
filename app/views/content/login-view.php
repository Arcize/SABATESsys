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
                <input type="text" placeholder="Usuario" name="username" required />
                <img
                    src="app/views/img/user_white.svg"
                    alt="user" />
            </div>
            <div class="input-box">
                <input
                    type="password"
                    placeholder="Contraseña"
                    name="password"
                    required />
                <img
                    src="app/views/img/lock.svg"
                    alt="password" />
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