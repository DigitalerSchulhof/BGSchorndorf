function cms_website_style_aendern() {
	cms_laden_an('Style ändern', 'Die Eingaben werden überprüft.');

	var aliashkandidaten = ['-', 'cms_style_h_haupt_schriftfarbepositiv', 'cms_style_h_haupt_schriftfarbenegativ', 'cms_style_h_haupt_hintergrund', 'cms_style_h_haupt_koerperhintergrund', 'cms_style_h_haupt_abstufung1', 'cms_style_h_haupt_abstufung2', 'cms_style_h_haupt_thema1', 'cms_style_h_haupt_thema2', 'cms_style_h_haupt_thema3', 'cms_style_h_haupt_meldungerfolghinter', 'cms_style_h_haupt_meldungerfolgakzent', 'cms_style_h_haupt_meldungwarnunghinter', 'cms_style_h_haupt_meldungwarnungakzent', 'cms_style_h_haupt_meldungfehlerhinter', 'cms_style_h_haupt_meldungfehlerakzent', 'cms_style_h_haupt_meldunginfohinter', 'cms_style_h_haupt_meldunginfoakzent', 'cms_style_h_haupt_notizschrift', 'cms_style_h_haupt_schriftfarbepositiv', 'cms_style_h_haupt_schriftfarbenegativ', 'cms_style_h_haupt_hintergrund', 'cms_style_h_haupt_koerperhintergrund', 'cms_style_h_haupt_abstufung1', 'cms_style_h_haupt_abstufung2', 'cms_style_h_haupt_thema1', 'cms_style_h_haupt_thema2', 'cms_style_h_haupt_thema3', 'cms_style_h_haupt_meldungerfolghinter', 'cms_style_h_haupt_meldungerfolgakzent', 'cms_style_h_haupt_meldungwarnunghinter', 'cms_style_h_haupt_meldungwarnungakzent', 'cms_style_h_haupt_meldungfehlerhinter', 'cms_style_h_haupt_meldungfehlerakzent', 'cms_style_h_haupt_meldunginfohinter', 'cms_style_h_haupt_meldunginfoakzent', 'cms_style_h_haupt_notizschrift'];
	var aliasdkandidaten = ['-', 'cms_style_d_haupt_schriftfarbepositiv', 'cms_style_d_haupt_schriftfarbenegativ', 'cms_style_d_haupt_hintergrund', 'cms_style_d_haupt_koerperhintergrund', 'cms_style_d_haupt_abstufung1', 'cms_style_d_haupt_abstufung2', 'cms_style_d_haupt_thema1', 'cms_style_d_haupt_thema2', 'cms_style_d_haupt_thema3', 'cms_style_d_haupt_meldungerfolghinter', 'cms_style_d_haupt_meldungerfolgakzent', 'cms_style_d_haupt_meldungwarnunghinter', 'cms_style_d_haupt_meldungwarnungakzent', 'cms_style_d_haupt_meldungfehlerhinter', 'cms_style_d_haupt_meldungfehlerakzent', 'cms_style_d_haupt_meldunginfohinter', 'cms_style_d_haupt_meldunginfoakzent', 'cms_style_d_haupt_notizschrift', 'cms_style_d_haupt_schriftfarbepositiv', 'cms_style_d_haupt_schriftfarbenegativ', 'cms_style_d_haupt_hintergrund', 'cms_style_d_haupt_koerperhintergrund', 'cms_style_d_haupt_abstufung1', 'cms_style_d_haupt_abstufung2', 'cms_style_d_haupt_thema1', 'cms_style_d_haupt_thema2', 'cms_style_d_haupt_thema3', 'cms_style_d_haupt_meldungerfolghinter', 'cms_style_d_haupt_meldungerfolgakzent', 'cms_style_d_haupt_meldungwarnunghinter', 'cms_style_d_haupt_meldungwarnungakzent', 'cms_style_d_haupt_meldungfehlerhinter', 'cms_style_d_haupt_meldungfehlerakzent', 'cms_style_d_haupt_meldunginfohinter', 'cms_style_d_haupt_meldunginfoakzent', 'cms_style_d_haupt_notizschrift'];
	var aliaswkandidaten = ['-', 'cms_style_haupt_schriftart', 'cms_style_haupt_schriftgroesse', 'cms_style_haupt_absatzwebsite', 'cms_style_haupt_absatzschulhof', 'cms_style_haupt_absatzbrotkrumen', 'cms_style_haupt_zeilenhoehewebsite', 'cms_style_haupt_zeilenhoeheschulhof', 'cms_style_haupt_seitenbreite', 'cms_style_haupt_radiussehrklein', 'cms_style_haupt_radiusklein', 'cms_style_haupt_radiusmittel', 'cms_style_haupt_radiusgross', 'cms_style_haupt_radiussehrgross'];

	var farbe = ['cms_style_h_haupt_schriftfarbepositiv', 'cms_style_h_haupt_schriftfarbenegativ', 'cms_style_h_haupt_hintergrund', 'cms_style_h_haupt_koerperhintergrund', 'cms_style_h_haupt_abstufung1', 'cms_style_h_haupt_abstufung2', 'cms_style_h_haupt_thema1', 'cms_style_h_haupt_thema2', 'cms_style_h_haupt_thema3', 'cms_style_h_haupt_meldungerfolghinter', 'cms_style_h_haupt_meldungerfolgakzent', 'cms_style_h_haupt_meldungwarnunghinter', 'cms_style_h_haupt_meldungwarnungakzent', 'cms_style_h_haupt_meldungfehlerhinter', 'cms_style_h_haupt_meldungfehlerakzent', 'cms_style_h_haupt_meldunginfohinter', 'cms_style_h_haupt_meldunginfoakzent', 'cms_style_h_haupt_notizschrift', 'cms_style_d_haupt_schriftfarbepositiv', 'cms_style_d_haupt_schriftfarbenegativ', 'cms_style_d_haupt_hintergrund', 'cms_style_d_haupt_koerperhintergrund', 'cms_style_d_haupt_abstufung1', 'cms_style_d_haupt_abstufung2', 'cms_style_d_haupt_thema1', 'cms_style_d_haupt_thema2', 'cms_style_d_haupt_thema3', 'cms_style_d_haupt_meldungerfolghinter', 'cms_style_d_haupt_meldungerfolgakzent', 'cms_style_d_haupt_meldungwarnunghinter', 'cms_style_d_haupt_meldungwarnungakzent', 'cms_style_d_haupt_meldungfehlerhinter', 'cms_style_d_haupt_meldungfehlerakzent', 'cms_style_d_haupt_meldunginfohinter', 'cms_style_d_haupt_meldunginfoakzent', 'cms_style_d_haupt_notizschrift', 'cms_style_h_link_schrift', 'cms_style_h_link_schrifthover', 'cms_style_h_button_hintergrund', 'cms_style_h_button_hintergrundhover', 'cms_style_h_button_schrift', 'cms_style_h_button_schrifthover', 'cms_style_h_formular_hintergrund', 'cms_style_h_formular_feldhintergrund', 'cms_style_h_formular_feldhoverhintergrund', 'cms_style_h_formular_feldfocushintergrund', 'cms_style_h_kopfzeile_hintergrund', 'cms_style_h_kopfzeile_buttonhintergrund', 'cms_style_h_kopfzeile_buttonhintergrund', 'cms_style_h_kopfzeile_buttonhintergrundhover', 'cms_style_h_kopfzeile_buttonschrift', 'cms_style_h_kopfzeile_buttonschrifthover', 'cms_style_h_kopfzeile_suchehintergrundhover', 'cms_style_h_kopfzeile_schattenfarbe', 'cms_style_h_logo_schriftfarbe', 'cms_style_h_hauptnavigation_kategoriefarbe', 'cms_style_h_hauptnavigation_kategoriehintergrund', 'cms_style_h_hauptnavigation_kategoriefarbehover', 'cms_style_h_hauptnavigation_kategoriehintergrundhover', 'cms_style_h_hauptnavigation_akzentfarbe', 'cms_style_h_mobilnavigation_iconhintergrund', 'cms_style_h_mobilnavigation_iconhintergrundhover', 'cms_style_h_fusszeile_hintergrund', 'cms_style_h_fusszeile_buttonhintergrund', 'cms_style_h_fusszeile_buttonschrift', 'cms_style_h_fusszeile_buttonhintergrundhover', 'cms_style_h_fusszeile_buttonschrifthover', 'cms_style_h_fusszeile_linkschrift', 'cms_style_h_fusszeile_linkschrifthover', 'cms_style_h_galerie_button', 'cms_style_h_galerie_buttonhover', 'cms_style_h_galerie_buttonaktiv', 'cms_style_h_zeitdiagramm_balken', 'cms_style_h_zeitdiagramm_balkenhover', 'cms_style_h_auszeichnung_hintergrund', 'cms_style_h_auszeichnung_schrift', 'cms_style_h_auszeichnung_hintergrundhover', 'cms_style_h_auszeichnung_schrifthover', 'cms_style_h_reiter_hintergrund', 'cms_style_h_reiter_farbe', 'cms_style_h_reiter_hintergrundhover', 'cms_style_h_reiter_farbehover', 'cms_style_h_reiter_hintergrundaktiv', 'cms_style_h_reiter_farbeaktiv', 'cms_style_h_kalenderklein_linienfarbe', 'cms_style_h_kalenderklein_hintergrundmonat', 'cms_style_h_kalenderklein_farbemonat', 'cms_style_h_kalenderklein_hintergrundtagbez', 'cms_style_h_kalenderklein_farbetagbez', 'cms_style_h_kalenderklein_hintergrundtagnr', 'cms_style_h_kalenderklein_farbetagnr', 'cms_style_h_kalendergross_linienfarbe', 'cms_style_h_kalendergross_hintergrundmonat', 'cms_style_h_kalendergross_farbemonat', 'cms_style_h_kalendergross_hintergrundtagbez', 'cms_style_h_kalendergross_farbetagbez', 'cms_style_h_kalendergross_hintergrundtagnr', 'cms_style_h_kalendergross_farbetagnr', 'cms_style_h_zugehoerig_hintergrundhover', 'cms_style_h_zugehoerig_farbehover', 'cms_style_h_hinweis_hintergrund', 'cms_style_h_neuigkeit_schrift', 'cms_style_h_neuigkeit_schrifthover', 'cms_style_h_chat_eigen', 'cms_style_h_chat_gegenueber', 'cms_style_d_link_schrift', 'cms_style_d_link_schrifthover', 'cms_style_d_button_hintergrund', 'cms_style_d_button_hintergrundhover', 'cms_style_d_button_schrift', 'cms_style_d_button_schrifthover', 'cms_style_d_formular_hintergrund', 'cms_style_d_formular_feldhintergrund', 'cms_style_d_formular_feldhoverhintergrund', 'cms_style_d_formular_feldfocushintergrund', 'cms_style_d_kopfzeile_hintergrund', 'cms_style_d_kopfzeile_buttonhintergrund', 'cms_style_d_kopfzeile_buttonhintergrund', 'cms_style_d_kopfzeile_buttonhintergrundhover', 'cms_style_d_kopfzeile_buttonschrift', 'cms_style_d_kopfzeile_buttonschrifthover', 'cms_style_d_kopfzeile_suchehintergrundhover', 'cms_style_d_kopfzeile_schattenfarbe', 'cms_style_d_logo_schriftfarbe', 'cms_style_d_hauptnavigation_kategoriefarbe', 'cms_style_d_hauptnavigation_kategoriehintergrund', 'cms_style_d_hauptnavigation_kategoriefarbehover', 'cms_style_d_hauptnavigation_kategoriehintergrundhover', 'cms_style_d_hauptnavigation_akzentfarbe', 'cms_style_d_mobilnavigation_iconhintergrund', 'cms_style_d_mobilnavigation_iconhintergrundhover', 'cms_style_d_fusszeile_hintergrund', 'cms_style_d_fusszeile_buttonhintergrund', 'cms_style_d_fusszeile_buttonschrift', 'cms_style_d_fusszeile_buttonhintergrundhover', 'cms_style_d_fusszeile_buttonschrifthover', 'cms_style_d_fusszeile_linkschrift', 'cms_style_d_fusszeile_linkschrifthover', 'cms_style_d_galerie_button', 'cms_style_d_galerie_buttonhover', 'cms_style_d_galerie_buttonaktiv', 'cms_style_d_zeitdiagramm_balken', 'cms_style_d_zeitdiagramm_balkenhover', 'cms_style_d_auszeichnung_hintergrund', 'cms_style_d_auszeichnung_schrift', 'cms_style_d_auszeichnung_hintergrundhover', 'cms_style_d_auszeichnung_schrifthover', 'cms_style_d_reiter_hintergrund', 'cms_style_d_reiter_farbe', 'cms_style_d_reiter_hintergrundhover', 'cms_style_d_reiter_farbehover', 'cms_style_d_reiter_hintergrundaktiv', 'cms_style_d_reiter_farbeaktiv', 'cms_style_d_kalenderklein_linienfarbe', 'cms_style_d_kalenderklein_hintergrundmonat', 'cms_style_d_kalenderklein_farbemonat', 'cms_style_d_kalenderklein_hintergrundtagbez', 'cms_style_d_kalenderklein_farbetagbez', 'cms_style_d_kalenderklein_hintergrundtagnr', 'cms_style_d_kalenderklein_farbetagnr', 'cms_style_d_kalendergross_linienfarbe', 'cms_style_d_kalendergross_hintergrundmonat', 'cms_style_d_kalendergross_farbemonat', 'cms_style_d_kalendergross_hintergrundtagbez', 'cms_style_d_kalendergross_farbetagbez', 'cms_style_d_kalendergross_hintergrundtagnr', 'cms_style_d_kalendergross_farbetagnr', 'cms_style_d_zugehoerig_hintergrundhover', 'cms_style_d_zugehoerig_farbehover', 'cms_style_d_hinweis_hintergrund', 'cms_style_d_neuigkeit_schrift', 'cms_style_d_neuigkeit_schrifthover', 'cms_style_d_chat_eigen', 'cms_style_d_chat_gegenueber'];
	var abstand = ['cms_style_kopfzeile_aussenabstand', 'cms_style_hauptnavigation_aussenabstandkategorie', 'cms_style_hauptnavigation_kategorieinnenabstand', 'cms_style_auszeichnung_aussenabstand'];
	var mass = ['cms_style_haupt_schriftgroesse', 'cms_style_haupt_absatzwebsite', 'cms_style_haupt_absatzschulhof', 'cms_style_haupt_absatzbrotkrumen', 'cms_style_haupt_zeilenhoehewebsite', 'cms_style_haupt_zeilenhoeheschulhof', 'cms_style_haupt_seitenbreite', 'cms_style_haupt_radiussehrklein', 'cms_style_haupt_radiusklein', 'cms_style_haupt_radiusmittel', 'cms_style_haupt_radiusgross', 'cms_style_haupt_radiussehrgross', 'cms_style_button_rundeecken', 'cms_style_kopfzeile_abstandvonoben', 'cms_style_kopfzeile_hoehe', 'cms_style_kopfzeile_platzhalter', 'cms_style_kopfzeile_linienstaerkeunten', 'cms_style_logo_breite', 'cms_style_hauptnavigation_abstandvonoben', 'cms_style_hauptnavigation_abstandvonunten', 'cms_style_hauptnavigation_abstandvonlinks', 'cms_style_hauptnavigation_abstandvonrechts', 'cms_style_hauptnavigation_kategoriehoehe', 'cms_style_hauptnavigation_kategorieradiusol', 'cms_style_hauptnavigation_kategorieradiusor', 'cms_style_hauptnavigation_kategorieradiusul', 'cms_style_hauptnavigation_kategorieradiusur', 'cms_style_unternavigation_abstandvonoben', 'cms_style_schulhofnavigation_abstandvonoben', 'cms_style_schulhofnavigation_abstandvonunten', 'cms_style_schulhofnavigation_abstandvonlinks', 'cms_style_schulhofnavigation_abstandvonrechts', 'cms_style_fusszeile_linienstaerkeoben', 'cms_style_galerie_buttonbreite', 'cms_style_galerie_buttonhoehe', 'cms_style_zeitdiagramm_radiusoben', 'cms_style_zeitdiagramm_radiusunten', 'cms_style_auszeichnung_radius', 'cms_style_reiter_radiusoben', 'cms_style_reiter_radiusunten', 'cms_style_kalenderklein_radiusobenmonat', 'cms_style_kalenderklein_radiusuntenmonat', 'cms_style_kalenderklein_radiusobentagbez', 'cms_style_kalenderklein_radiusuntentagbez', 'cms_style_kalenderklein_radiusobentagnr', 'cms_style_kalenderklein_radiusuntentagnr', 'cms_style_kalendergross_radiusobenmonat', 'cms_style_kalendergross_radiusuntenmonat', 'cms_style_kalendergross_radiusobentagbez', 'cms_style_kalendergross_radiusuntentagbez', 'cms_style_kalendergross_radiusobentagnr', 'cms_style_kalendergross_radiusuntentagnr', 'cms_style_zugehoerig_radius', 'cms_style_hinweis_radius'];
	var schattenmass = ['cms_style_kopfzeile_schattenausmasse'];
	var text = ['cms_style_haupt_schriftart'];
	var linie = ['cms_style_kalenderklein_linienstaerkeobenmonat', 'cms_style_kalenderklein_linienstaerkeuntenmonat', 'cms_style_kalenderklein_linienstaerkelinksmonat', 'cms_style_kalenderklein_linienstaerkerechtsmonat', 'cms_style_kalenderklein_linienstaerkeobentagbez', 'cms_style_kalenderklein_linienstaerkeuntentagbez', 'cms_style_kalenderklein_linienstaerkelinkstagbez', 'cms_style_kalenderklein_linienstaerkerechtstagbez', 'cms_style_kalenderklein_linienstaerkeobentagnr', 'cms_style_kalenderklein_linienstaerkeuntentagnr', 'cms_style_kalenderklein_linienstaerkelinkstagnr', 'cms_style_kalenderklein_linienstaerkerechtstagnr', 'cms_style_kalendergross_linienstaerkeobenmonat', 'cms_style_kalendergross_linienstaerkeuntenmonat', 'cms_style_kalendergross_linienstaerkelinksmonat', 'cms_style_kalendergross_linienstaerkerechtsmonat', 'cms_style_kalendergross_linienstaerkeobentagbez', 'cms_style_kalendergross_linienstaerkeuntentagbez', 'cms_style_kalendergross_linienstaerkelinkstagbez', 'cms_style_kalendergross_linienstaerkerechtstagbez', 'cms_style_kalendergross_linienstaerkeobentagnr', 'cms_style_kalendergross_linienstaerkeuntentagnr', 'cms_style_kalendergross_linienstaerkelinkstagnr', 'cms_style_kalendergross_linienstaerkerechtstagnr'];

	var alias = ['cms_style_h_link_schrift_alias', 'cms_style_h_link_schrifthover_alias', 'cms_style_h_button_hintergrund_alias', 'cms_style_h_button_hintergrundhover_alias', 'cms_style_h_button_schrift_alias', 'cms_style_h_button_schrifthover_alias', 'cms_style_h_formular_hintergrund_alias', 'cms_style_h_formular_feldhintergrund_alias', 'cms_style_h_formular_feldhoverhintergrund_alias', 'cms_style_h_formular_feldfocushintergrund_alias', 'cms_style_h_kopfzeile_hintergrund_alias', 'cms_style_h_kopfzeile_buttonhintergrund_alias', 'cms_style_h_kopfzeile_buttonhintergrund_alias', 'cms_style_h_kopfzeile_buttonhintergrundhover_alias', 'cms_style_h_kopfzeile_buttonschrift_alias', 'cms_style_h_kopfzeile_buttonschrifthover_alias', 'cms_style_h_kopfzeile_suchehintergrundhover_alias', 'cms_style_h_kopfzeile_schattenfarbe_alias', 'cms_style_h_logo_schriftfarbe_alias', 'cms_style_h_hauptnavigation_kategoriefarbe_alias', 'cms_style_h_hauptnavigation_kategoriehintergrund_alias', 'cms_style_h_hauptnavigation_kategoriefarbehover_alias', 'cms_style_h_hauptnavigation_kategoriehintergrundhover_alias', 'cms_style_h_hauptnavigation_akzentfarbe_alias', 'cms_style_h_mobilnavigation_iconhintergrund_alias', 'cms_style_h_mobilnavigation_iconhintergrundhover_alias', 'cms_style_h_fusszeile_hintergrund_alias', 'cms_style_h_fusszeile_buttonhintergrund_alias', 'cms_style_h_fusszeile_buttonschrift_alias', 'cms_style_h_fusszeile_buttonhintergrundhover_alias', 'cms_style_h_fusszeile_buttonschrifthover_alias', 'cms_style_h_fusszeile_linkschrift_alias', 'cms_style_h_fusszeile_linkschrifthover_alias', 'cms_style_h_galerie_button_alias', 'cms_style_h_galerie_buttonhover_alias', 'cms_style_h_galerie_buttonaktiv_alias', 'cms_style_h_zeitdiagramm_balken_alias', 'cms_style_h_zeitdiagramm_balkenhover_alias', 'cms_style_h_auszeichnung_hintergrund_alias', 'cms_style_h_auszeichnung_schrift_alias', 'cms_style_h_auszeichnung_hintergrundhover_alias', 'cms_style_h_auszeichnung_schrifthover_alias', 'cms_style_h_reiter_hintergrund_alias', 'cms_style_h_reiter_farbe_alias', 'cms_style_h_reiter_hintergrundhover_alias', 'cms_style_h_reiter_farbehover_alias', 'cms_style_h_reiter_hintergrundaktiv_alias', 'cms_style_h_reiter_farbeaktiv_alias', 'cms_style_h_kalenderklein_linienfarbe_alias', 'cms_style_h_kalenderklein_hintergrundmonat_alias', 'cms_style_h_kalenderklein_farbemonat_alias', 'cms_style_h_kalenderklein_hintergrundtagbez_alias', 'cms_style_h_kalenderklein_farbetagbez_alias', 'cms_style_h_kalenderklein_hintergrundtagnr_alias', 'cms_style_h_kalenderklein_farbetagnr_alias', 'cms_style_h_kalendergross_linienfarbe_alias', 'cms_style_h_kalendergross_hintergrundmonat_alias', 'cms_style_h_kalendergross_farbemonat_alias', 'cms_style_h_kalendergross_hintergrundtagbez_alias', 'cms_style_h_kalendergross_farbetagbez_alias', 'cms_style_h_kalendergross_hintergrundtagnr_alias', 'cms_style_h_kalendergross_farbetagnr_alias', 'cms_style_h_zugehoerig_hintergrundhover_alias', 'cms_style_h_zugehoerig_farbehover_alias', 'cms_style_h_hinweis_hintergrund_alias', 'cms_style_h_neuigkeit_schrift_alias', 'cms_style_h_neuigkeit_schrifthover_alias', 'cms_style_h_chat_eigen_alias', 'cms_style_h_chat_gegenueber_alias', 'cms_style_d_link_schrift_alias', 'cms_style_d_link_schrifthover_alias', 'cms_style_d_button_hintergrund_alias', 'cms_style_d_button_hintergrundhover_alias', 'cms_style_d_button_schrift_alias', 'cms_style_d_button_schrifthover_alias', 'cms_style_d_formular_hintergrund_alias', 'cms_style_d_formular_feldhintergrund_alias', 'cms_style_d_formular_feldhoverhintergrund_alias', 'cms_style_d_formular_feldfocushintergrund_alias', 'cms_style_d_kopfzeile_hintergrund_alias', 'cms_style_d_kopfzeile_buttonhintergrund_alias', 'cms_style_d_kopfzeile_buttonhintergrund_alias', 'cms_style_d_kopfzeile_buttonhintergrundhover_alias', 'cms_style_d_kopfzeile_buttonschrift_alias', 'cms_style_d_kopfzeile_buttonschrifthover_alias', 'cms_style_d_kopfzeile_suchehintergrundhover_alias', 'cms_style_d_kopfzeile_schattenfarbe_alias', 'cms_style_d_logo_schriftfarbe_alias', 'cms_style_d_hauptnavigation_kategoriefarbe_alias', 'cms_style_d_hauptnavigation_kategoriehintergrund_alias', 'cms_style_d_hauptnavigation_kategoriefarbehover_alias', 'cms_style_d_hauptnavigation_kategoriehintergrundhover_alias', 'cms_style_d_hauptnavigation_akzentfarbe_alias', 'cms_style_d_mobilnavigation_iconhintergrund_alias', 'cms_style_d_mobilnavigation_iconhintergrundhover_alias', 'cms_style_d_fusszeile_hintergrund_alias', 'cms_style_d_fusszeile_buttonhintergrund_alias', 'cms_style_d_fusszeile_buttonschrift_alias', 'cms_style_d_fusszeile_buttonhintergrundhover_alias', 'cms_style_d_fusszeile_buttonschrifthover_alias', 'cms_style_d_fusszeile_linkschrift_alias', 'cms_style_d_fusszeile_linkschrifthover_alias', 'cms_style_d_galerie_button_alias', 'cms_style_d_galerie_buttonhover_alias', 'cms_style_d_galerie_buttonaktiv_alias', 'cms_style_d_zeitdiagramm_balken_alias', 'cms_style_d_zeitdiagramm_balkenhover_alias', 'cms_style_d_auszeichnung_hintergrund_alias', 'cms_style_d_auszeichnung_schrift_alias', 'cms_style_d_auszeichnung_hintergrundhover_alias', 'cms_style_d_auszeichnung_schrifthover_alias', 'cms_style_d_reiter_hintergrund_alias', 'cms_style_d_reiter_farbe_alias', 'cms_style_d_reiter_hintergrundhover_alias', 'cms_style_d_reiter_farbehover_alias', 'cms_style_d_reiter_hintergrundaktiv_alias', 'cms_style_d_reiter_farbeaktiv_alias', 'cms_style_d_kalenderklein_linienfarbe_alias', 'cms_style_d_kalenderklein_hintergrundmonat_alias', 'cms_style_d_kalenderklein_farbemonat_alias', 'cms_style_d_kalenderklein_hintergrundtagbez_alias', 'cms_style_d_kalenderklein_farbetagbez_alias', 'cms_style_d_kalenderklein_hintergrundtagnr_alias', 'cms_style_d_kalenderklein_farbetagnr_alias', 'cms_style_d_kalendergross_linienfarbe_alias', 'cms_style_d_kalendergross_hintergrundmonat_alias', 'cms_style_d_kalendergross_farbemonat_alias', 'cms_style_d_kalendergross_hintergrundtagbez_alias', 'cms_style_d_kalendergross_farbetagbez_alias', 'cms_style_d_kalendergross_hintergrundtagnr_alias', 'cms_style_d_kalendergross_farbetagnr_alias', 'cms_style_d_zugehoerig_hintergrundhover_alias', 'cms_style_d_zugehoerig_farbehover_alias', 'cms_style_d_hinweis_hintergrund_alias', 'cms_style_d_neuigkeit_schrift_alias', 'cms_style_d_neuigkeit_schrifthover_alias', 'cms_style_d_chat_eigen_alias', 'cms_style_d_chat_gegenueber_alias', 'cms_style_kalenderklein_linienstaerkeobenmonat_alias', 'cms_style_kalenderklein_linienstaerkeuntenmonat_alias', 'cms_style_kalenderklein_linienstaerkelinksmonat_alias', 'cms_style_kalenderklein_linienstaerkerechtsmonat_alias', 'cms_style_kalenderklein_linienstaerkeobentagbez_alias', 'cms_style_kalenderklein_linienstaerkeuntentagbez_alias', 'cms_style_kalenderklein_linienstaerkelinkstagbez_alias', 'cms_style_kalenderklein_linienstaerkerechtstagbez_alias', 'cms_style_kalenderklein_linienstaerkeobentagnr_alias', 'cms_style_kalenderklein_linienstaerkeuntentagnr_alias', 'cms_style_kalenderklein_linienstaerkelinkstagnr_alias', 'cms_style_kalenderklein_linienstaerkerechtstagnr_alias', 'cms_style_kalendergross_linienstaerkeobenmonat_alias', 'cms_style_kalendergross_linienstaerkeuntenmonat_alias', 'cms_style_kalendergross_linienstaerkelinksmonat_alias', 'cms_style_kalendergross_linienstaerkerechtsmonat_alias', 'cms_style_kalendergross_linienstaerkeobentagbez_alias', 'cms_style_kalendergross_linienstaerkeuntentagbez_alias', 'cms_style_kalendergross_linienstaerkelinkstagbez_alias', 'cms_style_kalendergross_linienstaerkerechtstagbez_alias', 'cms_style_kalendergross_linienstaerkeobentagnr_alias', 'cms_style_kalendergross_linienstaerkeuntentagnr_alias', 'cms_style_kalendergross_linienstaerkelinkstagnr_alias', 'cms_style_kalendergross_linienstaerkerechtstagnr_alias', 'cms_style_kopfzeile_aussenabstand_alias', 'cms_style_hauptnavigation_aussenabstandkategorie_alias', 'cms_style_hauptnavigation_kategorieinnenabstand_alias', 'cms_style_auszeichnung_aussenabstand_alias', 'cms_style_button_rundeecken_alias', 'cms_style_kopfzeile_abstandvonoben_alias', 'cms_style_kopfzeile_hoehe_alias', 'cms_style_kopfzeile_platzhalter_alias', 'cms_style_kopfzeile_linienstaerkeunten_alias', 'cms_style_logo_breite_alias', 'cms_style_hauptnavigation_abstandvonoben_alias', 'cms_style_hauptnavigation_abstandvonunten_alias', 'cms_style_hauptnavigation_abstandvonlinks_alias', 'cms_style_hauptnavigation_abstandvonrechts_alias', 'cms_style_hauptnavigation_kategoriehoehe_alias', 'cms_style_hauptnavigation_kategorieradiusol_alias', 'cms_style_hauptnavigation_kategorieradiusor_alias', 'cms_style_hauptnavigation_kategorieradiusul_alias', 'cms_style_hauptnavigation_kategorieradiusur_alias', 'cms_style_unternavigation_abstandvonoben_alias', 'cms_style_schulhofnavigation_abstandvonoben_alias', 'cms_style_schulhofnavigation_abstandvonunten_alias', 'cms_style_schulhofnavigation_abstandvonlinks_alias', 'cms_style_schulhofnavigation_abstandvonrechts_alias', 'cms_style_fusszeile_linienstaerkeoben_alias', 'cms_style_galerie_buttonbreite_alias', 'cms_style_galerie_buttonhoehe_alias', 'cms_style_zeitdiagramm_radiusoben_alias', 'cms_style_zeitdiagramm_radiusunten_alias', 'cms_style_auszeichnung_radius_alias', 'cms_style_reiter_radiusoben_alias', 'cms_style_reiter_radiusunten_alias', 'cms_style_kalenderklein_radiusobenmonat_alias', 'cms_style_kalenderklein_radiusuntenmonat_alias', 'cms_style_kalenderklein_radiusobentagbez_alias', 'cms_style_kalenderklein_radiusuntentagbez_alias', 'cms_style_kalenderklein_radiusobentagnr_alias', 'cms_style_kalenderklein_radiusuntentagnr_alias', 'cms_style_kalendergross_radiusobenmonat_alias', 'cms_style_kalendergross_radiusuntenmonat_alias', 'cms_style_kalendergross_radiusobentagbez_alias', 'cms_style_kalendergross_radiusuntentagbez_alias', 'cms_style_kalendergross_radiusobentagnr_alias', 'cms_style_kalendergross_radiusuntentagnr_alias', 'cms_style_zugehoerig_radius_alias', 'cms_style_hinweis_radius_alias', 'cms_style_kopfzeile_schattenausmasse_alias'];

	var positionierungen = ['cms_style_kopfzeile_positionierung'];
	var dicke = ['cms_style_kalenderklein_schriftdickemonat', 'cms_style_kalenderklein_schriftdicketagbez', 'cms_style_kalenderklein_schriftdicketagnr', 'cms_style_kalendergross_schriftdickemonat', 'cms_style_kalendergross_schriftdicketagbez', 'cms_style_kalendergross_schriftdicketagnr'];
	var anzeige = ['cms_style_logo_anzeige', 'cms_style_hauptnavigation_anzeigekategorie', 'cms_style_suche_anzeige'];

	var fehler = false;
	var formulardaten = new FormData();

	for (var i=0; i<farbe.length; i++) {
	 	var rgb = document.getElementById(farbe[i]+'_rgb').value;
	 	var alpha = document.getElementById(farbe[i]+'_alpha').value;
	 	if (!cms_stylecheck_farbe(rgb)) {fehler = true;}
	 	else {formulardaten.append(farbe[i]+'_rgb', rgb);}
	 	if (!cms_stylecheck_alpha(alpha)) {fehler = true;}
	 	else {formulardaten.append(farbe[i]+'_alpha', alpha);}
	}
	for (var i=0; i<abstand.length; i++) {
		var wert = document.getElementById(abstand[i]).value;
		if (!cms_stylecheck_abstand(wert)) {fehler = true;}
		else {formulardaten.append(abstand[i], wert);}
	}
	for (var i=0; i<mass.length; i++) {
		var wert = document.getElementById(mass[i]).value;
		if (!cms_stylecheck_mass(wert)) {fehler = true;}
		else {formulardaten.append(mass[i], wert);}
	}
	for (var i=0; i<schattenmass.length; i++) {
		var wert = document.getElementById(schattenmass[i]).value;
		if (!cms_stylecheck_schattenmass(wert)) {fehler = true;}
		else {formulardaten.append(schattenmass[i], wert);}
	}
	for (var i=0; i<text.length; i++) {
		var wert = document.getElementById(text[i]).value;
		if (!cms_stylecheck_text(wert)) {fehler = true;}
		else {formulardaten.append(text[i], wert);}
	}
	for (var i=0; i<linie.length; i++) {
		var wert = document.getElementById(linie[i]).value;
		if (!cms_stylecheck_linie(wert)) {fehler = true;}
		else {formulardaten.append(linie[i], wert);}
	}
	for (var i=0; i<alias.length; i++) {
		var wert = document.getElementById(alias[i]).value;
		var aliasfehler = false;
		if (!cms_stylecheck_alias(wert)) {aliasfehler = true;}
		if (alias[i].match(/^cms_style_h_/)) {
			if (aliashkandidaten.indexOf(wert) == -1) {aliasfehler = true;}
		}
		else if (alias[i].match(/^cms_style_d_/)) {
			if (aliasdkandidaten.indexOf(wert) == -1) {aliasfehler = true;}
		}
		else {
			if (aliaswkandidaten.indexOf(wert) == -1) {aliasfehler = true;}
		}

		if (aliasfehler) {fehler = true;}
		else {formulardaten.append(alias[i], wert);}
	}
	for (var i=0; i<positionierungen.length; i++) {
		var wert = document.getElementById(positionierungen[i]).value;
		if (!cms_stylecheck_positionierung(wert)) {fehler = true;}
		else {formulardaten.append(positionierungen[i], wert);}
	}
	for (var i=0; i<dicke.length; i++) {
		var wert = document.getElementById(dicke[i]).value;
		if (!cms_stylecheck_dicke(wert)) {fehler = true;}
		else {formulardaten.append(dicke[i], wert);}
	}
	for (var i=0; i<anzeige.length; i++) {
		var wert = document.getElementById(anzeige[i]).value;
		if (!cms_stylecheck_anzeige(wert)) {fehler = true;}
		else {formulardaten.append(anzeige[i], wert);}
	}

	formulardaten.append("anfragenziel", '393');

	if (!fehler) {
		cms_laden_an('Style ändern', 'Daten werden übernommen, Stylesheets werden erzeugt.');
		function anfragennachbehandlung(rueckgabe) {
			if (rueckgabe == "ERFOLG") {
				cms_meldung_an('erfolg', 'Style ändern', '<p>Die neuen Styleregeln wurden übernommen. Damit sie sichtbar werden, kann es notwendig sein, den Browser-Cache zu leeren.</p>', '<p><span class="cms_button" onclick="cms_link(\'Schulhof/Website/Style_ändern\');">OK</span></p>');
			}
			else {
				cms_fehlerbehandlung(rueckgabe);
			}
		}
		cms_ajaxanfrage (formulardaten, anfragennachbehandlung);
	}
	else {
		cms_meldung_an('fehler', 'Style ändern', '<p>Der neue Style konnte nicht übernommen werden, denn die Stylingregeln enthalten Fehler.</p>', '<p><span class="cms_button" onclick="cms_meldung_aus();">Zurück</span></p>');
	}
}

function cms_stylecheck_alias(text) {
	return text.match(/^(cms_style[_a-z0-9]*)|-$/);
}

function cms_stylecheck_anzeige(text) {
	return text.match(/^(inline|block|inline-block|list-item|run-in|inline-table|table|table-caption|table-cell|table-column|table-columns-group|table-footer-group|table-header-group|table-row|table-row-group|flex|none|inherit){1}$/);
}

function cms_stylecheck_positionierung(text) {
	return text.match(/^(inherit|static|relative|absolute|fixed){1}$/);
}

function cms_stylecheck_dicke(text) {
	return text.match(/^(inherit|normal|bold|bolder|lighter){1}$/);
}

function cms_stylecheck_linie(text) {
	return text.match(/^(([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}( (dotted|dashed|solid|double|groove|ridge|inset|outset|inherit){1})?)|none|inherit){1}( !important)?$/);
}

function cms_stylecheck_text(text) {
	return text.match(/^('[ -_a-zA-Z0-9]+'){1}( !important)?$/);
}

function cms_stylecheck_schattenmass(text) {
	return text.match(/^(([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}( [0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}){2})|none|inherit|auto){1}$/);
}

function cms_stylecheck_mass(text) {
	return text.match(/^(([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1})|(auto|inherit|none)){1}( !important)?$/);
}

function cms_stylecheck_abstand(text) {
	return text.match(/^([0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}( [0-9]+\.?[0-9]*(px|ex|em|in|cm|mm|pt|pc|%){1}){0,3}){1}( !important)?$/);
}

function cms_stylecheck_alpha(text) {
	return cms_check_ganzzahl(text,0,100);
}

function cms_stylecheck_farbe(text) {
	return text.match(/^#[a-fA-F0-9]{6}$/);
}


function cms_alias_auswerten(id) {
	var aliaswert = document.getElementById(id+'_alias').value;
	// Wenn Farbe 2 Werte Laden und übertragen, an sonsten nur einen
	if (id.match(/^cms_style_[hd]{1}/)) {
		var neuerfarbwert = document.getElementById(aliaswert+'_rgb').value;
		var neueralphawert = document.getElementById(aliaswert+'_alpha').value;
		document.getElementById(id+'_rgb').value = neuerfarbwert;
		document.getElementById(id+'_alpha').value = neueralphawert;
	}
	else {
		var neuerwert = document.getElementById(aliaswert).value;
		document.getElementById(id).value = neuerwert;
	}
}
