<?php

use app\controllers\UserController;

$userController = new UserController();
$dashboardConfig = $userController->getDashboardConfig();
if ($dashboardConfig) {
    $dashboardConfig = json_decode($dashboardConfig, true);

}
?>
<script>
    // Pasa la configuración PHP a JavaScript
    // Ahora soporta dashboard y toolbar
    const dashboardConfig = <?php echo $dashboardConfig ? $dashboardConfig : 'null'; ?>;
    console.log("Configuración del backend:", dashboardConfig);
</script>
<?php include __DIR__ . '/layouts/dashboard-toolbar.php'; ?>
<div class="data-panels">
    <div class="grid-stack dashboard-gridstack grid-loading">
        <?php
        // Renderiza los widgets SOLO si no hay configuración guardada (primer uso)
        if (!$dashboardConfig) {
            // Renderiza los widgets por defecto con la nueva configuración de posiciones
        ?>
            <div class="grid-stack-item" gs-x="9" gs-y="3" gs-w="3" gs-h="3" gs-min-w="2" gs-min-h="3">
                <div class="grid-stack-item-content data-panel" data-panel="1">
                    <!-- Sin botón de cerrar -->
                </div>
            </div>
            <div class="grid-stack-item" gs-x="0" gs-y="0" gs-w="4" gs-h="3" gs-min-w="3" gs-min-h="3">
                <div class="grid-stack-item-content data-panel" data-panel="2">
                    <!-- Sin botón de cerrar -->
                </div>
            </div>
            <div class="grid-stack-item" gs-x="5" gs-y="3" gs-w="4" gs-h="3" gs-min-w="3" gs-min-h="2">
                <div class="grid-stack-item-content data-panel" data-panel="3">
                    <!-- Sin botón de cerrar -->
                </div>
            </div>
            <div class="grid-stack-item" gs-x="7" gs-y="0" gs-w="5" gs-h="3" gs-min-w="4" gs-min-h="3">
                <div class="grid-stack-item-content data-panel" data-panel="4">
                    <!-- Sin botón de cerrar -->
                </div>
            </div>
            <div class="grid-stack-item" gs-x="0" gs-y="3" gs-w="2" gs-h="3" gs-min-w="2" gs-min-h="3">
                <div class="grid-stack-item-content data-panel" data-panel="5">
                    <!-- Sin botón de cerrar -->
                </div>
            </div>
            <div class="grid-stack-item" gs-x="2" gs-y="3" gs-w="3" gs-h="3" gs-min-w="3" gs-min-h="3">
                <div class="grid-stack-item-content data-panel" data-panel="6">
                    <!-- Sin botón de cerrar -->
                </div>
            </div>
            <div class="grid-stack-item" gs-x="4" gs-y="0" gs-w="3" gs-h="3" gs-min-w="3" gs-min-h="2">
                <div class="grid-stack-item-content data-panel" data-panel="7">
                    <!-- Sin botón de cerrar -->
                </div>
            </div>
            <!-- Paneles 8, 10, 11 van al toolbar por defecto -->
            <?php
        } else if (isset($dashboardConfig['dashboard']) && is_array($dashboardConfig['dashboard'])) {
            // Renderiza solo los widgets que están en el dashboard según la configuración guardada
            foreach ($dashboardConfig['dashboard'] as $item) {
                $panelId = htmlspecialchars($item['panelId']);
                $x = (int)$item['x'];
                $y = (int)$item['y'];
                $w = (int)$item['w'];
                $h = (int)$item['h'];
                $minW = isset($item['minW']) ? (int)$item['minW'] : 1;
                $minH = isset($item['minH']) ? (int)$item['minH'] : 1;
            ?>
                <div class="grid-stack-item" gs-x="<?= $x ?>" gs-y="<?= $y ?>" gs-w="<?= $w ?>" gs-h="<?= $h ?>" gs-min-w="<?= $minW ?>" gs-min-h="<?= $minH ?>">
                    <div class="grid-stack-item-content data-panel" data-panel="<?= $panelId ?>">
                        <!-- Sin botón de cerrar -->
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
</div>
<script src="./js/gridstack-all.js"></script>

<script>
    const gridContainer = document.querySelector('.data-panels');
    // Selecciona solo el gridstack del dashboard
    const gridStackElement = document.querySelector('.dashboard-gridstack');
    const numColumns = 12; // Define el número de columnas
    const numRows = 6; // Define el número de filas
    const marginValue = 9;
    let gridInstance; // Variable para almacenar la instancia de GridStack

    function updateRowHeight() {
        if (gridContainer && gridInstance) {
            const containerHeight = gridContainer.offsetHeight;
            const newRowHeight = containerHeight / numRows;
            gridInstance.cellHeight(newRowHeight);
        }
    }

    if (gridContainer) {
        const containerHeightInitial = gridContainer.offsetHeight;
        const initialRowHeight = containerHeightInitial / numRows;

        // Inicializa GridStack normalmente, los atributos gs-x, gs-y, etc. ya están en el HTML
        gridInstance = GridStack.init({
            column: numColumns,
            rows: numRows,
            margin: marginValue,
            rowHeight: initialRowHeight,
            staticGrid: false,
            float: true, // <-- Cambiado a false para auto-organización
            maxRow: numRows,
            acceptWidgets: true // Permite drag&drop desde la toolbar
        }, '.dashboard-gridstack');
        window.gridInstance = gridInstance; // <-- Asegura que sea global para los listeners

        gridInstance.on('added removed change', function(event, items) {
            updateRowHeight();
            // Guardar la configuración SIEMPRE que haya un cambio
            if (typeof saveGridstackConfig === 'function') {
                saveGridstackConfig(gridInstance);
            } else if (window.saveGridstackConfig) {
                window.saveGridstackConfig(gridInstance);
            }
            addDashboardCloseListeners(); // <-- Asegura que los nuevos botones tengan listener
        });
        window.addEventListener('resize', function() {
            updateRowHeight();
        });
        updateRowHeight();
        addDashboardCloseListeners(); // <-- Llama al cargar
    } else {
        console.error("No se encontraron los elementos .data-panels o .grid-stack");
    }

    // Maneja el click en la equis para eliminar el widget del dashboard y devolverlo al toolbar
    async function addDashboardCloseListeners() {
        document.querySelectorAll('.dashboard-gridstack .panel-close-btn').forEach(btn => {
            btn.onclick = async function(e) {
                e.stopPropagation();
                const gridItem = btn.closest('.grid-stack-item');
                if (!gridItem || !window.gridInstance) return;

                const panelContent = gridItem.querySelector('.grid-stack-item-content[data-panel]');
                const panelId = panelContent?.getAttribute('data-panel');
                if (!panelId) return;

                // 1. Eliminar del dashboard
                window.gridInstance.removeWidget(gridItem, true);

                // 2. Actualizar dashboardConfig LOCALMENTE (¡SINCRÓNICO!)
                if (window.dashboardConfig) {
                    // Quitar del dashboard
                    window.dashboardConfig.dashboard = window.dashboardConfig.dashboard?.filter(
                        item => item.panelId !== panelId
                    ) || [];

                    // Añadir al toolbar si no existe
                    if (!window.dashboardConfig.toolbar?.some(item => item.panelId === panelId)) {
                        window.dashboardConfig.toolbar = [...(window.dashboardConfig.toolbar || []), {
                            panelId
                        }];
                    }
                }

                // 3. Guardar (usando dashboardConfig actualizado)
                await window.saveGridstackConfig(window.gridInstance, window.dashboardConfig);

                // 4. Re-renderizar toolbar y dashboard desde la config para asegurar sincronía visual y lógica
                if (typeof window.renderizarPanelesDesdeConfig === 'function') {
                    window.renderizarPanelesDesdeConfig(window.dashboardConfig);
                }
            };
        });
    }
</script>
<script src="./js/chartScript.js"></script>
<script src="./js/gridstack-config-saver.js"></script>
<style>
    .panel-close-btn {
        position: absolute;
        top: 0;
        right: 0;
        background: var(--color-white-hover);
        color: #fff;
        border: none;
        border-radius: 50%;
        width: 22px;
        height: 22px;
        font-size: 16px;
        line-height: 18px;
        cursor: pointer;
        z-index: 10;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.85;
        transition: background 0.2s, opacity 0.2s;
    }

    .panel-close-btn:hover {
        background: #c0392b;
        opacity: 1;
    }

    .grid-stack-item-content.data-panel {
        position: relative;
    }
</style>
<script>
    // Llama a la función después de cada cambio en el grid
    if (window.gridInstance) addDashboardCloseListeners();
    document.addEventListener('DOMContentLoaded', function() {
        addDashboardCloseListeners();
        if (window.gridInstance) {
            window.gridInstance.on('added removed change', function() {
                addDashboardCloseListeners();
            });
        }
    });
</script>