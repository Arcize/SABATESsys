<div class="data-panels">
    <div class="grid-stack">
        <div class="grid-stack-item" gs-x="9" gs-y="2" gs-w="3" gs-h="4">
            <div class="grid-stack-item-content data-panel" data-panel="1"></div>
        </div>

        <div class="grid-stack-item" gs-x="0" gs-y="0" gs-w="3" gs-h="3">
            <div class="grid-stack-item-content data-panel" data-panel="2"></div>
        </div>

        <div class="grid-stack-item" gs-x="7" gs-y="0" gs-w="5" gs-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="3"></div>
        </div>

        <div class="grid-stack-item" gs-x="3" gs-y="0" gs-w="4" gs-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="4"></div>
        </div>

        <div class="grid-stack-item" gs-x="3" gs-y="1" gs-w="2" gs-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="5"></div>
        </div>

        <div class="grid-stack-item" gs-x="0" gs-y="3" gs-w="3" gs-h="1">
            <div class="grid-stack-item-content data-panel" data-panel="6"></div>
        </div>

        <div class="grid-stack-item" gs-x="0" gs-y="4" gs-w="3" gs-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="7"></div>
        </div>
        <div class="grid-stack-item" gs-x="5" gs-y="2" gs-w="4" gs-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="8"></div>
        </div>
        <div class="grid-stack-item" gs-x="3" gs-y="4" gs-w="4" gs-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="9"></div>
        </div>
        <div class="grid-stack-item" gs-x="7" gs-y="4" gs-w="2" gs-h="2">
            <div class="grid-stack-item-content data-panel" data-panel="9"></div>
        </div>

    </div>
</div>

<script>
    const gridContainer = document.querySelector('.data-panels');
    const gridStackElement = document.querySelector('.grid-stack');
    const numColumns = 12; // Define el número de columnas
    const numRows = 6;    // Define el número de filas
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

        gridInstance = GridStack.init( {
            column: numColumns,
            rows: numRows,
            margin: marginValue,
            rowHeight: initialRowHeight, // Establecer la altura inicial
            staticGrid: false,
            float: true,
            maxRow: numRows
        });
        gridInstance.on('added removed change', function(event, items) {
            // Actualiza la altura de las filas al cambiar el tamaño de la cuadrícula
            updateRowHeight();
        });
        window.addEventListener('resize', function() {
            // Actualiza la altura de las filas al cambiar el tamaño de la cuadrícula
            updateRowHeight();
        });
        updateRowHeight();
    } else {
        console.error("No se encontraron los elementos .data-panels o .grid-stack");
    }
</script>
<script src="./js/chartScript.js"></script>