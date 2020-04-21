// HELL;

#cms_rechtepapa {
	margin-left: -34px
}

.cms_rechtebox {
	margin-left: 24px
}

.cms_rechtebox:not(.cms_recht_unterstes) {
	background-size: 24px;
	background-image: url('../res/sonstiges/rechtebaum/leer.png');
	background-repeat: repeat-y
}

.cms_rechtebox .cms_recht>.icon {
	content: ' ';
	display: inline-block;
	background-size: cover;
	transition: 0.3s background-image linear;
	background-image: url('../res/sonstiges/rechtebaum/wert.png');
	line-height: 24px;
	float: left;
	height: 24px;
	width: 24px
}

.cms_rechtebox .cms_recht.cms_hat_kinder>.icon {
	cursor: pointer;
	background-image: url('../res/sonstiges/rechtebaum/wert_k.png')
}

.cms_rechtebox .cms_recht.cms_hat_kinder>.icon.cms_recht_eingeklappt {
	background-image: url('../res/sonstiges/rechtebaum/wert_k_c.png')
}

.cms_rechtebox .cms_recht.cms_recht_unterstes>.icon {
	background-image: url('../res/sonstiges/rechtebaum/wert_u.png')
}

.cms_rechtebox .cms_recht.cms_recht_unterstes.cms_hat_kinder>.icon {
	background-image: url('../res/sonstiges/rechtebaum/wert_u_k.png')
}

.cms_rechtebox .cms_recht.cms_recht_unterstes.cms_hat_kinder>.icon.cms_recht_eingeklappt {
	background-image: url('../res/sonstiges/rechtebaum/wert_u_k_c.png')
}

.cms_rechtebox .cms_recht .cms_recht_beschreibung {
	height: 24px;
	display: inline-block
}

.cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i {
	height: 20px;
	cursor: pointer;
	transition: 250ms ease-in-out;
	border-radius: 1px;
	padding: 3px;
	margin: 2px;
	display: inline-block;
	border: 1px solid @h_haupt_schriftfarbepositiv;
}

.cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i:hover {
	border-color: transparent;
	color: @h_button_schrift;
	background-color: @h_button_hintergrund;
}

.cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i {
	border-color: transparent;
	color: @h_haupt_schriftfarbepositiv;
	background-color: @h_haupt_meldungerfolghinter;
}

.cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i:hover {
	background-color: @h_haupt_meldungfehlerhinter;
}

.cms_rechtebox .cms_recht.cms_recht_rolle .cms_recht_beschreibung_i {
	border-color: transparent;
	background-color: @h_haupt_meldunginfohinter !important;
	color: @h_haupt_schriftfarbepositiv !important;
}

.cms_demorecht {
	height: 24px;
	display: inline-block;
	padding: 5px;
	margin-bottom: 2px;
	border-radius: 1px;
	cursor: pointer;
	transition: 250ms ease-in-out;
	border: 1px solid @h_haupt_schriftfarbepositiv;
}

.cms_demorecht:hover {
	border-color: transparent;
	color: @h_button_schrift;
	background-color: @h_button_hintergrund;
}

.cms_demorecht.cms_demorecht_nutzer {
	border-color: transparent;
	color: @h_haupt_schriftfarbepositiv;
	background-color: @h_haupt_meldungerfolghinter;
}

.cms_demorecht.cms_demorecht_nutzer:hover {
	background-color: @h_haupt_meldungfehlerhinter;
}

.cms_demorecht.cms_demorecht_rolle {
	border-color: transparent;
	cursor: default;
	background-color: @h_haupt_meldunginfohinter !important;
	color: @h_haupt_schriftfarbepositiv !important;
}

// DUNKEL;

.cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i {
	border: 1px solid @d_haupt_schriftfarbepositiv;
}

.cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i:hover {
	color: @d_button_schrift;
	background-color: @d_button_hintergrund;
}

.cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_meldungerfolghinter;
}

.cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i:hover {
	background-color: @d_haupt_meldungfehlerhinter;
}

.cms_rechtebox .cms_recht.cms_recht_rolle .cms_recht_beschreibung_i {
	background-color: @d_haupt_meldunginfohinter !important;
	color: @d_haupt_schriftfarbepositiv !important;
}

.cms_demorecht {
	border: 1px solid @d_haupt_schriftfarbepositiv;
}

.cms_demorecht:hover {
	color: @d_button_schrift;
	background-color: @d_button_hintergrund;
}

.cms_demorecht.cms_demorecht_nutzer {
	color: @d_haupt_schriftfarbepositiv;
	background-color: @d_haupt_meldungerfolghinter;
}

.cms_demorecht.cms_demorecht_nutzer:hover {
	background-color: @d_haupt_meldungfehlerhinter;
}

.cms_demorecht.cms_demorecht_rolle {
	background-color: @d_haupt_meldunginfohinter !important;
	color: @d_haupt_schriftfarbepositiv !important;
}
