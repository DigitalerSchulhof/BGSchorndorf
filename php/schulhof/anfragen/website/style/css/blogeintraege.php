<?php
fwrite($hell, ".cms_bloguebersicht_liste {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste li {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink {");
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

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink h3 {");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink p,");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink p,");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink p,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink h3,");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink h3,");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink h3,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink span,");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink span,");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink span {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "text-overflow: ellipsis;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_notiz {margin-top: 0px;}");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink p:last-child {margin-bottom: 0px;}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink:hover .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblaetter {");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: 5px;");
fwrite($hell, "top: 5px;");
fwrite($hell, "width: 32px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_i {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "width: 32px;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_monat,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagnr,");
fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_tagbez {");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "width: 100%;");
fwrite($hell, "line-height: 1.2em !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_kalenderblatt_uhrzet {");
fwrite($hell, "display: block;");
fwrite($hell, "text-align: center;");
fwrite($hell, "font-weight: normal;");
fwrite($hell, "font-size: 80%;");
fwrite($hell, "line-height: 1.2em;");
fwrite($hell, "padding: 5px 0px 0px 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_liste .cms_bloglink .cms_bloglink_vorschaubild {");
fwrite($hell, "float: right;");
fwrite($hell, "margin-left: 5px;");
fwrite($hell, "width: 30%;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_artikel {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_bloguebersicht_artikel li,");
fwrite($hell, ".cms_optimierung_T .cms_bloguebersicht_artikel li {");
fwrite($hell, "width: 50%;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(odd),");
fwrite($hell, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(odd) {");
fwrite($hell, "border-right: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(even),");
fwrite($hell, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(even) {");
fwrite($hell, "border-left: 5px solid ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H .cms_bloguebersicht_artikel li {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_artikel li {");
fwrite($hell, "flex-wrap: nowrap;");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "//border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: block;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink {");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 5px 10px;");
fwrite($hell, "position: relative;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink h3 {");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink .cms_notiz {margin-top: 0px;}");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink p:last-child {margin-bottom: 0px;}");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");
fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink:hover .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_artikel .cms_bloglink .cms_bloglink_vorschaubild {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_diashow {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "border-top: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-bottom: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "display: block;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_wechselbilder_m {margin: 0px !important;}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_wechselbilder_wahl {top: 35px !important;}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink {");
fwrite($hell, "margin: 0px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "height: 100%;");
fwrite($hell, "display: block;");
fwrite($hell, "padding: 5px 10px;");
fwrite($hell, "position: relative;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "text-align: left !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink h3 {");
fwrite($hell, "font-size: 110%;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "margin-top: 2px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink h3+img {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink .cms_notiz {margin-top: 0px;}");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink p:last-child {margin-bottom: 0px;}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink:hover {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");
fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink:hover .cms_button {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_button_hintergrundhover'].";");
fwrite($hell, "color: ".$_POST['cms_style_h_button_schrifthover'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_bloguebersicht_diashow .cms_bloglink .cms_bloglink_vorschaubild {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");






// DARKMODE
fwrite($dunkel, ".cms_bloguebersicht_liste {");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_liste li {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink p,");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink p,");
fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink p,");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink h3,");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink h3,");
fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink h3,");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink span,");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink span,");
fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink span {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");
fwrite($dunkel, ".cms_bloguebersicht_liste .cms_bloglink:hover .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(odd),");
fwrite($dunkel, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(odd) {");
fwrite($dunkel, "border-right: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_optimierung_P .cms_bloguebersicht_artikel li:nth-child(even),");
fwrite($dunkel, ".cms_optimierung_T .cms_bloguebersicht_artikel li:nth-child(even) {");
fwrite($dunkel, "border-left: 5px solid ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");
fwrite($dunkel, ".cms_bloguebersicht_artikel .cms_bloglink:hover .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_diashow {");
fwrite($dunkel, "border-top: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "border-bottom: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink:hover {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_bloguebersicht_diashow .cms_bloglink:hover .cms_button {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_button_hintergrundhover'].";");
fwrite($dunkel, "color: ".$_POST['cms_style_d_button_schrifthover'].";");
fwrite($dunkel, "}");
?>
