<?php

use app\controllers\UserController;

$userController = new UserController();
$dashboardConfig = $userController->getDashboardConfig();
?>
<script>
    // Pasa la configuración PHP a JavaScript
    const dashboardConfig = <?php echo $dashboardConfig ? $dashboardConfig : 'null'; ?>;
    console.log("Configuración del backend:", dashboardConfig);
</script>
<div class="data-panels">
    <div class="grid-stack grid-loading">
        <div class="grid-stack-item" gs-x="9" gs-y="2" gs-w="3" gs-h="4" gs-min-w="2" gs-min-h="3">
            <div class="grid-stack-item-content data-panel" data-panel="1"></div>
        </div>

        <div class="grid-stack-item" gs-x="0" gs-y="0" gs-w="3" gs-h="3" gs-min-w="2" gs-min-h="3">
            <div class="grid-stack-item-content data-panel" data-panel="2"></div>
        </div>

        <div class="grid-stack-item" gs-x="7" gs-y="0" gs-w="5" gs-h="2" gs-min-w="4" gs-min-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="3"></div>
        </div>

        <div class="grid-stack-item" gs-x="3" gs-y="0" gs-w="4" gs-h="2" gs-min-w="4" gs-min-h="3">
            <div class="grid-stack-item-content data-panel" data-panel="4"></div>
        </div>

        <div class="grid-stack-item" gs-x="3" gs-y="1" gs-w="2" gs-h="2" gs-min-w="1" gs-min-h="1">
            <div class="grid-stack-item-content data-panel" data-panel="5"></div>
        </div>

        <div class="grid-stack-item" gs-x="0" gs-y="3" gs-w="3" gs-h="1" gs-min-w="1" gs-min-h="1">
            <div class="grid-stack-item-content data-panel" data-panel="6"></div>
        </div>

        <div class="grid-stack-item" gs-x="0" gs-y="4" gs-w="3" gs-h="2" gs-min-w="1" gs-min-h="1">
            <div class="grid-stack-item-content data-panel" data-panel="7"></div>
        </div>
        <div class="grid-stack-item" gs-x="5" gs-y="2" gs-w="4" gs-h="2" gs-min-w="1" gs-min-h="1">
            <div class="grid-stack-item-content data-panel" data-panel="8"></div>
        </div>
        <div class="grid-stack-item" gs-x="3" gs-y="4" gs-w="4" gs-h="2" gs-min-w="1" gs-min-h="1">
            <div class="grid-stack-item-content data-panel" data-panel="9"></div>
        </div>
    </div>
</div>
<script src="./js/gridstack-all.js"></script>

<script>
    const gridContainer = document.querySelector('.data-panels');
    const gridStackElement = document.querySelector('.grid-stack');
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

function aplicarConfiguracionInicial(configuracionGuardada) {
    const gridItems = gridStackElement.querySelectorAll('.grid-stack-item');

    configuracionGuardada.forEach(itemConfig => {
        const panelId = itemConfig.panelId;

        // Buscar el grid-stack-item que contiene un elemento con el data-panel correspondiente
        gridItems.forEach(gridItem => {
            const panelContent = gridItem.querySelector('.grid-stack-item-content[data-panel="' + panelId + '"]');
            if (panelContent) {
                gridItem.setAttribute('gs-x', itemConfig.x);
                gridItem.setAttribute('gs-y', itemConfig.y);
                gridItem.setAttribute('gs-w', itemConfig.w);
                gridItem.setAttribute('gs-h', itemConfig.h);
                if (itemConfig.minW && itemConfig.minW !== 1) gridItem.setAttribute('gs-min-w', itemConfig.minW);
                if (itemConfig.minH && itemConfig.minH !== 1) gridItem.setAttribute('gs-min-h', itemConfig.minH);
            }
        });
    });
}

    if (gridContainer) {
        const containerHeightInitial = gridContainer.offsetHeight;
        const initialRowHeight = containerHeightInitial / numRows;

        // Si hay configuración guardada, aplicarla ANTES de inicializar Gridstack
        if (dashboardConfig && Array.isArray(dashboardConfig)) {
            aplicarConfiguracionInicial(dashboardConfig);
        }

        gridInstance = GridStack.init({
            column: numColumns,
            rows: numRows,
            margin: marginValue,
            rowHeight: initialRowHeight,
            staticGrid: false,
            float: true,
            maxRow: numRows
        });

        gridInstance.on('added removed change', function(event, items) {
            updateRowHeight();
            saveGridstackConfig(gridInstance);
        });
        window.addEventListener('resize', function() {
            updateRowHeight();
        });
        updateRowHeight();
    } else {
        console.error("No se encontraron los elementos .data-panels o .grid-stack");
    }
</script>
<script src="./js/chartScript.js"></script>
<script src="./js/gridstack-config-saver.js"></script>