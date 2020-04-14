<?php
fwrite($hell, ".cms_bloguebersicht_liste {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste li {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding-top: 5px;\n");
fwrite($hell, "padding-bottom: 5px;\n");
fwrite($hell, "padding-right: 5px;\n");
fwrite($hell, "padding-left: 45px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "min-height: 70px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink h3 {\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink p,\n");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink p,\n");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink p,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink h3,\n");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink h3,\n");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink h3,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink span,\n");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink span,\n");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink span {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "text-overflow: ellipsis;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_notiz {margin-top: 0px;}\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink p:last-child {margin-bottom: 0px;}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink:hover .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblaetter {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: 5px;\n");
fwrite($hell, "top: 5px;\n");
fwrite($hell, "width: 32px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_i {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "width: 32px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,\n");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "line-height: 1.2em !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_uhrzet {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "font-weight: normal;\n");
fwrite($hell, "font-size: 80%;\n");
fwrite($hell, "line-height: 1.2em;\n");
fwrite($hell, "padding: 5px 0px 0px 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_bloglink_vorschaubild {\n");
fwrite($hell, "float: right;\n");
fwrite($hell, "margin-left: 5px;\n");
fwrite($hell, "width: 30%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_artikel {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_bloguebersicht_artikel li,\n");
fwrite($hell, ".cms_optimierung_T .cms_bloguebersicht_artikel li {\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(odd),\n");
fwrite($hell, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(odd) {\n");
fwrite($hell, "border-right: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(even),\n");
fwrite($hell, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(even) {\n");
fwrite($hell, "border-left: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H .cms_bloguebersicht_artikel li {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_artikel li {\n");
fwrite($hell, "flex-wrap: nowrap;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "//border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 5px 10px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink h3 {\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink .cms_notiz {margin-top: 0px;}\n");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink p:last-child {margin-bottom: 0px;}\n");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink:hover .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink .cms_bloglink_vorschaubild {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_diashow {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_wechselbilder_m {margin: 0px !important;}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_wechselbilder_wahl {top: 35px !important;}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink {\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 5px 10px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "text-align: left !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink h3 {\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink h3+img {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink .cms_notiz {margin-top: 0px;}\n");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink p:last-child {margin-bottom: 0px;}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink:hover .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink .cms_bloglink_vorschaubild {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");






// DARKMODE
fwrite($dunkel, ".cms_bloguebersicht_liste {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_liste li {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink p,\n");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink p,\n");
fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink p,\n");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink h3,\n");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink h3,\n");
fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink h3,\n");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink span,\n");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink span,\n");
fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink span {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink:hover .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(odd),\n");
fwrite($dunkel, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(odd) {\n");
fwrite($dunkel, "border-right: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(even),\n");
fwrite($dunkel, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(even) {\n");
fwrite($dunkel, "border-left: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink:hover .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_diashow {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink:hover .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");
?>
