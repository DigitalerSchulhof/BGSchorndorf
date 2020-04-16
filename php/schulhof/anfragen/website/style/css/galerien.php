<?php
fwrite($hell, ".cms_galerie_liste {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_liste li {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink {\n");
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

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink h3 {\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink p,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink h3,\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink span,\n");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink p,\n");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink h3,\n");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink span {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "text-overflow: ellipsis;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_notiz {margin-top: 0px;}\n");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink p:last-child {margin-bottom: 0px;}\n");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink:hover .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_galerielink_vorschaubild {\n");
fwrite($hell, "float: right;\n");
fwrite($hell, "margin-left: 5px;\n");
fwrite($hell, "width: 30%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_galerieuebersicht_artikel li,\n");
fwrite($hell, ".cms_optimierung_T .cms_galerieuebersicht_artikel li {\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(odd),\n");
fwrite($hell, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(odd) {\n");
fwrite($hell, "border-right: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(even),\n");
fwrite($hell, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(even) {\n");
fwrite($hell, "border-left: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H .cms_galerieuebersicht_artikel li {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel li {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: nowrap;\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "height: 100%;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "padding: 5px 10px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink h3 {\n");
fwrite($hell, "font-size: 110%;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "margin-top: 2px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink .cms_notiz {margin-top: 0px;}\n");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink p:last-child {margin-bottom: 0px;}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink:hover {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink:hover .cms_button {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";\n");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink .cms_galerielink_vorschaubild {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");






// DARKMODE
fwrite($dunkel, ".cms_galerie_liste {\n");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_galerieuebersicht_liste li {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink p,\n");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink h3,\n");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink span,\n");
fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink p,\n");
fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink h3,\n");
fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink span {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink:hover .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(odd),\n");
fwrite($dunkel, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(odd) {\n");
fwrite($dunkel, "border-right: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(even),\n");
fwrite($dunkel, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(even) {\n");
fwrite($dunkel, "border-left: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink {\n");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink:hover {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink:hover .cms_button {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";\n");
fwrite($dunkel, "}\n");
?>
