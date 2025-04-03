function confirmDelete(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "No podrás revertir esto.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f44336',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'custom-swal-font'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`index.php?view=employee&action=employee_fetch_delete&id_persona=${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al eliminar el registro');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: "¡Eliminado!",
                        text: "Tu registro ha sido eliminado.",
                        icon: "success",
                        timer: 2000,
                        customClass: {
                            popup: 'custom-swal-font'
                        }
                    });
                    // Recargar la tabla o realizar alguna acción adicional
                    const currentPage = parseInt(
                        document.querySelector(".page-button-active").getAttribute("data-page")
                    );
                    loadPage(currentPage); // Recargar la página actual
                } else {
                    throw new Error(data.message || 'Error desconocido');
                }
            })
            .catch(error => {
                Swal.fire({
                    title: "Error",
                    text: error.message,
                    icon: "error",
                    customClass: {
                        popup: 'custom-swal-font'
                    }
                });
            });

        }
    });
}

function fetchCreateSuccess() {
    Swal.fire({
        title: "¡Éxito!",
        text: "Tu registro ha sido guardado.",
        icon: "success",
        timer: 2000,
        customClass: {
            popup: 'custom-swal-font'
        }
    });
}
function fetchEditSuccess() {
    Swal.fire({
        title: "¡Éxito!",
        text: "Tu registro ha sido actualizado.",
        icon: "success",
        timer: 2000,
        customClass: {
            popup: 'custom-swal-font'
        }
    });
}