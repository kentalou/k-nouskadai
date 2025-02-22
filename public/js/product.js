document.addEventListener('DOMContentLoaded', function () {
    initImageHandling();
    restoreImageOnValidationError();
});

document.addEventListener('DOMContentLoaded', function () {
    // 🔥 削除ボタン押下時の確認ダイアログ
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function (event) {
            event.preventDefault(); // フォーム送信を一旦停止
            const confirmDelete = confirm("本当に削除しますか？");
            if (confirmDelete) {
                this.closest('form').submit(); // ✅ OKならフォーム送信
            }
        });
    });
});

/**
 * 🛠 画像のプレビュー＆クリアボタン制御
 */
function initImageHandling() {
    const fileInput = document.getElementById('image');
    const previewArea = document.getElementById('imagePreview');
    const clearButton = document.getElementById('clearImage');
    const deleteImageInput = document.querySelector('input[name="delete_image"]');

    if (!fileInput || !previewArea || !clearButton) return;

    // 🔥 画像の有無でクリアボタンの表示を切り替え
    const updateClearButtonVisibility = () => {
        clearButton.style.display = (previewArea.querySelector('img') || fileInput.files.length > 0) ? "inline-block" : "none";
    };

    // ✅ 画像プレビュー表示
    fileInput.addEventListener('change', function () {
        const file = fileInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            showPreviewImage(event.target.result);
            updateClearButtonVisibility(); // 🔹 クリアボタンの表示状態を更新
        };
        reader.readAsDataURL(file);

        // 🔹 画像が選択されたので、削除フラグをリセット
        deleteImageInput.value = "0";
    });

    // 🔥 クリアボタン押下時の処理
    clearButton.addEventListener('click', function () {
        fileInput.value = ""; // 🔹 ファイル選択をリセット（見た目としてはクリア）
        deleteImageInput.value = "1"; // 🔹 削除フラグをセット（Laravel 側で削除処理を行う）
        previewArea.innerHTML = ""; // 🔹 プレビューを非表示
        updateClearButtonVisibility(); // 🔹 クリアボタンの表示を更新

        console.log("✅ 画像クリア完了：プレビュー削除 & 削除フラグセット");
    });

    // ✅ 初回チェック（ページロード時）
    updateClearButtonVisibility();
}

/**
 * 🛠 画像プレビューエリアに画像を表示
 * @param {string} imageSrc - 表示する画像の src
 */
function showPreviewImage(imageSrc) {
    const previewArea = document.getElementById("imagePreview");

    if (previewArea) {
        previewArea.innerHTML = "";
        const img = document.createElement("img");
        img.src = imageSrc;
        img.style.display = "block"; // inline設定により期待しない表示になる可能性があるため上書き設定
        img.style.maxWidth = "100px"; 
        previewArea.appendChild(img);
        previewArea.style.display = "block"; // 🔥 ここで input-fieldに設定されているデフォルト表示削除用の`display: none;` を解除
    }
}

/**
 * 🛠 バリデーションエラー時に Laravel セッションの画像をプレビュー表示
 */
function restoreImageOnValidationError() {
    const tempImagePath = document.getElementById('tempImagePath');
    if (tempImagePath && tempImagePath.value) {
        showPreviewImage(tempImagePath.value);
    }
}

// ✅ 手動でファイル選択ボタンを Enter で操作可能にする
document.querySelector('.custom-file-label').addEventListener('keydown', function(event) {
    if (event.key === "Enter" || event.key === " ") { 
        document.getElementById(this.getAttribute('for')).click();
    }
});
