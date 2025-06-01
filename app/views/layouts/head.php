<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./ico/sabates.ico">
<title>SABATES</title>

<?php
$cssFiles = [
    "reset.css",
    "datatables.min.css",
    "gridstack.min.css",
    "dropzone.min.css",
    "dataTables.dateTime.min.css",
    "buttons.dataTables.min.css",
    "tippy.css",
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
    "sweetalert2.min.css",
    "perfil.css"
];

foreach ($cssFiles as $cssFile) {
    echo "<link rel='stylesheet' href='./css/$cssFile'>\n";
}
?>
<?php
$jsFiles = [
    "jquery-3.7.1.min.js",
    "datatables.min.js",
    "sweetalert2.all.min.js",
    "dropdownUserBar.js",
    "password_verification.js",
    "dataTables.dateTime.min.js",
    "pdfmake.min.js",
    "vfs_fonts.js",
    "popper.min.js",
    "tippy-bundle.umd.min.js",
    "moment.min.js",
    "datetime-moment.js",
    "dataTables.buttons.min.js",
    "buttons.html5.min.js",
    "customTextareaValidation.js"
];

foreach ($jsFiles as $jsFile) {
    echo "<script src='./js/$jsFile'></script>\n";
}
?>


<script>
</script>