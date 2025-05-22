function saveGridstackConfig(gridInstance) {
    const configGrid = gridInstance.save();
    const gridStackElement = document.querySelector('.grid-stack');
    const itemsToSave = [];

    gridStackElement.querySelectorAll('.grid-stack-item').forEach(gridItem => {
        const panelContent = gridItem.querySelector('.grid-stack-item-content[data-panel]');
        if (panelContent) {
            const panelId = panelContent.getAttribute('data-panel');
            const gridstackNode = gridItem.gridstackNode; // Get the Gridstack node

            if (gridstackNode) {
                const { x, y, w, h, minW, minH } = gridstackNode;
                const itemToSave = { x, y, w, h, panelId: panelId }; // Include panelId
                 if (minW && minW !== 1) itemToSave.minW = minW;
                if (minH && minH !== 1) itemToSave.minH = minH;
                itemsToSave.push(itemToSave);
            }        }
    });

    const jsonData = JSON.stringify(itemsToSave);
    console.log("Configuración JSON a guardar:", jsonData);

    fetch('index.php?view=user&action=dashboard_config', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: jsonData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Configuración del dashboard guardada exitosamente:', data);
    })
    .catch(error => {
        console.error('Error al guardar la configuración del dashboard:', error);
    });
}
