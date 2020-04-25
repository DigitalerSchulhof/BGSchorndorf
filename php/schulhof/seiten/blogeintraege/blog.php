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

	$code .= "<table class=\"cms_zeitwahl\"><tr><td><a class=\"cms_button\" href=\"$bloglink".($jahr-1)."\">".($jahr-1)."</a></td><td>";
	for ($i = 1; $i<=12; $i++) {
		$monatsname = cms_monatsnamekomplett($i);
		if ($i == $monat) {$zusatz = "_ja";} else {$zusatz = "";}
		$code .= "<a class=\"cms_button$zusatz\" href=\"$bloglink"."$jahr/".$monatsname."\">".$monatsname."</a> ";
	}
	$code .= "</td><td><a class=\"cms_button\" href=\"$bloglink".($jahr+1)."\">".($jahr+1)."</a></td></tr></table>";

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
		else if (($CMS_BENUTZERART == 's') || ($CMS_BENUTZERART == 'e')) {$oelimit = 3;}
		else {$oelimit = 4;}

		$sqloe = "(SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, 'oe' AS art, '' AS schuljahr, '' AS sjbez, '' AS gbez, '' AS gart FROM blogeintraege WHERE (id IN ($sqlm) OR oeffentlichkeit >= $oelimit) AND (datum BETWEEN $beginn AND $ende) AND aktiv = 1)";

		$sqlin = "";
		foreach ($CMS_GRUPPEN as $g) {
			$gk = cms_textzudb($g);
			$sqlin .= " UNION (SELECT $gk"."blogeintraegeintern.id, AES_DECRYPT($gk"."blogeintraegeintern.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, NULL AS vorschaubild, 'in' AS art, schuljahr, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sjbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez, '$g' AS gart FROM $gk"."blogeintraegeintern JOIN $gk ON gruppe = $gk.id LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID) AND (datum BETWEEN $beginn AND $ende) AND aktiv = 1)";
		}

		$BLOGS = array();
		$sql = $dbs->prepare("SELECT * FROM ($sqloe $sqlin) AS x ORDER BY datum ASC, bezeichnung ASC");
		// Blogausgabe erzeugen
		if ($sql->execute()) {
			$sql->bind_result($bid, $bbez, $bautor, $bdatum, $bgenehmigt, $baktiv, $btext, $bvorschau, $bvorschaubild, $bart, $bschuljahr, $bsjbez, $bgbez, $bgart);
			while ($sql->fetch()) {
				$B = array();
				$B['id'] = $bid;
				$B['bezeichnung'] = $bbez;
				$B['autor'] = $bautor;
				$B['datum'] = $bdatum;
				$B['genehmigt'] = $bgenehmigt;
				$B['aktiv'] = $baktiv;
				$B['text'] = $btext;
				$B['vorschau'] = $bvorschau;
				$B['vorschaubild'] = $bvorschaubild;
				$B['art'] = $bart;
				$B['schuljahr'] = $bschuljahr;
				$B['sjbez'] = $bsjbez;
				$B['gbez'] = $bgbez;
				$B['gart'] = $bgart;
				array_push($BLOGS, $B);
			}
		}
		$sql->close();

		foreach ($BLOGS AS $B) {
			if ($B['art'] == 'oe') {
				$code .= cms_blogeintrag_link_ausgeben($dbs, $B, $art, $CMS_URLGANZ);
			}
			else if ($B['art'] == 'in') {
				if (is_null($B['sjbez'])) {$B['sjbez'] = "Schuljahrübergreifend";}
				$vorlink = "Schulhof/Gruppen/".cms_textzulink($B['sjbez'])."/".cms_textzulink($B['gart'])."/".cms_textzulink($B['gbez']);
				$code .= cms_blogeintrag_link_ausgeben($dbs, $B, $art, $vorlink);
			}
		}
	}

	return $code;
}
?>
