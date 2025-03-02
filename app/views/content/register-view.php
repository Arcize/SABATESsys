<div class="fullContainer">
    <div class="wrapper">
        <form action="?view=register_user" method="POST">
            <h2>Crea una Cuenta</h2>
            <?php
            if (isset($_SESSION['register_failed'])) {
                echo '<p class="alertSession">' . $_SESSION['register_failed'] . '</p>';
                unset($_SESSION['register_failed']);
            }
            ?>
            <div class="input-box">
                <input
                    type="text"
                    placeholder="Cédula"
                    name="id"
                    required />
                <img
                    src="app/views/img/id.svg"
                    alt="id">
            </div>
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
                    id="password"
                    required />
                <img
                    src="app/views/img/lock.svg"
                    alt="password" />
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