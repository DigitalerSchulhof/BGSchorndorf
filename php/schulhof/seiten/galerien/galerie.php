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

	$code .= "<table class=\"cms_zeitwahl\"><tr><td><a class=\"cms_button\" href=\"$galerielink".($jahr-1)."\">".($jahr-1)."</a></td><td>";
	for ($i = 1; $i<=12; $i++) {
		$monatsname = cms_monatsnamekomplett($i);
		if ($i == $monat) {$zusatz = "_ja";} else {$zusatz = "";}
		$code .= "<a class=\"cms_button$zusatz\" href=\"$galerielink"."$jahr/".$monatsname."\">".$monatsname."</a> ";
	}
	$code .= "</td><td><a class=\"cms_button\" href=\"$galerielink".($jahr+1)."\">".($jahr+1)."</a></td></tr></table>";

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

		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(autor, '$CMS_SCHLUESSEL'), datum, genehmigt, aktiv, oeffentlichkeit, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild FROM galerien WHERE (datum BETWEEN ? AND ?) AND aktiv = 1 ORDER BY datum ASC");
		$sql->bind_param("ii", $beginn, $ende);
		if ($sql->execute()) {
			$sql->bind_result($gid, $gbez, $gautor, $gdatum, $ggenehmigt, $gaktiv, $goeffentlichkeit, $gbeschreibung, $vorschaubild);
			while ($sql->fetch()) {
				$G = array();
				$G['id'] = $gid;
				$G['bezeichnung'] = $gbez;
				$G['autor'] = $gautor;
				$G['datum'] = $gdatum;
				$G['genehmigt'] = $ggenehmigt;
				$G['aktiv'] = $gaktiv;
				$G['oeffentlichkeit'] = $goeffentlichkeit;
				$G['beschreibung'] = $gbeschreibung;
				$G['vorschaubild'] = $vorschaubild;
				$code .= cms_galerie_link_ausgeben($dbs, $G, $art, $CMS_URLGANZ);
			}
		}
		$sql->close();
	}

	return $code;
}
?>
