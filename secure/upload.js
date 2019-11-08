var previewImage = document.getElementById("preview");
var uploadingText = document.getElementById("uploading-text");
function submitForm(event) {
    // prevent default form submission
    event.preventDefault();
    uploadImage();
}
function uploadError(error){
    alert(error || 'Something happened and this failed.');
}
function showImage(url){
    previewImage.src = url;
    uploadingText.style.display = "none";
}
function uploadImage() {
    var imageSelector = document.getElementById("image-selector"),
        file = imageSelector.files[0];
    if(!file)
        return alert("Please select a file!");
    // Clear the previous image
    previewImage.removeAttribute("src");
    uploadingText.style.display = "block";
    
    // Create form data
    var formData = new FormData();
    formData.append("image", file);
    var ajax = new XMLHttpRequest();
    ajax.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            var json = JSON.parse(this.responseText);
            if(!json || json.status !== true)
                return uploadError(json.error);
            showImage(json.url);
        }
    }
    ajax.open("POST", "upload.php", true);
    ajax.send(formData); // sends the form data
}