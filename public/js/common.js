/**
 * Escuchamos el elemento del selector del lenguage y así no utilizamos onchange el cual no podemos con volt
 */
var selectlanguage = document.getElementById("select-language");
selectlanguage.addEventListener("change", changeLanguage);
function changeLanguage() {
    var language = selectlanguage.options[selectlanguage.selectedIndex].value;
    window.location.href = "/" + language;
}

/**
 * Evento login ajax
 */
document.getElementById('login-ajax').onclick=function(){
    document.getElementById("notification-ajax-login").style.display = "none";
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var errores = erroresLogin(email, password);
    $('#notification-ajax-login').empty();
    $('#notification-ajax-login').hide();
    if (errores.length == 0) {
        var r = new XMLHttpRequest();
        r.open("POST", "/usuarios/loginAjax", true);
        r.onreadystatechange = function () {
        if (r.readyState != 4 || r.status != 200) return;
            var data = JSON.parse(r.responseText);
            if (data['status'] == 'error') {
                console.log(data['errores']);
                document.getElementById("notification-ajax-login").text = '';
                document.getElementById("notification-ajax-login").style.display = "block";
                var elem = document.querySelector('#notification-ajax-login');
                elem.textContent = data['errores'];
            } else {
                // TODO: redirección a la página que estaba pero ya con login
            }
        };
        r.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
        r.send("email="+email+"&password="+password);
    } else {
        $('#notification-ajax-login').show();
        $('#notification-ajax-login').html(errores);
    }
}

function erroresLogin(email, password) {
    var errores = [];
    if (email.length == 0) {
        errores.push('Tienes que introducir un email.</br>');
    } else {
        if (!validarEmail(email)) errores.push('Introduce un email correcto.</br>');
    }
    if (password.length == 0) errores.push('Tienes que introducir una contraseña.</br>');
    return errores;
}

function validarEmail(email) {
    var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email) ? true : false;
}