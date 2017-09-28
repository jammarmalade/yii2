function isUndefined(variable) {
    return typeof variable == 'undefined' ? true : false;
}
function mb_strlen(str) {
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? 3 : 1;
    }
    return len;
}