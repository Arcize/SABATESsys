<?php

use app\controllers\SecurityQuestionsController;

$securityQuestionsController = new SecurityQuestionsController();
$securityQuestions = $securityQuestionsController->listSecurityQuestions();
?>

<div class="view-box security-questions-view">
    <h2 class="h2">Preguntas de Seguridad</h2>
    <div class="security-questions-container">
        <form id="security-questions-form" method="POST" action="?view=securityQuestions&action=securityQuestions_fetch_create" class="form">
            <div class="form-group">
                <div class="inputGroup">
                    <label for="securityQuestion-1">Primera Pregunta</label>
                    <select name="securityQuestion-1" id="securityQuestion-1" class="questionInput" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($securityQuestions as $securityQuestion) : ?>
                            <option value="<?php echo $securityQuestion['id_pregunta']; ?>"><?php echo $securityQuestion['texto_pregunta']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input class="input" type="text" name="securityAnswer-1" id="securityAnswer-1" placeholder="Respuesta" required>
                </div>
                <div class="inputGroup">
                    <label for="securityQuestion-2">Segunda Pregunta</label>
                    <select name="securityQuestion-2" id="securityQuestion-2" class="questionInput" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($securityQuestions as $securityQuestion) : ?>
                            <option value="<?php echo $securityQuestion['id_pregunta']; ?>"><?php echo $securityQuestion['texto_pregunta']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input class="input" type="text" name="securityAnswer-2" id="securityAnswer-2" placeholder="Respuesta" required>
                </div>
                <div class="inputGroup">
                    <label for="securityQuestion-3">Tercera Pregunta</label>
                    <select name="securityQuestion-3" id="securityQuestion-3" class="questionInput" required>
                        <option value="">Seleccione</option>
                        <?php foreach ($securityQuestions as $securityQuestion) : ?>
                            <option value="<?php echo $securityQuestion['id_pregunta']; ?>"><?php echo $securityQuestion['texto_pregunta']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input class="input" type="text" name="securityAnswer-3" id="securityAnswer-3" placeholder="Respuesta" required>
                </div>
            </div>
            <button class="btnIn" type="submit">Registrar</button>
        </form>
    </div>
    <script>

    </script>
    <script>
        document.querySelectorAll(".questionInput").forEach(select => {
            select.addEventListener("change", function() {
                let seleccionadas = new Set();

                // Obtener todas las respuestas seleccionadas
                document.querySelectorAll(".questionInput").forEach(s => {
                    if (s.value) {
                        seleccionadas.add(s.value);
                    }
                });

                // Eliminar opciones en otros select
                document.querySelectorAll(".questionInput").forEach(s => {
                    s.querySelectorAll("option").forEach(option => {
                        if (seleccionadas.has(option.value) && option.value !== s.value) {
                            option.remove(); // Elimina la opción del DOM
                        }
                    });
                });
            });
        });
    </script>
    <script>
        // Función para limpiar y formatear el texto
        function limpiarRespuesta(valor) {
            // Eliminar espacios al inicio y más de dos espacios seguidos
            valor = valor.replace(/^\s+/, '').replace(/\s{3,}/g, '  ');
            // Eliminar números y caracteres especiales (solo letras y espacios)
            valor = valor.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]/g, '');
            // Convertir a minúsculas y luego la primera letra a mayúscula
            valor = valor.toLowerCase();
            if (valor.length > 0) {
                valor = valor.charAt(0).toUpperCase() + valor.slice(1);
            }
            return valor;
        }
    
        // Validar en tiempo real cada input de respuesta
        document.querySelectorAll('input[name^="securityAnswer"]').forEach(input => {
            input.addEventListener('input', function(e) {
                let pos = this.selectionStart;
                let valorLimpio = limpiarRespuesta(this.value);
                this.value = valorLimpio;
                this.setSelectionRange(pos, pos);
            });
    
            // Eliminar espacios al final al perder el foco
            input.addEventListener('blur', function() {
                this.value = this.value.replace(/\s+$/, '');
            });
        });
    
        // Al enviar el formulario, limpiar espacios finales
        document.getElementById('security-questions-form').addEventListener('submit', function(e) {
            document.querySelectorAll('input[name^="securityAnswer"]').forEach(input => {
                input.value = input.value.replace(/\s+$/, '');
            });
        });
    </script>