<?php
if(!isset($keininclude) || $keininclude != true) {
	include_once("../../schulhof/funktionen/texttrafo.php");
	include_once("../../allgemein/funktionen/sql.php");
	include_once("../../schulhof/funktionen/config.php");
	include_once("../../schulhof/funktionen/check.php");
	include_once("../../schulhof/funktionen/generieren.php");
}
include_once(__DIR__."/../../../../schulhof/anfragen/website/style/check.php");
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}

// Eingaben prüfen
$aliashkandidaten = array('-', 'cms_style_h_haupt_schriftfarbepositiv', 'cms_style_h_haupt_schriftfarbenegativ', 'cms_style_h_haupt_hintergrund', 'cms_style_h_haupt_koerperhintergrund', 'cms_style_h_haupt_abstufung1', 'cms_style_h_haupt_abstufung2', 'cms_style_h_haupt_thema1', 'cms_style_h_haupt_thema2', 'cms_style_h_haupt_thema3', 'cms_style_h_haupt_meldungerfolghinter', 'cms_style_h_haupt_meldungerfolgakzent', 'cms_style_h_haupt_meldungwarnunghinter', 'cms_style_h_haupt_meldungwarnungakzent', 'cms_style_h_haupt_meldungfehlerhinter', 'cms_style_h_haupt_meldungfehlerakzent', 'cms_style_h_haupt_meldunginfohinter', 'cms_style_h_haupt_meldunginfoakzent', 'cms_style_h_haupt_notizschrift', 'cms_style_h_haupt_schriftfarbepositiv', 'cms_style_h_haupt_schriftfarbenegativ', 'cms_style_h_haupt_hintergrund', 'cms_style_h_haupt_koerperhintergrund', 'cms_style_h_haupt_abstufung1', 'cms_style_h_haupt_abstufung2', 'cms_style_h_haupt_thema1', 'cms_style_h_haupt_thema2', 'cms_style_h_haupt_thema3', 'cms_style_h_haupt_meldungerfolghinter', 'cms_style_h_haupt_meldungerfolgakzent', 'cms_style_h_haupt_meldungwarnunghinter', 'cms_style_h_haupt_meldungwarnungakzent', 'cms_style_h_haupt_meldungfehlerhinter', 'cms_style_h_haupt_meldungfehlerakzent', 'cms_style_h_haupt_meldunginfohinter', 'cms_style_h_haupt_meldunginfoakzent', 'cms_style_h_haupt_notizschrift');
$aliasdkandidaten = array('-', 'cms_style_d_haupt_schriftfarbepositiv', 'cms_style_d_haupt_schriftfarbenegativ', 'cms_style_d_haupt_hintergrund', 'cms_style_d_haupt_koerperhintergrund', 'cms_style_d_haupt_abstufung1', 'cms_style_d_haupt_abstufung2', 'cms_style_d_haupt_thema1', 'cms_style_d_haupt_thema2', 'cms_style_d_haupt_thema3', 'cms_style_d_haupt_meldungerfolghinter', 'cms_style_d_haupt_meldungerfolgakzent', 'cms_style_d_haupt_meldungwarnunghinter', 'cms_style_d_haupt_meldungwarnungakzent', 'cms_style_d_haupt_meldungfehlerhinter', 'cms_style_d_haupt_meldungfehlerakzent', 'cms_style_d_haupt_meldunginfohinter', 'cms_style_d_haupt_meldunginfoakzent', 'cms_style_d_haupt_notizschrift', 'cms_style_d_haupt_schriftfarbepositiv', 'cms_style_d_haupt_schriftfarbenegativ', 'cms_style_d_haupt_hintergrund', 'cms_style_d_haupt_koerperhintergrund', 'cms_style_d_haupt_abstufung1', 'cms_style_d_haupt_abstufung2', 'cms_style_d_haupt_thema1', 'cms_style_d_haupt_thema2', 'cms_style_d_haupt_thema3', 'cms_style_d_haupt_meldungerfolghinter', 'cms_style_d_haupt_meldungerfolgakzent', 'cms_style_d_haupt_meldungwarnunghinter', 'cms_style_d_haupt_meldungwarnungakzent', 'cms_style_d_haupt_meldungfehlerhinter', 'cms_style_d_haupt_meldungfehlerakzent', 'cms_style_d_haupt_meldunginfohinter', 'cms_style_d_haupt_meldunginfoakzent', 'cms_style_d_haupt_notizschrift');
$aliaswkandidaten = array('-', 'cms_style_haupt_schriftart', 'cms_style_haupt_schriftgroesse', 'cms_style_haupt_absatzwebsite', 'cms_style_haupt_absatzschulhof', 'cms_style_haupt_absatzbrotkrumen', 'cms_style_haupt_zeilenhoehewebsite', 'cms_style_haupt_zeilenhoeheschulhof', 'cms_style_haupt_seitenbreite', 'cms_style_haupt_radiussehrklein', 'cms_style_haupt_radiusklein', 'cms_style_haupt_radiusmittel', 'cms_style_haupt_radiusgross', 'cms_style_haupt_radiussehrgross');

$farbe = array('cms_style_h_haupt_schriftfarbepositiv', 'cms_style_h_haupt_schriftfarbenegativ', 'cms_style_h_haupt_hintergrund', 'cms_style_h_haupt_koerperhintergrund', 'cms_style_h_haupt_abstufung1', 'cms_style_h_haupt_abstufung2', 'cms_style_h_haupt_thema1', 'cms_style_h_haupt_thema2', 'cms_style_h_haupt_thema3', 'cms_style_h_haupt_meldungerfolghinter', 'cms_style_h_haupt_meldungerfolgakzent', 'cms_style_h_haupt_meldungwarnunghinter', 'cms_style_h_haupt_meldungwarnungakzent', 'cms_style_h_haupt_meldungfehlerhinter', 'cms_style_h_haupt_meldungfehlerakzent', 'cms_style_h_haupt_meldunginfohinter', 'cms_style_h_haupt_meldunginfoakzent', 'cms_style_h_haupt_notizschrift', 'cms_style_d_haupt_schriftfarbepositiv', 'cms_style_d_haupt_schriftfarbenegativ', 'cms_style_d_haupt_hintergrund', 'cms_style_d_haupt_koerperhintergrund', 'cms_style_d_haupt_abstufung1', 'cms_style_d_haupt_abstufung2', 'cms_style_d_haupt_thema1', 'cms_style_d_haupt_thema2', 'cms_style_d_haupt_thema3', 'cms_style_d_haupt_meldungerfolghinter', 'cms_style_d_haupt_meldungerfolgakzent', 'cms_style_d_haupt_meldungwarnunghinter', 'cms_style_d_haupt_meldungwarnungakzent', 'cms_style_d_haupt_meldungfehlerhinter', 'cms_style_d_haupt_meldungfehlerakzent', 'cms_style_d_haupt_meldunginfohinter', 'cms_style_d_haupt_meldunginfoakzent', 'cms_style_d_haupt_notizschrift', 'cms_style_h_link_schrift', 'cms_style_h_link_schrifthover', 'cms_style_h_button_hintergrund', 'cms_style_h_button_hintergrundhover', 'cms_style_h_button_schrift', 'cms_style_h_button_schrifthover', 'cms_style_h_formular_hintergrund', 'cms_style_h_formular_feldhintergrund', 'cms_style_h_formular_feldhoverhintergrund', 'cms_style_h_formular_feldfocushintergrund', 'cms_style_h_kopfzeile_hintergrund', 'cms_style_h_kopfzeile_buttonhintergrund', 'cms_style_h_kopfzeile_buttonhintergrund', 'cms_style_h_kopfzeile_buttonhintergrundhover', 'cms_style_h_kopfzeile_buttonschrift', 'cms_style_h_kopfzeile_buttonschrifthover', 'cms_style_h_kopfzeile_suchehintergrundhover', 'cms_style_h_kopfzeile_schattenfarbe', 'cms_style_h_logo_schriftfarbe', 'cms_style_h_hauptnavigation_kategoriefarbe', 'cms_style_h_hauptnavigation_kategoriehintergrund', 'cms_style_h_hauptnavigation_kategoriefarbehover', 'cms_style_h_hauptnavigation_kategoriehintergrundhover', 'cms_style_h_hauptnavigation_akzentfarbe', 'cms_style_h_mobilnavigation_iconhintergrund', 'cms_style_h_mobilnavigation_iconhintergrundhover', 'cms_style_h_fusszeile_hintergrund', 'cms_style_h_fusszeile_buttonhintergrund', 'cms_style_h_fusszeile_buttonschrift', 'cms_style_h_fusszeile_buttonhintergrundhover', 'cms_style_h_fusszeile_buttonschrifthover', 'cms_style_h_fusszeile_linkschrift', 'cms_style_h_fusszeile_linkschrifthover', 'cms_style_h_galerie_button', 'cms_style_h_galerie_buttonhover', 'cms_style_h_galerie_buttonaktiv', 'cms_style_h_zeitdiagramm_balken', 'cms_style_h_zeitdiagramm_balkenhover', 'cms_style_h_auszeichnung_hintergrund', 'cms_style_h_auszeichnung_schrift', 'cms_style_h_auszeichnung_hintergrundhover', 'cms_style_h_auszeichnung_schrifthover', 'cms_style_h_reiter_hintergrund', 'cms_style_h_reiter_farbe', 'cms_style_h_reiter_hintergrundhover', 'cms_style_h_reiter_farbehover', 'cms_style_h_reiter_hintergrundaktiv', 'cms_style_h_reiter_farbeaktiv', 'cms_style_h_kalenderklein_linienfarbe', 'cms_style_h_kalenderklein_hintergrundmonat', 'cms_style_h_kalenderklein_farbemonat', 'cms_style_h_kalenderklein_hintergrundtagbez', 'cms_style_h_kalenderklein_farbetagbez', 'cms_style_h_kalenderklein_hintergrundtagnr', 'cms_style_h_kalenderklein_farbetagnr', 'cms_style_h_kalendergross_linienfarbe', 'cms_style_h_kalendergross_hintergrundmonat', 'cms_style_h_kalendergross_farbemonat', 'cms_style_h_kalendergross_hintergrundtagbez', 'cms_style_h_kalendergross_farbetagbez', 'cms_style_h_kalendergross_hintergrundtagnr', 'cms_style_h_kalendergross_farbetagnr', 'cms_style_h_zugehoerig_hintergrundhover', 'cms_style_h_zugehoerig_farbehover', 'cms_style_h_hinweis_hintergrund', 'cms_style_h_neuigkeit_schrift', 'cms_style_h_neuigkeit_schrifthover', 'cms_style_h_chat_eigen', 'cms_style_h_chat_gegenueber', 'cms_style_d_link_schrift', 'cms_style_d_link_schrifthover', 'cms_style_d_button_hintergrund', 'cms_style_d_button_hintergrundhover', 'cms_style_d_button_schrift', 'cms_style_d_button_schrifthover', 'cms_style_d_formular_hintergrund', 'cms_style_d_formular_feldhintergrund', 'cms_style_d_formular_feldhoverhintergrund', 'cms_style_d_formular_feldfocushintergrund', 'cms_style_d_kopfzeile_hintergrund', 'cms_style_d_kopfzeile_buttonhintergrund', 'cms_style_d_kopfzeile_buttonhintergrund', 'cms_style_d_kopfzeile_buttonhintergrundhover', 'cms_style_d_kopfzeile_buttonschrift', 'cms_style_d_kopfzeile_buttonschrifthover', 'cms_style_d_kopfzeile_suchehintergrundhover', 'cms_style_d_kopfzeile_schattenfarbe', 'cms_style_d_logo_schriftfarbe', 'cms_style_d_hauptnavigation_kategoriefarbe', 'cms_style_d_hauptnavigation_kategoriehintergrund', 'cms_style_d_hauptnavigation_kategoriefarbehover', 'cms_style_d_hauptnavigation_kategoriehintergrundhover', 'cms_style_d_hauptnavigation_akzentfarbe', 'cms_style_d_mobilnavigation_iconhintergrund', 'cms_style_d_mobilnavigation_iconhintergrundhover', 'cms_style_d_fusszeile_hintergrund', 'cms_style_d_fusszeile_buttonhintergrund', 'cms_style_d_fusszeile_buttonschrift', 'cms_style_d_fusszeile_buttonhintergrundhover', 'cms_style_d_fusszeile_buttonschrifthover', 'cms_style_d_fusszeile_linkschrift', 'cms_style_d_fusszeile_linkschrifthover', 'cms_style_d_galerie_button', 'cms_style_d_galerie_buttonhover', 'cms_style_d_galerie_buttonaktiv', 'cms_style_d_zeitdiagramm_balken', 'cms_style_d_zeitdiagramm_balkenhover', 'cms_style_d_auszeichnung_hintergrund', 'cms_style_d_auszeichnung_schrift', 'cms_style_d_auszeichnung_hintergrundhover', 'cms_style_d_auszeichnung_schrifthover', 'cms_style_d_reiter_hintergrund', 'cms_style_d_reiter_farbe', 'cms_style_d_reiter_hintergrundhover', 'cms_style_d_reiter_farbehover', 'cms_style_d_reiter_hintergrundaktiv', 'cms_style_d_reiter_farbeaktiv', 'cms_style_d_kalenderklein_linienfarbe', 'cms_style_d_kalenderklein_hintergrundmonat', 'cms_style_d_kalenderklein_farbemonat', 'cms_style_d_kalenderklein_hintergrundtagbez', 'cms_style_d_kalenderklein_farbetagbez', 'cms_style_d_kalenderklein_hintergrundtagnr', 'cms_style_d_kalenderklein_farbetagnr', 'cms_style_d_kalendergross_linienfarbe', 'cms_style_d_kalendergross_hintergrundmonat', 'cms_style_d_kalendergross_farbemonat', 'cms_style_d_kalendergross_hintergrundtagbez', 'cms_style_d_kalendergross_farbetagbez', 'cms_style_d_kalendergross_hintergrundtagnr', 'cms_style_d_kalendergross_farbetagnr', 'cms_style_d_zugehoerig_hintergrundhover', 'cms_style_d_zugehoerig_farbehover', 'cms_style_d_hinweis_hintergrund', 'cms_style_d_neuigkeit_schrift', 'cms_style_d_neuigkeit_schrifthover', 'cms_style_d_chat_eigen', 'cms_style_d_chat_gegenueber');
$abstand = array('cms_style_kopfzeile_aussenabstand', 'cms_style_hauptnavigation_aussenabstandkategorie', 'cms_style_hauptnavigation_kategorieinnenabstand', 'cms_style_auszeichnung_aussenabstand');
$mass = array('cms_style_haupt_schriftgroesse', 'cms_style_haupt_absatzwebsite', 'cms_style_haupt_absatzschulhof', 'cms_style_haupt_absatzbrotkrumen', 'cms_style_haupt_zeilenhoehewebsite', 'cms_style_haupt_zeilenhoeheschulhof', 'cms_style_haupt_seitenbreite', 'cms_style_haupt_radiussehrklein', 'cms_style_haupt_radiusklein', 'cms_style_haupt_radiusmittel', 'cms_style_haupt_radiusgross', 'cms_style_haupt_radiussehrgross', 'cms_style_button_rundeecken', 'cms_style_kopfzeile_abstandvonoben', 'cms_style_kopfzeile_hoehe', 'cms_style_kopfzeile_platzhalter', 'cms_style_kopfzeile_linienstaerkeunten', 'cms_style_logo_breite', 'cms_style_hauptnavigation_abstandvonoben', 'cms_style_hauptnavigation_abstandvonunten', 'cms_style_hauptnavigation_abstandvonlinks', 'cms_style_hauptnavigation_abstandvonrechts', 'cms_style_hauptnavigation_kategoriehoehe', 'cms_style_hauptnavigation_kategorieradiusol', 'cms_style_hauptnavigation_kategorieradiusor', 'cms_style_hauptnavigation_kategorieradiusul', 'cms_style_hauptnavigation_kategorieradiusur', 'cms_style_unternavigation_abstandvonoben', 'cms_style_schulhofnavigation_abstandvonoben', 'cms_style_schulhofnavigation_abstandvonunten', 'cms_style_schulhofnavigation_abstandvonlinks', 'cms_style_schulhofnavigation_abstandvonrechts', 'cms_style_fusszeile_linienstaerkeoben', 'cms_style_galerie_buttonbreite', 'cms_style_galerie_buttonhoehe', 'cms_style_zeitdiagramm_radiusoben', 'cms_style_zeitdiagramm_radiusunten', 'cms_style_auszeichnung_radius', 'cms_style_reiter_radiusoben', 'cms_style_reiter_radiusunten', 'cms_style_kalenderklein_radiusobenmonat', 'cms_style_kalenderklein_radiusuntenmonat', 'cms_style_kalenderklein_radiusobentagbez', 'cms_style_kalenderklein_radiusuntentagbez', 'cms_style_kalenderklein_radiusobentagnr', 'cms_style_kalenderklein_radiusuntentagnr', 'cms_style_kalendergross_radiusobenmonat', 'cms_style_kalendergross_radiusuntenmonat', 'cms_style_kalendergross_radiusobentagbez', 'cms_style_kalendergross_radiusuntentagbez', 'cms_style_kalendergross_radiusobentagnr', 'cms_style_kalendergross_radiusuntentagnr', 'cms_style_zugehoerig_radius', 'cms_style_hinweis_radius');
$schattenmass = array('cms_style_kopfzeile_schattenausmasse');
$text = array('cms_style_haupt_schriftart');
$linie = array('cms_style_kalenderklein_linienstaerkeobenmonat', 'cms_style_kalenderklein_linienstaerkeuntenmonat', 'cms_style_kalenderklein_linienstaerkelinksmonat', 'cms_style_kalenderklein_linienstaerkerechtsmonat', 'cms_style_kalenderklein_linienstaerkeobentagbez', 'cms_style_kalenderklein_linienstaerkeuntentagbez', 'cms_style_kalenderklein_linienstaerkelinkstagbez', 'cms_style_kalenderklein_linienstaerkerechtstagbez', 'cms_style_kalenderklein_linienstaerkeobentagnr', 'cms_style_kalenderklein_linienstaerkeuntentagnr', 'cms_style_kalenderklein_linienstaerkelinkstagnr', 'cms_style_kalenderklein_linienstaerkerechtstagnr', 'cms_style_kalendergross_linienstaerkeobenmonat', 'cms_style_kalendergross_linienstaerkeuntenmonat', 'cms_style_kalendergross_linienstaerkelinksmonat', 'cms_style_kalendergross_linienstaerkerechtsmonat', 'cms_style_kalendergross_linienstaerkeobentagbez', 'cms_style_kalendergross_linienstaerkeuntentagbez', 'cms_style_kalendergross_linienstaerkelinkstagbez', 'cms_style_kalendergross_linienstaerkerechtstagbez', 'cms_style_kalendergross_linienstaerkeobentagnr', 'cms_style_kalendergross_linienstaerkeuntentagnr', 'cms_style_kalendergross_linienstaerkelinkstagnr', 'cms_style_kalendergross_linienstaerkerechtstagnr');

$alias = array('cms_style_h_link_schrift_alias', 'cms_style_h_link_schrifthover_alias', 'cms_style_h_button_hintergrund_alias', 'cms_style_h_button_hintergrundhover_alias', 'cms_style_h_button_schrift_alias', 'cms_style_h_button_schrifthover_alias', 'cms_style_h_formular_hintergrund_alias', 'cms_style_h_formular_feldhintergrund_alias', 'cms_style_h_formular_feldhoverhintergrund_alias', 'cms_style_h_formular_feldfocushintergrund_alias', 'cms_style_h_kopfzeile_hintergrund_alias', 'cms_style_h_kopfzeile_buttonhintergrund_alias', 'cms_style_h_kopfzeile_buttonhintergrund_alias', 'cms_style_h_kopfzeile_buttonhintergrundhover_alias', 'cms_style_h_kopfzeile_buttonschrift_alias', 'cms_style_h_kopfzeile_buttonschrifthover_alias', 'cms_style_h_kopfzeile_suchehintergrundhover_alias', 'cms_style_h_kopfzeile_schattenfarbe_alias', 'cms_style_h_logo_schriftfarbe_alias', 'cms_style_h_hauptnavigation_kategoriefarbe_alias', 'cms_style_h_hauptnavigation_kategoriehintergrund_alias', 'cms_style_h_hauptnavigation_kategoriefarbehover_alias', 'cms_style_h_hauptnavigation_kategoriehintergrundhover_alias', 'cms_style_h_hauptnavigation_akzentfarbe_alias', 'cms_style_h_mobilnavigation_iconhintergrund_alias', 'cms_style_h_mobilnavigation_iconhintergrundhover_alias', 'cms_style_h_fusszeile_hintergrund_alias', 'cms_style_h_fusszeile_buttonhintergrund_alias', 'cms_style_h_fusszeile_buttonschrift_alias', 'cms_style_h_fusszeile_buttonhintergrundhover_alias', 'cms_style_h_fusszeile_buttonschrifthover_alias', 'cms_style_h_fusszeile_linkschrift_alias', 'cms_style_h_fusszeile_linkschrifthover_alias', 'cms_style_h_galerie_button_alias', 'cms_style_h_galerie_buttonhover_alias', 'cms_style_h_galerie_buttonaktiv_alias', 'cms_style_h_zeitdiagramm_balken_alias', 'cms_style_h_zeitdiagramm_balkenhover_alias', 'cms_style_h_auszeichnung_hintergrund_alias', 'cms_style_h_auszeichnung_schrift_alias', 'cms_style_h_auszeichnung_hintergrundhover_alias', 'cms_style_h_auszeichnung_schrifthover_alias', 'cms_style_h_reiter_hintergrund_alias', 'cms_style_h_reiter_farbe_alias', 'cms_style_h_reiter_hintergrundhover_alias', 'cms_style_h_reiter_farbehover_alias', 'cms_style_h_reiter_hintergrundaktiv_alias', 'cms_style_h_reiter_farbeaktiv_alias', 'cms_style_h_kalenderklein_linienfarbe_alias', 'cms_style_h_kalenderklein_hintergrundmonat_alias', 'cms_style_h_kalenderklein_farbemonat_alias', 'cms_style_h_kalenderklein_hintergrundtagbez_alias', 'cms_style_h_kalenderklein_farbetagbez_alias', 'cms_style_h_kalenderklein_hintergrundtagnr_alias', 'cms_style_h_kalenderklein_farbetagnr_alias', 'cms_style_h_kalendergross_linienfarbe_alias', 'cms_style_h_kalendergross_hintergrundmonat_alias', 'cms_style_h_kalendergross_farbemonat_alias', 'cms_style_h_kalendergross_hintergrundtagbez_alias', 'cms_style_h_kalendergross_farbetagbez_alias', 'cms_style_h_kalendergross_hintergrundtagnr_alias', 'cms_style_h_kalendergross_farbetagnr_alias', 'cms_style_h_zugehoerig_hintergrundhover_alias', 'cms_style_h_zugehoerig_farbehover_alias', 'cms_style_h_hinweis_hintergrund_alias', 'cms_style_h_neuigkeit_schrift_alias', 'cms_style_h_neuigkeit_schrifthover_alias', 'cms_style_h_chat_eigen_alias', 'cms_style_h_chat_gegenueber_alias', 'cms_style_d_link_schrift_alias', 'cms_style_d_link_schrifthover_alias', 'cms_style_d_button_hintergrund_alias', 'cms_style_d_button_hintergrundhover_alias', 'cms_style_d_button_schrift_alias', 'cms_style_d_button_schrifthover_alias', 'cms_style_d_formular_hintergrund_alias', 'cms_style_d_formular_feldhintergrund_alias', 'cms_style_d_formular_feldhoverhintergrund_alias', 'cms_style_d_formular_feldfocushintergrund_alias', 'cms_style_d_kopfzeile_hintergrund_alias', 'cms_style_d_kopfzeile_buttonhintergrund_alias', 'cms_style_d_kopfzeile_buttonhintergrund_alias', 'cms_style_d_kopfzeile_buttonhintergrundhover_alias', 'cms_style_d_kopfzeile_buttonschrift_alias', 'cms_style_d_kopfzeile_buttonschrifthover_alias', 'cms_style_d_kopfzeile_suchehintergrundhover_alias', 'cms_style_d_kopfzeile_schattenfarbe_alias', 'cms_style_d_logo_schriftfarbe_alias', 'cms_style_d_hauptnavigation_kategoriefarbe_alias', 'cms_style_d_hauptnavigation_kategoriehintergrund_alias', 'cms_style_d_hauptnavigation_kategoriefarbehover_alias', 'cms_style_d_hauptnavigation_kategoriehintergrundhover_alias', 'cms_style_d_hauptnavigation_akzentfarbe_alias', 'cms_style_d_mobilnavigation_iconhintergrund_alias', 'cms_style_d_mobilnavigation_iconhintergrundhover_alias', 'cms_style_d_fusszeile_hintergrund_alias', 'cms_style_d_fusszeile_buttonhintergrund_alias', 'cms_style_d_fusszeile_buttonschrift_alias', 'cms_style_d_fusszeile_buttonhintergrundhover_alias', 'cms_style_d_fusszeile_buttonschrifthover_alias', 'cms_style_d_fusszeile_linkschrift_alias', 'cms_style_d_fusszeile_linkschrifthover_alias', 'cms_style_d_galerie_button_alias', 'cms_style_d_galerie_buttonhover_alias', 'cms_style_d_galerie_buttonaktiv_alias', 'cms_style_d_zeitdiagramm_balken_alias', 'cms_style_d_zeitdiagramm_balkenhover_alias', 'cms_style_d_auszeichnung_hintergrund_alias', 'cms_style_d_auszeichnung_schrift_alias', 'cms_style_d_auszeichnung_hintergrundhover_alias', 'cms_style_d_auszeichnung_schrifthover_alias', 'cms_style_d_reiter_hintergrund_alias', 'cms_style_d_reiter_farbe_alias', 'cms_style_d_reiter_hintergrundhover_alias', 'cms_style_d_reiter_farbehover_alias', 'cms_style_d_reiter_hintergrundaktiv_alias', 'cms_style_d_reiter_farbeaktiv_alias', 'cms_style_d_kalenderklein_linienfarbe_alias', 'cms_style_d_kalenderklein_hintergrundmonat_alias', 'cms_style_d_kalenderklein_farbemonat_alias', 'cms_style_d_kalenderklein_hintergrundtagbez_alias', 'cms_style_d_kalenderklein_farbetagbez_alias', 'cms_style_d_kalenderklein_hintergrundtagnr_alias', 'cms_style_d_kalenderklein_farbetagnr_alias', 'cms_style_d_kalendergross_linienfarbe_alias', 'cms_style_d_kalendergross_hintergrundmonat_alias', 'cms_style_d_kalendergross_farbemonat_alias', 'cms_style_d_kalendergross_hintergrundtagbez_alias', 'cms_style_d_kalendergross_farbetagbez_alias', 'cms_style_d_kalendergross_hintergrundtagnr_alias', 'cms_style_d_kalendergross_farbetagnr_alias', 'cms_style_d_zugehoerig_hintergrundhover_alias', 'cms_style_d_zugehoerig_farbehover_alias', 'cms_style_d_hinweis_hintergrund_alias', 'cms_style_d_neuigkeit_schrift_alias', 'cms_style_d_neuigkeit_schrifthover_alias', 'cms_style_d_chat_eigen_alias', 'cms_style_d_chat_gegenueber_alias', 'cms_style_kalenderklein_linienstaerkeobenmonat_alias', 'cms_style_kalenderklein_linienstaerkeuntenmonat_alias', 'cms_style_kalenderklein_linienstaerkelinksmonat_alias', 'cms_style_kalenderklein_linienstaerkerechtsmonat_alias', 'cms_style_kalenderklein_linienstaerkeobentagbez_alias', 'cms_style_kalenderklein_linienstaerkeuntentagbez_alias', 'cms_style_kalenderklein_linienstaerkelinkstagbez_alias', 'cms_style_kalenderklein_linienstaerkerechtstagbez_alias', 'cms_style_kalenderklein_linienstaerkeobentagnr_alias', 'cms_style_kalenderklein_linienstaerkeuntentagnr_alias', 'cms_style_kalenderklein_linienstaerkelinkstagnr_alias', 'cms_style_kalenderklein_linienstaerkerechtstagnr_alias', 'cms_style_kalendergross_linienstaerkeobenmonat_alias', 'cms_style_kalendergross_linienstaerkeuntenmonat_alias', 'cms_style_kalendergross_linienstaerkelinksmonat_alias', 'cms_style_kalendergross_linienstaerkerechtsmonat_alias', 'cms_style_kalendergross_linienstaerkeobentagbez_alias', 'cms_style_kalendergross_linienstaerkeuntentagbez_alias', 'cms_style_kalendergross_linienstaerkelinkstagbez_alias', 'cms_style_kalendergross_linienstaerkerechtstagbez_alias', 'cms_style_kalendergross_linienstaerkeobentagnr_alias', 'cms_style_kalendergross_linienstaerkeuntentagnr_alias', 'cms_style_kalendergross_linienstaerkelinkstagnr_alias', 'cms_style_kalendergross_linienstaerkerechtstagnr_alias', 'cms_style_kopfzeile_aussenabstand_alias', 'cms_style_hauptnavigation_aussenabstandkategorie_alias', 'cms_style_hauptnavigation_kategorieinnenabstand_alias', 'cms_style_auszeichnung_aussenabstand_alias', 'cms_style_button_rundeecken_alias', 'cms_style_kopfzeile_abstandvonoben_alias', 'cms_style_kopfzeile_hoehe_alias', 'cms_style_kopfzeile_platzhalter_alias', 'cms_style_kopfzeile_linienstaerkeunten_alias', 'cms_style_logo_breite_alias', 'cms_style_hauptnavigation_abstandvonoben_alias', 'cms_style_hauptnavigation_abstandvonunten_alias', 'cms_style_hauptnavigation_abstandvonlinks_alias', 'cms_style_hauptnavigation_abstandvonrechts_alias', 'cms_style_hauptnavigation_kategoriehoehe_alias', 'cms_style_hauptnavigation_kategorieradiusol_alias', 'cms_style_hauptnavigation_kategorieradiusor_alias', 'cms_style_hauptnavigation_kategorieradiusul_alias', 'cms_style_hauptnavigation_kategorieradiusur_alias', 'cms_style_unternavigation_abstandvonoben_alias', 'cms_style_schulhofnavigation_abstandvonoben_alias', 'cms_style_schulhofnavigation_abstandvonunten_alias', 'cms_style_schulhofnavigation_abstandvonlinks_alias', 'cms_style_schulhofnavigation_abstandvonrechts_alias', 'cms_style_fusszeile_linienstaerkeoben_alias', 'cms_style_galerie_buttonbreite_alias', 'cms_style_galerie_buttonhoehe_alias', 'cms_style_zeitdiagramm_radiusoben_alias', 'cms_style_zeitdiagramm_radiusunten_alias', 'cms_style_auszeichnung_radius_alias', 'cms_style_reiter_radiusoben_alias', 'cms_style_reiter_radiusunten_alias', 'cms_style_kalenderklein_radiusobenmonat_alias', 'cms_style_kalenderklein_radiusuntenmonat_alias', 'cms_style_kalenderklein_radiusobentagbez_alias', 'cms_style_kalenderklein_radiusuntentagbez_alias', 'cms_style_kalenderklein_radiusobentagnr_alias', 'cms_style_kalenderklein_radiusuntentagnr_alias', 'cms_style_kalendergross_radiusobenmonat_alias', 'cms_style_kalendergross_radiusuntenmonat_alias', 'cms_style_kalendergross_radiusobentagbez_alias', 'cms_style_kalendergross_radiusuntentagbez_alias', 'cms_style_kalendergross_radiusobentagnr_alias', 'cms_style_kalendergross_radiusuntentagnr_alias', 'cms_style_zugehoerig_radius_alias', 'cms_style_hinweis_radius_alias', 'cms_style_kopfzeile_schattenausmasse_alias');

$positionierungen = array('cms_style_kopfzeile_positionierung');
$dicke = array('cms_style_kalenderklein_schriftdickemonat', 'cms_style_kalenderklein_schriftdicketagbez', 'cms_style_kalenderklein_schriftdicketagnr', 'cms_style_kalendergross_schriftdickemonat', 'cms_style_kalendergross_schriftdicketagbez', 'cms_style_kalendergross_schriftdicketagnr');
$anzeige = array('cms_style_logo_anzeige', 'cms_style_hauptnavigation_anzeigekategorie', 'cms_style_suche_anzeige');

$aliasbereiche = array($abstand, $mass, $schattenmass, $text, $linie);
$ohnealiasbereiche = array($positionierungen, $dicke, $anzeige);

for ($i=0; $i<count($farbe); $i++) {
	$rgb = $_POST[$farbe[$i].'_rgb'];
	$alpha = $_POST[$farbe[$i].'_alpha'];
	if (!cms_stylecheck_farbe($rgb)) {echo "FEHLER"; exit;}
	if (!cms_stylecheck_alpha($alpha)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($abstand); $i++) {
	$wert = $_POST[$abstand[$i]];
	if (!cms_stylecheck_abstand($wert)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($mass); $i++) {
	$wert = $_POST[$mass[$i]];
	if (!cms_stylecheck_mass($wert)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($schattenmass); $i++) {
	$wert = $_POST[$schattenmass[$i]];
	if (!cms_stylecheck_schattenmass($wert)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($text); $i++) {
	$wert = $_POST[$text[$i]];
	if (!cms_stylecheck_text($wert)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($linie); $i++) {
	$wert = $_POST[$linie[$i]];
	if (!cms_stylecheck_linie($wert)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($alias); $i++) {
	$wert = $_POST[$alias[$i]];
	$aliasfehler = false;
	if (!cms_stylecheck_alias($wert)) {echo "FEHLER"; exit;}
	if (preg_match("/^cms_style_h_/", $alias[$i])) {
		if (!in_array($wert, $aliashkandidaten)) {echo "FEHLER"; exit;}
	}
	else if (preg_match("/^cms_style_d_/", $alias[$i])) {
		if (!in_array($wert, $aliasdkandidaten)) {echo "FEHLER"; exit;}
	}
	else {
		if (!in_array($wert, $aliaswkandidaten)) {echo "FEHLER"; exit;}
	}
}
for ($i=0; $i<count($positionierungen); $i++) {
	$wert = $_POST[$positionierungen[$i]];
	if (!cms_stylecheck_positionierung($wert)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($dicke); $i++) {
	$wert = $_POST[$dicke[$i]];
	if (!cms_stylecheck_dicke($wert)) {echo "FEHLER"; exit;}
}
for ($i=0; $i<count($anzeige); $i++) {
	$wert = $_POST[$anzeige[$i]];
	if (!cms_stylecheck_anzeige($wert)) {echo "FEHLER"; exit;}
}


if ((isset($keininclude) && $keininclude == true) || (cms_angemeldet() && cms_r("website.styleändern"))) {
	$fehler = false;

	if (!$fehler) {
		// Datenbank erneuern
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("UPDATE style SET wert = ?, alias = ? WHERE name = ?");
		foreach($farbe AS $e) {
			$name = $e;
			$alias = NULL;
			if (isset($_POST[$e.'_alias']) && ($_POST[$e.'_alias'] != '-')) {
				$alias = $_POST[$e.'_alias'];
				$_POST[$e.'_rgb'] = $_POST[$alias.'_rgb'];
				$_POST[$e.'_alpha'] = $_POST[$alias.'_alpha'];
			}
			$r = hexdec(substr($_POST[$e.'_rgb'], 1,2));
			$g = hexdec(substr($_POST[$e.'_rgb'], 3,2));
			$b = hexdec(substr($_POST[$e.'_rgb'], 5,2));
			$alpha = $_POST[$e.'_alpha']/100;
			$_POST[$e] = "rgba($r,$g,$b,$alpha)";
			$sql->bind_param("sss", $_POST[$e], $alias, $e);
			$sql->execute();
		}
		foreach($aliasbereiche AS $b) {
			foreach($b AS $e) {
				$alias = NULL;
				if (isset($_POST[$e.'_alias']) && ($_POST[$e.'_alias'] != '-')) {$alias = $_POST[$e.'_alias']; $_POST[$e] = $_POST[$alias];}
				$sql->bind_param("sss", $_POST[$e], $alias, $e);
				$sql->execute();
			}
		}
		$alias = NULL;
		foreach($ohnealiasbereiche AS $b) {
			foreach($b AS $e) {
				$sql->bind_param("sss", $_POST[$e], $alias, $e);
				$sql->execute();
			}
		}
		$sql->close();
		cms_trennen($dbs);

		ob_start();

		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/fonts.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/seite.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/text.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/navigationen.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/buttons.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/reiter.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/links.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/meldungen.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/wechselbilder.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/formulare.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/blende.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/tabellen.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/spezialfaelle.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/termine.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/blogeintraege.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/icons.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/dateisystem.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/hinweise.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/gruppen.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/sitemap.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/seitenwahl.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/bearbeiten.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/neuigkeiten.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/responsive.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/ladeicon.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/voranmeldung.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/contextmenue.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/galerien.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/pinnwaende.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/tagebuch.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/app.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/rechtebaum.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/bedingterechte.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/buchung.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/stundenplanung.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/kalender.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/website.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/summernote.php");

		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/fontsdruck.php");
		include_once(__DIR__."/../../../../schulhof/anfragen/website/style/css/drucken.php");

		$ob = ob_get_contents();
		ob_end_clean();
		$hell = "";
		$dunkel = "";
		$drucken = "";
		$modus = null;
		foreach(explode("\n", $ob) as $zeile) {
			if(substr($zeile, 0, strlen("// HELL;")) === "// HELL;") {
				$modus = &$hell;
				continue;
			}
			if(substr($zeile, 0, strlen("// DUNKEL;")) === "// DUNKEL;") {
				$modus = &$dunkel;
				continue;
			}
			if(substr($zeile, 0, strlen("// DRUCKEN;")) === "// DRUCKEN;") {
				$modus = &$drucken;
				continue;
			}
			if(substr($zeile, 0, 2) !== "//") {	// Kommentare weglassen
				$zeile = preg_replace("/\\t*/", "", $zeile);
				$modus .= $zeile;
			}
		}

		$hell 		= preg_replace_callback("/@((?!media|font|page|-moz-document|keyframes)[\\w_\\-]+)/", function($match) {return $_POST["cms_style_{$match[1]}"];}, $hell);
		$dunkel 	= preg_replace_callback("/@((?!media|font|page|-moz-document|keyframes)[\\w_\\-]+)/", function($match) {return $_POST["cms_style_{$match[1]}"];}, $dunkel);
		$drucken 	= preg_replace_callback("/@((?!media|font|page|-moz-document|keyframes)[\\w_\\-]+)/", function($match) {return $_POST["cms_style_{$match[1]}"];}, $drucken);

		$hell 		= preg_replace("/;}/", "}", $hell);
		$dunkel 	= preg_replace("/;}/", "}", $dunkel);
		$drucken 	= preg_replace("/;}/", "}", $drucken);

		$hell 		= "$hell";
		$dunkel 	= "@media (prefers-color-scheme: dark) { $dunkel }";
		$drucken 	= "@media screen {.cms_druckseite {display: none;}} @media print { $drucken }";

		@mkdir(__DIR__."/../../../../../css");
		file_put_contents(__DIR__."/../../../../../css/hell.css", 		$hell);
		file_put_contents(__DIR__."/../../../../../css/dunkel.css", 	$dunkel);
		file_put_contents(__DIR__."/../../../../../css/drucken.css", 	$drucken);

		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
