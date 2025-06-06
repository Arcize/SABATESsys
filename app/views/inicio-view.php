<?php
// Vista de Inicio para usuarios según su rol
?>
<div class="view-box">
    <div class="inicio-container">
        <?php
        $rol = isset($_SESSION['role']) ? $_SESSION['role'] : null;
        ?>
        <?php if ($rol == 2): // Usuario estándar ?>
            <h1 class="h3">Bienvenido a SABATESsys</h1>
            <p>Esta es la página de inicio para usuarios estándar. Aquí podrás ver información relevante, acceder a tus reportes y gestionar tus solicitudes.</p>
            <div class="inicio-info">
                <ul>
                    <li>Consulta el estado de tus reportes de fallas.</li>
                    <li>Accede a tu perfil y actualiza tu información.</li>
                    <li>Revisa notificaciones importantes.</li>
                </ul>
            </div>
            <div class="inicio-actions">
                <a href="index.php?view=myFaultReports" class="btn btn-primary">Ver mis reportes</a>
                <a href="index.php?view=perfil" class="btn btn-secondary">Mi perfil</a>
            </div>
        <?php elseif ($rol == 3): // Técnico ?>
            <h1 class="h3">Bienvenido Técnico a SABATESsys</h1>
            <p>Esta es la página de inicio para técnicos. Aquí podrás gestionar los reportes asignados y actualizar su estado.</p>
            <div class="inicio-info">
                <ul>
                    <li>Visualiza y atiende los reportes de fallas asignados.</li>
                    <li>Actualiza el estado de los reportes.</li>
                    <li>Consulta tu historial de intervenciones.</li>
                </ul>
            </div>
            <div class="inicio-actions">
                <a href="index.php?view=faultReportTable" class="btn btn-primary">Reportes de Falla</a>
                <a href="index.php?view=perfil" class="btn btn-secondary">Mi perfil</a>
            </div>
        <?php elseif ($rol == 4): // Monitor de actividades ?>
            <h1 class="h3">Bienvenido Monitor de Actividades a SABATESsys</h1>
            <p>Esta es la página de inicio para monitores de actividades. Aquí podrás supervisar el avance de los reportes y generar informes.</p>
            <div class="inicio-info">
                <ul>
                    <li>Registra las actividades realizadas</li>
                    <li>Genera y descarga informes de actividad.</li>
                    <li>Consulta estadísticas relevantes.</li>
                </ul>
            </div>
            <div class="inicio-actions">
                <a href="index.php?view=activitiesReportTable" class="btn btn-primary">Ver reportes de actividad</a>
                <a href="index.php?view=perfil" class="btn btn-secondary">Perfil</a>
            </div>
        <?php else: ?>
            <h1>Bienvenido a SABATESsys</h1>
            <p>No tienes un rol asignado o tu rol no tiene una vista específica.</p>
        <?php endif; ?>
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
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
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
