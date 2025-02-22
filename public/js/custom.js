document.addEventListener("DOMContentLoaded", function () {
    // ✅ 成功メッセージ
    let flashMessage = document.getElementById("flashMessage");
    if (flashMessage) {
        Swal.fire({
            title: "成功",
            text: flashMessage.innerText,
            icon: "success",
            toast: true,
            position: "top-end",
            showConfirmButton: false, // ❌ 確認ボタンなし
            timer: 5000 // ✅ 5秒後に自動で消える
        });
    }

    // ✅ エラーメッセージ
    let errorMessage = document.getElementById("errorMessage");
    if (errorMessage) {
        Swal.fire({
            title: "システムエラー",
            text: errorMessage.innerText,
            icon: "error",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000
        });
    }

    // ✅ バリデーションエラー通知
    let validationErrorFlag = document.getElementById("validationErrorFlag");
    if (validationErrorFlag) {
        Swal.fire({
            title: "入力エラー",
            text: "入力内容を確認してください。",
            icon: "warning",
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000
        });
    }
});
