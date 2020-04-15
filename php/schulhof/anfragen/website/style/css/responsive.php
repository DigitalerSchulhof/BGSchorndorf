<?php
fwrite($hell, "@media (max-width: 999px) {\n");
fwrite($hell, ".cms_optimierung_P #cms_kopfzeile_m,\n");
fwrite($hell, ".cms_optimierung_P #cms_hauptteil_m,\n");
fwrite($hell, ".cms_optimierung_P #cms_fusszeile_m,\n");
fwrite($hell, ".cms_optimierung_P #cms_website_bearbeiten_m {\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "margin: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_P .cms_unternavigation_m {\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "margin: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_aktionsschicht_m {width: 100%;}\n");
fwrite($hell, "}\n");

fwrite($hell, "@media (max-width: 799px) {\n");
fwrite($hell, ".cms_kopfnavigation {display: none !important;}\n");
fwrite($hell, "#cms_kopfnavigation {display: none !important;}\n");
fwrite($hell, "#cms_hauptnavigation {display: none !important;}\n");
fwrite($hell, "#cms_mobilnavigation {display: block !important;}\n");

fwrite($hell, ".cms_bloguebersicht_artikel {\n");
fwrite($hell, "display: block !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_bloguebersicht_artikel li {\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "border-left: none !important;\n");
fwrite($hell, "border-right: none !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "@media (max-width: 599px) {\n");
fwrite($hell, ".cms_eventuebersicht_box_a {\n");
fwrite($hell, "float: none !important;\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "margin-bottom: 30px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "ul.cms_uebersicht a p img {\n");
fwrite($hell, "height: 100px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit {\n");
fwrite($hell, "width: 50% !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_voranmeldung_navigation li {\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_eventuebersicht_box_blog, .cms_eventuebersicht_box_termine,\n");
fwrite($hell, ".cms_eventuebersicht_box_galerie {width: 100% !important;}\n");

fwrite($hell, ".cms_eventuebersicht_box_i {padding: 0px;}\n");

fwrite($hell, ".cms_optimierung_P .cms_spalte_2, .cms_optimierung_P .cms_spalte_3,\n");
fwrite($hell, ".cms_optimierung_P .cms_spalte_60, .cms_optimierung_P .cms_spalte_40,\n");
fwrite($hell, ".cms_optimierung_P .cms_spalte_4, .cms_optimierung_P .cms_spalte_34,\n");
fwrite($hell, ".cms_optimierung_P .cms_spalte_6, .cms_optimierung_P .cms_spalte_15,\n");
fwrite($hell, ".cms_optimierung_P .cms_spalte_25,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_2, .cms_optimierung_T .cms_spalte_3,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_60, .cms_optimierung_T .cms_spalte_40,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_4, .cms_optimierung_T .cms_spalte_34,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_6 .cms_optimierung_T .cms_spalte_15,\n");
fwrite($hell, ".cms_optimierung_T .cms_spalte_25 {\n");
fwrite($hell, "float: none !important;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "padding-bottom: 40px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_anschlag_aussen {\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termindetailansicht_kalenderblaetter_o,\n");
fwrite($hell, ".cms_termindetailansicht_details_o {\n");
fwrite($hell, "float: none !important;\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "margin-bottom: 20px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_termindetailansicht_kalenderblaetter_i,\n");
fwrite($hell, ".cms_termindetailansicht_details_i {\n");
fwrite($hell, "padding: 0px !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_fussnavigation {\n");
fwrite($hell, "padding-right: 0px !important;\n");
fwrite($hell, "min-height: auto !important;\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auszeichnung {\n");
fwrite($hell, "position: static !important;\n");
fwrite($hell, "top: auto !important;\n");
fwrite($hell, "right: auto !important;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof']." !important;\n");
fwrite($hell, "text-align: center  !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "#cms_fusszeile_i .cms_notiz {text-align: center;}\n");

fwrite($hell, "#cms_blende_m {width: 100%;}\n");

fwrite($hell, ".cms_sidebar_inhalt {display: none !important;}\n");
fwrite($hell, ".cms_hauptteil_inhalt {width: 100% !important;}\n");
fwrite($hell, ".cms_kopfnavigation {display: none !important;}\n");
fwrite($hell, "#cms_kopfnavigation {display: none !important;}\n");
fwrite($hell, "#cms_hauptnavigation {display: none !important;}\n");
fwrite($hell, "#cms_mobilnavigation {display: block !important;}\n");

fwrite($hell, ".cms_optimierung_P ul.cms_uebersicht a p img,\n");
fwrite($hell, ".cms_optimierung_T ul.cms_uebersicht a p img,\n");
fwrite($hell, ".cms_optimierung_H ul.cms_uebersicht a p img {\n");
fwrite($hell, "width: 100% !important;\n");
fwrite($hell, "height: auto !important;\n");
fwrite($hell, "margin-left: 0px !important;\n");
fwrite($hell, "float: none !important;\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
fwrite($hell, "}\n");
?>
