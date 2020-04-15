<?php
fwrite($hell, ".cms_hinweis_aussen {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "padding-right: 20px;\n");
fwrite($hell, "background-image: url('../res/icons/klein/fragezeichen.png');\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "background-position: right, center;\n");
fwrite($hell, "min-height: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hinweis {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbenegativ']." !important;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";\n");
fwrite($hell, "padding: 0px 5px 0px 5px;\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "font-family: 'robl';\n");
fwrite($hell, "font-weight: normal !important;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_hinweis_radius'].";\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_hinweis_radius'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_hinweis_radius'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_hinweis_radius'].";\n");
fwrite($hell, "z-index: 50;\n");
fwrite($hell, "width: 150px;\n");
fwrite($hell, "overflow: visible;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "bottom: 25px;\n");
fwrite($hell, "transition-delay: 1s;\n");
fwrite($hell, "transition: 250ms ease-in-out 500ms;\n");
fwrite($hell, "max-height: 0px;\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_layout_zeile_plus .cms_hinweis {\n");
fwrite($hell, "bottom: 15px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_layout_spalte_plus .cms_hinweis,\n");
fwrite($hell, ".cms_layout_spalte .cms_hinweis {\n");
fwrite($hell, "bottom: 105px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_button:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_aktion_klein:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_postfach_papierkorb_aussen:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_button_nein:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_button_ja:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_button_passiv:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_icon_klein_o:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_beschluss_icon:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_hinweis_aussen:hover > .cms_hinweis,\n");
fwrite($hell, ".cms_element_icon:hover > .cms_hinweis,\n");
fwrite($hell, "img:hover + .cms_hinweis{\n");
fwrite($hell, "max-height: 100px;\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "z-index: 5;\n");
fwrite($hell, "}\n");

fwrite($hell, "td:last-child .cms_hinweis, .cms_notifikation_schliessen .cms_hinweis,\n");
fwrite($hell, ".cms_beschluss_icon .cms_hinweis, .cms_element_icon .cms_hinweis,\n");
fwrite($hell, ".cms_layout_spalte_plus:last-child .cms_hinweis, .cms_vollbild_schliessen .cms_hinweis,\n");
fwrite($hell, ".cms_neuigkeit_oeffnen .cms_hinweis, .cms_gruppen_oeffentlich_art .cms_hinweis {\n");
fwrite($hell, "right: 0px !important;\n");
fwrite($hell, "left: auto !important;\n");
fwrite($hell, "text-align: right !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_vollbild_schliessen .cms_hinweis {\n");
fwrite($hell, "top: 25px;\n");
fwrite($hell, "bottom: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste .cms_button_nein .cms_hinweis {\n");
fwrite($hell, "left: 25px !important;\n");
fwrite($hell, "top: auto !important;\n");
fwrite($hell, "bottom: 0px !important;\n");
fwrite($hell, "text-align: left !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_icon_klein .cms_hinweis {\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "}\n");




// DARKMODE"
fwrite($dunkel, ".cms_hinweis {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbenegativ']." !important;\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";\n");
fwrite($dunkel, "}\n");
?>
