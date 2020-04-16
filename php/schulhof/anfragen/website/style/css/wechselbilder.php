<?php
fwrite($hell, ".cms_wechselbilder_o {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbilder_m {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbilder_bild {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "line-height: 0;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "margin-right: -100%;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "z-index: 1;\n");
fwrite($hell, "transition: 1s ease-in-out;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbilder_bild > img {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbild_voriges, .cms_wechselbild_naechstes {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_button'].";\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "top: 0px;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "width: auto;\n");
fwrite($hell, "line-height: 100%;\n");
fwrite($hell, "width: 20px;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbild_voriges {left: 0px;}\n");
fwrite($hell, ".cms_wechselbild_naechstes {right: 0px;}\n");

fwrite($hell, ".cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_buttonhover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {\n");
fwrite($hell, "opacity: 1 !important;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbilder_wahl {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "top: 15px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "z-index: 2;\n");
fwrite($hell, "padding: 0px 25px 0px 25px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbilder_wahl span {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_button'].";\n");
fwrite($hell, "width: ".$_POST['cms_style_galerie_buttonbreite'].";\n");
fwrite($hell, "height: ".$_POST['cms_style_galerie_buttonhoehe'].";\n");
fwrite($hell, "border-radius: 10px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "}\n");

fwrite($hell, "span.cms_wechselbilder_knopf_aktiv {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_buttonaktiv'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbild_voriges,\n");
fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbild_naechstes,\n");
fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbilder_knopf {opacity: .5;}\n");

fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbilder_knopf_aktiv {opacity: 1 !important;}\n");

fwrite($hell, ".cms_wechselbilder_wahl span:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_buttonhover'].";\n");
fwrite($hell, "opacity: 1 !important;\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_wechselbilder_galerie_unterschrift {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "}\n");





// DARKMODE
fwrite($dunkel, ".cms_wechselbild_voriges, .cms_wechselbild_naechstes {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_button'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_buttonhover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_wechselbilder_wahl span {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_button'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "span.cms_wechselbilder_knopf_aktiv {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_buttonaktiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_wechselbilder_wahl span:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_buttonhover'].";\n");
fwrite($dunkel, "}\n");
?>
