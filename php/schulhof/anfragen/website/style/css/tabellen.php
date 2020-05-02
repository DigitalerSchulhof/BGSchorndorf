// HELL;

td.cms_notiz {
	text-align: center !important;
	color: @h_haupt_abstufung2;
}

th.cms_zwischenueberschrift,
th.cms_zwischenueberschrift *  {
	text-align: center !important;
	color: @h_haupt_abstufung2;
	font-weight: bold;
}

table .cms_senkrecht {
writing-mode: vertical-rl;
	transform: rotate(180deg);
	font-weight: inherit;
}

.cms_formular {
	border-collapse: collapse;
	border-spacing: 0px;
	background: @h_formular_hintergrund;
	width: 100%;
}

.cms_formular td {
	line-height: 2em;
}

.cms_formular th {
	padding-top: 10px !important;
	line-height: 1.5em;
	vertical-align: top !important;
}

.cms_formular tbody, .cms_formular thead {
	width: 100%;
}

.cms_formular th {
vertical-align: top;
	padding: 5px 7px;
	text-align: left;
	font-weight: bold;
	position: relative;
}

.cms_formular th * {
	font-weight: bold;
}

.cms_formular th .cms_hinweis_aussen {
	font-weight: bold;
}

.cms_formular td {
vertical-align: top;
	font-weight: normal;
	padding: 5px 7px;
	text-align: left;
	position: relative;
}

.cms_formular td > img {
	top: 5px;
	position: relative;
}

.cms_liste {
	width: 100%;
	border-collapse: collapse;
	border-spacing: 0px;
	background: @h_haupt_hintergrund;
	border-top: 1px @h_haupt_abstufung1 solid;
	border-bottom: 1px @h_haupt_abstufung1 solid;
	margin-bottom: 7px;
}

.cms_liste tr.min,.cms_liste  td.min {
	width: 1%;
}

.cms_liste th {
vertical-align: middle;
	font-weight: bold;
	padding: 3px 7px;
	text-align: left;
	border-top: 1px @h_haupt_abstufung1 solid;
	border-bottom: 1px @h_haupt_abstufung1 solid;
	line-height: 1.5em;
	transition: 250ms ease-in-out;
}

.cms_liste td {
	font-weight: normal;
	padding: 3px 7px;
	text-align: left;
	border-bottom: 1px @h_haupt_abstufung1 solid;
	transition: 250ms ease-in-out;
}

.cms_liste thead th, .cms_liste tr:first-child th, .cms_liste tr:first-child td {
	border-top: none !important;
}

.cms_liste tbody tr:hover td, .cms_liste tbody tr:hover th {
	background: @h_haupt_abstufung1;
}

.cms_postfach_liste .cms_postfach_vorschau {
	border-top: none;
	background: @h_haupt_abstufung1;
	display: none;
}

.cms_postfach_nachricht_lesen:hover {
	cursor: pointer;
}

.cms_schulhof_nutzerkonto_profildaten_mehrF, .cms_schulhof_verwaltung_personen_details_mehrF,
.cms_website_seiten_fortgeschritten_mehrF {
	display: none;
}

.cms_tabelle_zwischentitel {
	font-weight: bold !important;
	text-align: center !important;
	color: @h_haupt_abstufung2;
}

table.cms_zeitwahl {
	width: 100%;
	margin-bottom: @haupt_absatzschulhof;
	border-collapse: collapse;
}

table.cms_zeitwahl td {width: auto;}
table.cms_zeitwahl td:nth-child(2) {text-align: center !important;}
table.cms_zeitwahl td:nth-child(3) {text-align: right;}

table.table {
	width: 100%;
	margin-bottom: @haupt_absatzschulhof;
	border-collapse: collapse;
}

table.table td {
	border: none !important;
	padding: 3px;
	border-right: 1px solid @h_haupt_hintergrund !important;
	background-color: @h_haupt_abstufung1;
}

table.table td:last-child {
	border-right: none !important;
}

table.table tr:nth-child(2n+1) td {
	background-color: @h_formular_feldhintergrund;
}

th.cms_zwischenueberschrift {
	text-align: center;
	background-color: @h_haupt_abstufung1;
}

th.cms_zahl, td.cms_zahl {
	text-align: right !important;
}

.cms_sortieren:hover {
	cursor: s-resize;
}

.cms_auswaehlen:hover {
	cursor: crosshair;
}

// DUNKEL;

td.cms_notiz {
	color: @d_haupt_abstufung2;
}

th.cms_zwischenueberschrift,
th.cms_zwischenueberschrift *  {
	color: @d_haupt_abstufung2;
}

.cms_formular {
	background: @d_formular_hintergrund;
}

.cms_liste {
	background: @d_haupt_hintergrund;
	border-top: 1px @d_haupt_abstufung1 solid;
	border-bottom: 1px @d_haupt_abstufung1 solid;
}

.cms_liste th {
	border-top: 1px @d_haupt_abstufung1 solid;
	border-bottom: 1px @d_haupt_abstufung1 solid;
}

.cms_liste td {
	border-bottom: 1px @d_haupt_abstufung1 solid;
}

.cms_liste tbody tr:hover td, .cms_liste tbody tr:hover th {
	background: @d_haupt_abstufung1;
}

.cms_postfach_liste .cms_postfach_vorschau {
	background: @d_haupt_abstufung1;
}

.cms_tabelle_zwischentitel {
	color: @d_haupt_abstufung2;
}

table.table td {
	border-right: 1px solid @d_haupt_hintergrund !important;
	background-color: @d_haupt_abstufung1;
}

table.table tr:nth-child(2n+1) td {
	background-color: @d_formular_feldhintergrund;
}
