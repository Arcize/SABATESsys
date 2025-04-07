<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="./ico/sabates.ico">
<title>SABATES</title>
<?php
$cssFiles = [
    "reset.css",
    "style.css",
    "navbar.css",
    "form.css",
    "customAlert.css",
    "nunitoFont.css",
    "poppinsFont.css",
    "login-register.css",
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
    "sweetalert2.all.min.js",
    "dropdownUserBar.js",
    "multi_step_form.js",
    "chart.umd.js",
    "password_verification.js"
];

foreach ($jsFiles as $jsFile) {
    echo "<script src='./js/$jsFile'></script>\n";
}
?>