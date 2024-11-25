document.getElementById('toggle-password').addEventListener('click', function () {
    const passwordField = document.getElementById('login-password');
    const passwordType = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', passwordType);

    const icon = this.querySelector('img');
    icon.src = passwordType === 'password' ? '../icons/visibility_on.png' : '../icons/visibility_off.png';
});
