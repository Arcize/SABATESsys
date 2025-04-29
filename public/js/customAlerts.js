const viewName = form.getAttribute("formType");
const idName =  inputKey.getAttribute("id");
function confirmDelete(id) {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "No podrás revertir esto.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#f44336",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
    customClass: {
      popup: "custom-swal-font",
    },
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(
        `index.php?view=${viewName}&action=${viewName}_fetch_delete&${idName}=${id}`,
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
        }
      )
        .then((response) => {
          if (!response.ok) {
            throw new Error("Error al eliminar el registro");
          }
          return response.json();
        })
        .then((data) => {
          if (data.success) {
            const tableId = document.querySelector(".table").id;
            $(`#${tableId}`).DataTable().ajax.reload(null, false); // false mantiene la página actual
            Swal.fire({
              title: "¡Eliminado!",
              text: "Tu registro ha sido eliminado.",
              icon: "success",
              timer: 2000,
              customClass: {
                popup: "custom-swal-font",
              },
            });
          } else {
            throw new Error(data.message || "Error desconocido");
          }
        })
        .catch((error) => {
          Swal.fire({
            title: "Error",
            text: error.message,
            icon: "error",
            customClass: {
              popup: "custom-swal-font",
            },
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
      popup: "custom-swal-font",
    },
  });
}

function fetchEditSuccess() {
  Swal.fire({
    title: "¡Éxito!",
    text: "Tu registro ha sido actualizado.",
    icon: "success",
    timer: 2000,
    customClass: {
      popup: "custom-swal-font",
    },
  });
}
