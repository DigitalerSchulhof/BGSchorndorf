<?php
fwrite($hell, "td.cms_notiz {\n");
fwrite($hell, "text-align: center !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "th.cms_zwischenueberschrift,\n");
fwrite($hell, "th.cms_zwischenueberschrift *  {\n");
fwrite($hell, "text-align: center !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, "table .cms_senkrecht {\n");
fwrite($hell, "writing-mode: vertical-rl;\n");
fwrite($hell, "transform: rotate(180deg);\n");
fwrite($hell, "font-weight: inherit;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular {\n");
fwrite($hell, "border-collapse: collapse;\n");
fwrite($hell, "border-spacing: 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_hintergrund'].";\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular td {\n");
fwrite($hell, "line-height: 2em;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular th {\n");
fwrite($hell, "padding-top: 10px !important;\n");
fwrite($hell, "line-height: 1.5em;\n");
fwrite($hell, "vertical-align: top !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular tbody, .cms_formular thead {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "}\n");

fwrite($hell, "/*.cms_formular tbody tr th:first-child,\n");
fwrite($hell, ".cms_liste tbody tr th:first-child {\n");
fwrite($hell, "width: 33%;\n");
fwrite($hell, "}*/\n");

fwrite($hell, ".cms_formular th {\n");
fwrite($hell, "vertical-align: top;\n");
fwrite($hell, "padding: 5px 7px;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular th * {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular th .cms_hinweis_aussen {\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular td {\n");
fwrite($hell, "vertical-align: top;\n");
fwrite($hell, "font-weight: normal;\n");
fwrite($hell, "padding: 5px 7px;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_formular td > img {\n");
fwrite($hell, "top: 5px;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_liste {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "border-collapse: collapse;\n");
fwrite($hell, "border-spacing: 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "border-top: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;\n");
fwrite($hell, "border-bottom: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;\n");
fwrite($hell, "margin-bottom: 7px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_liste tr.min,.cms_liste  td.min {\n");
fwrite($hell, "width: 1%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_liste th {\n");
fwrite($hell, "vertical-align: middle;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "padding: 3px 7px;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "border-top: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;\n");
fwrite($hell, "border-bottom: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;\n");
fwrite($hell, "line-height: 1.5em;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_liste td {\n");
fwrite($hell, "font-weight: normal;\n");
fwrite($hell, "padding: 3px 7px;\n");
fwrite($hell, "text-align: left;\n");
fwrite($hell, "border-bottom: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_liste thead th, .cms_liste tr:first-child th, .cms_liste tr:first-child td {\n");
fwrite($hell, "border-top: none !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_liste tbody tr:hover td, .cms_liste tbody tr:hover th {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_postfach_liste .cms_postfach_vorschau {\n");
fwrite($hell, "border-top: none;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_postfach_nachricht_lesen:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_schulhof_nutzerkonto_profildaten_mehrF, .cms_schulhof_verwaltung_personen_details_mehrF,\n");
fwrite($hell, ".cms_website_seiten_fortgeschritten_mehrF {\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_tabelle_zwischentitel {\n");
fwrite($hell, "font-weight: bold !important;\n");
fwrite($hell, "text-align: center !important;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "table.cms_zeitwahl {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "border-collapse: collapse;\n");
fwrite($hell, "border-spacing: collapse;\n");
fwrite($hell, "}\n");

fwrite($hell, "table.cms_zeitwahl td {width: auto;}\n");
fwrite($hell, "table.cms_zeitwahl td:nth-child(2) {text-align: center !important;}\n");
fwrite($hell, "table.cms_zeitwahl td:nth-child(3) {text-align: right;}\n");

fwrite($hell, "table.table {\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "border-collapse: collapse;\n");
fwrite($hell, "border-spacing: collapse;\n");
fwrite($hell, "}\n");

fwrite($hell, "table.table td {\n");
fwrite($hell, "border: none !important;\n");
fwrite($hell, "padding: 3px;\n");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_hintergrund']." !important;\n");
fwrite($hell, "background-color: #dddddd;\n");
fwrite($hell, "}\n");

fwrite($hell, "table.table td:last-child {\n");
fwrite($hell, "border-right: none !important;\n");
fwrite($hell, "}\n");

fwrite($hell, "table.table tr:nth-child(2n+1) td {\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_formular_feldhintergrund'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "th.cms_zwischenueberschrift {\n");
fwrite($hell, "text-align: center;\n");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, "th.cms_zahl, td.cms_zahl {\n");
fwrite($hell, "text-align: right !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_sortieren:hover {\n");
fwrite($hell, "cursor: s-resize;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_auswaehlen:hover {\n");
fwrite($hell, "cursor: crosshair;\n");
fwrite($hell, "}\n");







// DARKMODE
fwrite($dunkel, "td.cms_notiz {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "th.cms_zwischenueberschrift,\n");
fwrite($dunkel, "th.cms_zwischenueberschrift *  {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_formular {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_liste {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "border-top: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;\n");
fwrite($dunkel, "border-bottom: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_liste th {\n");
fwrite($dunkel, "border-top: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;\n");
fwrite($dunkel, "border-bottom: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_liste td {\n");
fwrite($dunkel, "border-bottom: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_liste tbody tr:hover td, .cms_liste tbody tr:hover th {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_postfach_liste .cms_postfach_vorschau {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_tabelle_zwischentitel {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "table.table td {\n");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_hintergrund']." !important;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "table.table tr:nth-child(2n+1) td {\n");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_formular_feldhintergrund'].";\n");
fwrite($dunkel, "}\n");
?>
