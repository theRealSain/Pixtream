function toggleForms() {
    var login = document.getElementById('login');
    var register = document.getElementById('register');
    if (login.style.display === 'none') {
        login.style.display = 'block';
        register.style.display = 'none';
    } else {
        login.style.display = 'none';
        register.style.display = 'block';
    }
}