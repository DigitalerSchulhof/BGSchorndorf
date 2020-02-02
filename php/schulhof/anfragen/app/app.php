<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/meldungen.php");
include_once("../../schulhof/seiten/plaene/buchungen/ausgabe.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['art'])) 				  {$art = $_POST['art'];} 	                    else {$art = "";}
if (isset($_POST['benutzer'])) 			{$benutzer = $_POST['benutzer'];} 						else {$benutzer = "";}
if (isset($_POST['passwort'])) 			{$passwort = $_POST['passwort'];} 						else {$passwort = "";}

$fehler = false;
$code = "";
if ($art != 'VPLAN') {
	$fehler = true;
	$code .= cms_meldung('fehler', "<h4>Unbekannter Dienst</h4><p>Der angeforderte Dienst wird nicht angeboten.</p>");
}
else if ((strlen($benutzer) == 0) || (strlen($passwort) == 0)) {
	$fehler = true;
	$code .= cms_meldung('info', "<h4>Falsche Zugangsdaten</h4><p>Die angegebenen Zugangsdaten sind nicht korrekt und müssen in den Profileinstellungen angepasst werden.</p>");
}

$dbs = cms_verbinden('s');
if (!$fehler) {
	// Prüfen, ob die Zugangsdaten stimmen
	$sql = $dbs->prepare("SELECT personen.id AS id, AES_DECRYPT(salt, '$CMS_SCHLUESSEL') AS salt, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE benutzername = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
	$sql->bind_param("s", $benutzer);

	if ($sql->execute()) {
	  $id = "";
	  $sql->bind_result($CMS_BENUTZERID, $salt, $CMS_BENUTZERART);
	  if (!$sql->fetch()) {$fehler = true;}
	}
	else {$fehler = true;}
	$sql->close();

	if ($fehler) {
		$code .= cms_meldung('info', "<h4>Falsche Zugangsdaten</h4><p>Die angegebenen Zugangsdaten sind nicht korrekt und müssen in den Profileinstellungen angepasst werden.</p>");
	}
	else {
		$passwortsalted = $passwort.$salt;
		$passwortsalted = cms_texttrafo_e_db($passwortsalted);

		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM nutzerkonten WHERE passwort = SHA1(?) AND id = ?");
		$sql->bind_param("si", $passwortsalted, $id);

		if ($sql->execute()) {
			$anzahl = 0;
			$sql->bind_result($anzahl);
			if ($sql->fetch()) {
				if ($anzahl != 1) {$fehler = true;}
			}
			else {$fehler = true;}
		}
		else {$fehler = true;}
		$sql->close();

		if ($fehler) {
			$code .= cms_meldung('info', "<h4>Falsche Zugangsdaten</h4><p>Die angegebenen Zugangsdaten sind nicht korrekt und müssen in den Profileinstellungen angepasst werden.</p>");
		}
		else if (($CMS_BENUTZERART != 'l') && ($CMS_BENUTZERART != 's') && ($art == 'VPLAN')) {
			$code .= cms_meldung('info', "<h4>Dienst nicht zugänglich</h4><p>Für Sie ist dieser Deinst nicht zugänglich.</p>");
		}
		else {
			if ($art == 'VPLAN') {
				$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
				if ($CMS_EINSTELLUNGEN['Vertretungsplan extern'] == '1') {
					include_once('../../schulhof/seiten/verwaltung/vertretungsplanung/vplanexternausgeben.php');
					include_once('../../schulhof/seiten/verwaltung/vertretungsplanung/vplanexternpersoenlich.php');
					$vplan = cms_vertretungsplan_extern_persoenlich();
					echo "<h2>Vertretungsplan</h2>";
					if (strlen($vplan) > 0) {
						echo $vplan;
					}
					else {echo "<p class=\"cms_notiz\">Aktuell keine Vertretungen.</p>";}
				}
				else {
					include_once('../../schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
					$vplan = cms_vertretungsplan_persoenlich($dbs, true);

					echo "<h2>Mein Tag</h2>";
					if ((strlen($vplan) > 0) || (strlen($vplan) > 0)) {
						echo $vplan;
					}
					else {echo "<p class=\"cms_notiz\">Aktuell keine Vertretungen.</p>";}
				}
			}
		}
	}
}


echo $code;
?>
