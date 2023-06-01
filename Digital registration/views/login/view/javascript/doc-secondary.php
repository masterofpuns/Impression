// FUNCTIES
function submitLoginForm(e) {
    var form = $('#form-login');
    form.submit();
}
function onKeyUpLoginFields(e) {
    e.preventDefault();

    if (e.keyCode == 13) {
        submitLoginForm();
    }
}

// LISTENERS
$('body').on('click', '[data-hook="registration-submit_login_form"]', submitLoginForm);
$('body').on('keyup', 'input[name="Login[identification]"], input[name="Login[password]"]', onKeyUpLoginFields);