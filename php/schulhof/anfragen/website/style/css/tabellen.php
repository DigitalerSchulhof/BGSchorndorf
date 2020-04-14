<?php
fwrite($hell, "td.cms_notiz {");
fwrite($hell, "text-align: center !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, "th.cms_zwischenueberschrift,");
fwrite($hell, "th.cms_zwischenueberschrift *  {");
fwrite($hell, "text-align: center !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, "table .cms_senkrecht {");
fwrite($hell, "writing-mode: vertical-rl;");
fwrite($hell, "transform: rotate(180deg);");
fwrite($hell, "font-weight: inherit;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular {");
fwrite($hell, "border-collapse: collapse;");
fwrite($hell, "border-spacing: 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_formular_hintergrund'].";");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular td {");
fwrite($hell, "line-height: 2em;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular th {");
fwrite($hell, "padding-top: 10px !important;");
fwrite($hell, "line-height: 1.5em;");
fwrite($hell, "vertical-align: top !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular tbody, .cms_formular thead {");
fwrite($hell, "width: 100%;");
fwrite($hell, "}");

fwrite($hell, "/*.cms_formular tbody tr th:first-child,");
fwrite($hell, ".cms_liste tbody tr th:first-child {");
fwrite($hell, "width: 33%;");
fwrite($hell, "}*/");

fwrite($hell, ".cms_formular th {");
fwrite($hell, "vertical-align: top;");
fwrite($hell, "padding: 5px 7px;");
fwrite($hell, "text-align: left;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular th * {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular th .cms_hinweis_aussen {");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular td {");
fwrite($hell, "vertical-align: top;");
fwrite($hell, "font-weight: normal;");
fwrite($hell, "padding: 5px 7px;");
fwrite($hell, "text-align: left;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_formular td > img {");
fwrite($hell, "top: 5px;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_liste {");
fwrite($hell, "width: 100%;");
fwrite($hell, "border-collapse: collapse;");
fwrite($hell, "border-spacing: 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "border-top: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;");
fwrite($hell, "border-bottom: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;");
fwrite($hell, "margin-bottom: 7px;");
fwrite($hell, "tr.min, td.min {");
fwrite($hell, "width: 1%;");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, ".cms_liste th {");
fwrite($hell, "vertical-align: middle;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "padding: 3px 7px;");
fwrite($hell, "text-align: left;");
fwrite($hell, "border-top: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;");
fwrite($hell, "border-bottom: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;");
fwrite($hell, "line-height: 1.5em;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_liste td {");
fwrite($hell, "font-weight: normal;");
fwrite($hell, "padding: 3px 7px;");
fwrite($hell, "text-align: left;");
fwrite($hell, "border-bottom: 1px ".$_POST['cms_style_h_haupt_abstufung1']." solid;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "}");

fwrite($hell, ".cms_liste thead th, .cms_liste tr:first-child th, .cms_liste tr:first-child td {");
fwrite($hell, "border-top: none !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_liste tbody tr:hover td, .cms_liste tbody tr:hover th {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_postfach_liste .cms_postfach_vorschau {");
fwrite($hell, "border-top: none;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_postfach_nachricht_lesen:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_schulhof_nutzerkonto_profildaten_mehrF, .cms_schulhof_verwaltung_personen_details_mehrF,");
fwrite($hell, ".cms_website_seiten_fortgeschritten_mehrF {");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_tabelle_zwischentitel {");
fwrite($hell, "font-weight: bold !important;");
fwrite($hell, "text-align: center !important;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, "table.cms_zeitwahl {");
fwrite($hell, "width: 100%;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "border-collapse: collapse;");
fwrite($hell, "border-spacing: collapse;");
fwrite($hell, "}");

fwrite($hell, "table.cms_zeitwahl td {width: auto;}");
fwrite($hell, "table.cms_zeitwahl td:nth-child(2) {text-align: center !important;}");
fwrite($hell, "table.cms_zeitwahl td:nth-child(3) {text-align: right;}");

fwrite($hell, "table.table {");
fwrite($hell, "width: 100%;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "border-collapse: collapse;");
fwrite($hell, "border-spacing: collapse;");
fwrite($hell, "}");

fwrite($hell, "table.table td {");
fwrite($hell, "border: none !important;");
fwrite($hell, "padding: 3px;");
fwrite($hell, "border-right: 1px solid ".$_POST['cms_style_h_haupt_hintergrund']." !important;");
fwrite($hell, "background-color: #dddddd;");
fwrite($hell, "}");

fwrite($hell, "table.table td:last-child {");
fwrite($hell, "border-right: none !important;");
fwrite($hell, "}");

fwrite($hell, "table.table tr:nth-child(2n+1) td {");
fwrite($hell, "background-color: ".$_POST['cms_style_h_formular_feldhintergrund'].";");
fwrite($hell, "}");

fwrite($hell, "th.cms_zwischenueberschrift {");
fwrite($hell, "text-align: center;");
fwrite($hell, "background-color: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, "th.cms_zahl, td.cms_zahl {");
fwrite($hell, "text-align: right !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_sortieren:hover {");
fwrite($hell, "cursor: s-resize;");
fwrite($hell, "}");

fwrite($hell, ".cms_auswaehlen:hover {");
fwrite($hell, "cursor: crosshair;");
fwrite($hell, "}");







// DARKMODE
fwrite($dunkel, "td.cms_notiz {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "th.cms_zwischenueberschrift,");
fwrite($dunkel, "th.cms_zwischenueberschrift *  {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_formular {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_formular_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_liste {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "border-top: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;");
fwrite($dunkel, "border-bottom: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_liste th {");
fwrite($dunkel, "border-top: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;");
fwrite($dunkel, "border-bottom: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_liste td {");
fwrite($dunkel, "border-bottom: 1px ".$_POST['cms_style_d_haupt_abstufung1']." solid;");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_liste tbody tr:hover td, .cms_liste tbody tr:hover th {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_postfach_liste .cms_postfach_vorschau {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_tabelle_zwischentitel {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, "table.table td {");
fwrite($dunkel, "border-right: 1px solid ".$_POST['cms_style_d_haupt_hintergrund']." !important;");
fwrite($dunkel, "}");

fwrite($dunkel, "table.table tr:nth-child(2n+1) td {");
fwrite($dunkel, "background-color: ".$_POST['cms_style_d_formular_feldhintergrund'].";");
fwrite($dunkel, "}");
?>
