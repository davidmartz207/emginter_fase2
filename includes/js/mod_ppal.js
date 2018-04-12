function rnd_string(long_string){
    long_string         = (long_string == "" ? 10 : long_string);
    var str_rnd_string  = "";
    var possible        = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < long_string; i++){
        str_rnd_string += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return str_rnd_string;
}

function valid_alphanumeric(cadena){
    var validos="abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789.,' ";
    var i;
    var j;
    var coinci=0;
    for(j=0; j<=cadena.length; j++){               
        for(i=0; i<validos.length; i++){
            if(cadena.charAt(j)==validos.charAt(i)){coinci++;}
        }
    }
    return (cadena.length==coinci) ? 1 : 0;
}

function valid_price(cadena){
    var validos="0123456789.,";
    var i;
    var j;
    var coinci=0;
    for(j=0; j<=cadena.length; j++){               
        for(i=0; i<validos.length; i++){
            if(cadena.charAt(j)==validos.charAt(i)){coinci++;}
        }
    }
    return (cadena.length==coinci) ? 1 : 0;
}

function reset_format_price(cadena){
    var result  = "";
    var validos = "0123456789.";
    var j;
    var i;
    for(j=0; j<=cadena.length; j++){               
        for(i=0; i<validos.length; i++){
            if(cadena.charAt(j)==validos.charAt(i)){
                result = result + cadena.charAt(j);
            }
        }
    }
    return result;
}

/*http://jcesar.artelogico.com/2010/08/formato-de-moneda-en-javascript/*/
function currency(value, decimals, separators) {
    decimals = decimals >= 0 ? parseInt(decimals, 0) : 2;
    separators = separators || ['.', "'", ','];
    var number = (parseFloat(value) || 0).toFixed(decimals);
    if (number.length <= (4 + decimals))
        return number.replace('.', separators[separators.length - 1]);
    var parts = number.split(/[-.]/);
    value = parts[parts.length > 1 ? parts.length - 2 : 0];
    var result = value.substr(value.length - 3, 3) + (parts.length > 1 ?
        separators[separators.length - 1] + parts[parts.length - 1] : '');
    var start = value.length - 6;
    var idx = 0;
    while (start > -3) {
        result = (start > 0 ? value.substr(start, 3) : value.substr(0, 3 + start))
            + separators[idx] + result;
        idx = (++idx) % 2;
        start -= 3;
    }
    return (parts.length == 3 ? '-' : '') + result;
}