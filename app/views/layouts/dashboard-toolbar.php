<!-- Toolbar lateral para el dashboard -->

<script>
    // Define allPanels globalmente (debe coincidir con los paneles en PHP)
    window.allPanels =
        <?php echo json_encode([
            ["label" => "Panel 1", "panelId" => "1", "minW" => 2, "minH" => 3],
            ["label" => "Panel 2", "panelId" => "2", "minW" => 3, "minH" => 3],
            ["label" => "Panel 3", "panelId" => "3", "minW" => 3, "minH" => 2],
            ["label" => "Panel 4", "panelId" => "4", "minW" => 4, "minH" => 3],
            ["label" => "Panel 5", "panelId" => "5", "minW" => 2, "minH" => 3],
            ["label" => "Panel 6", "panelId" => "6", "minW" => 3, "minH" => 3],
            ["label" => "Panel 7", "panelId" => "7", "minW" => 3, "minH" => 2],
            ["label" => "Panel 8", "panelId" => "8", "minW" => 3, "minH" => 3],
            ["label" => "Panel 9", "panelId" => "9", "minW" => 1, "minH" => 1],
            ["label" => "Panel 10", "panelId" => "10", "minW" => 1, "minH" => 1],
            ["label" => "Panel 11", "panelId" => "11", "minW" => 1, "minH" => 1]

        ]); ?>;
</script>
<div id="dashboard-toolbar" class="dashboard-toolbar-hidden">
    <div class="toolbar-toggle" id="toolbar-toggle">
        <span class="toolbar-arrow">&#x25C0;</span>
    </div>
    <div class="toolbar-content">
        <div id="toolbar-widgets" class="grid-stack toolbar-gridstack">
            <?php
            // Paneles posibles en la toolbar (ahora permite cualquier panelId)
            $allPanels = [
                ["label" => "Panel 1", "panelId" => "1", "minW" => 2, "minH" => 3],
                ["label" => "Panel 2", "panelId" => "2", "minW" => 3, "minH" => 3],
                ["label" => "Panel 3", "panelId" => "3", "minW" => 3, "minH" => 2],
                ["label" => "Panel 4", "panelId" => "4", "minW" => 4, "minH" => 3],
                ["label" => "Panel 5", "panelId" => "5", "minW" => 2, "minH" => 3],
                ["label" => "Panel 6", "panelId" => "6", "minW" => 3, "minH" => 3],
                ["label" => "Panel 7", "panelId" => "7", "minW" => 3, "minH" => 2],
                ["label" => "Panel 8", "panelId" => "8", "minW" => 3, "minH" => 3],
                ["label" => "Panel 9", "panelId" => "9", "minW" => 1, "minH" => 1],
                ["label" => "Panel 10", "panelId" => "10", "minW" => 1, "minH" => 1],
                ["label" => "Panel 11", "panelId" => "11", "minW" => 1, "minH" => 1]
            ];
            // Si hay configuración, solo renderiza los paneles que están en toolbar
            if (isset($dashboardConfig['toolbar']) && is_array($dashboardConfig['toolbar'])) {
                $toolbarPanelIds = array_map(function ($item) {
                    return $item['panelId'];
                }, $dashboardConfig['toolbar']);
                foreach ($allPanels as $panel) {
                    if (in_array($panel['panelId'], $toolbarPanelIds)) {
                        // Buscar si hay override de minW/minH en la config
                        $toolbarItem = null;
                        foreach ($dashboardConfig['toolbar'] as $item) {
                            if ($item['panelId'] === $panel['panelId']) {
                                $toolbarItem = $item;
                                break;
                            }
                        }
                        $minW = isset($toolbarItem['minW']) ? (int)$toolbarItem['minW'] : (int)$panel['minW'];
                        $minH = isset($toolbarItem['minH']) ? (int)$toolbarItem['minH'] : (int)$panel['minH'];
            ?>
                        <div class="grid-stack-item" gs-min-w="<?= $minW ?>" gs-min-h="<?= $minH ?>">
                            <div class="grid-stack-item-content data-panel widget-toolbar" data-panel="<?= htmlspecialchars($panel['panelId']) ?>">
                                <!-- Sin label -->
                            </div>
                        </div>
                    <?php
                    }
                }
            } else {
                // Si no hay configuración, muestra solo los widgets de la toolbar por defecto (10 y 11)
                foreach ($allPanels as $panel) {
                    if (in_array($panel['panelId'], ['8'])) {
                    ?>
                        <div class="grid-stack-item" gs-min-w="<?= (int)$panel['minW'] ?>" gs-min-h="<?= (int)$panel['minH'] ?>">
                            <div class="grid-stack-item-content data-panel widget-toolbar" data-panel="<?= htmlspecialchars($panel['panelId']) ?>">
                                <!-- Sin label -->
                            </div>
                        </div>
            <?php
                    }
                }
            }
            ?>
        </div>
    </div>
</div>

<style>
    #dashboard-toolbar {
        position: fixed;
        top: 0;
        right: 0;
        width: 340px;
        /* Aumentado de 220px a 340px */
        height: 100vh;
        background: rgba(34, 51, 76 , 0.9);
        backdrop-filter: blur(10px);
        color: #fff;
        box-shadow: -2px 0 8px rgba(0, 0, 0, 0.15);
        z-index: 2000;
        transition: right 0.4s cubic-bezier(.77, 0, .18, 1);
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: stretch;
    }

    #dashboard-toolbar.dashboard-toolbar-hidden {
        right: -340px;
        /* Aumentado igual que el width */
    }

    #dashboard-toolbar.dashboard-toolbar-visible {
        right: 0;
    }

    .toolbar-toggle {
        position: absolute;
        left: -32px;
        top: 50%;
        transform: translateY(-50%);
        width: 32px;
        height: 64px;
        background: #222e3c;
        border-radius: 8px 0 0 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: -2px 0 8px rgba(0, 0, 0, 0.10);
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .toolbar-toggle:hover {
        opacity: 1;
    }

    .toolbar-arrow {
        font-size: 1.5em;
        color: #fff;
    }

    .toolbar-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
        height: 100%;
        overflow-y: auto;
        
    }

    .toolbar-gridstack {
        width: 100%;
        min-height: 200px;
        background:transparent;
        border-radius: 8px;
        margin-top: 8px;
        flex: 1 1 auto;
        overflow-y: none;
    }

    .widget-toolbar {
        background: #ffffff;
        color: var(--color-texto, #222e3c);
        border-radius: var(--border-radius, 6px);
        padding: 12px;
        text-align: center;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        cursor: grab;
        /* Igual que .data-panel del dashboard */
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>

<script src="./js/gridstack-all.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toolbar = document.getElementById('dashboard-toolbar');
        const toggle = document.getElementById('toolbar-toggle');
        let isOpen = false;

        // Mostrar flecha cuando el mouse está cerca del borde derecho
        document.addEventListener('mousemove', function(e) {
            if (!isOpen && (window.innerWidth - e.clientX) < 32) {
                toggle.style.opacity = '1';
            } else if (!isOpen) {
                toggle.style.opacity = '0.5';
            }
        });

        // Abrir/cerrar toolbar
        toggle.addEventListener('mousedown', function(event) {
            event.stopPropagation();
            isOpen = !isOpen;
            if (isOpen) {
                toolbar.classList.remove('dashboard-toolbar-hidden');
                toolbar.classList.add('dashboard-toolbar-visible');
                toggle.querySelector('.toolbar-arrow').innerHTML = '&#x25B6;'; // Flecha hacia la derecha
            } else {
                toolbar.classList.remove('dashboard-toolbar-visible');
                toolbar.classList.add('dashboard-toolbar-hidden');
                toggle.querySelector('.toolbar-arrow').innerHTML = '&#x25C0;'; // Flecha hacia la izquierda
            }
        });

        // Cerrar si se hace clic fuera
        // document.addEventListener('mousedown', function(e) {
        //     if (isOpen && !toolbar.contains(e.target)) {
        //         isOpen = false;
        //         toolbar.classList.remove('dashboard-toolbar-visible');
        //         toolbar.classList.add('dashboard-toolbar-hidden');
        //         toggle.querySelector('.toolbar-arrow').innerHTML = '&#x25C0;';
        //     }
        // });

        // Inicializar gridstack en la toolbar
        const toolbarGrid = GridStack.init({
            column: 3, // Aumentado de 1 a 3 columnas
            float: false,
            disableOneColumnMode: true,
            staticGrid: false,
            margin: 5,
            cellHeight: 80,
            acceptWidgets: true // Permite arrastrar widgets desde el dashboard
        }, '#toolbar-widgets');
        // Elimina la carga por defecto de widgets en la toolbar

        // Renderizar paneles en el dashboard y toolbar según dashboardConfig
        function renderizarPanelesDesdeConfig(config) {
            const toolbarGrid = document.querySelector('#toolbar-widgets');

            // Limpiar toolbar (conserva el contenedor)
            toolbarGrid.querySelectorAll('.grid-stack-item').forEach(item => item.remove());

            // Agregar widgets al toolbar con sus propiedades
            if (config.toolbar) {
                config.toolbar.forEach(item => {
                    const panelInfo = allPanels.find(p => p.panelId === item.panelId);
                    const widget = document.createElement('div');
                    widget.className = 'grid-stack-item';

                    // Aplicar minW/minH desde la configuración
                    widget.setAttribute('gs-min-w', item.minW || 1);
                    widget.setAttribute('gs-min-h', item.minH || 1);

                    widget.innerHTML = `
                <div class="grid-stack-item-content data-panel widget-toolbar" data-panel="${item.panelId}">
                    ${panelInfo?.label || item.panelId}
                </div>
            `;
                    toolbarGrid.appendChild(widget);
                });
            }

            // Actualizar la instancia de GridStack del toolbar
            window.toolbarGridInstance?.load(toolbarGrid);
        }
        window.renderizarPanelesDesdeConfig = renderizarPanelesDesdeConfig;

        // Cuando un widget se mueve de la toolbar al dashboard, guardar la configuración y re-renderizar
        toolbarGrid.on('removed', function(event, items) {
            // Solo guarda la configuración, no rerenderices todo
            if (window.saveGridstackConfig) {
                saveGridstackConfig(window.gridInstance);
            }
        });
        // Cuando un widget se agrega al toolbar (drag desde dashboard), guardar la configuración
        toolbarGrid.on('added', function(event, items) {
            if (window.saveGridstackConfig) {
                saveGridstackConfig(window.gridInstance);
            }
        });
    });
</script>