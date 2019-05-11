<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<form method="post" action="<?= WP_PLUGIN_URL . '/moskee-tv-scherm/' ?>update_user.php" enctype="multipart/form-data" id="uploadForm">
    <input type="hidden" name="user_id" value="<?=$user->ID ?>">
    <table style="border-spacing: 2px; border-collapse: separate;">
        <tr>
            <td>
                Vakkleur: 
            </td>
            <td>
                <input name="vakkleur" class="jscolor" onchange="update_vakkleur(this.jscolor)" value="#<?= $user->vakkleur ?>">
            </td>
            <td>
                Kleur letters vakken: 
            </td>
            <td>
                <input name="lettertype_kleur_vak" class="jscolor" onchange="update_vak_letter_kleur(this.jscolor)" value="#<?= $user->lettertype_kleur_vak ?>">
            </td>
        </tr>
        <tr>
            <td>
                Achtergrond lettertype kleur:
            </td>
            <td>
                <input name="lettertype_kleur" class="jscolor" onchange="update_lettertype_kleur(this.jscolor)" value="#<?= $user->lettertype_kleur ?>">
            </td>
            <td>
                Kleur letters nieuws balk
            </td>
            <td>
                <input name="nieuws_balk_letter_kleur" class="jscolor" onchange="update_nieuws_balk_letter_kleur(this.jscolor)" value="#<?= $user->nieuws_balk_letter_kleur ?>">
            </td>
        </tr>
        <tr>
            <td>
                Achtergrond Afbeelding<br>(alleen voor preview echte upload moet via profiel)
            </td>
            <td>
                <input type="file" name="file" id="file" />
            </td>
            <td>
                Klok
            </td>
            <td>
                <input id='r1' type='radio' class='rg' name="klok_weergeven" value="on" checked="checked"> Weergeven - 
                <input id='r2' type='radio' class='rg' name="klok_weergeven" value="off"> Verbergen
            </td>

            <td>

            </td>
            <td>
                <input type="submit" name="submit" value="Wijzigen"/>
            </td>
        </tr>
    </table>
</form>