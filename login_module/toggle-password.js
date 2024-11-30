document.addEventListener('DOMContentLoaded', () => {
    // Obsługa dla głównego hasła
    const togglePasswordMain = document.getElementById('toggle-password-main');
    const passwordMain = document.getElementById('register-password');
    togglePasswordMain.addEventListener('click', () => {
        const type = passwordMain.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordMain.setAttribute('type', type);

        const icon = togglePasswordMain.querySelector('img');
        icon.src = type === 'password' ? '../icons/visibility_on.png' : '../icons/visibility_off.png';
    });

    // Obsługa dla powtórzenia hasła
    const togglePasswordRepeat = document.getElementById('toggle-password-repeat');
    const passwordRepeat = document.getElementById('register-password-repeat');
    togglePasswordRepeat.addEventListener('click', () => {
        const type = passwordRepeat.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordRepeat.setAttribute('type', type);

        const icon = togglePasswordRepeat.querySelector('img');
        icon.src = type === 'password' ? '../icons/visibility_on.png' : '../icons/visibility_off.png';
    });

});

document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordField = document.getElementById('login-password');
    const passwordType = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', passwordType);

    const icon = this.querySelector('img');
    icon.src = passwordType === 'password' ? '../icons/visibility_on.png' : '../icons/visibility_off.png';
});
