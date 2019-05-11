
function ColorLuminance(hex, lum) {
    // validate hex string
    hex = String(hex).replace(/[^0-9a-f]/gi, '');
    if (hex.length < 6) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    lum = lum || 0;

    // convert to decimal and change luminosity
    var rgb = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i * 2, 2), 16);
        c = Math.min(Math.max(0, c + lum), 255).toString(16);
        rgb += ("00" + c).substr(c.length);
    }

    return rgb;
}

function update_vakkleur(jscolor) {
    // 'jscolor' instance can be used as a string
    var vakkleur = "#" + jscolor;
    var lichtkleur = ColorLuminance(vakkleur, 80)

    $('#adhan-naam').css("background", "#" + jscolor);
    $('#adhan-naam').css("background", "-moz-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('#adhan-naam').css("background", "-webkit-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('#adhan-naam').css("background", "linear-gradient(to right, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('#temperatuur').css("background", "#" + jscolor);
    $('#temperatuur').css("background", "-moz-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('#temperatuur').css("background", "-webkit-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('#temperatuur').css("background", "linear-gradient(to right, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('.active').css("background", "#" + jscolor);
    $('.active').css("background", "-moz-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('.active').css("background", "-webkit-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('.active').css("background", "linear-gradient(to right, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('.news-title').css("background", "#" + jscolor);
    $('.news-title').css("background", "-moz-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('.news-title').css("background", "-webkit-linear-gradient(left, " + vakkleur + " 0%, " + lichtkleur + " 100%");
    $('.news-title').css("background", "linear-gradient(to right, " + vakkleur + " 0%, " + lichtkleur + " 100%");
}

function update_lettertype_kleur(jscolor) {
    var lettertype = "#" + jscolor;
    $('body').css("color", lettertype);
}

function update_vak_letter_kleur(jscolor) {
    $('#adhan-naam').css("color", "#" + jscolor);
    $('#temperatuur').css("color", "#" + jscolor);
    $('.active').css("color", "#" + jscolor);
    $('.news-title').css("color", "#" + jscolor);
}

function update_nieuws_balk_letter_kleur(jscolor) {
    $('#tekst-balk').css("color", "#" + jscolor);
}

function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            //$('#uploadForm + img').remove();
            //$('#uploadForm').after('<img src="'+e.target.result+'" width="450" height="300"/>');
            $('body').css("background-image", "url(" + e.target.result + ")");
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(function () {
$(".top").css("background", "white");
var $_GET = {};
if(document.location.toString().indexOf('?') !== -1) {
    var query = document.location
                   .toString()
                   // get the query string
                   .replace(/^.*?\?/, '')
                   // and remove any existing hash string (thanks, @vrijdenker)
                   .replace(/#.*$/, '')
                   .split('&');

    for(var i=0, l=query.length; i<l; i++) {
       var aux = decodeURIComponent(query[i]).split('=');
       $_GET[aux[0]] = aux[1];
    }
}
//get the 'index' query parameter
$_GET['index'];

if("off"=="<?= $user->klok_weergeven?>"){
    $('#tijdstip').hide();
    $('#r2').prop('checked', true);
}

$("#file").change(function () {
    filePreview(this);
});

$(".rg").change(function () {

    selected_value = $("input[name='klok_weergeven']:checked").val();
    if(selected_value==="off"){
        //alert("iets gebeurt");
        $('#tijdstip').hide();
    }
    else {
        $('#tijdstip').show();
    }
});


});