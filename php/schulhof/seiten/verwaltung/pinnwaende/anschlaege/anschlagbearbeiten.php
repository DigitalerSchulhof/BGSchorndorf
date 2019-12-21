<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$angemeldet = cms_angemeldet();
if ($angemeldet) {
	$fehler = false;
	// PINNWAND SUCHEN
	$bezeichnung = cms_linkzutext($CMS_URL[count($CMS_URL)-2]);

	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, id, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), sichtbars, sichtbarl, sichtbare, sichtbarv, sichtbarx, schreibens, schreibenl, schreibene, schreibenv, schreibenx FROM pinnwaende WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");

	$sql->bind_param("s", $bezeichnung);
	if ($sql->execute()) {
	  $sql->bind_result($anzahl, $id, $beschreibung, $sichtbars, $sichtbarl, $sichtbare, $sichtbarv, $sichtbarx, $schreibens, $schreibenl, $schreibene, $schreibenv, $schreibenx);
	  if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
	}
	$sql->close();

	if (!$fehler) {
		$zugriff = false;

		if ($CMS_BENUTZERART == 's') {
			if ($schreibens == '1') {$zugriff = true;}
		}
		if ($CMS_BENUTZERART == 'e') {
			if ($schreibene == '1') {$zugriff = true;}
		}
		if ($CMS_BENUTZERART == 'l') {
			if ($schreibenl == '1') {$zugriff = true;}
		}
		if ($CMS_BENUTZERART == 'v') {
			if ($schreibenv == '1') {$zugriff = true;}
		}
		if ($CMS_BENUTZERART == 'x') {
			if ($schreibenx == '1') {$zugriff = true;}
		}

		if ($zugriff || $CMS_RECHTE['Organisation']['Pinnwandanschläge bearbeiten']) {
			$code .= "";
			$code .= "<h1>Anschlag für die Pinnwand »$bezeichnung"."« bearbeiten</h1>";

			if (isset($_SESSION["ANSCHLAGBEARBEITEN"])) {
				include_once('php/schulhof/seiten/verwaltung/pinnwaende/anschlaege/anschlaegedetails.php');
				include_once('php/schulhof/seiten/website/editor/editor.php');
				$code .=  cms_pinnwandanschlaege_ausgeben($_SESSION["ANSCHLAGBEARBEITEN"], $id);
				$code .=  "<p><span class=\"cms_button\" onclick=\"cms_pinnwandanschlag_bearbeiten('".cms_textzulink($bezeichnung)."');\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Pinnwände/".cms_textzulink($bezeichnung)."\">Abbrechen</a></p>";
			}
			else {
				$code .= cms_meldung_bastler();
			}
		}
		else {
			$code .= "<h1>Pinnwandanschläge</h1>";
			$code .= cms_meldung_berechtigung();
		}
	}
	else {
		$code .= cms_meldung_fehler();
	}
}
else {
	$code .= "<h1>Pinnwandanschläge</h1>";
	$code .= cms_meldung_berechtigung();
}


$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;

?>
