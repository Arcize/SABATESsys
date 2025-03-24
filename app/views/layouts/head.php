<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" href="app/views/img/sabates_icon.png">
<title>SABATES</title>
<!-- CSS -->
<?php
$cssFiles = [
    "reset.css",
    "style.css",
    "navbar.css",
    "form.css",
    "custom.css",
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
    "sweetalert2.min.css"
];

foreach ($cssFiles as $cssFile) {
    echo "<link rel='stylesheet' href='app/views/css/$cssFile'>\n";
}
?>

<!-- JS -->
<?php
$jsFiles = [
    "jquery-3.7.1.min.js",
    "sweetalert2.all.min.js",
    "dropdown.js",
    "accordion.js",
    "multi_step_form.js",
    "dropdownNotifications.js",
    "chart.umd.js",
    "password_verification.js"
];

foreach ($jsFiles as $jsFile) {
    echo "<script src='app/views/js/$jsFile'></script>\n";
}
?>