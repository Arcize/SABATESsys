<!-- Elimino el bloque <style> ya que los estilos están en perfil.css -->
<div class="view-box">
    <div class="perfil-selector">
        <button id="btn-info" class="active" onclick="showSection('info')">Información Básica</button>
        <button id="btn-security" onclick="showSection('security')">Seguridad</button>
    </div>
    <div id="section-info" class="perfil-section active">
        <form class="perfil-form" id="form-info">
            <h2>Información Básica</h2>
            <div class="perfil-form-row">
                <div>
                    <label for="cedula">Cédula</label>
                    <input type="text" id="cedula" name="cedula" disabled>
                </div>
                <div>
                    <label for="departamento">Departamento</label>
                    <input type="text" id="departamento" name="departamento" disabled>
                </div>
            </div>
            <div class="perfil-form-row">
                <div>
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" disabled>
                </div>
                <div>
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" disabled>
                </div>
            </div>
            <div class="perfil-form-row">
                <div style="flex:2;">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" required>
                </div>
            </div>
            <div class="perfil-form-row">
                <div>
                    <label for="sexo">Sexo</label>
                    <select id="sexo" name="sexo" disabled>
                        <option value="">Seleccione</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
                <div>
                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" disabled>
                </div>
            </div>
            <button type="submit">Actualizar</button>
        </form>
    </div>
    <div id="section-security" class="perfil-section">
        <div class="perfil-security-flex">
            <form class="perfil-form" id="form-password">
                <h2>Cambiar Contraseña</h2>
                <label for="current_password">Contraseña actual</label>
                <input type="password" id="current_password" name="current_password" required>
                <label for="new_password">Nueva contraseña</label>
                <input type="password" id="new_password" name="new_password" required>
                <div id="password-req-msg" class="perfil-password-msg"></div>
                <label for="confirm_password">Confirmar nueva contraseña</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <div id="confirm-password-error" class="perfil-password-msg"></div>
                <button type="submit">Actualizar</button>
            </form>
            <form class="perfil-form" id="form-security-questions">
                <h2>Preguntas de Seguridad</h2>
                <div id="security-questions-container">
                    <!-- Aquí se cargarán dinámicamente las preguntas -->
                </div>
                <button type="submit">Actualizar preguntas</button>
            </form>
        </div>
    </div>
</div>
<script>
    // Alternar secciones
    function showSection(section) {
        if (section === 'info') {
            $('#section-info').addClass('active');
            $('#section-security').removeClass('active');
            $('#btn-info').addClass('active');
            $('#btn-security').removeClass('active');
        } else {
            $('#section-info').removeClass('active');
            $('#section-security').addClass('active');
            $('#btn-info').removeClass('active');
            $('#btn-security').addClass('active');
        }
    }

    $(document).ready(function() {
        // Cargar datos del usuario actual desde EmployeeController
        $.get('index.php?view=employee&action=get_profile', function(data) {
            if (data && data.success) {
                $('#nombre').val(data.user.nombre);
                $('#apellido').val(data.user.apellido);
                $('#cedula').val(data.user.cedula);
                $('#correo').val(data.user.correo);
                $('#departamento').val(data.user.departamento);
                $('#fecha_nacimiento').val(data.user.fecha_nac);
                // Selecciona el sexo correcto
                if (data.user.sexo) {
                    $('#sexo').val(data.user.sexo.charAt(0).toUpperCase());
                }
            }
        }, 'json');

        // Cargar preguntas de seguridad y todas las posibles para los selects
        function cargarPreguntasSeguridad() {
            // Obtener todas las preguntas posibles
            $.get('index.php?view=securityQuestions&action=listSecurityQuestions', function(allQuestions) {
                // Obtener las preguntas actuales del usuario
                $.get('index.php?view=securityQuestions&action=get_user_questions', function(data) {
                    if (data && data.success && Array.isArray(allQuestions)) {
                        let html = '';
                        for (let i = 0; i < 3; i++) {
                            let pregunta = data.questions[i] ? data.questions[i].pregunta : '';
                            let id_pregunta = data.questions[i] ? data.questions[i].id_pregunta : '';
                            let respuesta = data.questions[i] ? data.questions[i].respuesta : '';
                            html += `<div class='perfil-form-row' style='flex-direction:column;gap:0;'>`;
                            html += `<div style='width:100%;'>`;
                            html += `<label for='securityQuestion${i+1}'>Pregunta ${i+1}</label>`;
                            html += `<select name='securityQuestion${i+1}' id='securityQuestion${i+1}' class='questionInput' required style='width:100%;margin-bottom:6px;'>`;
                            html += `<option value=''>Seleccione</option>`;
                            allQuestions.forEach(function(q) {
                                let selected = (q.id_pregunta == id_pregunta) ? 'selected' : '';
                                html += `<option value='${q.id_pregunta}' ${selected}>${q.texto_pregunta}</option>`;
                            });
                            html += `</select>`;
                            html += `<input class='input' type='text' name='respuesta_${i}' value='${respuesta || ''}' placeholder='Respuesta' required style='width:100%;margin-bottom:0;'>`;
                            html += `</div>`;
                            html += `</div>`;
                        }
                        $('#security-questions-container').html(html);

                        // Lógica para evitar preguntas repetidas
                        $('.questionInput').on('change', function() {
                            let seleccionadas = [];
                            $('.questionInput').each(function() {
                                if ($(this).val()) seleccionadas.push($(this).val());
                            });
                            $('.questionInput').each(function() {
                                let actual = $(this).val();
                                $(this).find('option').each(function() {
                                    if ($(this).val() !== '' && seleccionadas.includes($(this).val()) && $(this).val() !== actual) {
                                        $(this).hide();
                                    } else {
                                        $(this).show();
                                    }
                                });
                            });
                        });
                    } else {
                        $('#security-questions-container').html('<div style="color:red">No se pudieron cargar las preguntas de seguridad.</div>');
                    }
                }, 'json');
            }, 'json');
        }
        cargarPreguntasSeguridad();
    });

    // Actualizar correo (y otros datos en el futuro)
    $('#form-info').on('submit', function(e) {
        e.preventDefault();
        // Validación extra de correo (por si acaso)
        var correo = $('#correo').val();
        if (!correo || !/^([a-zA-Z0-9_\.-]+)@([\da-zA-Z\.-]+)\.([a-zA-Z\.]{2,6})$/.test(correo)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Correo electrónico no válido'
            });
            return;
        }
        $.post('index.php?view=employee&action=update_profile', {
            correo: correo
        }, function(data) {
            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? 'Éxito' : 'Error',
                text: data.message
            });
        }, 'json');
    });

    // Cambiar contraseña desde perfil (EmployeeController)
    $('#form-password').on('submit', function(e) {
        e.preventDefault();
        var current_password = $('#current_password').val();
        var new_password = $('#new_password').val();
        var confirm_password = $('#confirm_password').val();
        // Validación frontend: mínimo 8 caracteres, mayúscula, minúscula y número
        var valid = true;
        var errorMsg = '';
        if (new_password.length < 8) {
            valid = false;
            errorMsg = 'La contraseña debe tener al menos 8 caracteres.';
        } else if (!/[A-Z]/.test(new_password)) {
            valid = false;
            errorMsg = 'La contraseña debe tener al menos una letra mayúscula.';
        } else if (!/[a-z]/.test(new_password)) {
            valid = false;
            errorMsg = 'La contraseña debe tener al menos una letra minúscula.';
        } else if (!/[0-9]/.test(new_password)) {
            valid = false;
            errorMsg = 'La contraseña debe tener al menos un número.';
        } else if (new_password !== confirm_password) {
            valid = false;
            errorMsg = 'Las contraseñas no coinciden.';
        }
        if (!valid) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMsg
            });
            return;
        }
        $.post('index.php?view=employee&action=update_password', {
            current_password: current_password,
            new_password: new_password,
            confirm_password: confirm_password
        }, function(data) {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Contraseña actualizada!',
                    text: 'Tu contraseña se ha cambiado correctamente.'
                });
                $('#form-password')[0].reset();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message
                });
            }
        }, 'json');
    });

    // Actualizar preguntas de seguridad
    $('#form-security-questions').on('submit', function(e) {
        e.preventDefault();
        let respuestas = {};
        // Recoger los ids de las preguntas seleccionadas y las respuestas
        for (let i = 0; i < 3; i++) {
            let idPregunta = $('#securityQuestion' + (i+1)).val();
            let respuesta = $('input[name="respuesta_' + i + '"]').val();
            if (idPregunta && respuesta) {
                respuestas['pregunta_' + i] = idPregunta;
                respuestas['respuesta_' + i] = respuesta;
            }
        }
        $.post('index.php?view=securityQuestions&action=update_user_questions', respuestas, function(data) {
            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? 'Éxito' : 'Error',
                text: data.message
            });
        }, 'json');
    });

    // Validación en tiempo real del correo con reglas avanzadas y mensaje de error
    const correoInput = document.getElementById('correo');
    if (correoInput) {
        correoInput.classList.add('validate-email');
        // Crear elemento de error si no existe
        let errorElement = correoInput.parentElement.querySelector('.emailError');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'emailError';
            errorElement.style.color = 'red';
            errorElement.style.fontSize = '0.95em';
            correoInput.parentElement.appendChild(errorElement);
        }
        const emailRegex = /^[a-zA-Z0-9](?!.*[._-]{2})[a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+$/;
        correoInput.addEventListener('input', function () {
            let enteredEmail = correoInput.value.trim();
            // Eliminar caracteres no válidos automáticamente
            enteredEmail = enteredEmail
                .replace(/[^a-zA-Z0-9._@-]/g, "") // Eliminar caracteres no permitidos
                .replace(/^[@._-]/, "") // Eliminar punto, arroba, guion o guion bajo solo al inicio
                .replace(/(\.{2,}|_{2,}|-{2,})/g, ".") // Reemplazar caracteres consecutivos no válidos
                .replace(/@{2,}/g, "@") // Eliminar múltiples arrobas
                .replace(/@.*@/, "@"); // Asegurarse de que solo haya una arroba
            correoInput.value = enteredEmail;
            // Validar el correo electrónico
            if (emailRegex.test(enteredEmail)) {
                errorElement.textContent = "";
            } else {
                errorElement.textContent = "Por favor, ingrese un correo electrónico válido.";
            }
        });
    }

    // Mostrar mensaje de requisitos faltantes de contraseña en tiempo real
    const passwordInput = document.getElementById('new_password');
    const confirmInput = document.getElementById('confirm_password');
    const msgBox = document.getElementById('password-req-msg');
    const confirmError = document.getElementById('confirm-password-error');
    if (passwordInput) {
        passwordInput.addEventListener('input', function () {
            const val = passwordInput.value;
            let faltantes = [];
            if (val.length < 8) faltantes.push('al menos 8 caracteres');
            if (!/[A-Z]/.test(val)) faltantes.push('una letra mayúscula');
            if (!/[a-z]/.test(val)) faltantes.push('una letra minúscula');
            if (!/[0-9]/.test(val)) faltantes.push('un número');
            if (faltantes.length === 0) {
                msgBox.textContent = '';
            } else {
                msgBox.textContent = 'A la contraseña le falta: ' + faltantes.join(', ');
            }
            // Validar coincidencia en tiempo real
            if (confirmInput && confirmInput.value.length > 0) {
                if (confirmInput.value !== val) {
                    confirmError.textContent = 'Las contraseñas no coinciden.';
                } else {
                    confirmError.textContent = '';
                }
            }
        });
    }
    if (confirmInput) {
        confirmInput.addEventListener('input', function () {
            if (confirmInput.value !== passwordInput.value) {
                confirmError.textContent = 'Las contraseñas no coinciden.';
            } else {
                confirmError.textContent = '';
            }
        });
    }

    // Validación en tiempo real de respuestas de preguntas de seguridad (igual que en securityQuestions.php)
    function limpiarRespuesta(valor) {
        valor = valor.replace(/^\s+/, '').replace(/\s{3,}/g, '  ');
        valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
        valor = valor.toLowerCase();
        if (valor.length > 0) {
            valor = valor.charAt(0).toUpperCase() + valor.slice(1);
        }
        return valor;
    }
    $(document).on('input', '#form-security-questions input[type="text"]', function() {
        let pos = this.selectionStart;
        let valorLimpio = limpiarRespuesta(this.value);
        this.value = valorLimpio;
        this.setSelectionRange(pos, pos);
    });
    $(document).on('blur', '#form-security-questions input[type="text"]', function() {
        this.value = this.value.replace(/\s+$/, '');
    });
    $('#form-security-questions').on('submit', function(e) {
        $('#form-security-questions input[type="text"]').each(function() {
            this.value = this.value.replace(/\s+$/, '');
        });
    });
</script>