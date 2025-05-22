document.addEventListener("DOMContentLoaded", function () {
    const slidePage = document.querySelector(".slidePage");
    const pages = document.getElementsByClassName("page-form");
    const bullets = document.getElementsByClassName("bullet");
    const stepNumbers = document.getElementsByClassName("step-number");
    const checks = document.getElementsByClassName("check");
    const maxSteps = pages.length;
    let currentStep = 0;

    // Botones fuera del form
    const btnArea = document.querySelector(".btnArea");
    const prevButton = btnArea.querySelector(".modal-button.prevBtn");
    const nextButton = btnArea.querySelector(".modal-button.sentBtn");
    const form = document.getElementById("pcForm");

    function updateStepVisuals() {
        for (let i = 0; i < maxSteps; i++) {
            bullets[i].classList.toggle("completed", i < currentStep);
            bullets[i].classList.toggle("bulletActive", i === currentStep);
            stepNumbers[i].classList.toggle("display", i >= currentStep);
            checks[i].classList.toggle("display", i < currentStep);
            pages[i].style.display = (i === currentStep) ? "block" : "none";
        }
        // Mueve la slidePage si usas animación
        slidePage.style.marginLeft = `-${currentStep * 100}%`;
    }

    function updateNavigationButtons() {
    if (currentStep === 0) {
        prevButton.textContent = "Cancelar";
        prevButton.classList.add("close-modal");
        prevButton.removeEventListener("click", prevStep); // Evita conflictos
        prevButton.addEventListener("click", closeModal);  // Solo cierra el modal en el primer paso
    } else {
        prevButton.textContent = "Anterior";
        prevButton.classList.remove("close-modal");
        prevButton.removeEventListener("click", closeModal); // Evita cierre accidental en pasos intermedios
        prevButton.addEventListener("click", prevStep);
    }

    if (currentStep < maxSteps - 1) {
        nextButton.textContent = "Siguiente";
    } else {
        nextButton.textContent = "Enviar";
    }
}


    function nextStep(e) {
        if (e) e.preventDefault();
        if (validateStep(currentStep)) {
            if (currentStep < maxSteps - 1) {
                currentStep++;
                updateStepVisuals();
                updateNavigationButtons();
            } else {
                // Último paso: enviar el formulario manualmente
                form.requestSubmit();
            }
        } else {
            showValidationError();
        }
    }

    function prevStep(e) {
    if (e) e.preventDefault();
    if (currentStep > 0) {
        currentStep--;
        updateStepVisuals();
        updateNavigationButtons();
    } else {
              prevButton.addEventListener("click", closeModal);
        
    }
}


    function showValidationError() {
        Swal.fire({
            icon: "warning",
            text: "Por favor, rellene todos los campos antes de continuar.",
            customClass: {
                popup: "custom-swal-font",
            },
        });
    }

    function validateStep(stepIndex) {
        const inputs = pages[stepIndex].querySelectorAll("input, select, textarea");
        return Array.from(inputs).every(input => input.value.trim() !== "");
    }

    // Inicialización
    // Oculta todos los fieldsets menos el primero
    for (let i = 0; i < pages.length; i++) {
        pages[i].style.display = (i === 0) ? "block" : "none";
    }
    updateStepVisuals();
    updateNavigationButtons();

    // Enlaza los botones a las funciones SOLO UNA VEZ
    nextButton.addEventListener("click", nextStep);
    prevButton.addEventListener("click", prevStep);

    // Exponer funciones si las necesitas globalmente
    window.nextStep = nextStep;
    window.prevStep = prevStep;

      window.resetMultiStepForm = function() {
    currentStep = 0;
    if (slidePage) slidePage.style.marginLeft = `0px`;
    if (bullets && stepNumbers && checks) {
        for (let i = 0; i < bullets.length; i++) {
            bullets[i].classList.remove("bulletActive", "completed");
            stepNumbers[i].classList.add("display");
            checks[i].classList.remove("display");
            if (i === 0) bullets[i].classList.add("bulletActive");
        }
    }
    // Restablece los textos de los botones
    prevButton.textContent = "Cancelar";
    nextButton.textContent = "Siguiente";
    nextButton.type = "button";
    // No uses setAttribute ni removeEventListener aquí
    updateStepVisuals();
    updateNavigationButtons();
    console.log("MultiStepForm reset.");
};

    // RAM
    document.getElementById('add-ram').addEventListener('click', function() {
        const container = document.getElementById('ram-modules');
        const module = container.querySelector('.ram-module').cloneNode(true);
        module.querySelectorAll('input').forEach(input => input.value = '');
        container.appendChild(module);
        module.querySelector('.remove-ram').addEventListener('click', function() {
            if (container.querySelectorAll('.ram-module').length > 1) {
                module.remove();
                updateRamTitles();
            }
        });
        updateRamTitles();
    });
    document.querySelectorAll('.remove-ram').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = document.getElementById('ram-modules');
            if (container.querySelectorAll('.ram-module').length > 1) {
                btn.closest('.ram-module').remove();
                updateRamTitles();
            }
        });
    });
    updateRamTitles();

    // Almacenamiento
    document.getElementById('add-storage').addEventListener('click', function() {
        const container = document.getElementById('storage-modules');
        const module = container.querySelector('.storage-module').cloneNode(true);
        module.querySelectorAll('input').forEach(input => input.value = '');
        container.appendChild(module);
        module.querySelector('.remove-storage').addEventListener('click', function() {
            if (container.querySelectorAll('.storage-module').length > 1) {
                module.remove();
                updateStorageTitles();
            }
        });
        updateStorageTitles();
    });
    document.querySelectorAll('.remove-storage').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = document.getElementById('storage-modules');
            if (container.querySelectorAll('.storage-module').length > 1) {
                btn.closest('.storage-module').remove();
                updateStorageTitles();
            }
        });
    });
    updateStorageTitles();

    function updateRamTitles() {
        const modules = document.querySelectorAll('#ram-modules .ram-module');
        modules.forEach((mod, idx) => {
            let title = mod.querySelector('.ram-title');
            if (!title) {
                title = document.createElement('h5');
                title.className = 'ram-title';
                mod.insertBefore(title, mod.firstChild);
            }
            title.textContent = `Módulo ${idx + 1}`;
            // Mostrar/ocultar botón quitar
            const removeBtn = mod.querySelector('.remove-ram');
            if (modules.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = '';
            }
        });
    }

    function updateStorageTitles() {
        const modules = document.querySelectorAll('#storage-modules .storage-module');
        modules.forEach((mod, idx) => {
            let title = mod.querySelector('.storage-title');
            if (!title) {
                title = document.createElement('h5');
                title.className = 'storage-title';
                mod.insertBefore(title, mod.firstChild);
            }
            title.textContent = `Módulo ${idx + 1}`;
            // Mostrar/ocultar botón quitar
            const removeBtn = mod.querySelector('.remove-storage');
            if (modules.length === 1) {
                removeBtn.style.display = 'none';
            } else {
                removeBtn.style.display = '';
            }
        });
    }

    function fillRamModules(ramData) {
        const container = document.getElementById('ram-modules');
        // Elimina todos los módulos menos el primero
        while (container.children.length > 1) {
            container.removeChild(container.lastChild);
        }
        const firstModule = container.querySelector('.ram-module');
        if (ramData.length > 0) {
            // Llena el primer módulo
            for (const key in ramData[0]) {
                const input = firstModule.querySelector(`[name="${key}[]"]`);
                if (input) input.value = ramData[0][key];
            }
        }
        // Agrega y llena los demás módulos
        for (let i = 1; i < ramData.length; i++) {
            const module = firstModule.cloneNode(true);
            for (const key in ramData[i]) {
                const input = module.querySelector(`[name="${key}[]"]`);
                if (input) input.value = ramData[i][key];
            }
            container.appendChild(module);
        }
        updateRamTitles();
    }

    function fillStorageModules(storageData) {
        const container = document.getElementById('storage-modules');
        while (container.children.length > 1) {
            container.removeChild(container.lastChild);
        }
        const firstModule = container.querySelector('.storage-module');
        if (storageData.length > 0) {
            for (const key in storageData[0]) {
                const input = firstModule.querySelector(`[name="${key}[]"]`);
                if (input) input.value = storageData[0][key];
            }
        }
        for (let i = 1; i < storageData.length; i++) {
            const module = firstModule.cloneNode(true);
            for (const key in storageData[i]) {
                const input = module.querySelector(`[name="${key}[]"]`);
                if (input) input.value = storageData[i][key];
            }
            container.appendChild(module);
        }
        updateStorageTitles();
    }

    window.fillRamModules = fillRamModules;
window.fillStorageModules = fillStorageModules;
});
