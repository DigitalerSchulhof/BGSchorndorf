// HELL;

input, textarea, select, .cms_wahl {
	font-weight: normal;
	padding: 5px 7px;
	border-top-right-radius: 3px;
	border-top-left-radius: 3px;
	border-bottom-right-radius: 3px;
	border-bottom-left-radius: 3px;
	background: @h_formular_feldhintergrund;
	border: none;
	border-bottom: 1px solid @h_formular_feldfocushintergrund;
	width: 100%;
	transition: 500ms ease-in-out;
}

textarea.cms_textarea {
	width: 100%;
	height: 250px;
}

input:hover, textarea:hover, select:hover {
	background: @h_formular_feldhoverhintergrund;
}

input:focus, textarea:focus, select:focus {
	background: @h_formular_feldfocushintergrund;
}

input.cms_klein, select.cms_klein {
	width: 35%;
}

input.cms_gross, select.cms_gross {
	width: 60%;
}

span.cms_input_Tbez {
	display: inline-block;
	width: 30px;
	text-align: center;
}

input.cms_input_h, input.cms_input_m, input.cms_input_T, input.cms_input_M, input.cms_input_klein {
	width: 30px;
}

input.cms_input_J {
	width: 60px;
}

.cms_nutzerkonto_postfach_nachricht {
	padding: 0px 7px;
}

.cms_nutzerkonto_postfach_nachricht textarea {
	width: 100%;
	height: 300px;
}

.cms_personensuche_feld,
.cms_gruppensuche_feld {
	width: 100%;
	position: absolute;
	left: 0px;
	top: 0px;
	background: @h_haupt_abstufung1;
	border-bottom-right-radius: 5px;
	border-bottom-left-radius: 5px;
	display: none;
	z-index: 2;
	margin-bottom: 20px;
	box-shadow: @h_haupt_hintergrund 0px 0px 7px;
}

.cms_personensuche_feld input {
	width: 100% !important;
}

.cms_personensuche_feld_aussen,
.cms_gruppensuche_feld_aussen {
	position: relative;
}

.cms_personenauswahl {
	display: inline-block;
	margin-right: 25px;
	position: relative;
	font-family: 'robl', sans-serif;
}

.cms_personenauswahl:hover .cms_personenauswahl_schliessen {
	display: inline-block;
}

.cms_personenauswahl:hover {
	cursor: default;
}

.cms_fenster_schliessen {
	position: absolute;
	right: 0px;
	top: -20px;
}

.cms_personenauswahl_schliessen {
	display: none;
	position: absolute;
	left: -8px;
	top: 0px;
	z-index: 5;
}



.cms_schieber_o_aktiv, .cms_schieber_o_inaktiv {
	border: 1px solid @h_haupt_hintergrund;
	border-radius: 11px;
	display: inline-block;
	width: 40px;
	line-height: 0px !important;
	transition: 250ms ease-in-out;
	text-align: left;
}

.cms_schieber_o_aktiv {
	background: @h_haupt_meldungerfolghinter;
}

.cms_schieber_o_inaktiv {
	background: @h_haupt_meldungfehlerhinter;
}

.cms_schieber_o_aktiv .cms_schieber_i {
	margin-left: 0px;
}

.cms_schieber_o_inaktiv .cms_schieber_i {
	margin-left: 18px;
}

.cms_schieber_i {
	width: 20px;
	height: 20px;
	border-radius: 10px;
	background: @h_haupt_hintergrund;
	display: inline-block;
	transition: 250ms ease-in-out;
}

.cms_schieber_o_aktiv:hover, .cms_schieber_o_inaktiv:hover {
	border: 1px solid @h_haupt_schriftfarbepositiv;
	cursor: pointer;
}

.cms_eingabe_icon {
	display: inline-block;
	position: relative;
	width: 16px;
}

.cms_eingabe_icon img {
	bottom: -5px;
	position: absolute;
}

.cms_vorschau img, .cms_vorschau video {
	max-width: 100%;
	max-height: 300px;
}

.cms_dateiwahl_tabelle td:last-child {
	text-align: left !important;
}

.cms_dateiwahl_tabelle td:hover {
	cursor: pointer;
}

.cms_notizzettel {
	background-color: @h_haupt_meldunginfohinter;
	height: 200px;
	resize: vertical;
}

.cms_notizzettelleer {
	background-color: @h_haupt_abstufung1;
}

.cms_notizzettel:hover, .cms_notizzettel:focus {
	background-color: @h_haupt_abstufung1;
}

.cms_farbwahl_rgb {
	height: 30px;
	width: 70%;
}
.cms_farbwahl_alpha {width: 20%;}

// DUNKEL;

input, textarea, select, .cms_wahl {
	background: @d_formular_feldhintergrund;
	border-bottom: 1px solid @d_formular_feldfocushintergrund;
}

input:hover, textarea:hover, select:hover {
	background: @d_formular_feldhoverhintergrund;
}

input:focus, textarea:focus, select:focus {
	background: @d_formular_feldfocushintergrund;
}

.cms_personensuche_feld,
.cms_gruppensuche_feld {
	background: @d_haupt_abstufung1;
	box-shadow: @d_haupt_hintergrund 0px 0px 7px;
}

.cms_schieber_o_aktiv, .cms_schieber_o_inaktiv {
	border: 1px solid @d_haupt_hintergrund;
}

.cms_schieber_o_aktiv {
	background: @d_haupt_meldungerfolghinter;
}

.cms_schieber_o_inaktiv {
	background: @d_haupt_meldungfehlerhinter;
}

.cms_schieber_i {
	background: @d_haupt_hintergrund;
}

.cms_schieber_o_aktiv:hover, .cms_schieber_o_inaktiv:hover {
	border: 1px solid @d_haupt_schriftfarbepositiv;
}

.cms_notizzettel {
	background-color: @d_haupt_meldunginfohinter;
}

.cms_notizzettelleer {
	background-color: @d_haupt_abstufung1;
}

.cms_notizzettel:hover, .cms_notizzettel:focus {
	background-color: @d_haupt_abstufung1;
}
