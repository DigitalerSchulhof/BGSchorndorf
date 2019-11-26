<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION["STUNDENPLANZEITRAUM"])) {$zeitraum = $_SESSION["STUNDENPLANZEITRAUM"];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Stunden anlegen'] || $CMS_RECHTE['Planung']['Stunden löschen'];

if (cms_angemeldet() && $zugriff) {
	// Prüfen, ob Klasse in zugeordnetem Zeitraum liegt
	$fehler = false;
	$dbs = cms_verbinden('s');
	$sql = "SELECT COUNT(*) AS anzahl FROM (SELECT id, klassenstufe FROM klassen WHERE id = ?) AS x JOIN klassenstufen ON x.klassenstufe = klassenstufen.id JOIN schuljahre ON schuljahre.id = klassenstufen.schuljahr JOIN zeitraeume ON zeitraeume.schuljahr = schuljahre.id WHERE zeitraeume.id = ?";
	$sql = $dbs->prepare($dbs);
	$sql->bind_param("ii", $klasse, $zeitraum);
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		if ($sql->fetch()) {
			if ($anzahl != 1) {$fehler = true;}
		} else {$fehler = true;}
		$sql->close();
	} else {$fehler = true;}

	if (!$fehler) {
		$code = "";
		// Kurse laden
		$sql = "SELECT kurse.id AS id, AES_DECRYPT(kurse.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(faecher.bezeichnung, '$CMS_SCHLUESSEL') AS fach, AES_DECRYPT(faecher.kuerzel, '$CMS_SCHLUESSEL') AS kuerzel FROM (SELECT kurs FROM kursklassen WHERE klasse = $klasse)";
		$sql .= " AS x JOIN kurse ON x.kurs = kurse.id JOIN faecher ON kurse.fach = faecher.id ORDER BY fach ASC, bezeichnung ASC";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil ID existiert
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= "<option value=\"".$daten['id']."\">".$daten['bezeichnung']." - ".$daten['fach']." (".$daten['kuerzel'].")</option>";
			}
			$anfrage->free();
		} else {$fehler = true;}
	}
	cms_trennen($dbs);

	if ($fehler) {echo "BASTLER";}
	else if (strlen($code) == 0) {echo "KEIN";}
	else {
		echo $code;
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
