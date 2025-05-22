function renderQuestions(questions, box, cedula) {
  box.innerHTML = ""; // Limpiar contenido previo
  const selectedQuestions = selectRandomQuestions(questions);
  console.log("Selected Questions:", selectedQuestions); // Verificar las preguntas seleccionadas
  // Crear el formulario
  const form = document.createElement("form");
  form.className = "login-register-form";
  form.id = "securityQuestionsForm";
  form.method = "POST";
  form.autocomplete = "off"; // Desactivar autocompletar del navegador

  // Agregar un encabezado al formulario
  const header = document.createElement("h2");
  header.className = "login-register-header";
  header.textContent = "Preguntas de Seguridad";
  form.appendChild(header);

  selectedQuestions.forEach((question, index) => {
    const questionContainer = document.createElement("div");
    questionContainer.className = "question-container";

    // Crear el texto de la pregunta
    const questionText = document.createElement("p");
    questionText.textContent = `${question.texto_pregunta}`;

    // Crear el contenedor para el input
    const inputBox = document.createElement("div");
    inputBox.className = "input-box";

    // Crear el campo de entrada
    const input = document.createElement("input");

    // Agregar un event listener para formatear el texto mientras se escribe
    input.addEventListener("input", (event) => {
      let value = event.target.value;

      // Eliminar caracteres no permitidos (números y caracteres especiales)
      value = value.replace(/[^a-zA-Z\s]/g, "");

      // No permitir espacios al inicio
      value = value.replace(/^\s+/g, "");

      // No permitir múltiples espacios consecutivos
      value = value.replace(/\s{2,}/g, " ");

      // Convertir la primera letra a mayúscula y el resto a minúscula
      value = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();

      event.target.value = value;
    });

    // Agregar un event listener para eliminar el espacio final al enviar
    input.addEventListener("blur", (event) => {
      event.target.value = event.target.value.trimEnd();
    });

    // Agregar un event listener para eliminar el espacio final al presionar Enter
    input.addEventListener("keydown", (event) => {
      if (event.key === "Enter") {
        event.preventDefault(); // Evitar el envío inmediato del formulario
        event.target.value = event.target.value.trimEnd();
        event.target.form.dispatchEvent(
          new Event("submit", { cancelable: true })
        );
      }
    });
    input.type = "text";
    input.className = "input";
    input.name = `securityAnswer`;
    input.placeholder = "Escribe tu respuesta aquí";
    input.required = true;

    // Agregar el input al contenedor input-box
    inputBox.appendChild(input);

    // Agregar los elementos al contenedor de la pregunta
    questionContainer.appendChild(questionText);
    questionContainer.appendChild(inputBox);

    // Agregar el contenedor de la pregunta al formulario
    form.appendChild(questionContainer);
  });

  // Agregar un botón de envío al formulario
  const submitButton = document.createElement("button");
  submitButton.type = "submit";
  submitButton.className = "btnIn";
  submitButton.textContent = "Enviar Respuestas";
  form.appendChild(submitButton);

  // Agregar el formulario al contenedor principal
  box.appendChild(form);

  // Agregar un event listener para manejar el envío del formulario de forma asíncrona
  form.addEventListener("submit", async function (event) {
    event.preventDefault(); // Evitar el envío tradicional del formulario

    try {
      const formData = new FormData(form); // Obtener los datos del formulario
      formData.append("cedula", cedula); // Agregar la cédula al FormData
      formData.append("questionId", selectedQuestions[0].id_pregunta); // Agregar el ID de la pregunta al FormData
      const response = await fetch(
        "index.php?view=securityQuestions&action=validateAnswers",
        {
          method: "POST",
          body: formData,
        }
      );

      // Verificar si la respuesta es exitosa
      if (!response.ok) {
        throw new Error(`Error HTTP: ${response.status}`);
      }

      const data = await response.json(); // Convertir respuesta a JSON

      // Manejar la respuesta positiva o negativa
      if (data.success) {
        box.innerHTML = ""; // Limpiar el contenido del contenedor

        // Crear el formulario para actualizar la contraseña
        const passwordForm = document.createElement("form");
        passwordForm.className = "login-register-form";
        passwordForm.id = "updatePasswordForm";
        passwordForm.method = "POST";
        passwordForm.autocomplete = "off";

        // Agregar un encabezado al formulario
        const passwordHeader = document.createElement("h2");
        passwordHeader.className = "login-register-header";
        passwordHeader.textContent = "Actualizar Contraseña";
        passwordForm.appendChild(passwordHeader);

        // Crear el campo para la nueva contraseña
        const newPasswordContainer = document.createElement("div");
        newPasswordContainer.className = "input-box";

        const newPasswordInput = document.createElement("input");
        newPasswordInput.type = "password";
        newPasswordInput.className = "input";
        newPasswordInput.name = "newPassword";
        newPasswordInput.placeholder = "Nueva Contraseña";
        newPasswordInput.required = true;

        newPasswordContainer.appendChild(newPasswordInput);
        passwordForm.appendChild(newPasswordContainer);

        // Crear el campo para confirmar la nueva contraseña
        const confirmPasswordContainer = document.createElement("div");
        confirmPasswordContainer.className = "input-box";

        const confirmPasswordInput = document.createElement("input");
        confirmPasswordInput.type = "password";
        confirmPasswordInput.className = "input";
        confirmPasswordInput.name = "confirmPassword";
        confirmPasswordInput.placeholder = "Confirmar Contraseña";
        confirmPasswordInput.required = true;

        confirmPasswordContainer.appendChild(confirmPasswordInput);
        passwordForm.appendChild(confirmPasswordContainer);

        // Agregar un botón de envío al formulario
        const updateButton = document.createElement("button");
        updateButton.type = "submit";
        updateButton.className = "btnIn";
        updateButton.textContent = "Actualizar Contraseña";
        passwordForm.appendChild(updateButton);

        
        // Agregar el formulario al contenedor principal
        box.appendChild(passwordForm);

        // Agregar un event listener para manejar el envío del formulario
        passwordForm.addEventListener("submit", async function (event) {
          event.preventDefault(); // Evitar el envío tradicional del formulario

          const newPassword = newPasswordInput.value;
          const confirmPassword = confirmPasswordInput.value;

          // Validar que las contraseñas coincidan
          if (newPassword !== confirmPassword) {
            alert("Las contraseñas no coinciden. Intenta nuevamente.");
            return;
          }

          try {
            const formData = new FormData(passwordForm); // Obtener los datos del formulario
            formData.append("cedula", cedula); // Agregar la cédula al FormData
            const response = await fetch(
              "index.php?view=user&action=update_password",
              {
                method: "POST",
                body: formData,
              }
            );

            // Verificar si la respuesta es exitosa
            if (!response.ok) {
              throw new Error(`Error HTTP: ${response.status}`);
            }

            const data = await response.json(); // Convertir respuesta a JSON

            // Manejar la respuesta positiva o negativa
            if (data.success) {
              alert("Contraseña actualizada exitosamente.");
              console.log("Actualización exitosa:", data);
              // Aquí puedes redirigir o realizar otra acción
            } else {
              alert("Error al actualizar la contraseña. Intenta nuevamente.");
              console.log("Actualización fallida:", data);
            }
          } catch (error) {
            console.error("Error en la solicitud:", error);
            alert("Ocurrió un error al actualizar la contraseña.");
          }
        });
        console.log("Validación exitosa:", data);
        // Aquí puedes redirigir o realizar otra acción
      } else {
        alert("Respuestas incorrectas. Intenta nuevamente.");
        console.log("Validación fallida:", data);
      }
    } catch (error) {
      console.error("Error en la solicitud:", error);
      alert("Ocurrió un error al validar las respuestas.");
    }
  });
}

function selectRandomQuestions(questions) {
  if (questions.length < 1) {
    console.error("There must be at least 2 available questions.");
    return [];
  }

  let questionsCopy = [...questions]; // Create a copy of the original array
  let selectedQuestions = [];

  for (let i = 0; i < 1; i++) {
    let randomIndex = Math.floor(Math.random() * questionsCopy.length);
    selectedQuestions.push(questionsCopy[randomIndex]);
    questionsCopy.splice(randomIndex, 1); // Remove the selected question
  }

  return selectedQuestions;
}
