<?php
$pcModel = new pcModel();
$pc = $pcModel->readOne($_GET['id']);
?>
<div class="view-box">
	<h3 class="h3">Editar PC</h3>
	<div class="form-box">
		<div class="progressBar">
			<div class="bullet bulletActive">
				<span class="step-number display">1</span>
				<div class="check"><img src="app/views/img/check.svg" alt=""></div>
			</div>
			<div class="bullet">
				<span class="step-number display">2</span>
				<div class="check"><img src="app/views/img/check.svg" alt=""></div>
			</div>
			<div class="bullet">
				<span class="step-number display">3</span>
				<div class="check"><img src="app/views/img/check.svg" alt=""></div>
			</div>
			<div class="bullet">
				<span class="step-number display">4</span>
				<div class="check"><img src="app/views/img/check.svg" alt=""></div>
			</div>
			<div class="bullet">
				<span class="step-number display">5</span>
				<div class="check"><img src="app/views/img/check.svg" alt=""></div>
			</div>
			<div class="bullet">
				<span class="step-number display">6</span>
				<div class="check"><img src="app/views/img/check.svg" alt=""></div>
			</div>
		</div>
		<div class="form-layout">
			<form action="index.php?view=pc&action=pc_update" method="post" class="multi-step-form">
				<input type="hidden" name="id" value="<?php echo isset($pc['id_equipo_informatico']) ? $pc['id_equipo_informatico'] : ''; ?>">
				<fieldset class="page slidePage">
					<legend>
						<h4 class="h4">Información General</h4>
					</legend>
					<div class="pageInputs">
						<label for="fabricante">Fabricante:</label>
						<input type="text" id="fabricante" name="fabricante" class="input" value="<?php echo isset($pc['fabricante_equipo_informatico']) ? $pc['fabricante_equipo_informatico'] : ''; ?>" required>

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
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">Procesador</h4>
					</legend>
					<div class="pageInputs">
						<label for="fabricante_procesador">Fabricante del procesador:</label>
						<input type="text" id="fabricante_procesador" name="fabricante_procesador" class="input" value="<?php echo isset($pc['fabricante_procesador']) ? $pc['fabricante_procesador'] : ''; ?>" required>

						<label for="nombre_procesador">Nombre del procesador:</label>
						<input type="text" id="nombre_procesador" name="nombre_procesador" class="input" value="<?php echo isset($pc['nombre_procesador']) ? $pc['nombre_procesador'] : ''; ?>" required>

						<label for="nucleos">Núcleos:</label>
						<input type="text" id="nucleos" name="nucleos" class="input" value="<?php echo isset($pc['nucleos']) ? $pc['nucleos'] : ''; ?>" required>

						<label for="frecuencia_procesador">Frecuencia del procesador (GHz):</label>
						<input type="text" id="frecuencia_procesador" name="frecuencia_procesador" class="input" value="<?php echo isset($pc['frecuencia_procesador']) ? $pc['frecuencia_procesador'] : ''; ?>" required>
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">Motherboard</h4>
					</legend>
					<div class="pageInputs">
						<label for="fabricante_motherboard">Fabricante de la motherboard:</label>
						<input type="text" id="fabricante_motherboard" name="fabricante_motherboard" class="input" value="<?php echo isset($pc['fabricante_motherboard']) ? $pc['fabricante_motherboard'] : ''; ?>" required>

						<label for="modelo_motherboard">Modelo de la motherboard:</label>
						<input type="text" id="modelo_motherboard" name="modelo_motherboard" class="input" value="<?php echo isset($pc['modelo_motherboard']) ? $pc['modelo_motherboard'] : ''; ?>" required>
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">Fuente</h4>
					</legend>
					<div class="pageInputs">
						<label for="fabricante_fuente">Fabricante de la fuente:</label>
						<input type="text" id="fabricante_fuente" name="fabricante_fuente" class="input" value="<?php echo isset($pc['fabricante_fuente_poder']) ? $pc['fabricante_fuente_poder'] : ''; ?>" required>

						<label for="wattage_fuente">Wattage de la fuente:</label>
						<input type="text" id="wattage_fuente" name="wattage_fuente" class="input" value="<?php echo isset($pc['wattage_fuente']) ? $pc['wattage_fuente'] : ''; ?>" required>
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">RAM</h4>
					</legend>
					<div class="pageInputs">
						<label for="fabricante_ram">Fabricante de la RAM:</label>
						<input type="text" id="fabricante_ram" name="fabricante_ram" class="input" value="<?php echo isset($pc['fabricante_ram']) ? $pc['fabricante_ram'] : ''; ?>" required>

						<label for="tipo_ram">Tipo de RAM:</label>
						<input type="text" id="tipo_ram" name="tipo_ram" class="input" value="<?php echo isset($pc['tipo_ram']) ? $pc['tipo_ram'] : ''; ?>" required>

						<label for="frecuencia_ram">Frecuencia de la RAM (MHz):</label>
						<input type="text" id="frecuencia_ram" name="frecuencia_ram" class="input" value="<?php echo isset($pc['frecuencia_ram']) ? $pc['frecuencia_ram'] : ''; ?>" required>

						<label for="capacidad_ram">Capacidad de la RAM (GB):</label>
						<input type="text" id="capacidad_ram" name="capacidad_ram" class="input" value="<?php echo isset($pc['capacidad_ram']) ? $pc['capacidad_ram'] : ''; ?>" required>
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">Almacenamiento</h4>
					</legend>
					<div class="pageInputs">
						<label for="fabricante_almacenamiento">Fabricante del almacenamiento:</label>
						<input type="text" id="fabricante_almacenamiento" name="fabricante_almacenamiento" class="input" value="<?php echo isset($pc['fabricante_almacenamiento']) ? $pc['fabricante_almacenamiento'] : ''; ?>" required>

						<label for="tipo_almacenamiento">Tipo de almacenamiento:</label>
						<input type="text" id="tipo_almacenamiento" name="tipo_almacenamiento" class="input" value="<?php echo isset($pc['tipo_almacenamiento']) ? $pc['tipo_almacenamiento'] : ''; ?>" required>

						<label for="capacidad_almacenamiento">Capacidad del almacenamiento (GB):</label>
						<input type="text" id="capacidad_almacenamiento" name="capacidad_almacenamiento" class="input" value="<?php echo isset($pc['capacidad_almacenamiento']) ? $pc['capacidad_almacenamiento'] : ''; ?>" required>
					</div>
				</fieldset>
			</form>
		</div>
		<div class="btnArea">
			<button class="button" type="button" onclick="prevStep()">Volver</button>
			<button class="button" type="button" onclick="nextStep()">Siguiente</button>
		</div>
	</div>
</div>