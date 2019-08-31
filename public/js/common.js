/**
 * Escuchamos el elemento del selector del lenguage y así no utilizamos onchange el cual no podemos con volt
 */
var selectlanguage = document.getElementById("select-language");
selectlanguage.addEventListener("change", changeLanguage);
function changeLanguage() {
    console.log('ftgf');
    var language = selectlanguage.options[selectlanguage.selectedIndex].value;
    window.location.href = "/" + language;
}