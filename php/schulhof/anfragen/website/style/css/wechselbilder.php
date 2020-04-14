<?php
fwrite($hell, ".cms_wechselbilder_o {");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbilder_m {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbilder_bild {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "line-height: 0;");
fwrite($hell, "width: 100%;");
fwrite($hell, "float: left;");
fwrite($hell, "margin-right: -100%;");
fwrite($hell, "position: relative;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "display: block;");
fwrite($hell, "z-index: 1;");
fwrite($hell, "transition: 1s ease-in-out;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbilder_bild > img {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbild_voriges, .cms_wechselbild_naechstes {");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: 0px;");
fwrite($hell, "height: 100%;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_button'].";");
fwrite($hell, "display: block;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "top: 0px;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "width: auto;");
fwrite($hell, "line-height: 100%;");
fwrite($hell, "width: 20px;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbild_voriges {left: 0px;}");
fwrite($hell, ".cms_wechselbild_naechstes {right: 0px;}");

fwrite($hell, ".cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_buttonhover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {");
fwrite($hell, "opacity: 1 !important;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbilder_wahl {");
fwrite($hell, "position: absolute;");
fwrite($hell, "text-align: center;");
fwrite($hell, "top: 15px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "z-index: 2;");
fwrite($hell, "padding: 0px 25px 0px 25px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbilder_wahl span {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_button'].";");
fwrite($hell, "width: ".$_POST['cms_style_galerie_buttonbreite'].";");
fwrite($hell, "height: ".$_POST['cms_style_galerie_buttonhoehe'].";");
fwrite($hell, "border-radius: 10px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "}");

fwrite($hell, "span.cms_wechselbilder_knopf_aktiv {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_buttonaktiv'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbild_voriges,");
fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbild_naechstes,");
fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbilder_knopf {opacity: .5;}");

fwrite($hell, ".cms_wechselbilder_o:hover .cms_wechselbilder_knopf_aktiv {opacity: 1 !important;}");

fwrite($hell, ".cms_wechselbilder_wahl span:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_galerie_buttonhover'].";");
fwrite($hell, "opacity: 1 !important;");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_wechselbilder_galerie_unterschrift {");
fwrite($hell, "width: 100%;");
fwrite($hell, "padding: 5px;");
fwrite($hell, "}");





// DARKMODE
fwrite($dunkel, ".cms_wechselbild_voriges, .cms_wechselbild_naechstes {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_button'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_wechselbild_voriges:hover, .cms_wechselbild_naechstes:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_buttonhover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_wechselbilder_wahl span {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_button'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "span.cms_wechselbilder_knopf_aktiv {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_buttonaktiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_wechselbilder_wahl span:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_galerie_buttonhover'].";");
fwrite($dunkel, "}");
?>
