const uploadZone = document.querySelector(".uploadZone");
const form = document.querySelector(".form");
const fileInput = uploadZone.querySelector(".file-input");
const progressArea = document.querySelector(".progress-area");
const uploadedArea = document.querySelector(".uploaded-area");
const dropMessage = uploadZone.querySelector(".drop-message"); // Seleccionamos el mensaje

uploadZone.addEventListener("click", () => {
  fileInput.click();
});

fileInput.onchange = ({ target }) => {
  console.log(target.files);

  let file = target.files[0];
  if (file) {
    let fileName = file.name;
    if (fileName.length >= 24) {
      let splitName = fileName.split(".");
      fileName = splitName[0].substring(0, 24) + "... ." + splitName[1];
    }
    console.log(fileName);
    uploadFileFetch(fileName); // Pasamos el objeto File a la función
  }
};

function uploadFileFetch(fileName) {
  try {
    let xhr = new XMLHttpRequest();

    xhr.open("POST", "index.php?view=bulkUpload&action=process_file");

    xhr.upload.addEventListener("progress", ({ loaded, total }) => {
      let fileLoaded = Math.floor((loaded / total) * 100);
      let fileTotal = Math.floor(total / 1024);
      let fileSize;
      fileSize = (fileTotal < 1024) ? fileTotal + "Kb" : (total / (1024 * 1024)).toFixed(2) + "Mb";

      let progressHTML = `
                  <div class="row">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121">
                    <path d="M360-240h240q17 0 28.5-11.5T640-280q0-17-11.5-28.5T600-320H360q-17 0-28.5 11.5T320-280q0 17 11.5 28.5T360-240Zm0-160h240q17 0 28.5-11.5T640-440q0-17-11.5-28.5T600-480H360q-17 0-28.5 11.5T320-440q0 17 11.5 28.5T360-400ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h287q16 0 30.5 6t25.5 17l194 194q11 11 17 25.5t6 30.5v447q0 33-23.5 56.5T720-80H240Zm280-560q0 17 11.5 28.5T560-600h160L520-800v160Z" />
                </svg>
                <div class="row-info">
                    <div class="upload-info">
                        <div class="details">
                            <span class="upload-name">${fileName}</span>
                        </div>
                        <span class="upload-percent">${fileLoaded}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress" style="width: ${fileLoaded}%;"></div>
                    </div>
                </div>
            </div>`;
      progressArea.innerHTML = progressHTML;
      if (loaded == total) {
        progressArea.innerHTML = ``;
        let uploadedHTML = `
                  <div class="row">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121">
                    <path d="M360-240h240q17 0 28.5-11.5T640-280q0-17-11.5-28.5T600-320H360q-17 0-28.5 11.5T320-280q0 17 11.5 28.5T360-240Zm0-160h240q17 0 28.5-11.5T640-440q0-17-11.5-28.5T600-480H360q-17 0-28.5 11.5T320-440q0 17 11.5 28.5T360-400ZM240-80q-33 0-56.5-23.5T160-160v-640q0-33 23.5-56.5T240-880h287q16 0 30.5 6t25.5 17l194 194q11 11 17 25.5t6 30.5v447q0 33-23.5 56.5T720-80H240Zm280-560q0 17 11.5 28.5T560-600h160L520-800v160Z" />
                </svg>
                <div class="row-info">
                    <div class="upload-info">
                        <div class="details">
                            <span class="upload-name">${fileName}</span>
                            <span class="upload-size">${fileSize}</span>

                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#212121" class="checkFile">
                            <path d="m382-354 339-339q12-12 28-12t28 12q12 12 12 28.5T777-636L410-268q-12 12-28 12t-28-12L182-440q-12-12-11.5-28.5T183-497q12-12 28.5-12t28.5 12l142 143Z" />
                        </svg>
                    </div>
                </div>
            </div>`;
        uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
      }
    });
    let formData = new FormData(form);
    xhr.send(formData);
  } catch (error) {
    console.error("There was an error during the upload:", error);
    // Aquí podrías mostrar un mensaje de error visual al usuario
  }
}
