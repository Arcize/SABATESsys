document.addEventListener("DOMContentLoaded", function () {
  const slidePage = document.querySelector(".slidePage");
  const page = document.getElementsByClassName("page-form");
  const bullets = document.getElementsByClassName("bullet");
  const numbers = document.getElementsByClassName("step-number");
  const checks = document.getElementsByClassName("check");
  let max = page.length;
  let current = 0;

  function nextStep() {
  /*  if (validateStep(current)) { */// Activar la validación
      if (current < max - 1) {
        bullets[current].classList.add("completed");
        numbers[current].classList.remove("display");
        checks[current].classList.add("display");
        current++;
        bullets[current].classList.add("bulletActive");
        slidePage.style.marginLeft = `-${current * 450}px`;
        if (current === max - 1) {
          const btnNext = document.querySelector(".btnArea .button:last-child");
          btnNext.textContent = "Enviar";
          btnNext.setAttribute("onclick", "submitForm()");
        }
      }
  /*  } else {
      Swal.fire({
        icon: 'warning',
        text: 'Por favor, rellene todos los campos antes de continuar.',
        customClass: {
          popup: 'custom-swal-font'
        }
      });
    }*/
  }

  function prevStep() {
    if (current === 0) {
      window.location.href = "index.php?view=pcTable";
    } else {
      bullets[current].classList.remove("bulletActive");
      current--;
      numbers[current].classList.add("display");
      checks[current].classList.remove("display");
      bullets[current].classList.remove("completed");
      slidePage.style.marginLeft = `-${current * 450}px`;

      if (current < max - 1) {
        const btnNext = document.querySelector(".btnArea .button:last-child");
        btnNext.textContent = "Siguiente";
        btnNext.setAttribute("onclick", "nextStep()");
      }
    }
  }

  function validateStep(step) {
    const inputs = page[step].querySelectorAll("input, select");
    for (let input of inputs) {
      if (!input.value) {
        return false;
      }
    }
    return true;
  }

  function submitForm() {
    document.querySelector(".multi-step-form").submit();
  }

  window.nextStep = nextStep;
  window.prevStep = prevStep;
  window.submitForm = submitForm;
});
