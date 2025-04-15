document.addEventListener("DOMContentLoaded", function () {
  const slidePage = document.querySelector(".slidePage");
  const pages = document.getElementsByClassName("page-form");
  const bullets = document.getElementsByClassName("bullet");
  const stepNumbers = document.getElementsByClassName("step-number");
  const checks = document.getElementsByClassName("check");
  const maxSteps = pages.length;
  let currentStep = 0;
  let isSubmitListenerActive = false; // Variable to track if the submit listener is active

  function submitForm() {
      const form = document.querySelector(".form");
      if (form) {
          form.dispatchEvent(new Event("submit"));
      } else {
          console.error("Form with class .form not found for submission.");
      }
      isSubmitListenerActive = false; // Deactivate the tracker after submission
  }

  function nextStep() {
      if (validateStep(currentStep)) {
          if (currentStep < maxSteps - 1) {
              const prevButton = document.querySelector(".btnArea .modal-button:first-child");
              prevButton.removeEventListener("click", closeModal);
              bullets[currentStep].classList.add("completed");
              stepNumbers[currentStep].classList.remove("display");
              checks[currentStep].classList.add("display");
              currentStep++;
              bullets[currentStep].classList.add("bulletActive");
              slidePage.style.marginLeft = `-${currentStep * 450}px`;
              if (currentStep === maxSteps - 1) {
                  const nextButton = document.querySelector(".btnArea .modal-button:last-child");
                  nextButton.textContent = "Enviar";
                  nextButton.removeAttribute("onclick");
                  nextButton.addEventListener("click", submitForm);
                  isSubmitListenerActive = true; // Activate the tracker
              }
          }
      } else {
          Swal.fire({
              icon: "warning",
              text: "Por favor, rellene todos los campos antes de continuar.",
              customClass: {
                  popup: "custom-swal-font",
              },
          });
      }
  }

  function prevStep() {
      if (currentStep > 0) {
          bullets[currentStep].classList.remove("bulletActive");
          currentStep--;
          stepNumbers[currentStep].classList.add("display");
          checks[currentStep].classList.remove("display");
          bullets[currentStep].classList.remove("completed");
          slidePage.style.marginLeft = `-${currentStep * 450}px`;
          if (currentStep === 0) {
              const prevButton = document.querySelector(".btnArea .modal-button:first-child");
              prevButton.addEventListener("click", closeModal);
          }
          if (currentStep < maxSteps - 1) {
              const nextButton = document.querySelector(".btnArea .modal-button:last-child");
              nextButton.textContent = "Next";
              nextButton.setAttribute("onclick", "nextStep()");
              // Remove the submit listener when going back
              nextButton.removeEventListener("click", submitForm);
              isSubmitListenerActive = false; // Deactivate the tracker
          }
      }
  }

  function validateStep(stepIndex) {
      const inputs = pages[stepIndex].querySelectorAll("input, select");
      for (const input of inputs) {
          if (!input.value) {
              return false;
          }
      }
      return true;
  }

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
      const prevButton = document.querySelector(".btnArea .modal-button:first-child");
      const nextButton = document.querySelector(".btnArea .modal-button:last-child");
      if (nextButton) {
          nextButton.textContent = "Siguiente";
          nextButton.type = "button";
          nextButton.setAttribute("onclick", "nextStep()");
          // Ensure the submit listener is removed on reset
          nextButton.removeEventListener("click", submitForm);
          isSubmitListenerActive = false; // Deactivate the tracker
      }
      console.log("MultiStepForm reset.");
  };

  window.nextStep = nextStep;
  window.prevStep = prevStep;
});