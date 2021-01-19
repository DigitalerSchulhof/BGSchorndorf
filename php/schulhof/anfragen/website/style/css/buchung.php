// HELL;

#cms_neue_buchung {
	margin-bottom: 20px;
}

#cms_buchungsplan {
	margin-top: 50px;
	position: relative;
	margin-bottom: 20px;
}

.cms_buchungsspalte_uhrzeiten, .cms_buchungsspalte {
	position: relative;
	float: left;
}

.cms_buchungsspalte_uhrzeiten {
	width: 9%;
}

.cms_buchungsspalte {
	width: 13%;
	border-left: 3px solid @h_haupt_hintergrund;
}

.cms_buchungsspalte_ferien {
background: @h_haupt_meldungerfolghinter;
}

.cms_buchungsspalte .cms_buchungsspaltetitel {
	font-weight: bold;
	color: @h_haupt_abstufung2;
	position: absolute;
	top: -40px;
	text-align: center;
	width: 100%;
	display: block;
}

.cms_buchungsuhrzeit {
	display: inline-block;
	position: absolute;
	color: @h_haupt_abstufung2;
	opacity: .5;
}

.cms_buchungsuhrzeitlinien {
	width: 100%;
	border-top: 1px solid @h_haupt_abstufung2;
	opacity: .5;
	position: absolute;
	left: 0px;
}

.cms_buchung_blockierung, .cms_buchung_selbst, .cms_buchung_fremd {
	padding: 5px;
	position: absolute;
	width: 96%;
	overflow-y: scroll;
	border-radius: 5px;
	left: 2%;
}

.cms_buchung_blockierung {background: @h_haupt_abstufung1; border: 1px solid @h_haupt_abstufung2;}
.cms_buchung_selbst {background: @h_haupt_meldunginfohinter; border: 1px solid @h_haupt_meldunginfoakzent;}
.cms_buchung_fremd {background: @h_haupt_meldungwarnunghinter; border: 1px solid @h_haupt_meldungwarnungakzent;}

.cms_buchung_zeit, .cms_buchung_von, .cms_buchung_aktion {
	display: block;
	font-size:  80%;
	text-align: center;
}

.cms_buchung_zeit {
	margin-bottom: 3px;
}

.cms_buchung_aktion {
	margin-top: 3px;
	transition: 250ms ease-in-out;
	opacity: 0;
}

.cms_buchung_aktion .cms_button_nein {
line-height: 1;
	font-size: 100%;
}

.cms_buchung_selbst:hover .cms_buchung_aktion,
.cms_buchung_fremd:hover .cms_buchung_aktion {
	opacity: 1;
}

.cms_buchung_grund {
	display: block;
	text-align: center;
	font-weight: bold;
	margin-bottom: 3px;
	font-size:  80%;
}

// DUNKEL;

.cms_buchungsspalte {
	border-left: 3px solid @d_haupt_hintergrund;
}

.cms_buchungsspalte_ferien {
background: @d_haupt_meldungerfolghinter;
}

.cms_buchungsspalte .cms_buchungsspaltetitel {
	color: @d_haupt_abstufung2;
}

.cms_buchungsuhrzeit {
	color: @d_haupt_abstufung2;
}

.cms_buchungsuhrzeitlinien {
	border-top: 1px solid @d_haupt_abstufung2;
}

.cms_buchung_blockierung {background: @d_haupt_abstufung1; border: 1px solid @d_haupt_abstufung2;}
.cms_buchung_selbst {background: @d_haupt_meldunginfohinter; border: 1px solid @d_haupt_meldunginfoakzent;}
.cms_buchung_fremd {background: @d_haupt_meldungwarnunghinter; border: 1px solid @d_haupt_meldungwarnungakzent;}
