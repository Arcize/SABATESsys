document.addEventListener('DOMContentLoaded', function() {
    const headers = document.querySelectorAll('.accordion__header');

    headers.forEach(header => {
        header.addEventListener('click', function() {
            const activeHeader = this; // El header específico que activa la función
            const allContents = document.querySelectorAll('.accordion__content'); // Todos los elementos con la clase 'accordion__content'
            const currentHeaderIndex = Array.from(headers).indexOf(activeHeader);
            
            // Encontrar el siguiente accordion__content después del activeHeader
            let nextContent = allContents[currentHeaderIndex];

            // Cerrar todos los contenidos abiertos excepto el correspondiente al activeHeader
            allContents.forEach((content, index) => {
                if (index !== currentHeaderIndex) {
                    content.style.maxHeight = null;
                    content.classList.remove('accordion__content--active');
                }
            });

            // Alternar el contenido actual si se encuentra
            if (nextContent) {
                if (nextContent.style.maxHeight) {
                    nextContent.style.maxHeight = null;
                } else {
                    nextContent.style.maxHeight = nextContent.scrollHeight + 'px';
                }
                nextContent.classList.toggle('accordion__content--active');
            }
        });
    });
});
