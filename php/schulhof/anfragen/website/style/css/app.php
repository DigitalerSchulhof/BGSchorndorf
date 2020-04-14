<?php
fwrite($hell, "body.cms_seite_app {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund']." !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_seite_app #cms_kopfzeile_i {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_seite_app #cms_logo {");
fwrite($hell, "position: static;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: left;");

fwrite($hell, "#cms_logo_bild {");
fwrite($hell, "float: left;");
fwrite($hell, "padding-right: 10px;");
fwrite($hell, "width: ".$_POST['cms_style_logo_breite'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_logo_schrift {");
fwrite($hell, "float: left;");
fwrite($hell, "display: ".$_POST['cms_style_logo_anzeige']." !important;");

fwrite($hell, "#cms_logo_o, #cms_logo_u {");
fwrite($hell, "position: relative;");
fwrite($hell, "color: ".$_POST['cms_style_h_logo_schriftfarbe'].";");
fwrite($hell, "font-size: 170%;");
fwrite($hell, "padding: 2px 0px 0px 0px;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, "#cms_logo_o {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, "#cms_appnavigation {");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrund']." !important;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "padding: 4px 10px;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "line-height: 20px;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "user-select: none;");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: 10px;");
fwrite($hell, "top: 10px;");
fwrite($hell, "}");

fwrite($hell, "#cms_appzurueck {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "position: absolute;");
fwrite($hell, "right: 10px;");
fwrite($hell, "bottom: 10px;");
fwrite($hell, "}");

fwrite($hell, "#cms_appnavigation:hover {");
fwrite($hell, "cursor: pointer !important;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_mobilnavigation_iconhintergrundhover']." !important;");
fwrite($hell, "}");

fwrite($hell, "#cms_appmenue_a {");
fwrite($hell, "border-left: 10px solid ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "display: none;");
fwrite($hell, "padding: 10px 10px 20px 10px;");
fwrite($hell, "position: fixed;");
fwrite($hell, "right: 0px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "width: 80%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "z-index: 10;");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_appmenue_a .cms_uebersicht li {");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_appmenue_a .cms_uebersicht li:first-child {");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_appmenue_schliessen {");
fwrite($hell, "position: fixed;");
fwrite($hell, "width: 20%;");
fwrite($hell, "height: 100px;");
fwrite($hell, "line-height: 100px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "left: 0px;");
fwrite($hell, "top: 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "font-size: 500%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, "#cms_appmenue_schliessen:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_appmenue_uliste {");
fwrite($hell, "display: inline-block !important;");
fwrite($hell, "padding: 3px 0px 3px 0px !important;");
fwrite($hell, "margin: 0px 15px 0px 0px !important;");
fwrite($hell, "border: none !important;");
fwrite($hell, "min-height: auto !important;");
fwrite($hell, "background: none !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_appmenue_uliste:hover {background: none !important;}");

fwrite($hell, "#cms_app_impressum {");
fwrite($hell, "text-align: right;");
fwrite($hell, "padding: 20px 0px 0px 15%;");
fwrite($hell, "font-size: 70%;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");




// DARKMODE
fwrite($dunkel, "body.cms_seite_app {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_seite_app #cms_logo #cms_logo_schrift #cms_logo_o,");
fwrite($dunkel, ".cms_seite_app #cms_logo #cms_logo_schrift #cms_logo_u {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_logo_schriftfarbe'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_appnavigation {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrund']." !important;");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_appnavigation:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_mobilnavigation_iconhintergrundhover']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_appmenue_a {");
fwrite($dunkel, "border-left: 10px solid ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_appmenue_a .cms_uebersicht li {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_appmenue_a .cms_uebersicht li:first-child {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_appmenue_schliessen {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_appmenue_schliessen:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_app_impressum {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");
?>
