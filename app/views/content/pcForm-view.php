<div class="view-box">
	<h3 class="h3">Registre su Equipo</h3>
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
		</div>
		<div class="form-layout">
			<form action="index.php?view=pc&action=pc_create" method="post" class="multi-step-form">
				<fieldset class="page slidePage">
					<legend>
						<h4 class="h4">Información General</h4>
					</legend>
					<div class="pageInputs">

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
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">Procesador</h4>
					</legend>
					<div class="pageInputs">
						<label for="marca_procesador">Marca del procesador:</label>
						<input type="text" id="marca_procesador" name="marca_procesador" class="input" required>

						<label for="nombre_procesador">Nombre del procesador:</label>
						<input type="text" id="nombre_procesador" name="nombre_procesador" class="input" required>

						<label for="nucleos">Núcleos:</label>
						<input type="text" id="nucleos" name="nucleos" class="input" required>

						<label for="frecuencia_procesador">Frecuencia del procesador (GHz):</label>
						<input type="text" id="frecuencia_procesador" name="frecuencia_procesador" class="input" required>
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">Motherboard</h4>
					</legend>
					<div class="pageInputs">
						<label for="marca_motherboard">Marca de la motherboard:</label>
						<input type="text" id="marca_motherboard" name="marca_motherboard" class="input" required>

						<label for="modelo_motherboard">Modelo de la motherboard:</label>
						<input type="text" id="modelo_motherboard" name="modelo_motherboard" class="input" required>
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">Fuente</h4>
					</legend>
					<div class="pageInputs">
						<label for="marca_fuente">Marca de la fuente:</label>
						<input type="text" id="marca_fuente" name="marca_fuente" class="input" required>

						<label for="wattage_fuente">Wattage de la fuente:</label>
						<input type="text" id="wattage_fuente" name="wattage_fuente" class="input" required>
					</div>
				</fieldset>
				<fieldset class="page">
					<legend>
						<h4 class="h4">RAM</h4>
					</legend>
					<div class="pageInputs">
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

			</form>
		</div>
		<div class="btnArea">
			<button class="button" type="button" onclick="prevStep()">Volver</button>
			<button class="button" type="button" onclick="nextStep()">Siguiente</button>
		</div>
	</div>


</div>