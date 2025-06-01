// Script para comprobar el estado del empleado y destruir la sesión si está inactivo

(function() {
    // Cambia el intervalo según lo que necesites (ejemplo: cada 30 segundos)
    const CHECK_INTERVAL_MS = 5000;

    function checkEmployeeStatus() {
        fetch('index.php?view=employee&action=employee_check_status', {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.estado_empleado === 'Inactivo') {
                // Destruir sesión y recargar/redirigir
                fetch('index.php?view=logout', { method: 'POST', credentials: 'same-origin' })
                    .then(() => {
                        window.location.href = 'index.php?view=login';
                    });
            }
        })
        .catch(() => {
            // Si hay error de red, puedes ignorar o mostrar un mensaje si lo deseas
        });
    }

    setInterval(checkEmployeeStatus, CHECK_INTERVAL_MS);
})();
