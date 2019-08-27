<?php
function cms_gesicherteinhalte($feldid, $netz, $aktion) {
	global $CMS_ONLOAD_EVENTS, $CMS_SEITE, $CMS_IMLN;
	$code = "";
	if (($netz == "l") && ($CMS_IMLN)) {

		$code .= "<div class=\"cms_gesichertedaten\" id=\"$feldid\">";
		$code .= "<p class=\"cms_zentriert\">Inhalte aus gesicherten Netzen werden abgerufen.</p>";

		if (($netz == "l") && ($aktion == "lehrerdatenbankdaten")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerzimmer_laden('$feldid', 'php/lehrerzimmer/anfragen/verwaltung/schulnetze/configladen.php');";}
    // Gruppeninfos laden, falls eine Gruppe angefordert wurde
    $gruppeninfos = "";
    if (substr($aktion, 0, 7) == "gruppen") {
      $gruppeninfos = "'".$_SESSION["GRUPPE"]."', '".$_SESSION["GRUPPEID"]."'";
    }
    // GRUPPEN » Blog
    if (($netz == "l") && ($aktion == "gruppenuebersicht_blog")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppenuebersicht_laden($gruppeninfos, 'blog');";}
		if (($netz == "l") && ($aktion == "gruppen_blogeintrag_bearbeiten")) {
			$blogid = $_SESSION['BLOGBEARBEITEN'];
			$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_blogeintragbearbeiten_laden($gruppeninfos, '$blogid');";
		}
		if (($netz == "l") && ($aktion == "gruppen_blogeintrag")) {
			$blogid = $_SESSION['BLOGEINTRAGID'];
			$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_blogeintragansicht_laden($gruppeninfos, $blogid);";
		}
		if (($netz == "l") && ($aktion == "gruppen_blogalleeintraege")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_blogalleeintraege_laden($gruppeninfos);";}
    // GRUPPEN » Beschlüsse
    if (($netz == "l") && ($aktion == "gruppenuebersicht_beschluesse")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppenuebersicht_laden($gruppeninfos, 'beschluesse');";}
		if (($netz == "l") && ($aktion == "gruppen_beschluessealleeintraege")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_beschluessealleeintraege_laden($gruppeninfos);";}

    // GRUPPEN » Dateien
    if (($netz == "l") && ($aktion == "gruppenuebersicht_dateien")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppenuebersicht_dateien_laden($gruppeninfos);";}

    // ALTER KRUSCHT!! NOCH NICHT GENERISCH!!
		// GREMIEN » Beschlüsse

		$code .= cms_ladeicon();
		$code .= "<p>Bitte warten...</p>";
		$code .= "</div>";
	}
	else {
		$code .= cms_meldung_geschuetzer_inhalt();
	}
	return $code;
}

function cms_gesichert($feldid, $netz) {
	global $CMS_ONLOAD_EVENTS, $CMS_ONLOAD_EXTERN_EVENTS;

	$code = "";

	// Inhalte ohne vorige Netzprüfung
	$ohnenetz[0] = 'intern';
	$ohnenetz[1] = 'interngeraete';


	if (in_array($feldid, $ohnenetz)) {
		$code .= "<div class=\"cms_gesichertedaten\" id=\"$feldid\">";
		$code .= "<p>Es wird versucht, geschützte Inhalte abzurufen.</p>";
		$code .= cms_ladeicon();
		$code .= "<p>Bitte warten...</p>";

		if (($netz == "l") && ($feldid == "intern")) {$CMS_ONLOAD_EXTERN_EVENTS .= "var CMS_BEARBEITUNGSART = window.setInterval('cms_intern_laden()', 300000);";}
		if (($netz == "l") && ($feldid == "interngeraete")) {$CMS_ONLOAD_EXTERN_EVENTS .= "var CMS_BEARBEITUNGSART = window.setInterval('cms_interngeraete_laden()', 300000);";}
		$code .= "</div>";
	}




	/*if (($netz == "l") && ($CMS_IMLN)) {

		$code .= "<div class=\"cms_gesichertedaten\" id=\"$feldid\">";
		$code .= "<p class=\"cms_zentriert\">Inhalte aus gesicherten Netzen werden abgerufen.</p>";

		if (($netz == "l") && ($aktion == "lehrerdatenbankdaten")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerzimmer_laden('$feldid', 'php/lehrerzimmer/anfragen/verwaltung/schulnetze/configladen.php');";}
    // Gruppeninfos laden, falls eine Gruppe angefordert wurde
    $gruppeninfos = "";
    if (substr($aktion, 0, 7) == "gruppen") {
      $gruppeninfos = "'".$_SESSION["GRUPPE"]."', '".$_SESSION["GRUPPEID"]."'";
    }
    // GRUPPEN » Blog
    if (($netz == "l") && ($aktion == "gruppenuebersicht_blog")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppenuebersicht_laden($gruppeninfos, 'blog');";}
		if (($netz == "l") && ($aktion == "gruppen_blogeintrag_bearbeiten")) {
			$blogid = $_SESSION['BLOGBEARBEITEN'];
			$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_blogeintragbearbeiten_laden($gruppeninfos, '$blogid');";
		}
		if (($netz == "l") && ($aktion == "gruppen_blogeintrag")) {
			$blogid = $_SESSION['BLOGEINTRAGID'];
			$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_blogeintragansicht_laden($gruppeninfos, $blogid);";
		}
		if (($netz == "l") && ($aktion == "gruppen_blogalleeintraege")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_blogalleeintraege_laden($gruppeninfos);";}
    // GRUPPEN » Beschlüsse
    if (($netz == "l") && ($aktion == "gruppenuebersicht_beschluesse")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppenuebersicht_laden($gruppeninfos, 'beschluesse');";}
		if (($netz == "l") && ($aktion == "gruppen_beschluessealleeintraege")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppen_beschluessealleeintraege_laden($gruppeninfos);";}

    // GRUPPEN » Dateien
    if (($netz == "l") && ($aktion == "gruppenuebersicht_dateien")) {$CMS_ONLOAD_EVENTS .= "cms_lehrerdb_gruppenuebersicht_dateien_laden($gruppeninfos);";}

    // ALTER KRUSCHT!! NOCH NICHT GENERISCH!!
		// GREMIEN » Beschlüsse

		$code .= cms_ladeicon();
		$code .= "<p>Bitte warten...</p>";
		$code .= "</div>";
	}
	else {
		$code .= cms_meldung_geschuetzer_inhalt();
	}*/
	return $code;
}
?>
