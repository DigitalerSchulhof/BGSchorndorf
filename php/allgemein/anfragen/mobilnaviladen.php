<?php
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../website/seiten/navigationen/navigationen.php");
include_once("../../website/seiten/seitenauswertung.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER"; exit;}

$fehler = false;
if (!cms_check_ganzzahl($id)) {$fehler = true;}

$dbs = cms_verbinden();

$sql = $dbs->prepare("SELECT * FROM seiten WHERE id = ?");
$sql->bind_param("i", $id);
if ($sql->execute()) {
	$sql->bind_result($sid, $sart, $sposition, $szuordnung, $sbezeichnung, $sbeschreibung, $ssidebar, $sstatus, $sstyles, $sklassen, $sidvon, $sidzeit);
	if ($sql->fetch()) {
		$seite['id'] = $sid;
		$seite['art'] = $sart;
		$seite['position'] = $sposition;
		$seite['zuordnung'] = $szuordnung;
		$seite['bezeichnung'] = $sbezeichnung;
		$seite['beschreibung'] = $sbeschreibung;
		$seite['sidebar'] = $ssidebar;
		$seite['status'] = $sstatus;
		$seite['styles'] = $sstyles;
		$seite['klassen'] = $sklassen;
		$seite['idvon'] = $sidvon;
		$seite['idzeit'] = $sidzeit;
	}
	else {$fehler = true;}
}
else {$fehler = true;}
$sql->close();

if (!$fehler) {
	if (($seite['art'] == 's') || ($seite['art'] == 'm')) {
		echo cms_mobilnavigation_oberseite($dbs, $seite['id']);
	}
	else if (($seite['art'] == 't') || ($seite['art'] == 'g') || ($seite['art'] == 'b')) {
		$jetzt = time();
		$jahrbeginn = date('Y', $jetzt);
		$jahrende = $jahrbeginn;
		$art = '';
		if ($seite['art'] == 't') {
			$sql = $dbs->prepare("SELECT MIN(beginn) AS beginn, MAX(ende) AS ende FROM termine WHERE oeffentlichkeit = 4 AND genehmigt = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')");
			$art = 'Termine';
		}
		if ($seite['art'] == 'b') {
			$sql = $dbs->prepare("SELECT MIN(datum) AS beginn, MAX(datum) AS ende FROM blogeintraege WHERE aktiv = '1'");
			$art = 'Blog';
		}
		if ($seite['art'] == 'g') {
			$sql = $dbs->prepare("SELECT MIN(datum) AS beginn, MAX(datum) AS ende FROM galerien WHERE aktiv = '1'");
			$art = 'Galerien';
		}
		if ($sql->execute()) {
			$sql->bind_result($beginn, $ende);
			if ($sql->fetch()) {
				if (!is_null($beginn)) {
					$jahrbeginn = min($jahrbeginn, date('Y', $beginn));
					$jahrende = max($jahrende, date('Y', $ende));
				}
			}
		}
		$sql->close();
		$monat = cms_monatsnamekomplett(date('n', $jetzt));
		$code = "";
		for ($i = $jahrende; $i>=$jahrbeginn; $i--) {
			$code .= "<li><a href=\"Website/".$art."/$i/$monat\">".$i."</a></li>";
		}
		if (strlen($code) > 0) {$code = "<ul>".$code."</ul>";}
		echo $code;
	}
	else {echo "FEHLER";}
}
else {
	echo "FEHLER";
}
cms_trennen($dbs);
?>
