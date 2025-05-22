const inputs = document.querySelectorAll("input, textarea");

// Función para formatear automáticamente como decimal
function formatToDecimal(value) {
  // Eliminar cualquier carácter que no sea un número
  value = value.replace(/[^0-9]/g, "");

  // Si el valor tiene más de 2 dígitos, insertar el punto decimal
  if (value.length > 2) {
    value = value.slice(0, -2) + "." + value.slice(-2);
  } else if (value.length === 2) {
    // Si tiene exactamente 2 dígitos, agregar el prefijo "0."
    value = "0." + value;
  } else if (value.length === 1) {
    // Si solo tiene 1 dígito, agregar el prefijo "0.0"
    value = "0.0" + value;
  }

  return value.replace(/^0+(?!\.)/, ""); // Eliminar ceros innecesarios al principio
}

// Función para limitar el número de caracteres al aplicar el formato
function applyLengthLimit(value, maxLength) {
  // Contar el punto como un carácter en el límite
  if (value.includes(".")) {
    return value.slice(0, maxLength);
  }
  return value; // Sin cambios si no hay punto
}

// Añadir validación según la clase del campo
inputs.forEach(function (input) {
  // Validar campos que no permiten espacios (por ejemplo, clase "no-spaces")
  if (input.classList.contains("no-spaces")) {
    input.addEventListener("input", function () {
      // Eliminar cualquier espacio en el valor
      input.value = input.value.replace(/\s/g, "");
    });
  }

  // Validar campos donde no puede haber nada después de un espacio (por ejemplo, clase "no-empty-after-space")
  if (input.classList.contains("no-empty-after-space")) {
    input.addEventListener("input", function () {
      // Reemplazar múltiples espacios consecutivos por un único espacio
      input.value = input.value.replace(/\s{2,}/g, " ");
    });
  }

  // Validar espacios múltiples (general, si aplica)
  // input.addEventListener("input", function () {
  // input.value = input.value.replace(/\s{2,}/g, " ");
  // });

  // Evitar espacios como primer carácter
  input.addEventListener("keydown", function (event) {
    if (event.key === " " && input.value === "") {
      event.preventDefault();
    }
  });

  // Validar cédulas de identidad (clase "ci")
  if (input.classList.contains("ci")) {
    input.addEventListener("input", function () {
      // Permitir solo números y limitar a 8 caracteres
      input.value = input.value.replace(/\D/g, "").slice(0, 8);
    });
  }

  // Validar solo números (clase "numbers", sin límite de longitud)
  if (input.classList.contains("numbers")) {
    input.addEventListener("input", function () {
      // Permitir solo números
      input.value = input.value.replace(/\D/g, "");
    });
  }

  // Validar campos que deben tener la primera letra en mayúscula y el resto en minúscula (clase "capitalize-first")
  if (input.classList.contains("capitalize-first")) {
    input.addEventListener("input", function () {
      // Convertir la primera letra en mayúscula y el resto en minúsculas
      input.value =
        input.value.charAt(0).toUpperCase() +
        input.value.slice(1).toLowerCase();
    });
  }

  // Validar campos que solo permiten letras y espacios (clase "only-letters")
  if (input.classList.contains("only-letters")) {
    input.addEventListener("input", function () {
      // Eliminar números y caracteres especiales, permitiendo solo letras y espacios
      input.value = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, "");
    });
  }

  // Convertir todo el texto en minúsculas (clase "lowercase")
  if (input.classList.contains("lowercase")) {
    input.addEventListener("input", function () {
      // Convertir todo el texto a minúsculas
      input.value = input.value.toLowerCase();
    });
  }

  // Validar campos que deben tener un formato de correo electrónico (clase "validate-email")
  if (input.classList.contains("validate-email")) {
    const emailRegex =
      /^[a-zA-Z0-9](?!.*[._-]{2})[a-zA-Z0-9._-]*[a-zA-Z0-9]@[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+$/;

    input.addEventListener("input", function () {
      let enteredEmail = input.value.trim();
      const errorElement = input.parentElement.querySelector(".emailError");

      // Eliminar caracteres no válidos automáticamente
      enteredEmail = enteredEmail
        .replace(/[^a-zA-Z0-9._@-]/g, "") // Eliminar caracteres no permitidos
        .replace(/^[@._-]/, "") // Eliminar punto, arroba, guion o guion bajo solo al inicio
        .replace(/(\.{2,}|_{2,}|-{2,})/g, ".") // Reemplazar caracteres consecutivos no válidos
        .replace(/@{2,}/g, "@") // Eliminar múltiples arrobas
        .replace(/@.*@/, "@"); // Asegurarse de que solo haya una arroba

      input.value = enteredEmail; // Actualizar el valor del input

      // Validar el correo electrónico
      if (emailRegex.test(enteredEmail)) {
        // Email válido: limpiar mensaje de error
        if (errorElement) {
          errorElement.textContent = "";
        }
      } else {
        // Email no válido: mostrar mensaje de error
        if (errorElement) {
          errorElement.textContent =
            "Por favor, ingrese un correo electrónico válido.";
        }
      }
    });
  }

  // Validar y formatear automáticamente como decimal con un límite dinámico (clase "auto-decimal")
  if (input.classList.contains("auto-decimal")) {
    input.addEventListener("input", function () {
      // Leer el límite de caracteres del atributo personalizado data-length
      const maxLength = parseInt(input.getAttribute("data-length")) || 4; // Valor predeterminado: 4

      // Paso 1: Obtener el valor del input
      let value = input.value;

      // Paso 2: Formatear como decimal
      value = formatToDecimal(value);

      // Paso 3: Asegurarse de que solo haya un entero y 2 decimales
      const parts = value.split(".");
      if (parts[0].length > 1) {
        parts[0] = parts[0].slice(0, 1); // Limitar a un solo dígito en la parte entera
      }
      if (parts[1] && parts[1].length > 2) {
        parts[1] = parts[1].slice(0, 2); // Limitar a dos dígitos en la parte decimal
      }
      value = parts.join(".");

      // Paso 4: Aplicar el límite de longitud después del formato
      value = applyLengthLimit(value, maxLength);

      // Actualizar el valor del input
      input.value = value;
    });
  }
});

async function checkCedulaAvailability() {
  const cedula = document.getElementById("cedula").value;
  const errorMessage = document.querySelector(".cedulaError");
  const sentBtn = document.querySelector(".sentBtn");
  const employeeId = document.getElementById("id_persona").value; // Obtener el ID de la persona

  if (cedula.length > 0) {
    try {
      const response = await fetch(
        `index.php?view=employee&action=employee_fetch_cedula&cedula=${cedula}&id_persona=${employeeId}`
      );
      const data = await response.json();
      console.log(data); // Verificar la respuesta del servidor
      if (data.exist) {
        errorMessage.textContent = "La cédula ya está registrada.";
        sentBtn.disabled = true;
        console.log("La cédula ya está registrada.");
      } else {
        errorMessage.textContent = "";
        sentBtn.disabled = false;
      }
    } catch (error) {
      console.error("Error checking cedula availability:", error);
    }
  } else {
    errorMessage.textContent = "";
  }
}

// Campos cruzados
function crossFields() {
  const cedulaPC = document.getElementById("cedulaPC");
  const idPC = document.getElementById("idPC");

  if (cedulaPC.value.length > 0 && !cedulaPC.dataset.locked) {
    checkCedulaPC();
  } else if (idPC.value.length > 0 && !idPC.dataset.locked) {
    checkIdPC();
  } else if (cedulaPC.value.length === 0) {
    // Si cedulaPC está vacío, desbloquea y limpia idPC
    idPC.value = "";
    idPC.readOnly = false;
    idPC.dataset.locked = "";
  } else if (idPC.value.length === 0) {
    // Si idPC está vacío, desbloquea y limpia cedulaPC
    cedulaPC.value = "";
    cedulaPC.readOnly = false;
    cedulaPC.dataset.locked = "";
  }
}

async function checkCedulaPC() {
  const cedulaPC = document.getElementById("cedulaPC");
  const idPC = document.getElementById("idPC");

  if (cedulaPC.value.length > 0) {
    try {
      const response = await fetch(
        `index.php?view=pc&action=pc_fetch_id&cedulaPC=${cedulaPC.value}`
      );
      const data = await response.json();

      if (data > 0) {
        idPC.value = data;
        idPC.readOnly = true;
        idPC.dataset.locked = "true";
        cedulaPC.readOnly = false;
        cedulaPC.dataset.locked = "";
        idPC.classList.add("inputValid");
        getPC(data); // Llamada a tu función con el idPC
      } else {
        idPC.value = "";
        idPC.readOnly = false;
        idPC.dataset.locked = "";
        idPC.classList.remove("inputValid");
        pcDetails = document.querySelector(".pcDetails");
        spans = pcDetails.querySelectorAll("div > span:last-child");
        for (let i = 0; i < spans.length; i++) {
          spans[i].textContent = "";
        }
      }
    } catch (error) {
      console.error("Error checking cedula availability:", error);
    }
  }
}

async function checkIdPC() {
  const idPC = document.getElementById("idPC");
  const cedulaPC = document.getElementById("cedulaPC");

  if (idPC.value.length > 0) {
    try {
      const response = await fetch(
        `index.php?view=employee&action=get_Cedula_By_Pc&idPC=${idPC.value}`
      );
      const data = await response.json();

      if (data > 0) {
        cedulaPC.value = data;
        cedulaPC.readOnly = true;
        cedulaPC.dataset.locked = "true";
        idPC.readOnly = false;
        idPC.dataset.locked = "";
        cedulaPC.classList.add("inputValid");
        getPC(idPC.value); // Llamada a tu función con el idPC
      } else {
        cedulaPC.value = "";
        cedulaPC.readOnly = false;
        cedulaPC.dataset.locked = "";
        cedulaPC.classList.remove("inputValid");
        pcDetails = document.querySelector(".pcDetails");
        spans = pcDetails.querySelectorAll("div > span:last-child");
        for (let i = 0; i < spans.length; i++) {
          spans[i].textContent = "";
        }
      }
    } catch (error) {
      console.error("Error checking cedula availability:", error);
    }
  }
}

// Aquí defines tu función que utiliza el idPC
async function getPC(id) {
  if (id) {
    try {
      const response = await fetch(
        `index.php?view=pc&action=pc_fetch_one&id_equipo_informatico=${id}`
      );
      let data = await response.json();

      // Sumar la capacidad total de RAM
      let totalRam = 0;
      let tipoRam = "";
      if (Array.isArray(data.ramData) && data.ramData.length > 0) {
        totalRam = data.ramData.reduce((sum, ram) => sum + Number(ram.capacidad_ram || 0), 0);
        tipoRam = data.ramData[0].tipo_ram || "";
      }

      // Tomar solo el primer almacenamiento
      let almacenamiento = "";
      if (Array.isArray(data.storageData) && data.storageData.length > 0) {
        almacenamiento = data.storageData[0].capacidad_almacenamiento + "Gb " + data.storageData[0].tipo_almacenamiento;
      }

      let dataPC = [
        data.fabricante_equipo_informatico,
        data.fabricante_procesador + " " + data.nombre_procesador,
        data.fabricante_motherboard + " " + data.modelo_motherboard,
        data.fabricante_fuente_poder + " " + data.wattage_fuente,
        totalRam + "Gb " + tipoRam,
        almacenamiento,
      ];

      console.log(dataPC); // Verificar el contenido de dataPC
      pcDetails = document.querySelector(".pcDetails");
      spans = pcDetails.querySelectorAll("div > span:last-child");
      for (let i = 0; i < spans.length; i++) {
        spans[i].textContent = dataPC[i];
      }
    } catch (error) {}
  }
  console.log("El ID del PC es:", id);
  // Aquí puedes realizar las acciones que necesites con el idPC
}
