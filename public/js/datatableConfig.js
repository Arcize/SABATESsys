// datatable_config.js
DataTable.defaults.layout = {
    topStart: null,
    topEnd: null,
    bottomStart: null,
    bottomEnd: null,
    bottom: null
};

const commonDatatableConfig = {
  columnDefs: [{ className: "centered", targets: "_all" }], // Centra todas las celdas por defecto
  layout: {
    topStart: "pageLength",
    bottomStart: "buttons",
    topEnd: "search",
    bottomEnd: "paging",
  },
  buttons: [
                'pdf',
                // Puedes agregar más botones aquí si los necesitas
            ],

  info: false,
  ordering: false,
  lengthMenu: [10, 15, 20, 25],
  language: {
    emptyTable: "", // Dejarlo vacío evita el mensaje por defecto
    url: "lang/es-ES.json",
    paginate: {
      first: "«",
      last: "»",
      previous: "‹",
      next: "›",
    },
  },
  // Aquí puedes agregar más configuraciones comunes
};
