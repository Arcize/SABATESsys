document.addEventListener('DOMContentLoaded', function() {
    const headers = document.querySelectorAll('.accordion__header');

    headers.forEach(header => {
        header.addEventListener('click', function() {
            const activeHeader = this; // El header específico que activa la función
            const allContents = document.querySelectorAll('.accordion__content'); // Todos los elementos con la clase 'accordion__content'
            const allArrows = document.querySelectorAll('.imgArrow'); // Todas las flechas
            const currentHeaderIndex = Array.from(headers).indexOf(activeHeader);
            
            // Encontrar el siguiente accordion__content y flecha asociada al activeHeader
            let nextContent = allContents[currentHeaderIndex];
            let nextArrow = allArrows[currentHeaderIndex];

            // Cerrar todos los contenidos abiertos y resetear las flechas
            allContents.forEach((content, index) => {
                if (index !== currentHeaderIndex) {
                    content.style.maxHeight = null;
                    content.classList.remove('accordion__content--active');
                    allArrows[index].classList.remove('rotate'); // Asegurarse de que la flecha no esté rotada
                }
            });

            // Alternar el contenido y la flecha actual
            if (nextContent) {
                if (nextContent.style.maxHeight) {
                    nextContent.style.maxHeight = null;
                    nextArrow.classList.remove('rotate'); // Quitar rotación al cerrar
                } else {
                    nextContent.style.maxHeight = nextContent.scrollHeight + 'px';
                    nextArrow.classList.add('rotate'); // Añadir rotación al abrir
                }
                nextContent.classList.toggle('accordion__content--active');
            }
        });
    });
});
