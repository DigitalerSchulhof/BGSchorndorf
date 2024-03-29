// HELL;

.cms_dateisystem_tabelle {
	margin-bottom: 0px;
}

.cms_dateisystem_box {
	border: 1px solid @h_haupt_abstufung1;
	border-radius: 5px;
	background: @h_haupt_abstufung1;
	margin-bottom: @haupt_absatzschulhof;
}

.cms_dateisystem_pfad, .cms_dateisystem_aktionen, .cms_dateisystem_inhalt {
	margin: 5px 0px;
	background: @h_haupt_hintergrund;
	position: relative;
}

.cms_dateisystem_inhalt {
	margin-bottom: 0px;
	max-height: 150px;
	overflow-y: scroll;
}

.cms_dateisystem_pfad_icon {
	padding: 2px 5px 5px 5px;
	display: inline-block;
	line-height: 16px;
}

.cms_dateisystem_pfad_aktionen {
	padding: 4px 5px 0px 5px;
	display: inline-block;
}

.cms_dateisystem_pfad_aktionen:first-child {
	margin-right: 20px;
}

.cms_dateisystem_pfad_icon img {
	position: relative;
	top: 2px;
	margin-right: 5px;
}

.cms_dateisystem_pfad_icon:hover, .cms_dateisystem_pfad_aktionen:hover {
	background: @h_haupt_abstufung1;
	cursor: pointer;
}

.cms_dateisystem_pfad_icon:hover {
	background: @h_haupt_abstufung1;
	cursor: pointer;
}

.cms_dateisystem_inhalt table {
	width: 100%;
	border-spacing: 0px;
	border-collapse: collapse;
}

.cms_dateisystem_inhalt table td:first-child {
	width: 16px;
}

.cms_dateisystem_inhalt table td:first-child img {
	top: 2px;
	position: relative;
max-width: none !important;
}

.cms_dateisystem_inhalt table td {
	padding: 5px;
}

.cms_dateisystem_inhalt table td:last-child {
	text-align: right;
}

.cms_dateisystem_inhalt table td:last-child .cms_hinweis {
	text-align: right;
	left: auto;
	right: 0px;
}

.cms_dateisystem_inhalt table tr:hover td {
	background: @h_formular_feldhintergrund;
}

.cms_dateisystem_status {
	padding: 5px;
	background: @h_haupt_abstufung1;
}

.cms_dateisystem_status p {
	font-size: 75%;
}

.cms_dateisystem_pfad_aktionen {
	position: relative;
}

.cms_dateisystem_pfad_aktionen:hover .cms_hinweis {
	display: block;
	bottom: 40px;
	left: 0px;
}

td.cms_dateisystem_meldung {
	text-align: center!important;
}

td.cms_dateisystem_ordner:hover {
	cursor: pointer;
}

.cms_dateisystem_laden {
	padding: 10px;
	margin-bottom: 5px;
	text-align: center;
}

.cms_dateisystem_meldung {
	padding: 10px;
	margin-bottom: 5px;
}

.cms_dateisystem_aktionen_neuerordner, .cms_dateisystem_aktionen_hochladen {
	padding: 5px;
	display: none;
}

.cms_dateisystem_uploadzone {
	border-radius: 5px;
	background-color: @h_haupt_meldunginfohinter;
	height: 100px;
	width: 100%;
	margin-top: 6px;
	border: 1px dashed @h_haupt_meldunginfoakzent;
	background-image: url('../res/icons/gross/dateiupload.png');
	background-position: center 25px;
	background-repeat: no-repeat;
}

.cms_dateisystem_uploadzone p {
	text-align: center;
	color: @h_haupt_schriftfarbenegativ;
	font-size: 90%;
	margin-top: 60px !important;
}

.cms_dateisystem_uploadzone:hover {
	cursor: move;
}

.cms_dateisystem_hochladen_dateiliste li {
	position: relative;
}

.cms_dateisystem_hochladen_dateiliste .cms_button_nein {
	position: absolute;
	left: -22px;
	top: -3px;
	opacity: 0;
}

.cms_dateisystem_hochladen_dateiliste li:hover .cms_button_nein {
	opacity: 1;
}

.cms_dateisystem_hochladen_dateiliste li, #cms_hochladen_fehlgeschlagen_liste {
	font-size: 90%;
}

.cms_dateisystem_hochladen_dateiliste {
	margin-top: 0px !important;
	max-height: 50px;
	overflow-y: scroll;
	padding: 5px;
	background: @h_formular_feldhintergrund;
	border-radius: 5px;
}

.cms_upload_dateiknopf {
	position: relative;
	display: inline-block;
}

.cms_upload_dateiknopf:hover .cms_hinweis {
	display: block;
	right: 20px !important;
	bottom: -2px;
}

.cms_upload_dateiknopf:hover {
	cursor: pointer;
}

#cms_hochladen_fehlgeschlagen {
	background: @h_haupt_meldungfehlerhinter;
	border: 1px solid @h_haupt_meldungfehlerakzent;
	padding: 5px;
	border-radius: 5px;
	display: none;
	margin-top: 20px;
}

.cms_datei_gewaehlt {
	font-size: @haupt_schriftgroesse;
	position: relative;
}

.cms_datei_gewaehlt img {
	position: relative;
	bottom: -4px;
}

// DUNKEL;

.cms_dateisystem_box {
	border: 1px solid @d_haupt_abstufung1;
	background: @d_haupt_abstufung1;
}

.cms_dateisystem_pfad, .cms_dateisystem_aktionen, .cms_dateisystem_inhalt {
	background: @d_haupt_hintergrund;
}

.cms_dateisystem_pfad_icon:hover, .cms_dateisystem_pfad_aktionen:hover {
	background: @d_haupt_abstufung1;
}

.cms_dateisystem_pfad_icon:hover {
	background: @d_haupt_abstufung1;
}

.cms_dateisystem_inhalt table tr:hover td {
	background: @d_formular_feldhintergrund;
}

.cms_dateisystem_status {
	background: @d_haupt_abstufung1;
}

.cms_dateisystem_uploadzone {
	background-color: @d_haupt_meldunginfohinter;
	border: 1px dashed @d_haupt_meldunginfoakzent;
}

.cms_dateisystem_uploadzone p {
	color: @d_haupt_schriftfarbenegativ;
}

.cms_dateisystem_hochladen_dateiliste {
	background: @d_formular_feldhintergrund;
}

#cms_hochladen_fehlgeschlagen {
	background: @d_haupt_meldungfehlerhinter;
	border: 1px solid @d_haupt_meldungfehlerakzent;
}
