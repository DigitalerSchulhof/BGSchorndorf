<?php
fwrite($hell, "#cms_rechtepapa {");
fwrite($hell, "	margin-left: -34px");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox {");
fwrite($hell, "	margin-left: 24px");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox:not(.cms_recht_unterstes) {");
fwrite($hell, "	background-size: 24px;");
fwrite($hell, "	background-image: url('../res/sonstiges/rechtebaum/leer.png');");
fwrite($hell, "	background-repeat: repeat-y");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht>.icon {");
fwrite($hell, "	content: ' ';");
fwrite($hell, "	display: inline-block;");
fwrite($hell, "	background-size: cover;");
fwrite($hell, "	transition: 0.3s background-image linear;");
fwrite($hell, "	background-image: url('../res/sonstiges/rechtebaum/wert.png');");
fwrite($hell, "	line-height: 24px;");
fwrite($hell, "	float: left;");
fwrite($hell, "	height: 24px;");
fwrite($hell, "	width: 24px");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_hat_kinder>.icon {");
fwrite($hell, "	cursor: pointer;");
fwrite($hell, "	background-image: url('../res/sonstiges/rechtebaum/wert_k.png')");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_hat_kinder>.icon.cms_recht_eingeklappt {");
fwrite($hell, "	background-image: url('../res/sonstiges/rechtebaum/wert_k_c.png')");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_recht_unterstes>.icon {");
fwrite($hell, "	background-image: url('../res/sonstiges/rechtebaum/wert_u.png')");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_recht_unterstes.cms_hat_kinder>.icon {");
fwrite($hell, "	background-image: url('../res/sonstiges/rechtebaum/wert_u_k.png')");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_recht_unterstes.cms_hat_kinder>.icon.cms_recht_eingeklappt {");
fwrite($hell, "	background-image: url('../res/sonstiges/rechtebaum/wert_u_k_c.png')");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht .cms_recht_beschreibung {");
fwrite($hell, "	height: 24px;");
fwrite($hell, "	display: inline-block");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i {");
fwrite($hell, "	height: 20px;");
fwrite($hell, "	cursor: pointer;");
fwrite($hell, "	transition: 250ms ease-in-out;");
fwrite($hell, "	border-radius: 1px;");
fwrite($hell, "	padding: 3px;");
fwrite($hell, "	margin: 2px;");
fwrite($hell, "	display: inline-block;");
fwrite($hell, "	border: 1px solid ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i:hover {");
fwrite($hell, "	border-color: transparent;");
fwrite($hell, "	color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_button_hintergrund'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i {");
fwrite($hell, "	border-color: transparent;");
fwrite($hell, "	color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i:hover {");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_rechtebox .cms_recht.cms_recht_rolle .cms_recht_beschreibung_i {");
fwrite($hell, "	border-color: transparent;");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;\n");
fwrite($hell, "	color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($hell, "}");

fwrite($hell, ".cms_demorecht {");
fwrite($hell, "	height: 24px;");
fwrite($hell, "	display: inline-block;");
fwrite($hell, "	padding: 5px;");
fwrite($hell, "	margin-bottom: 2px;");
fwrite($hell, "	border-radius: 1px;");
fwrite($hell, "	cursor: pointer;");
fwrite($hell, "	transition: 250ms ease-in-out;");
fwrite($hell, "	border: 1px solid ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_demorecht:hover {");
fwrite($hell, "	border-color: transparent;");
fwrite($hell, "	color: ".$_POST['cms_style_h_button_schrift'].";\n");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_button_hintergrund'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_demorecht.cms_demorecht_nutzer {");
fwrite($hell, "	border-color: transparent;");
fwrite($hell, "	color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_haupt_meldungerfolghinter'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_demorecht.cms_demorecht_nutzer:hover {");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";\n");
fwrite($hell, "}");

fwrite($hell, ".cms_demorecht.cms_demorecht_rolle {");
fwrite($hell, "	border-color: transparent;");
fwrite($hell, "	cursor: default;");
fwrite($hell, "	background-color: ".$_POST['cms_style_h_haupt_meldunginfohinter']." !important;\n");
fwrite($hell, "	color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv']." !important;\n");
fwrite($hell, "}");


// DARKMODE
fwrite($dunkel, ".cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i {");
fwrite($dunkel, "	border: 1px solid ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_rechtebox .cms_recht .cms_recht_beschreibung .cms_recht_beschreibung_i:hover {");
fwrite($dunkel, "	color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_button_hintergrund'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i {");
fwrite($dunkel, "	color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_rechtebox .cms_recht.cms_recht_nutzer .cms_recht_beschreibung_i:hover {");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_rechtebox .cms_recht.cms_recht_rolle .cms_recht_beschreibung_i {");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_haupt_meldunginfohinter']." !important;\n");
fwrite($dunkel, "	color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_demorecht {");
fwrite($dunkel, "	border: 1px solid ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_demorecht:hover {");
fwrite($dunkel, "	color: ".$_POST['cms_style_d_button_schrift'].";\n");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_button_hintergrund'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_demorecht.cms_demorecht_nutzer {");
fwrite($dunkel, "	color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_haupt_meldungerfolghinter'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_demorecht.cms_demorecht_nutzer:hover {");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_haupt_meldungfehlerhinter'].";\n");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_demorecht.cms_demorecht_rolle {");
fwrite($dunkel, "	background-color: ".$_POST['cms_style_d_haupt_meldunginfohinter']." !important;\n");
fwrite($dunkel, "	color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv']." !important;\n");
fwrite($dunkel, "}");
?>
