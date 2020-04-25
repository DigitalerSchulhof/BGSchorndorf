<?php
$dbs = cms_verbinden('s');

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

$gruppenrecht = cms_gruppenrechte_laden($dbs, $g, $gruppenid);
if (!$gruppenrecht['sichtbar']) {$fehler = true;}

if (!$fehler) {
	include_once('php/schulhof/seiten/termine/termineausgeben.php');
	$code = "";
	$code .= "<div class=\"cms_spalte_i\">";
		$code .= "<p class=\"cms_brotkrumen\">";
		$code .= cms_brotkrumen($CMS_URL);
		$code .= "</p>";
		$code .= "<h1>$gbez Kalender</h1>";
		$code .= cms_meldung('info', '<h4>In Kürze</h4><p>Diese Funktion steht im Moment noch nicht zur Verfügung.</p>');
	$code .= "</div>";
}
else {
	cms_fehler('Schulhof', '404');
}

echo $code;
cms_trennen($dbs);
?>
