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
            console.log(r.responseText);
            var data = JSON.parse(r.responseText);
            if (data['status'] == 'error') {
                console.log(data['errores']);
                document.getElementById("notification-ajax-login").text = '';
                document.getElementById("notification-ajax-login").style.display = "block";
                var elem = document.querySelector('#notification-ajax-login');
                elem.textContent = data['errores'];
            } else {
                window.location.href = data['UrlAnterior'];
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

/**
 * Evento registro ajax
 */
document.getElementById('registrate-ajax').onclick=function(){
    document.getElementById("notification-ajax-registro").style.display = "none";
    var email = document.getElementById("email_registro").value;
    var password = document.getElementById("password_registro").value;
    var paisId = document.getElementById("select-pais").value;
    var errores = erroresRegistro(email, password, paisId);
    $('#notification-ajax-registro').empty();
    $('#notification-ajax-registro').hide();
    if (errores.length == 0) {
        var r = new XMLHttpRequest();
        r.open("POST", "/usuarios/registroAjax", true);
        r.onreadystatechange = function () {
        if (r.readyState != 4 || r.status != 200) return;
            console.log(r.responseText);
            var data = JSON.parse(r.responseText);
            if (data['status'] == 'error') {
                document.getElementById("notification-ajax-registro").text = '';
                document.getElementById("notification-ajax-registro").style.display = "block";
                var elem = document.querySelector('#notification-ajax-registro');
                elem.textContent = data['errores'];
            } else {
                // TODO: redirección a la página que estaba pero ya con login
            }
        };
        r.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        r.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=utf-8');
        r.send("email="+email+"&password="+password+"&paisId="+paisId+"&email_repite="+emailRepite);
    } else {
        $('#notification-ajax-registro').show();
        $('#notification-ajax-registro').html(errores);
    }
}

function erroresRegistro(email, password, paisId) {
    var errores = [];
    if (email.length == 0) {
        errores.push('Tienes que introducir un email.</br>');
    } else {
        if (!validarEmail(email)) errores.push('Introduce un email correcto.</br>');
    }
    if (password.length == 0) errores.push('Tienes que introducir una contraseña.</br>');
    if (paisId.length == 0) errores.push('Tienes que elegir un país.</br>');
    return errores;
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