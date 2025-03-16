// ============================
// 画像プレビュー＆クリアボタン制御
// ============================

function initImageHandling() {
    const fileInput = document.getElementById('image');
    const previewArea = document.getElementById('imagePreview');
    const clearButton = document.getElementById('clearImage');
    const deleteImageInput = document.querySelector('input[name="delete_image"]');

    if (!fileInput || !previewArea || !clearButton) return;

    const updateClearButtonVisibility = () => {
        clearButton.style.display = (previewArea.querySelector('img') || fileInput.files.length > 0) ? "inline-block" : "none";
    };

    fileInput.addEventListener('change', function () {

        const file = fileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            showPreviewImage(event.target.result);
            updateClearButtonVisibility();
        };
        reader.readAsDataURL(file);
        deleteImageInput.value = "0";
    });

    clearButton.addEventListener('click', function () {
        fileInput.value = "";
        deleteImageInput.value = "1";
        previewArea.innerHTML = "";
        updateClearButtonVisibility();
    });

    updateClearButtonVisibility();
}

function showPreviewImage(imageSrc) {
    const previewArea = document.getElementById("imagePreview");
    if (previewArea) {
        previewArea.innerHTML = "";
        const img = document.createElement("img");
        img.src = imageSrc;
        img.style.display = "block";
        img.style.maxWidth = "100px";
        previewArea.appendChild(img);
        previewArea.style.display = "block";
    }
}

function restoreImageOnValidationError() {
    const tempImagePath = document.getElementById('tempImagePath');
    if (tempImagePath && tempImagePath.value) {
        showPreviewImage(tempImagePath.value);
    }
}

// ============================
// 手動でファイル選択ボタンを Enter/Space で操作可能にする
// ============================

document.querySelector('.custom-file-label').addEventListener('keydown', function(event) {
    if (event.key === "Enter" || event.key === " ") {
        document.getElementById(this.getAttribute('for')).click();
    }
});

// DOMContentLoaded 時の初期化
document.addEventListener('DOMContentLoaded', function () {
    initImageHandling();
    restoreImageOnValidationError();
});