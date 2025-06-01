<?php
// Vista de Inicio para usuarios estándar (no administradores)
?>
<div class="inicio-container">
    <h1>Bienvenido a SABATESsys</h1>
    <p>Esta es la página de inicio para usuarios estándar. Aquí podrás ver información relevante, acceder a tus reportes y gestionar tus solicitudes.</p>
    <div class="inicio-info">
        <ul>
            <li>Consulta el estado de tus reportes de fallas.</li>
            <li>Accede a tu perfil y actualiza tu información.</li>
            <li>Revisa notificaciones importantes.</li>
        </ul>
    </div>
    <div class="inicio-actions">
        <a href="index.php?view=faultReportV" class="btn btn-primary">Ver mis reportes</a>
        <a href="index.php?view=perfil" class="btn btn-secondary">Mi perfil</a>
    </div>
</div>
<style>
.inicio-container {
    max-width: 600px;
    margin: 40px auto;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 32px 24px;
    text-align: center;
}
.inicio-container h1 {
    color: #2a4365;
    margin-bottom: 16px;
}
.inicio-info {
    margin: 24px 0;
    text-align: left;
}
.inicio-info ul {
    list-style: disc inside;
    color: #444;
}
.inicio-actions {
    margin-top: 24px;
}
.inicio-actions .btn {
    margin: 0 8px;
    padding: 10px 24px;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    background: #3182ce;
    transition: background 0.2s;
}
.inicio-actions .btn-secondary {
    background: #4a5568;
}
.inicio-actions .btn:hover {
    background: #2b6cb0;
}
</style>
