<?php
fwrite($hell, ".cms_dateisystem_tabelle {\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_box {\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad, .cms_dateisystem_aktionen, .cms_dateisystem_inhalt {\n");
fwrite($hell, "margin: 5px 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt {\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "max-height: 150px;\n");
fwrite($hell, "overflow-y: scroll;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_icon {\n");
fwrite($hell, "padding: 2px 5px 5px 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "line-height: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_aktionen {\n");
fwrite($hell, "padding: 4px 5px 0px 5px;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_aktionen:first-child {\n");
fwrite($hell, "margin-right: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_icon img {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "top: 2px;\n");
fwrite($hell, "margin-right: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_icon:hover, .cms_dateisystem_pfad_aktionen:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_icon:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt table {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "border-spacing: 0px;\n");
fwrite($hell, "border-collapse: collapse;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt table td:first-child {\n");
fwrite($hell, "width: 16px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt table td:first-child img {\n");
fwrite($hell, "top: 2px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "max-width: none !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt table td {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt table td:last-child {\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt table td:last-child .cms_hinweis {\n");
fwrite($hell, "text-align: right;\n");
fwrite($hell, "left: auto;\n");
fwrite($hell, "right: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_inhalt table tr:hover td {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_status {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_status p {\n");
fwrite($hell, "font-size: 75%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_aktionen {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_pfad_aktionen:hover .cms_hinweis {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "bottom: 40px;\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, "td.cms_dateisystem_meldung {\n");
fwrite($hell, "text-align: center!important;\n");
fwrite($hell, "}\n");

fwrite($hell, "td.cms_dateisystem_ordner:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_laden {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "margin-bottom: 5px;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_meldung {\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "margin-bottom: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_aktionen_neuerordner, .cms_dateisystem_aktionen_hochladen {\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_uploadzone {\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($hell, "height: 100px;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "margin-top: 6px;\n");
fwrite($hell, "border: 1px dashed ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";\n");
fwrite($hell, "background-image: url('../res/icons/gross/dateiupload.png');\n");
fwrite($hell, "background-position: center 25px;\n");
fwrite($hell, "background-repeat: no-repeat;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_uploadzone p {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "font-size: 90%;\n");
fwrite($hell, "margin-top: 60px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_uploadzone:hover {\n");
fwrite($hell, "cursor: move;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste li {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste .cms_button_nein {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "left: -22px;\n");
fwrite($hell, "top: -3px;\n");
fwrite($hell, "opacity: 0;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste li:hover .cms_button_nein {\n");
fwrite($hell, "opacity: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste li, #cms_hochladen_fehlgeschlagen_liste {\n");
fwrite($hell, "font-size: 90%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_dateisystem_hochladen_dateiliste {\n");
fwrite($hell, "margin-top: 0px !important;\n");
fwrite($hell, "max-height: 50px;\n");
fwrite($hell, "overflow-y: scroll;\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_feldhintergrund'].";\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_upload_dateiknopf {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_upload_dateiknopf:hover .cms_hinweis {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "right: 20px !important;\n");
fwrite($hell, "bottom: -2px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_upload_dateiknopf:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_hochladen_fehlgeschlagen {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "border: 1px solid ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($hell, "padding: 5px;\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "margin-top: 20px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_datei_gewaehlt {\n");
fwrite($hell, "font-size: ".$_POST['cms_style_haupt_schriftgroesse'].";\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_datei_gewaehlt img {\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "bottom: -4px;\n");
fwrite($hell, "}\n");






// DARKMODE
fwrite($dunkel, ".cms_dateisystem_box {\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_pfad, .cms_dateisystem_aktionen, .cms_dateisystem_inhalt {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_pfad_icon:hover, .cms_dateisystem_pfad_aktionen:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_pfad_icon:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_inhalt table tr:hover td {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_status {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_uploadzone {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_h_haupt_meldunginfoakzent'].";\n");
fwrite($dunkel, "border: 1px dashed ".$_POST['cms_style_h_haupt_meldunginfohinter'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_uploadzone p {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_dateisystem_hochladen_dateiliste {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_feldhintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "#cms_hochladen_fehlgeschlagen {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";\n");
fwrite($dunkel, "border: 1px solid ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}\n");
?>
