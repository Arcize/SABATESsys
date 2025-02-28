<h2>Registro de PC's</h2>

<form action="index.php?view=pc&action=pc_create" method="post" class="formContainer">
  
  <label for="marca">Marca:</label>
  <input type="text" id="marca" name="marca" class="input" required>
  
  <label for="estado">Estado del equipo:</label>
  <select id="estado" name="estado" class="input" required>
    <option value="" selected>-----------------------</option>
    <option value="1">Operativo</option>
    <option value="2">Averiado</option>
    <option value="3">En reparación</option>
    <option value="4">En espera de piezas</option>
    <option value="5">Retirado</option>
  </select>
  
  <label for="persona_id">ID de la persona asignada:</label>
  <input type="text" id="persona_id" name="persona_id" class="input" required>
  
  <fieldset>
    <legend>Procesador</legend>
    <label for="marca_procesador">Marca del procesador:</label>
    <input type="text" id="marca_procesador" name="marca_procesador" class="input" required>
    
    <label for="nombre_procesador">Nombre del procesador:</label>
    <input type="text" id="nombre_procesador" name="nombre_procesador" class="input" required>
    
    <label for="nucleos">Núcleos:</label>
    <input type="text" id="nucleos" name="nucleos" class="input" required>
    
    <label for="frecuencia_procesador">Frecuencia del procesador (GHz):</label>
    <input type="text" id="frecuencia_procesador" name="frecuencia_procesador" class="input" required>
  </fieldset>
  
  <fieldset>
    <legend>Motherboard</legend>
    <label for="marca_motherboard">Marca de la motherboard:</label>
    <input type="text" id="marca_motherboard" name="marca_motherboard" class="input" required>
    <label for="modelo_motherboard">Modelo de la motherboard:</label>
    <input type="text" id="modelo_motherboard" name="modelo_motherboard" class="input" required>
  </fieldset>
  
  <fieldset>
    <legend>Fuente</legend>
    <label for="marca_fuente">Marca de la fuente:</label>
    <input type="text" id="marca_fuente" name="marca_fuente" class="input" required>
    
    <label for="wattage_fuente">Wattage de la fuente:</label>
    <input type="text" id="wattage_fuente" name="wattage_fuente" class="input" required>
  </fieldset>
  
  <fieldset>
    <legend>RAM</legend>
    <!-- Puedes duplicar este bloque para cada módulo de RAM -->
    <div class="ram-module">
      <label for="marca_ram">Marca de la RAM:</label>
      <input type="text" id="marca_ram" name="marca_ram" class="input" required>
      
      <label for="tipo_ram">Tipo de RAM:</label>
      <input type="text" id="tipo_ram" name="tipo_ram" class="input" required>
      
      <label for="frecuencia_ram">Frecuencia de la RAM (MHz):</label>
      <input type="text" id="frecuencia_ram" name="frecuencia_ram" class="input" required>
      
      <label for="capacidad_ram">Capacidad de la RAM (GB):</label>
      <input type="text" id="capacidad_ram" name="capacidad_ram" class="input" required>
    </div>
  </fieldset>
  
  <div class="btnArea">
    <input type="submit" value="Registrar PC" class="btn submit">
  </div>
  
</form>