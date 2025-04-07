const uploadZone = document.querySelector(".uploadZone");
const fileInput = uploadZone.querySelector(".file-input");
const progressArea = document.querySelector(".progress-area");
const uploadedArea = document.querySelector(".uploaded-area");

uploadZone.addEventListener("click", () => {
  fileInput.click();
});

uploadZone.addEventListener("dragover", (event) => {
    event.preventDefault();
    uploadZone.querySelector(".drop-message").textContent = Suelta aqui tu archivo;
  });

fileInput.onchange = ({target}) => {
  console.log(target.files);
  let file = target.files[0];
  if(file){
    let fileName = file.name
    uploadFile(fileName);
  }
};

function uploadFile(name){

}
