// HELL;

.cms_kalenderuebersicht {
	width: 100%;
}

.cms_kalenderuebersicht th, .cms_kalenderuebersicht td {
	width: 12.5%;
	text-align: center;
}

.cms_kalender_kw, .cms_kalender_tag {font-weight: bold; padding: 6px 0px 5px 0px;}
.cms_kalender_kw {color: @h_haupt_abstufung2;}

.cms_kalender_kwzahl, .cms_kalender_tagzahl {font-weight: normal;}
.cms_kalender_kwzahl {color: @h_haupt_abstufung2;}

.cms_kalenderzahl {
	color: @h_haupt_schriftfarbepositiv;
	padding: 6px 0px 5px 0px;
	border-radius: 7px;
	text-align: center;
	display: inline-block;
	width: 100%;
	border: 3px solid @h_haupt_hintergrund;
	line-height: 1;
}

.cms_kalenderzahl:hover {
	border: 3px solid @h_haupt_abstufung2;
	color: @h_haupt_schriftfarbepositiv;
}

.cms_kalenderzahl_heute {border: 3px solid @h_haupt_meldungfehlerakzent;}
.cms_kalenderzahl_gewaehlt {background: @h_haupt_meldunginfohinter !important;}
.cms_kalenderzahl_persoenlich {background: @h_haupt_meldungfehlerhinter;}
.cms_kalenderzahl_oeffentlich {background: @h_haupt_meldungwarnunghinter;}
.cms_kalenderzahl_ferien {background: @h_haupt_meldungerfolghinter;}
.cms_kalenderzahl_sichtbar {background: @h_haupt_abstufung1;}

// DUNKEL;

.cms_kalender_kw {color: @d_haupt_abstufung2;}

.cms_kalender_kwzahl {color: @d_haupt_abstufung2;}

.cms_kalenderzahl {
	color: @d_haupt_schriftfarbepositiv;
	border: 3px solid @d_haupt_hintergrund;
}

.cms_kalenderzahl:hover {
	border: 3px solid @d_haupt_abstufung2;
	color: @d_haupt_schriftfarbepositiv;
}

.cms_kalenderzahl_heute {border: 3px solid @d_haupt_meldungfehlerakzent;}
.cms_kalenderzahl_gewaehlt {background: @d_haupt_meldunginfohinter !important;}
.cms_kalenderzahl_persoenlich {background: @d_haupt_meldungfehlerhinter;}
.cms_kalenderzahl_oeffentlich {background: @d_haupt_meldungwarnunghinter;}
.cms_kalenderzahl_ferien {background: @d_haupt_meldungerfolghinter;}
.cms_kalenderzahl_sichtbar {background: @d_haupt_abstufung1;}
