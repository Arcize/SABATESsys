function saveGridstackConfig(gridInstance, forcedConfig) {
    // Usar forcedConfig SIEMPRE que exista (prioridad máxima)
    if (forcedConfig) {
        console.log("Enviando al backend (forcedConfig):", forcedConfig);
        return fetch('index.php?view=user&action=dashboard_config', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(forcedConfig)
        }).then(response => response.json());
    }

    // Si no hay forcedConfig, generar la configuración desde el DOM
    const dashboardItems = [];
    const toolbarItems = [];

    // 1. Obtener widgets del dashboard (conserva minW/minH)
    document.querySelectorAll('.dashboard-gridstack .grid-stack-item').forEach(gridItem => {
        const panelContent = gridItem.querySelector('.grid-stack-item-content[data-panel]');
        if (panelContent) {
            const panelId = panelContent.getAttribute('data-panel');
            const gridstackNode = gridItem.gridstackNode;
            if (gridstackNode) {
                const { x, y, w, h, minW, minH } = gridstackNode;
                dashboardItems.push({ x, y, w, h, panelId, ...(minW && { minW }), ...(minH && { minH }) });
            }
        }
    });

    // 2. Obtener widgets del toolbar (¡AHORA CON minW/minH!)
    document.querySelectorAll('#toolbar-widgets .grid-stack-item').forEach(gridItem => {
        const panelContent = gridItem.querySelector('.grid-stack-item-content[data-panel]');
        if (panelContent) {
            const panelId = panelContent.getAttribute('data-panel');
            const gridstackNode = gridItem.gridstackNode;
            toolbarItems.push({ 
                panelId,
                minW: gridstackNode?.minW || parseInt(gridItem.getAttribute('gs-min-w')) || 1,
                minH: gridstackNode?.minH || parseInt(gridItem.getAttribute('gs-min-h')) || 1
            });
        }
    });

    const configToSave = {
        dashboard: dashboardItems,
        toolbar: toolbarItems // <- Ahora incluye minW/minH
    };

    console.log("Enviando al backend:", configToSave);
    return fetch('index.php?view=user&action=dashboard_config', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(configToSave)
    }).then(response => response.json());
}
