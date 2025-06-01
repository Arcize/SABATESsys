// Acciones especiales para pcTable: desasignar equipo

document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('pcTable');
    if (!table) return;

    table.addEventListener('click', function(e) {
        // Desasignar equipo
        const btnUnassign = e.target.closest('.unassign-pc-btn');
        if (btnUnassign) {
            e.preventDefault();
            const id = btnUnassign.getAttribute('data-id');
            if (window.Swal) {
                Swal.fire({
                    title: '¿Está seguro?',
                    text: '¿Desea desasignar este equipo? Esta acción es reversible.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, desasignar',
                    cancelButtonText: 'Cancelar',
                    customClass: { popup: 'swal2-popup' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('index.php?view=pc&action=unassign_pc', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `id_equipo_informatico=${encodeURIComponent(id)}`
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Desasignado', data.message, 'success');
                                $('#pcTable').DataTable().ajax.reload(null, false);
                            } else {
                                Swal.fire('Error', data.message || 'No se pudo desasignar.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                        });
                    }
                });
            }
        }
    });
});
