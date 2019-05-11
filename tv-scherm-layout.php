<?php
/* Template Name: Layout TV Scherm*/

function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2) . str_repeat(substr($hex, 1, 1), 2) . str_repeat(substr($hex, 2, 1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
        $color = hexdec($color); // Convert to decimal
        $color = max(0, min(255, $color + $steps)); // Adjust color
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
}

$user = get_user_by('login', $_GET["naam"]);
$login = $_GET["naam"];
$vakkleur = $user->vakkleur;
$lichtkleur = adjustBrightness($vakkleur, 80);
$lettertype_kleur = $user->lettertype_kleur;
//echo $vakkleur."<br>";
//echo $lichtkleur;

if ($user->logo_moskee == "") {
    die("Deze moskee is nog niet geregistreerd");
}
//echo $user->achtergrond;
$logo = wp_get_attachment_image_src($user->logo_moskee, 'full', false);

if ($user->achtergrond != "") {
    $achtergrond = wp_get_attachment_image_src($user->achtergrond, 'full', false);
}


$css = WP_PLUGIN_URL . '/moskee-tv-scherm/css';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title><?= $moskee_naam; ?></title>

        <!-- Reset -->
        <link href="<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>css/reset.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>css/bootstrap.min.css" rel="stylesheet">
        <!-- Style sheet -->
        <link href="<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>css/style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
        <?php if($_GET["mode"]=="edit"){ echo '<script src="'. WP_PLUGIN_URL . '/moskee-tv-scherm/js/jscolor.js"></script>'; }?>
        <script>
            $(function () {
                var blinking=false;
                var gebedsnaam;
                var gebedstijd;
                var refresh=true;
                var fajr;
                var shuroeq;
                var duhr;
                var asr;
                var maghreb;
                var isha;
                var active;
                var adhan;
                var style=true;
                var currentDate = new Date();
                var dd = String(currentDate.getDate()).padStart(2, '0');
                var mm = String(currentDate.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = currentDate.getFullYear();
                getGebedstijden(yyyy+"-"+mm+"-"+dd);
                
                function getGebedstijden(date){
                    $.getJSON("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>gebedstijden.php?moskee_tijd=<?= urlencode($user->gebedstijden_locatie) ?>&datum="+date, function (data)
                    {
                        fajr=data['fajr']['time'];
                        shuroeq=data['shuroeq']['time'];
                        duhr=data['duhr']['time'];
                        asr=data['asr']['time'];
                        maghreb=data['maghreb']['time'];
                        ishaa=data['ishaa']['time'];
                        $("#fajr").html(data['fajr']['time']);
                        $("#shuroeq").html(data['shuroeq']['time']);
                        $("#duhr").html(data['duhr']['time']);
                        $("#asr").html(data['asr']['time']);
                        $("#maghreb").html(data['maghreb']['time']);
                        $("#ishaa").html(data['ishaa']['time']);
                        style=true;
                    });
                }
                
                
                $("#tekst-balk").load("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>tekst_ophalen.php?moskee=<?= $login ?>", function () {});
                $("#euro-date").load("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>euro_date.php", function () {});
                $("#hijri-date").load("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>hijri_date.php", function () {});

                setInterval(function () {
                    $("#tekst-balk").load("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>tekst_ophalen.php?moskee=<?= $login ?>", function () {});
                }, 60000);

                setInterval(function () {
                    $("#euro-date").load("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>euro_date.php", function () {});
                }, 60000);

                setInterval(function () {
                    $("#hijri-date").load("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>hijri_date.php", function () {});
                }, 60000);

                $.getJSON("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>temperatuur.php?plaats=<?= $user->plaats ?>", function (data) {
                    var intvalue = Math.floor(data['main']['temp']);
                    $("#temperatuur").html(intvalue + '&#730;');
                    var src = data['weather'][0]['icon'];
                    $("#temperatuur").append("<img style='height:9vh;' src='<?= WP_PLUGIN_URL . '/moskee-tv-scherm/img/weather/' ?>" + src + ".svg'/>");
                });

                setInterval(function () {
                    $.getJSON("<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>temperatuur.php?plaats=<?= $user->plaats ?>", function (data) {
                        var intvalue = Math.floor(data['main']['temp']);
                        $("#temperatuur").html(intvalue + '&#730;');
                        var src = data['weather'][0]['icon'];
                        $("#temperatuur").append("<img style='height:9vh;' src='<?= WP_PLUGIN_URL . '/moskee-tv-scherm/img/weather/' ?>" + src + ".svg'/>");
                    });
                }, 60000);
                
                setInterval(function () {
                    
                    var d = new Date();
                    var dd = String(d.getDate()).padStart(2, '0');
                    var mm = String(d.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = d.getFullYear();
                    var h = String(d.getHours()).padStart(2, '0');
                    var m = String(d.getMinutes()).padStart(2, '0');
                    
                    
                    //Checken welke gebeden zijn geweest en welke nog niet.
                    var hm = Math.round(d.getTime() / 60000);
                    var fajr_tijd = new Date(yyyy+"-"+mm+"-"+dd+" "+fajr);
                    fajr_m=Math.round(fajr_tijd.getTime()/60000);
                    var shuroeq_tijd = new Date(yyyy+"-"+mm+"-"+dd+" "+shuroeq);
                    shuroeq_m=Math.round(shuroeq_tijd.getTime()/60000);                    
                    var duhr_tijd = new Date(yyyy+"-"+mm+"-"+dd+" "+duhr);
                    duhr_m=Math.round(duhr_tijd.getTime()/60000);     
                    var asr_tijd = new Date(yyyy+"-"+mm+"-"+dd+" "+asr);
                    asr_m=Math.round(asr_tijd.getTime()/60000);     
                    var maghreb_tijd = new Date(yyyy+"-"+mm+"-"+dd+" "+maghreb);
                    maghreb_m=Math.round(maghreb_tijd.getTime()/60000);     
                    var ishaa_tijd = new Date(yyyy+"-"+mm+"-"+dd+" "+ishaa);
                    ishaa_m=Math.round(ishaa_tijd.getTime()/60000);
                                        
                    if(hm<fajr_m){
                        if(style==true){
                            $("#div_fajr").attr( "class", "active" );
                            style=false;
                        }
                        //teller tot adhan berekenen
                        gebedsnaam="Fajr";
                        gebedstijd = fajr_tijd;
                    }
                    else if(hm==fajr_m){
                        style=true
                        $("#div_fajr").attr( "class", "flash" );
                    }
                    else if(hm<shuroeq_m){
                        if(style==true){
                            $("#div_fajr").attr( "class", "old" );
                            $("#div_shuroeq").attr( "class", "active" );
                            style=false;
                        }
                        gebedsnaam="Shuroeq";
                        gebedstijd = shuroeq_tijd;
                    }
                    else if(hm==shuroeq_m){
                        style=true
                        $("#div_fajr").attr( "class", "old" );
                        $("#div_shuroeq").attr( "class", "flash" );
                    }
                    else if(hm<duhr_m){
                        if(style==true){
                            $("#div_fajr").attr( "class", "old" );
                            $("#div_shuroeq").attr( "class", "old" );
                            $("#div_duhr").attr( "class", "active" );
                            style=false;
                        }
                        gebedsnaam="Duhr";
                        gebedstijd = duhr_tijd;
                    }
                    else if(hm==duhr_m){
                        style=true
                        $("#div_fajr").attr( "class", "old" );
                        $("#div_shuroeq").attr( "class", "old" );
                        $("#div_duhr").attr( "class", "flash" );
                    }
                    else if(hm<asr_m){
                        if(style==true){
                            $("#div_fajr").attr( "class", "old" );
                            $("#div_shuroeq").attr( "class", "old" );
                            $("#div_duhr").attr( "class", "old" );
                            $("#div_asr").attr( "class", "active" );
                            style=false;
                        }
                        gebedsnaam="Asr";
                        gebedstijd = asr_tijd;
                    }
                    else if(hm==asr_m){
                        style=true
                        $("#div_fajr").attr( "class", "old" );
                        $("#div_shuroeq").attr( "class", "old" );
                        $("#div_duhr").attr( "class", "old" );
                        $("#div_asr").attr( "class", "flash" );
                    }
                    else if(hm<maghreb_m){
                        if(style==true){
                            $("#div_fajr").attr( "class", "old" );
                            $("#div_shuroeq").attr( "class", "old" );
                            $("#div_duhr").attr( "class", "old" );
                            $("#div_asr").attr( "class", "old" );
                            $("#div_maghreb").attr( "class", "active" );
                            style=false;
                        }
                        gebedsnaam="Maghreb";
                        gebedstijd = maghreb_tijd;
                    }
                    else if(hm==maghreb_m){
                        style=true
                        $("#div_fajr").attr( "class", "old" );
                        $("#div_shuroeq").attr( "class", "old" );
                        $("#div_duhr").attr( "class", "old" );
                        $("#div_asr").attr( "class", "old" );
                        $("#div_maghreb").attr( "class", "flash" );
                    }
                    else if(hm<ishaa_m){
                        if(style==true){
                            $("#div_fajr").attr( "class", "old" );
                            $("#div_shuroeq").attr( "class", "old" );
                            $("#div_duhr").attr( "class", "old" );
                            $("#div_asr").attr( "class", "old" );
                            $("#div_maghreb").attr( "class", "old" );
                            $("#div_ishaa").attr( "class", "active" );
                            style=false;
                        }
                        gebedsnaam="Ishaa";
                        gebedstijd = ishaa_tijd;
                    }
                    else if(hm==ishaa_m){
                        style=true
                        $("#div_fajr").attr( "class", "old" );
                        $("#div_shuroeq").attr( "class", "old" );
                        $("#div_duhr").attr( "class", "old" );
                        $("#div_asr").attr( "class", "old" );
                        $("#div_maghreb").attr( "class", "old" );
                        $("#div_ishaa").attr( "class", "flash" );
                    }else{
                        if(style==true){
                            $("#div_fajr").attr( "class", "active" );
                            $("#div_shuroeq").attr( "class", "" );
                            $("#div_duhr").attr( "class", "" );
                            $("#div_asr").attr( "class", "" );
                            $("#div_maghreb").attr( "class", "" );
                            $("#div_ishaa").attr( "class", "" );
                            
                            //De gebedstijden voor de volgende dag binnenhalen.
                            var newDate = new Date(new Date().getTime() + 24 * 60 * 60 * 1000);
                            var dd = String(newDate.getDate()).padStart(2, '0');
                            var mm = String(newDate.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyy = newDate.getFullYear();
                            getGebedstijden(yyyy+"-"+mm+"-"+dd);
                            gebedsnaam="Fajr";
                            var fajr_tijd = new Date(yyyy+"-"+mm+"-"+dd+" "+fajr);
                            gebedstijd = fajr_tijd;
                            style=false;
                        }
                        
                    }

                    $("#adhan-naam").html("Adhaan "+gebedsnaam);
                    
                    var date = new Date(new Date(gebedstijd)).valueOf();
                    //alert(d.getTime());
                    var tijdover = Math.round((date - d.getTime()) / 1000);
                    hours = Math.floor(tijdover / 3600);

                    tijdover %= 3600;
                    minutes = Math.floor(tijdover / 60);
                    var minutes = ("0" + minutes).slice(-2);
                    seconds = tijdover % 60;
                    var seconds = ("0" + seconds).slice(-2);
                    if (hours == 0) {
                        hours = '';
                    } else {
                        hours = hours + ':';
                    }
                    if (minutes == 0) {
                        minutes = '00:';
                    } else {
                        minutes = minutes + ':';
                    }

                    $('#gebedstijd').html(hours + minutes + seconds);

                    $('#h').html(h);
                    $('#m').html(m);

                }, 1000);

            });
            
        </script>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php if($_GET["mode"]=="edit"){ echo '<script src="'. WP_PLUGIN_URL . '/moskee-tv-scherm/js/edit.js"></script>'; }?>
        <style>
            <?php if ($user->achtergrond != ""): ?>
            
                body{
                    background-image: url(<?= $achtergrond[0] ?>);
                    color:<?= $lettertype_kleur ?>;
                }
                
            <?php endif; ?>
            <?php if ($user->vakkleur != ""): ?>
                
                .temperatuur{
                    background: <?= $user->vakkleur ?>;
                    background: -moz-linear-gradient(left, <?= $vakkleur ?> 0%, <?= $lichtkleur ?> 100%); /* FF3.6-15 */
                    background: -webkit-linear-gradient(left, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%); /* Chrome10-25,Safari5.1-6 */
                    background: linear-gradient(to right, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%);
                    color: <?= $user->lettertype_kleur_vak ?>;
                }
                
                #adhan-naam{
                    background: <?= $user->vakkleur ?>;
                    background: -moz-linear-gradient(left, <?= $vakkleur ?> 0%, <?= $lichtkleur ?> 100%); /* FF3.6-15 */
                    background: -webkit-linear-gradient(left, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%); /* Chrome10-25,Safari5.1-6 */
                    background: linear-gradient(to right, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%);
                    color: <?= $user->lettertype_kleur_vak ?>;
                }
                
                .active{
                    background: <?= $user->vakkleur ?>;
                    background: -moz-linear-gradient(left, <?= $vakkleur ?> 0%, <?= $lichtkleur ?> 100%); /* FF3.6-15 */
                    background: -webkit-linear-gradient(left, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%); /* Chrome10-25,Safari5.1-6 */
                    background: linear-gradient(to right, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%);
                    color: <?= $user->lettertype_kleur_vak ?>;
                }
                
                .news-title{
                    background: <?= $user->vakkleur ?>;
                    background: -moz-linear-gradient(left, <?= $vakkleur ?> 0%, <?= $lichtkleur ?> 100%); /* FF3.6-15 */
                    background: -webkit-linear-gradient(left, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%); /* Chrome10-25,Safari5.1-6 */
                    background: linear-gradient(to right, <?= $vakkleur ?> 0%,<?= $lichtkleur ?> 100%);
                    color: <?= $user->lettertype_kleur_vak ?>;
                }
            <?php endif; ?>
            <?php if ($user->nieuws_balk_letter_kleur != ""): ?>
                #tekst-balk{
                    color:<?=$user->nieuws_balk_letter_kleur?>;
                }
            <?php endif; ?>    
        </style>
    </head>
    <body>
        <div class="mw-logo"></div>
        
        <div class="top row" style='background-color:none; color: #6c757d;padding-left:50px; font-size: 10px;'>
            <?php if($_GET["mode"]=="edit"){ include "edit.php"; }?>
        </div>
        <div class="hfd_balk row">
            <div class="col-md-6 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-8">
                                <div>
                                    <div class="row">
                                        
                                    </div>
                                </div>
                                <span id="adhan-naam" class="adhan_gebed"></span>
                                <div>
                                    <div class="row">
                                        <!-- <span  class="aftellen_adhan"><i class="fas fa-arrow-down"></i></span> -->
                                        <span id="gebedstijd" class="aftellen_adhan"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-8">
                                <span id="temperatuur" class="temperatuur"></span>
                                <br><br>
                                <span class="datum_voluit" id="euro-date"></span><br>
                                <span class="datum_voluit" id="hijri-date"></span>
                            </div>
                            <div class="col-md-2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="gebedsrij" class="row gb_balk">
            <div class="col-md-2 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div id="div_ishaa" class="col-md-12">
                                <span class="gebedsnaam">Ishaa <i class="fas fa-caret-down"></i> العشاء</span><br>
                                <span id="ishaa" class="gebedstijd ishaa"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div id="div_maghreb" class="col-md-12">
                                <span class="gebedsnaam">Maghreb <i class="fas fa-caret-down"></i> المغرب</span><br>
                                <span id="maghreb" class="gebedstijd maghreb"> </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div id="div_asr" class="col-md-12">
                                <span class="gebedsnaam">Asr <i class="fas fa-caret-down"></i> العصر</span><br>
                                <span id="asr" class="gebedstijd asr"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div id="div_duhr" class="col-md-12">
                                <span class="gebedsnaam">Duhr <i class="fas fa-caret-down"></i> الظهر</span><br>
                                <span id="duhr" class="gebedstijd dhoehr"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div id="div_shuroeq" class="col-md-12">
                                <span class="gebedsnaam">Shuroeq <i class="fas fa-caret-down"></i> الشروق</span><br>
                                <span id="shuroeq" class="gebedstijd shoeroeq"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 border">
                <div class="vak">
                    <div class="centeralign">
                        <div class="row">
                            <div id="div_fajr" class="col-md-12">
                                <span class="gebedsnaam">Fajr <i class="fas fa-caret-down"></i> الفجر</span><br>
                                <span id="fajr" class="gebedstijd fadjr"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row ft_balk">
                <div class="col-md-2 news-title" style="padding:0px;-webkit-transform: skew(-20deg);-moz-transform: skew(-20deg);-o-transform: skew(-20deg);">
                    <?php if($user->klok_weergeven=="off"):?>
                    <span class="clock"><span id="h"></span><span id="time_seperator">:</span><span id="m"></span></span>
                    <?php else:?>
                    Nieuws
                    <?php endif;?>
                </div>
                <div class="col-md-8">
                    <div class="nieuws-bericht">
                        <span style="display:inline;" id="tekst-balk"></span>
                    </div>
                </div>
                <div class="col-md-2 logo-div">
                    <img class="img-responsive" style="max-width:100%;height:100%; float:right; padding:5px;" src="<?= $logo[0] ?>" />
                </div>
            </div>
            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>js/bootstrap.min.js"></script>
    </body>
</html>