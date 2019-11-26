<?php
include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
	$MONATSNAMEN = array('Januar','Februar','März','April','Mai','Juni','Juli','August','September','Oktober','November','Dezember');

	// Prüfen, ob Jahreszahlen agegeben wurden
	if (!isset($CMS_URL[2]) || (!cms_check_ganzzahl($CMS_URL[2],0))) {$CMS_URL[2] = date('Y');}
	if (!isset($CMS_URL[3]) || (!in_array($CMS_URL[3], $MONATSNAMEN))) {$CMS_URL[3] = cms_monatsnamekomplett(date('m'));}

	$jahr = $CMS_URL[2];
	$monat = cms_monatnamezuzahl($CMS_URL[3]);

	$code .= "<p class=\"cms_brotkrumen\">";
	$code .= cms_brotkrumen($CMS_URL);
	$code .= "</p>";
	$code .= "<h1>Blog</h1>";

	$bloglink = implode('/', array_slice($CMS_URL,0,2)).'/';

	$code .= "<p>";
		$code .= "<a class=\"cms_button\" href=\"$bloglink".($jahr-1)."\">".($jahr-1)."</a> ";
		for ($i = 1; $i<=12; $i++) {
			$monatsname = cms_monatsnamekomplett($i);
			if ($i == $monat) {$zusatz = "_ja";} else {$zusatz = "";}
			$code .= "<a class=\"cms_button$zusatz\" href=\"$bloglink"."$jahr/".$monatsname."\">".$monatsname."</a> ";
		}
		$code .= "<a class=\"cms_button\" href=\"$bloglink".($jahr+1)."\">".($jahr+1)."</a> ";
	$code .= "</p>";

	$zwischenurl = implode('/', array_slice($CMS_URL,0,1));
	$blogcode = cms_blogeintraege_monat_ausgeben($dbs, 'artikel', $zwischenurl, $monat, $jahr);
	if (strlen($blogcode) > 0) {$code .= "<ul class=\"cms_bloguebersicht_artikel\">".$blogcode."</ul>";}
	else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine Blogeinträge vorhanden.</p>";}
$code .= "</div>";

echo $code;

function cms_blogeintraege_monat_ausgeben($dbs, $art, $CMS_URLGANZ, $monat, $jahr) {
	global $CMS_SCHLUESSEL, $CMS_GRUPPEN, $CMS_BENUTZERID, $CMS_BENUTZERART;
	$code = "";

	if (cms_check_ganzzahl($monat,1,12) && cms_check_ganzzahl($jahr,0)) {
    $beginn = mktime (0, 0, 0, $monat, 1, $jahr);
    $ende = mktime(0,0,0,$monat+1,1,$jahr)-1;

		$mitgliedschaftenblogs = array();

		$sqlm = "";
		foreach ($CMS_GRUPPEN as $g) {
			$gk = cms_textzudb($g);
			$sqlm .= " UNION (SELECT DISTINCT blogeintrag AS id FROM $gk"."blogeintraege WHERE gruppe IN (SELECT DISTINCT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID))";
		}
		$sqlm = substr($sqlm, 7);
		$sqlm = "SELECT DISTINCT id FROM ($sqlm) AS x";

		if ($CMS_BENUTZERART == 'l') {$oelimit = 1;}
		else if ($CMS_BENUTZERART == 'v') {$oelimit = 2;}
		else {$oelimit = 3;}

		$sqloe = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, 'oe' AS art FROM blogeintraege WHERE (id IN ($sqlm) OR oeffentlichkeit >= $oelimit) AND (datum BETWEEN $beginn AND $ende)";
		$sql = "SELECT * FROM ($sqloe) AS x ORDER BY datum DESC, bezeichnung ASC";

		// Blogausgabe erzeugen
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_blogeintrag_link_ausgeben($dbs, $daten, $art, $CMS_URLGANZ);
			}
			$anfrage->free();
		}
	}

	return $code;
}
?>
