document.getElementById('removeImageButton').addEventListener('click', function () {
    // Reset the image preview
    document.getElementById('imagePreview').src = '';
    document.getElementById('imagePreview').style.display = 'none';

    // Uncheck the remove image checkbox
    document.getElementById('removeImage').checked = false;

    // Clear the file input value to trigger change event even if the same file is selected
    document.getElementById('image').value = '';
});

document.getElementById('addImageButton').addEventListener('click', function () {
    document.getElementById('image').click();
});

document.getElementById('image').addEventListener('change', function (e) {
    var reader = new FileReader();
    reader.onload = function (event) {
        document.getElementById('imagePreview').src = event.target.result;
        document.getElementById('imagePreview').style.display = 'block';
    }
    reader.readAsDataURL(this.files[0]);
});
