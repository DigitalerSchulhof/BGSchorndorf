// HELL;

.cms_voranmeldung_navigation {
	display: flex;
	flex-wrap: nowrap;
	margin: @haupt_absatzschulhof 0px @haupt_absatzschulhof 0px;
	padding: 0px;
}

.cms_optimierung_P .cms_voranmeldung_navigation li,
.cms_optimierung_T .cms_voranmeldung_navigation li {
	width: 25%;
}

.cms_optimierung_H .cms_voranmeldung_navigation li {
	width: 50%;
}

.cms_voranmeldung_navigation li {
	padding: 10px;
	background: @h_button_hintergrund;
	color: @h_button_schrift;
	display: block;
	list-style-type: none;
	margin: 0px;
	transition: 250ms ease-in-out;
	text-align: center !important;
}

.cms_voranmeldung_navigation li:hover {
	background: @h_button_hintergrundhover;
	color: @h_button_schrifthover;
	cursor: pointer;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen {
	background: @h_haupt_meldungerfolghinter;
	color: @h_button_schrift;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen:hover {
	background: @h_haupt_meldungerfolgakzent;
	color: @h_button_schrifthover;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_aktuell {
	background: @h_haupt_meldunginfohinter !important;
	color: @h_button_schrift !important;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_aktuell:hover {
	background: @h_haupt_meldunginfoakzent !important;
	color: @h_button_schrifthover !important;
	cursor: default !important;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_fehler {
	background: @h_haupt_meldungfehlerhinter;
	color: @h_button_schrift;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_fehler:hover {
	background: @h_haupt_meldungfehlerakzent;
	color: @h_button_schrifthover;
}

// DUNKEL;

.cms_voranmeldung_navigation li {
	background: @d_button_hintergrund;
	color: @d_button_schrift;
}

.cms_voranmeldung_navigation li:hover {
	background: @d_button_hintergrundhover;
	color: @d_button_schrifthover;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen {
	background: @d_haupt_meldungerfolghinter;
	color: @d_button_schrift;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_abgeschlossen:hover {
	background: @d_haupt_meldungerfolgakzent;
	color: @d_button_schrifthover;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_aktuell {
	background: @d_haupt_meldunginfohinter !important;
	color: @d_button_schrift !important;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_aktuell:hover {
	background: @d_haupt_meldunginfoakzent !important;
	color: @d_button_schrifthover !important;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_fehler {
	background: @d_haupt_meldungfehlerhinter;
	color: @d_button_schrift;
}

.cms_voranmeldung_navigation li.cms_voranmeldung_fehler:hover {
	background: @d_haupt_meldungfehlerakzent;
	color: @d_button_schrifthover;
}
