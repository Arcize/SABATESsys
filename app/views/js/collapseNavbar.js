const navbar = document.querySelector(".navbar");
(function () {
  const isCollapsed = localStorage.getItem("navbarCollapsed");
  if (isCollapsed === "true") {
    navbar.classList.add("collapsed");
  } else {
    navbar.classList.remove("collapsed");
  }
})();

// Cambia el estado y almacénalo en localStorage cuando se haga clic
function toggleNavbar() {
  const isCollapsed = navbar.classList.toggle("collapsed");
  localStorage.setItem("navbarCollapsed", isCollapsed);
}
window.toggleNavbar = toggleNavbar;
