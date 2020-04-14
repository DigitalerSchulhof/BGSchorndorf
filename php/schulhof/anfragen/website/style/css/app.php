<?php
fwrite($hell, "body.cms_seite_app {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seite_app #cms_kopfzeile_i {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_seite_app #cms_logo {\n");
fwrite($hell, "position: static;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_bild {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "padding-right: 10px;\n");
fwrite($hell, "width: ".$_POST['cms_style_logo_breite'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_schrift {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "display: ".$_POST['cms_style_logo_anzeige']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_o, #cms_logo_u {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_logo_schriftfarbe'].";\n");
fwrite($hell, "font-size: 170%;\n");
fwrite($hell, "padding: 2px 0px 0px 0px;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_logo_o {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appnavigation {\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrund']." !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;\n");
fwrite($hell, "padding: 4px 10px;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "line-height: 20px;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "user-select: none;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "top: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appzurueck {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "bottom: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appnavigation:hover {\n");
fwrite($hell, "cursor: pointer !important;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrundhover']." !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appmenue_a {\n");
fwrite($hell, "border-left: 10px solid ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "padding: 10px 10px 20px 10px;\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "right: 0px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "width: 80%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "z-index: 10;\n");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appmenue_a .cms_uebersicht li {\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appmenue_a .cms_uebersicht li:first-child {\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appmenue_schliessen {\n");
fwrite($hell, "position: fixed;\n");
fwrite($hell, "width: 20%;\n");
fwrite($hell, "height: 100px;\n");
fwrite($hell, "line-height: 100px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "font-size: 500%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_appmenue_schliessen:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_appmenue_uliste {\n");
fwrite($hell, "display: inline-block !important;\n");
fwrite($hell, "padding: 3px 0px 3px 0px !important;\n");
fwrite($hell, "margin: 0px 15px 0px 0px !important;\n");
fwrite($hell, "border: none !important;\n");
fwrite($hell, "min-height: auto !important;\n");
fwrite($hell, "background: none !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_appmenue_uliste:hover {background: none !important;}\n");

fwrite($hell, "#cms_app_impressum {\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "padding: 20px 0px 0px 15%;\n");
fwrite($hell, "font-size: 70%;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");




// DARKMODE
fwrite($dunkel, "body.cms_seite_app {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_seite_app #cms_logo #cms_logo_schrift #cms_logo_o,\n");
fwrite($dunkel, ".cms_seite_app #cms_logo #cms_logo_schrift #cms_logo_u {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_logo_schriftfarbe'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_appnavigation {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrund']." !important;\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_appnavigation:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrundhover']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_appmenue_a {\n");
fwrite($dunkel, "border-left: 10px solid ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_appmenue_a .cms_uebersicht li {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_appmenue_a .cms_uebersicht li:first-child {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_appmenue_schliessen {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_appmenue_schliessen:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_app_impressum {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");
?>
