/**
 * Escuchamos el elemento del selector del lenguage y así no utilizamos onclick
 */
var selectlanguage = document.getElementById("select-language");
selectlanguage.addEventListener("change", changeLanguage);
function changeLanguage() {
    var language = selectlanguage.options[selectlanguage.selectedIndex].value;
    window.location.href = "/" + language;
}