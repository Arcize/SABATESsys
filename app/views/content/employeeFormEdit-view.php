<?php
$employeeModel = new employeeModel();
$employee = $employeeModel->readOne($_GET['id']);
?>
<div class="view-box">
    <div class="formContainer">
        <h3 class="h3">Actualice sus Datos</h2>
            <form action="index.php?view=employee&action=employee_update" method="POST">
                <input type="hidden" name="id" value="<?php echo isset($employee['id_persona']) ? $employee['id_persona'] : ''; ?>">

                <div class="userDetails">
                    <div class="inputGroup">
                        <label for="nombre">Nombre:</label>
                        <input class="input" id="nombre" required type="text" name="nombre" value="<?php echo isset($employee['nombre']) ? $employee['nombre'] : ''; ?>" placeholder="Nombre">
                    </div>
                    <div class="inputGroup">
                        <label for="apellido">Apellido:</label>
                        <input class="input" id="apellido" required type="text" name="apellido" value="<?php echo isset($employee['apellido']) ? $employee['apellido'] : ''; ?>" placeholder="Apellido">
                    </div>
                    <div class="inputGroup">
                        <label for="cedula">Cédula:</label>
                        <input class="input" id="cedula" required type="text" name="cedula" value="<?php echo isset($employee['cedula']) ? $employee['cedula'] : ''; ?>" placeholder="Cédula">
                    </div>
                    <div class="inputGroup">
                        <label for="correo">Email:</label>
                        <input class="input" id="correo" required type="text" name="correo" value="<?php echo isset($employee['correo']) ? $employee['correo'] : ''; ?>" placeholder="Email">
                    </div>
                    <div class="inputGroup">
                        <label for="departamento">Departamento:</label>
                        <select id="departamento" required name="departamento">
                            <option value="">------------------------------------</option>
                            <option value="1" <?php echo isset($employee['id_departamento']) && $employee['id_departamento'] == '1' ? 'selected' : ''; ?>>Informática</option>
                            <option value="2" <?php echo isset($employee['id_departamento']) && $employee['id_departamento'] == '2' ? 'selected' : ''; ?>>Recursos Humanos</option>
                            <option value="3" <?php echo isset($employee['id_departamento']) && $employee['id_departamento'] == '3' ? 'selected' : ''; ?>>Contabilidad</option>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="sexo">Sexo:</label>
                        <select id="sexo" required name="sexo">
                            <option value="">------------------------------------</option>
                            <option value="1" <?php echo isset($employee['id_sexo']) && $employee['id_sexo'] == '1' ? 'selected' : ''; ?>>Masculino</option>
                            <option value="2" <?php echo isset($employee['id_sexo']) && $employee['id_sexo'] == '2' ? 'selected' : ''; ?>>Femenino</option>
                        </select>
                    </div>
                    <div class="inputGroup">
                        <label for="fecha_nac">Fecha de Nacimiento:</label>
                        <input class="input date" id="fecha_nac" required type="date" name="fecha_nac" value="<?php echo isset($employee['fecha_nac']) ? $employee['fecha_nac'] : ''; ?>" placeholder="Fecha de Nacimiento">
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