<?php
$schuljahr = cms_linkzutext($CMS_URL[2]);
$g = cms_linkzutext($CMS_URL[3]);
$gk = cms_textzudb($g);
$gbez = cms_linkzutext($CMS_URL[4]);
$gruppenid = "";

$fehler = false;

// Prüfen, ob diese Gruppe existiert
if (in_array($g, $CMS_GRUPPEN)) {
	if ($schuljahr == "Schuljahrübergreifend") {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IS NULL");
	  $sql->bind_param("s", $gbez);
	}
	else {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, sichtbar, chataktiv, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IN (SELECT id FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
	  $sql->bind_param("ss", $gbez, $schuljahr);
	}
	// Schuljahr finden
  if ($sql->execute()) {
    $sql->bind_result($gruppenid, $icon, $sichtbar, $chataktiv, $anzahl);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();
}
else {$fehler = true;}



if (!$fehler) {
	include_once('php/schulhof/seiten/blogeintraege/blogeintraegeausgeben.php');
	include_once('php/schulhof/seiten/gruppen/ausgaben.php');
	include_once('php/schulhof/seiten/verwaltung/beschluesse/beschluesse.php');
	$code = "";
	$code .= "<div class=\"cms_spalte_i\">";

		// Prüfen, ob Jahreszahlen agegeben wurden
		if (!isset($CMS_URL[6]) || (!cms_check_ganzzahl($CMS_URL[6],0))) {$CMS_URL[6] = date('Y');}

		$jahr = $CMS_URL[6];

		$code .= "<p class=\"cms_brotkrumen\">";
		$code .= cms_brotkrumen($CMS_URL);
		$code .= "</p>";
		$code .= "<h1>$gbez Beschlüsse</h1>";

		$beschlusslink = implode('/', array_slice($CMS_URL,0,6)).'/';

		$code .= "<p>";
			$code .= "<a class=\"cms_button\" href=\"$beschlusslink".($jahr-1)."\">".($jahr-1)."</a> ";
			$code .= "<a class=\"cms_button\" href=\"$beschlusslink".($jahr+1)."\">".($jahr+1)."</a> ";
		$code .= "</p>";

		$zwischenurl = implode('/', array_slice($CMS_URL,0,5));
		$blogcode = cms_gruppenbeschluesse_jahr_ausgeben($dbs, $g, $gruppenid, $zwischenurl, $jahr);
		if (strlen($blogcode) > 0) {$code .= "<ul class=\"cms_beschlussuebersicht_jahr\">".$blogcode."</ul>";}
		else {$code .= "<p class=\"cms_notiz\">Derzeit sind keine Blogeinträge vorhanden.</p>";}
	$code .= "</div>";
}
else {
	cms_fehler('Schulhof', '404');
}

echo $code;
?>
