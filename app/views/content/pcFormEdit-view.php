
<?php
$pcModel = new pcModel();
$pc = $pcModel->readOne($_GET['id']);
?>
<h2>Editar PC</h2>

<form action="index.php?view=pc&action=pc_update" method="post" class="formContainer">

  <input type="hidden" name="id" value="<?php echo isset($pc['id_equipo_informatico']) ? $pc['id_equipo_informatico'] : ''; ?>">

  <label for="marca">Marca:</label>
  <input type="text" id="marca" name="marca" class="input" value="<?php echo isset($pc['marca_equipo_informatico']) ? $pc['marca_equipo_informatico'] : ''; ?>" required>

  <label for="estado">Estado del equipo:</label>
  <select id="estado" name="estado" class="input" required>
    <option value="" selected>-----------------------</option>
    <option value="1" <?php echo isset($pc['id_estado_equipo']) && $pc['id_estado_equipo'] == 1 ? 'selected' : ''; ?>>Operativo</option>
    <option value="2" <?php echo isset($pc['id_estado_equipo']) && $pc['id_estado_equipo'] == 2 ? 'selected' : ''; ?>>Averiado</option>
    <option value="3" <?php echo isset($pc['id_estado_equipo']) && $pc['id_estado_equipo'] == 3 ? 'selected' : ''; ?>>En reparación</option>
    <option value="4" <?php echo isset($pc['id_estado_equipo']) && $pc['id_estado_equipo'] == 4 ? 'selected' : ''; ?>>En espera de piezas</option>
    <option value="5" <?php echo isset($pc['id_estado_equipo']) && $pc['id_estado_equipo'] == 5 ? 'selected' : ''; ?>>Retirado</option>
  </select>

  <label for="persona_id">ID de la persona asignada:</label>
  <input type="text" id="persona_id" name="persona_id" class="input" value="<?php echo isset($pc['id_persona']) ? $pc['id_persona'] : ''; ?>" required>

  <fieldset>
    <legend>Procesador</legend>
    <label for="marca_procesador">Marca del procesador:</label>
    <input type="text" id="marca_procesador" name="marca_procesador" class="input" value="<?php echo isset($pc['marca_procesador']) ? $pc['marca_procesador'] : ''; ?>" required>

    <label for="nombre_procesador">Nombre del procesador:</label>
    <input type="text" id="nombre_procesador" name="nombre_procesador" class="input" value="<?php echo isset($pc['nombre_procesador']) ? $pc['nombre_procesador'] : ''; ?>" required>

    <label for="nucleos">Núcleos:</label>
    <input type="text" id="nucleos" name="nucleos" class="input" value="<?php echo isset($pc['nucleos']) ? $pc['nucleos'] : ''; ?>" required>

    <label for="frecuencia_procesador">Frecuencia del procesador (GHz):</label>
    <input type="text" id="frecuencia_procesador" name="frecuencia_procesador" class="input" value="<?php echo isset($pc['frecuencia_procesador']) ? $pc['frecuencia_procesador'] : ''; ?>" required>
  </fieldset>

  <fieldset>
    <legend>Motherboard</legend>
    <label for="marca_motherboard">Marca de la motherboard:</label>
    <input type="text" id="marca_motherboard" name="marca_motherboard" class="input" value="<?php echo isset($pc['marca_motherboard']) ? $pc['marca_motherboard'] : ''; ?>" required>
    <label for="modelo_motherboard">Modelo de la motherboard:</label>
    <input type="text" id="modelo_motherboard" name="modelo_motherboard" class="input" value="<?php echo isset($pc['modelo_motherboard']) ? $pc['modelo_motherboard'] : ''; ?>" required>
  </fieldset>

  <fieldset>
    <legend>Fuente</legend>
    <label for="marca_fuente">Marca de la fuente:</label>
    <input type="text" id="marca_fuente" name="marca_fuente" class="input" value="<?php echo isset($pc['marca_fuente_poder']) ? $pc['marca_fuente_poder'] : ''; ?>" required>

    <label for="wattage_fuente">Wattage de la fuente:</label>
    <input type="text" id="wattage_fuente" name="wattage_fuente" class="input" value="<?php echo isset($pc['wattage_fuente']) ? $pc['wattage_fuente'] : ''; ?>" required>
  </fieldset>

  <fieldset>
    <legend>RAM</legend>
    <!-- Puedes duplicar este bloque para cada módulo de RAM -->
    <div class="ram-module">
      <label for="marca_ram">Marca de la RAM:</label>
      <input type="text" id="marca_ram" name="marca_ram" class="input" value="<?php echo isset($pc['marca_ram']) ? $pc['marca_ram'] : ''; ?>" required>

      <label for="tipo_ram">Tipo de RAM:</label>
      <input type="text" id="tipo_ram" name="tipo_ram" class="input" value="<?php echo isset($pc['tipo_ram']) ? $pc['tipo_ram'] : ''; ?>" required>

      <label for="frecuencia_ram">Frecuencia de la RAM (MHz):</label>
      <input type="text" id="frecuencia_ram" name="frecuencia_ram" class="input" value="<?php echo isset($pc['frecuencia_ram']) ? $pc['frecuencia_ram'] : ''; ?>" required>

      <label for="capacidad_ram">Capacidad de la RAM (GB):</label>
      <input type="text" id="capacidad_ram" name="capacidad_ram" class="input" value="<?php echo isset($pc['capacidad_ram']) ? $pc['capacidad_ram'] : ''; ?>" required>
    </div>
  </fieldset>

  <div class="btnArea">
    <input type="submit" value="Actualizar PC" class="btn submit">
  </div>

</form>