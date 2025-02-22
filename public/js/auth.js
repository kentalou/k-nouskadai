document.addEventListener('DOMContentLoaded', function () {
    // ✅ パスワード確認フィールドの一致チェック
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    const errorMessageElement = document.getElementById('password-error');

    if (confirmPasswordField) {
        confirmPasswordField.addEventListener('input', function () {
            if (passwordField.value !== confirmPasswordField.value) {
                errorMessageElement.textContent = 'パスワードが一致しません。';
            } else {
                errorMessageElement.textContent = '';
            }
        });
    }

    // ✅ メール入力の自動補完防止
    const emailField = document.querySelector('input[name="email"]');
    if (emailField) {
        emailField.setAttribute('placeholder', 'example@example.com');
    }
});
