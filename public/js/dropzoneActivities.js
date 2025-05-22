Dropzone.options.dropzoneArea = {
  dictDefaultMessage: "Arrastra aquí tus archivos o haz clic para subirlos",
  url: "index.php?view=upload",
  autoProcessQueue: false,
  uploadMultiple: true,
  maxFiles: 60,
  acceptedFiles: "image/*",
  init: function () {
    const myDropzone = this;

    // Verifica si hay imágenes existentes (modo edición)
    const existingImagesElement = document.getElementById('existingImages');
    if (existingImagesElement && existingImagesElement.value) {
      const existingImages = JSON.parse(existingImagesElement.value); // JSON con rutas de imágenes
      if (existingImages.imagenes && Array.isArray(existingImages.imagenes)) {
        existingImages.imagenes.forEach(function (imagePath) {
          // Construye la ruta relativa desde index.php hacia el directorio uploads
          const relativePath = `../uploads/${imagePath}`;
          console.log("Ruta relativa de la imagen:", relativePath); // Verifica la ruta en la consola

          const mockFile = { name: imagePath.split('/').pop(), size: 12345 }; // Usa el nombre del archivo como nombre
          myDropzone.emit("addedfile", mockFile);
          myDropzone.emit("thumbnail", mockFile, relativePath); // Usa la ruta relativa
          myDropzone.emit("complete", mockFile);

          // Marca el archivo como existente para evitar que se vuelva a subir
          mockFile.existing = true;
          mockFile.path = imagePath; // Guarda la ruta original para manejar eliminaciones
        });

        // Manejar la eliminación de imágenes existentes
        myDropzone.on("removedfile", function (file) {
          if (file.existing && file.path) {
            // Lógica para eliminar la imagen del servidor
            fetch(`index.php?view=activitiesReport&action=deleteImage&path=${file.path}`, {
              method: "POST",
            });
          }
        });
      }
    }

    // Manejar la subida de nuevas imágenes
    myDropzone.on("sending", function (file, xhr, formData) {
      // Puedes agregar datos adicionales si es necesario
      formData.append("id_reporte_actividades", document.getElementById("id_reporte_actividades").value || "");
    });
  },
};


