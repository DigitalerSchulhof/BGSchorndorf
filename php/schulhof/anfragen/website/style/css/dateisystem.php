<?php
fwrite($hell, ".cms_dateisystem_tabelle {");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_box {");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad, .cms_dateisystem_aktionen, .cms_dateisystem_inhalt {");
fwrite($hell, "margin: 5px 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt {");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "max-height: 150px;");
fwrite($hell, "overflow-y: scroll;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_icon {");
fwrite($hell, "padding: 2px 5px 5px 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "line-height: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_aktionen {");
fwrite($hell, "padding: 4px 5px 0px 5px;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_aktionen:first-child {");
fwrite($hell, "margin-right: 20px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_icon img {");
fwrite($hell, "position: relative;");
fwrite($hell, "top: 2px;");
fwrite($hell, "margin-right: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_icon:hover, .cms_dateisystem_pfad_aktionen:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_icon:hover {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt table {");
fwrite($hell, "width: 100%;");
fwrite($hell, "border-spacing: 0px;");
fwrite($hell, "border-collapse: collapse;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt table td:first-child {");
fwrite($hell, "width: 16px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt table td:first-child img {");
fwrite($hell, "top: 2px;");
fwrite($hell, "position: relative;");
fwrite($hell, "max-width: none !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt table td {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt table td:last-child {");
fwrite($hell, "text-align: right;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt table td:last-child .cms_hinweis {");
fwrite($hell, "text-align: right;");
fwrite($hell, "left: auto;");
fwrite($hell, "right: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_inhalt table tr:hover td {");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhintergrund'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_status {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_status p {");
fwrite($hell, "font-size: 75%;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_aktionen {");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_pfad_aktionen:hover .cms_hinweis {");
fwrite($hell, "display: block;");
fwrite($hell, "bottom: 40px;");
fwrite($hell, "left: 0px;");
fwrite($hell, "}");

fwrite($hell, "td.cms_dateisystem_meldung {");
fwrite($hell, "text-align: center!important;");
fwrite($hell, "}");

fwrite($hell, "td.cms_dateisystem_ordner:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_laden {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "margin-bottom: 5px;");
fwrite($hell, "text-align: center;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_meldung {");
fwrite($hell, "padding: 10px;");
fwrite($hell, "margin-bottom: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_aktionen_neuerordner, .cms_dateisystem_aktionen_hochladen {");
fwrite($hell, "padding: 5px;");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_uploadzone {");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($hell, "height: 100px;");
fwrite($hell, "width: 100%;");
fwrite($hell, "margin-top: 6px;");
fwrite($hell, "border: 1px dashed ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($hell, "background-image: url('../res/icons/gross/dateiupload.png');");
fwrite($hell, "background-position: center 25px;");
fwrite($hell, "background-repeat: no-repeat;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_uploadzone p {");
fwrite($hell, "text-align: center;");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($hell, "font-size: 90%;");
fwrite($hell, "margin-top: 60px !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_uploadzone:hover {");
fwrite($hell, "cursor: move;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste li {");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste .cms_button_nein {");
fwrite($hell, "position: absolute;");
fwrite($hell, "left: -22px;");
fwrite($hell, "top: -3px;");
fwrite($hell, "opacity: 0;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste li:hover .cms_button_nein {");
fwrite($hell, "opacity: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste li, #cms_hochladen_fehlgeschlagen_liste {");
fwrite($hell, "font-size: 90%;");
fwrite($hell, "}");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste {");
fwrite($hell, "margin-top: 0px !important;");
fwrite($hell, "max-height: 50px;");
fwrite($hell, "overflow-y: scroll;");
fwrite($hell, "padding: 5px;");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhintergrund'].";");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_upload_dateiknopf {");
fwrite($hell, "position: relative;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "}");

fwrite($hell, ".cms_upload_dateiknopf:hover .cms_hinweis {");
fwrite($hell, "display: block;");
fwrite($hell, "right: 20px !important;");
fwrite($hell, "bottom: -2px;");
fwrite($hell, "}");

fwrite($hell, ".cms_upload_dateiknopf:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, "#cms_hochladen_fehlgeschlagen {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($hell, "padding: 5px;");
fwrite($hell, "border-radius: 5px;");
fwrite($hell, "display: none;");
fwrite($hell, "margin-top: 20px;");
fwrite($hell, "}");

fwrite($hell, ".cms_datei_gewaehlt {");
fwrite($hell, "font-size: ".$_POST['cms_style_haupt_schriftgroesse'].";");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_datei_gewaehlt img {");
fwrite($hell, "position: relative;");
fwrite($hell, "bottom: -4px;");
fwrite($hell, "}");






// DARKMODE
fwrite($dunkel, ".cms_dateisystem_box {");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_pfad, .cms_dateisystem_aktionen, .cms_dateisystem_inhalt {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_pfad_icon:hover, .cms_dateisystem_pfad_aktionen:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_pfad_icon:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_inhalt table tr:hover td {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_status {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_uploadzone {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";");
fwrite($dunkel, "border: 1px dashed ".$_POST['cms_style_h_haupt_meldunginfohinter'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_uploadzone p {");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_dateisystem_hochladen_dateiliste {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "#cms_hochladen_fehlgeschlagen {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";");
fwrite($dunkel, "}");
?>
