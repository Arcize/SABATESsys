let currentStep = 0;
showStep(currentStep);

function showStep(step) {
    const steps = document.getElementsByClassName("step");
    steps[step].classList.add("active");
}

function nextStep() {
    const steps = document.getElementsByClassName("step");
    steps[currentStep].classList.remove("active");
    currentStep++;
    if (currentStep >= steps.length) {
        currentStep = steps.length - 1;
    }
    showStep(currentStep);
}

function prevStep() {
    const steps = document.getElementsByClassName("step");
    steps[currentStep].classList.remove("active");
    currentStep--;
    if (currentStep < 0) {
        currentStep = 0;
    }
    showStep(currentStep);
}
