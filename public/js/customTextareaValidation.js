document.addEventListener('input', function (e) {
    // Asegura que sea un <textarea> (no un input con type="textarea")
    if (e.target && e.target.nodeName === 'TEXTAREA') {
        let value = e.target.value;

        // No permitir dos espacios seguidos
        value = value.replace(/ {2,}/g, ' ');

        // No permitir espacio al inicio
        value = value.replace(/^\s+/, '');

        // No permitir dos saltos de línea seguidos
        value = value.replace(/\n{2,}/g, '\n');

        // Primera letra mayúscula (si hay texto)
        if (value.length > 0) {
            value = value.charAt(0).toUpperCase() + value.slice(1);
        }

        // Solo actualiza si hubo cambios
        if (e.target.value !== value) {
            e.target.value = value;
        }
    }
});
