<?php
fwrite($hell, ".cms_galerie_liste {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_liste li {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink {");
fwrite($hell, "width: 100%;");
fwrite($hell, "display: block;");
fwrite($hell, "padding-top: 5px;");
fwrite($hell, "padding-bottom: 5px;");
fwrite($hell, "padding-right: 5px;");
fwrite($hell, "padding-left: 45px;");
fwrite($hell, "position: relative;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "min-height: 70px;");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink h3 {");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink p,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink h3,");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink span,");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink p,");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink h3,");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink span {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "text-overflow: ellipsis;");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_notiz {margin-top: 0px;}");
fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink p:last-child {margin-bottom: 0px;}");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink:hover .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_liste .cms_galerielink .cms_galerielink_vorschaubild {");
fwrite($hell, "float: right;");
fwrite($hell, "margin-left: 5px;");
fwrite($hell, "width: 30%;");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_artikel {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_galerieuebersicht_artikel li,");
fwrite($hell, ".cms_optimierung_T .cms_galerieuebersicht_artikel li {");
fwrite($hell, "width: 50%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(odd),");
fwrite($hell, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(odd) {");
fwrite($hell, "border-right: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(even),");
fwrite($hell, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(even) {");
fwrite($hell, "border-left: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H .cms_galerieuebersicht_artikel li {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_artikel li {");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: nowrap;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "//border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink {");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 5px 10px;");
fwrite($hell, "position: relative;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink h3 {");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink .cms_notiz {margin-top: 0px;}");
fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink p:last-child {margin-bottom: 0px;}");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink:hover .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_galerieuebersicht_artikel .cms_galerielink .cms_galerielink_vorschaubild {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");






// DARKMODE
fwrite($dunkel, ".cms_galerie_liste {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_galerieuebersicht_liste li {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink p,");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink h3,");
fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink span,");
fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink p,");
fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink h3,");
fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink span {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_galerieuebersicht_liste .cms_galerielink:hover .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(odd),");
fwrite($dunkel, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(odd) {");
fwrite($dunkel, "border-right: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_optimierung_P .cms_galerieuebersicht_artikel li:nth-child(even),");
fwrite($dunkel, ".cms_optimierung_T .cms_galerieuebersicht_artikel li:nth-child(even) {");
fwrite($dunkel, "border-left: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_galerieuebersicht_artikel .cms_galerielink:hover .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");
?>
