// datatable_config.js
const commonDatatableConfig = {
    columnDefs: [{ className: "centered", targets: "_all" }], // Centra todas las celdas por defecto
    info: false,
    ordering: false,
    lengthMenu: [10, 15, 20, 25],
    language: {
      url: "lang/es-ES.json",
      paginate: {
        first: "«",
        last: "»",
        previous: "‹",
        next: "›"
      }
    }
    // Aquí puedes agregar más configuraciones comunes
  };