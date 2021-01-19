<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$angemeldet = cms_angemeldet();
if ($angemeldet) {
	$fehler = false;
	// DAUERBRENNER SUCHEN
	$bezeichnung = cms_linkzutext($CMS_URL[count($CMS_URL)-1]);

	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, id, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL'), sichtbars, sichtbarl, sichtbare, sichtbarv, sichtbarx FROM dauerbrenner WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");

	$sql->bind_param("s", $bezeichnung);
	if ($sql->execute()) {
	  $sql->bind_result($anzahl, $id, $inhalt, $sichtbars, $sichtbarl, $sichtbare, $sichtbarv, $sichtbarx);
	  if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
	}
	$sql->close();

	if (!$fehler) {
		$zugriff = false;

		if ($CMS_BENUTZERART == 's') {if ($sichtbars == '1') {$zugriff = true;}}
		if ($CMS_BENUTZERART == 'e') {if ($sichtbare == '1') {$zugriff = true;}}
		if ($CMS_BENUTZERART == 'l') {if ($sichtbarl == '1') {$zugriff = true;}}
		if ($CMS_BENUTZERART == 'v') {if ($sichtbarv == '1') {$zugriff = true;}}
		if ($CMS_BENUTZERART == 'x') {if ($sichtbarx == '1') {$zugriff = true;}}

		if ($zugriff) {
			$code .= "<h1>$bezeichnung</h1>";
			$code .= cms_ausgabe_editor($inhalt);
		}
		else {
			$code .= "<h1>Dauerbrenneransicht</h1>";
			$code .= cms_meldung_berechtigung();
		}
	}
	else {
		cms_fehler('Schulhof', '404');
		$code = "";
	}
}
else {
	$code .= "<h1>Dauerbrenneransicht</h1>";
	$code .= cms_meldung_berechtigung();
}


$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
