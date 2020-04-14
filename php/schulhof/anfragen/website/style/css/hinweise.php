<?php
fwrite($hell, ".cms_hinweis_aussen {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "position: relative;");
fwrite($hell, "padding-right: 20px;");
fwrite($hell, "background-image: url('../res/icons/klein/fragezeichen.png');");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "background-position: right, center;");
fwrite($hell, "min-height: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_hinweis {");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;");
fwrite($hell, "background: ".$_POST['cms_style_h_hinweis_hintergrund'].";");
fwrite($hell, "padding: 0px 5px 0px 5px;");
fwrite($hell, "position: absolute;");
fwrite($hell, "font-family: 'robl';");
fwrite($hell, "font-weight: normal !important;");
fwrite($hell, "display: block;");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_hinweis_radius'].";");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_hinweis_radius'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_hinweis_radius'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_hinweis_radius'].";");
fwrite($hell, "z-index: 50;");
fwrite($hell, "width: 150px;");
fwrite($hell, "overflow: visible;");
fwrite($hell, "left: 0px;");
fwrite($hell, "bottom: 25px;");
fwrite($hell, "transition-delay: 1s;");
fwrite($hell, "transition: 250ms ease-in-out 500ms;");
fwrite($hell, "max-height: 0px;");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "text-align: left;");
fwrite($hell, "}");

fwrite($hell, ".cms_layout_zeile_plus .cms_hinweis {");
fwrite($hell, "bottom: 15px;");
fwrite($hell, "}");

fwrite($hell, ".cms_layout_spalte_plus .cms_hinweis,");
fwrite($hell, ".cms_layout_spalte .cms_hinweis {");
fwrite($hell, "bottom: 105px;");
fwrite($hell, "}");

fwrite($hell, ".cms_button:hover > .cms_hinweis,");
fwrite($hell, ".cms_aktion_klein:hover > .cms_hinweis,");
fwrite($hell, ".cms_postfach_papierkorb_aussen:hover > .cms_hinweis,");
fwrite($hell, ".cms_button_nein:hover > .cms_hinweis,");
fwrite($hell, ".cms_button_ja:hover > .cms_hinweis,");
fwrite($hell, ".cms_button_passiv:hover > .cms_hinweis,");
fwrite($hell, ".cms_icon_klein_o:hover > .cms_hinweis,");
fwrite($hell, ".cms_beschluss_icon:hover > .cms_hinweis,");
fwrite($hell, ".cms_hinweis_aussen:hover > .cms_hinweis,");
fwrite($hell, ".cms_element_icon:hover > .cms_hinweis,");
fwrite($hell, "img:hover + .cms_hinweis{");
fwrite($hell, "max-height: 100px;");
fwrite($hell, "padding: 5px;");
fwrite($hell, "z-index: 5;");
fwrite($hell, "}");

fwrite($hell, "td:last-child .cms_hinweis, .cms_notifikation_schliessen .cms_hinweis,");
fwrite($hell, ".cms_beschluss_icon .cms_hinweis, .cms_element_icon .cms_hinweis,");
fwrite($hell, ".cms_layout_spalte_plus:last-child .cms_hinweis, .cms_vollbild_schliessen .cms_hinweis,");
fwrite($hell, ".cms_neuigkeit_oeffnen .cms_hinweis, .cms_gruppen_oeffentlich_art .cms_hinweis {");
fwrite($hell, "right: 0px !important;");
fwrite($hell, "left: auto !important;");
fwrite($hell, "text-align: right !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_vollbild_schliessen .cms_hinweis {");
fwrite($hell, "top: 25px;");
fwrite($hell, "bottom: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste .cms_button_nein .cms_hinweis {");
fwrite($hell, "left: 25px !important;");
fwrite($hell, "top: auto !important;");
fwrite($hell, "bottom: 0px !important;");
fwrite($hell, "text-align: left !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_icon_klein .cms_hinweis {");
fwrite($hell, "left: 0px;");
fwrite($hell, "text-align: left;");
fwrite($hell, "}");




// DARKMODE"
fwrite($dunkel, ".cms_hinweis {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;");
fwrite($dunkel, "background: ".$_POST['cms_style_d_hinweis_hintergrund'].";");
fwrite($dunkel, "}");
?>
