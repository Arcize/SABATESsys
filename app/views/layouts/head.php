<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./ico/sabates.ico">
<title>SABATES</title>

<link href="https://cdn.datatables.net/buttons/3.2.3/css/buttons.dataTables.min.css" rel="stylesheet" integrity="sha384-gZdV4/a6Gt/Qu0qCP3bchrOj0WlpkAfszB1m4/eFzOSnvvHUFMv9+C/KcgMO8CeR" crossorigin="anonymous">

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js" integrity="sha384-VFQrHzqBh5qiJIU0uGU5CIW3+OWpdGGJM9LBnGbuIH2mkICcFZ7lPd/AAtI7SNf7" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js" integrity="sha384-/RlQG9uf0M2vcTw3CX7fbqgbj/h8wKxw7C3zu9/GxcBPRKOEcESxaxufwRXqzq6n" crossorigin="anonymous"></script>


<?php
$cssFiles = [
    "reset.css",
    "datatables.min.css",
    "gridstack.min.css",
    "dropzone.min.css",
    "dataTables.dateTime.min.css",
    "style.css",
    "navbar.css",
    "form.css",
    "customAlert.css",
    "nunitoFont.css",
    "poppinsFont.css",
    "login-register.css",
    "calendar.css",
    "table.css",
    "config.css",
    "dashboard.css",
    "user-bar.css",
    "role.css",
    "switch.css",
    "modal.css",
    "multi-step-form.css",
    "fileUpload.css",
    "sweetalert2.min.css"
];

foreach ($cssFiles as $cssFile) {
    echo "<link rel='stylesheet' href='./css/$cssFile'>\n";
}
?>
<?php
$jsFiles = [
    "jquery-3.7.1.min.js",
    "dayjs.min.js",
    "es.js",
    "utc.js",
    "timezone.js",
    "datatables.min.js",
    "sweetalert2.all.min.js",
    "dropdownUserBar.js",
    "password_verification.js",
    "dataTables.dateTime.min.js"
];

foreach ($jsFiles as $jsFile) {
    echo "<script src='./js/$jsFile'></script>\n";
}
?>



<script src="https://cdn.datatables.net/buttons/3.2.3/js/dataTables.buttons.min.js" integrity="sha384-zlMvVlfnPFKXDpBlp4qbwVDBLGTxbedBY2ZetEqwXrfWm+DHPvVJ1ZX7xQIBn4bU" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/buttons/3.2.3/js/buttons.html5.min.js" integrity="sha384-+E6fb8f66UPOVDHKlEc1cfguF7DOTQQ70LNUnlbtywZiyoyQWqtrMjfTnWyBlN/Y" crossorigin="anonymous"></script>

<script>
    dayjs.locale('es'); // Establecer el idioma (opcional)
    dayjs.extend(dayjs_plugin_utc);
    dayjs.extend(dayjs_plugin_timezone);
    dayjs.tz.setDefault(Intl.DateTimeFormat().resolvedOptions().timeZone); // Establecer la zona horaria del navegador (opcional)
</script>