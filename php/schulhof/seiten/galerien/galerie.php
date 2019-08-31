<?php
include_once('php/schulhof/seiten/galerien/galerienausgeben.php');
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
	$code .= "<h1>Galerien</h1>";

	$galerielink = implode('/', array_slice($CMS_URL,0,2)).'/';

	$code .= "<p>";
		$code .= "<a class=\"cms_button\" href=\"$galerielink".($jahr-1)."\">".($jahr-1)."</a> ";
		for ($i = 1; $i<=12; $i++) {
			$monatsname = cms_monatsnamekomplett($i);
			if ($i == $monat) {$zusatz = "_ja";} else {$zusatz = "";}
			$code .= "<a class=\"cms_button$zusatz\" href=\"$galerielink"."$jahr/".$monatsname."\">".$monatsname."</a> ";
		}
		$code .= "<a class=\"cms_button\" href=\"$galerielink".($jahr+1)."\">".($jahr+1)."</a> ";
	$code .= "</p>";

	$zwischenurl = implode('/', array_slice($CMS_URL,0,1));
	$galeriecode = cms_galerien_monat_ausgeben($dbs, 'artikel', $zwischenurl, $monat, $jahr);
	if (strlen($galeriecode) > 0) {$code .= "<ul class=\"cms_galerieuebersicht_artikel\">".$galeriecode."</ul>";}
	else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine Galerien vorhanden.</p>";}
$code .= "</div>";

echo $code;

function cms_galerien_monat_ausgeben($dbs, $art, $CMS_URLGANZ, $monat, $jahr) {
	global $CMS_SCHLUESSEL, $CMS_GRUPPEN, $CMS_BENUTZERID, $CMS_BENUTZERART;
	$code = "";

	if (cms_check_ganzzahl($monat,1,12) && cms_check_ganzzahl($jahr,0)) {
    $beginn = mktime (0, 0, 0, $monat, 1, $jahr);
    $ende = mktime(0,0,0,$monat+1,1,$jahr)-1;

		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, oeffentlichkeit, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild FROM galerien WHERE (datum BETWEEN $beginn AND $ende)";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_galerie_link_ausgeben($dbs, $daten, $art, $CMS_URLGANZ);
			}
			$anfrage->free();
		}
	}

	return $code;
}
?>
