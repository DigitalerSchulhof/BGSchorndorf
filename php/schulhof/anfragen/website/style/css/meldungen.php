// HELL;

.cms_meldung {
	background-color: @h_haupt_abstufung1;
	background-repeat: no-repeat;
	background-position: 5px 5px;
	border-left: 4px solid @h_haupt_abstufung2;
	padding: 10px 10px 10px 10px;
	margin: 10px 0px;
	min-height: 42px;
	text-align: left !important;
  border-top-right-radius: 2px;
  border-bottom-right-radius: 2px;
}

.cms_meldung:first-child {
	margin-top: 0px !important;
}

.cms_meldung_laden {
	background-color: @h_haupt_hintergrund;
	border: 3px dashed @h_haupt_abstufung1;
	padding: 5px;
	margin: 10px 0px;
	text-align: center;
	color: @h_haupt_abstufung2;
}

.cms_meldung_info {
	background-color: @h_haupt_meldunginfohinter;
	border-color: @h_haupt_meldunginfoakzent;
}

.cms_meldung_vplan {
	background-color: @h_haupt_abstufung1;
	border-color: @h_haupt_abstufung2;
	opacity: 1;
}

.cms_meldung_erfolg {
	background-color: @h_haupt_meldungerfolghinter;
	border-color: @h_haupt_meldungerfolgakzent;
}

.cms_meldung_fehler {
	background-color: @h_haupt_meldungfehlerhinter;
	border-color: @h_haupt_meldungfehlerakzent;
}

.cms_meldung_warnung {
	background-color: @h_haupt_meldungwarnunghinter;
	border-color: @h_haupt_meldungwarnungakzent;
}

.cms_meldung_bauarbeiten {
	background-color: @h_haupt_meldungwarnunghinter;
	border-color: @h_haupt_meldungwarnungakzent;
}

.cms_meldung_firewall {
	background-color: @h_haupt_meldunginfohinter;
	border-color: @h_haupt_meldunginfoakzent;
}

.cms_gesichertedaten {
	font-family: inherit;
	background-color: @h_haupt_hintergrund;
	border: 2px dashed @h_haupt_meldungerfolghinter;
	padding: 5px;
	margin: 10px 0px;
}

.cms_geschuetzerinhalt {
	font-family: inherit;
	background-color: @h_haupt_hintergrund;
	color: @h_haupt_abstufung2;
	border: 2px dashed @h_haupt_meldungfehlerhinter;
	padding: 5px;
	margin: 10px 0px;
}

.cms_geschuetzerinhalt p, .cms_ladebox {
	color: @h_haupt_abstufung2;
	text-align: center;
}

.cms_ladebox {
	border: 2px dashed @h_haupt_abstufung2;
	padding: 10px;
}

.cms_systemvoraussetzung {
	background: @h_haupt_abstufung1;
	border-radius: 5px;
}

.cms_systemvoraussetzung li {
	width:100%;
	padding: 10px;
	text-align: center;
	margin: 0px;
	list-style-type: none;
}

// DUNKEL;

.cms_meldung {
	background-color: @d_haupt_abstufung1;
	border-left: 4px solid @d_haupt_abstufung2;
}

.cms_meldung_laden {
	background-color: @d_haupt_hintergrund;
	border: 3px dashed @d_haupt_abstufung1;
	color: @d_haupt_abstufung2;
}

.cms_meldung_info {
	background-color: @d_haupt_meldunginfohinter;
	border-color: @d_haupt_meldunginfoakzent;
}

.cms_meldung_vplan {
	background-color: @d_haupt_abstufung1;
	border-color: @d_haupt_abstufung2;
}

.cms_meldung_erfolg {
	background-color: @d_haupt_meldungerfolghinter;
	border-color: @d_haupt_meldungerfolgakzent;
}

.cms_meldung_fehler {
	background-color: @d_haupt_meldungfehlerhinter;
	border-color: @d_haupt_meldungfehlerakzent;
}

.cms_meldung_warnung {
	background-color: @d_haupt_meldungwarnunghinter;
	border-color: @d_haupt_meldungwarnungakzent;
}

.cms_meldung_bauarbeiten {
	background-color: @d_haupt_meldungwarnunghinter;
	border-color: @d_haupt_meldungwarnungakzent;
}

.cms_meldung_firewall {
	background-color: @d_haupt_meldunginfohinter;
	border-color: @d_haupt_meldunginfoakzent;
}

.cms_gesichertedaten {
	background-color: @d_haupt_hintergrund;
	border: 2px dashed @d_haupt_meldungerfolghinter;
}

.cms_geschuetzerinhalt {
	background-color: @d_haupt_hintergrund;
	color: @d_haupt_abstufung2;
	border: 2px dashed @d_haupt_meldungfehlerhinter;
}

.cms_geschuetzerinhalt p, .cms_ladebox {
	color: @d_haupt_abstufung2;
}

.cms_ladebox {
	border: 2px dashed @d_haupt_abstufung2;
}

.cms_systemvoraussetzung {
	background: @d_haupt_abstufung1;
}
