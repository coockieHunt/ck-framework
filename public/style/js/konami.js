let keyKonami = [38, 38, 40, 40, 37, 39, 37, 39, 66, 65], n = 0;

let konami = function (e) {
    if (e.keyCode === keyKonami[n++]) {
        if (n === keyKonami.length) {
            alert('Konami !!!');
            n = 0;
            return false;
        }
    }else {n = 0;}
};

$(document).keydown(konami);

