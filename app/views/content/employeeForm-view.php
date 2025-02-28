<div class="view-box">
    <div class="formContainer">
        <h3 class="h3">Registre un Empleado</h2>
            <form action="index.php?view=employee&action=employee_create" method="POST">
                <div class="userDetails">
                    <div class="inputGroup">
                        <label for="nombre">Nombre:</label>
                        <input class="input" id="nombre" required type="text" name="nombre">
                    </div>
                    <div class="inputGroup">
                        <label for="apellido">Apellido:</label>
                        <input class="input" id="apellido" required type="text" name="apellido">
                    </div>
                    <div class="inputGroup">
                        <label for="cedula">Cédula:</label>
                        <input class="input" id="cedula" required type="text" name="cedula">
                    </div>
                    <div class="inputGroup">
                        <label for="correo">Email:</label>
                        <input class="input" id="correo" required type="text" name="correo">
                    </div>
                    <div class="inputGroup">
                        <label for="departamento">Departamento:</label>
                        <select id="departamento" required name="departamento">
                            <option value="">------------------------------------</option>
                            <option value="1">Informática</option>
                            <option value="2">Recursos Humanos</option>
                            <option value="3">Contabilidad</option>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="sexo">Sexo:</label>
                        <select id="sexo" required name="sexo">
                            <option value="">------------------------------------</option>
                            <option value="1">Masculino</option>
                            <option value="2">Femenino</option>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="fecha_nac">Fecha de Nacimiento:</label>
                        <input class="input date" id="fecha_nac" required type="date" name="fecha_nac">
                    </div>
                </div>

                <div class="btnArea">
                    <a href="index.php?view=employeeTable">
                        <button class="button" type="button">Volver</button>
                    </a>
                    <a href="index.php?view=employee&action=employee_create">
                        <button class="button" type="submit">Guardar</button>
                    </a>
                </div>
            </form>
    </div>
</div>