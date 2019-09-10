<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['id'])) {$id = $_POST['id'];} else {echo "FEHLER";exit;}

if (!cms_check_ganzzahl($id,0) && ($id !== '-')) {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Vertretungsplanung durchführen'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	$dbs = cms_verbinden("s");

	$sql = "SELECT COUNT(*) AS anzahl, unterricht.kurs, traum, tlehrer, stufe, klasse FROM unterricht JOIN kurse ON unterricht.kurs = kurse.id LEFT JOIN kurseklassen ON kurse.id = kurseklassen.kurs WHERE unterricht.id = ?";
	$sql = $dbs->prepare($sql);
	$sql -> bind_param("i", $id);
	if ($sql -> execute()) {
		$sql->bind_result ($anzahl, $kurs, $raum, $lehrer, $stufe, $klasse);
		if ($sql -> fetch()) {
			if ($anzahl > 0) {
				if ($stufe === null) {$stufe = '-';}
				if ($klasse === null) {$klasse = '-';}
			}
			else {$fehler = true;}
		} else {$fehler = true;}
	} else {$fehler = true;}

	if (!$fehler) {
		$_SESSION['VERTRETUNGSPLANUNGSTUFEN'] = $stufe;
		$_SESSION['VERTRETUNGSPLANUNGKLASSEN'] = $klasse;
		$_SESSION['VERTRETUNGSPLANUNGKURSE'] = $kurs;
		$_SESSION['VERTRETUNGSPLANUNGLEHRER'] = $lehrer;
		$_SESSION['VERTRETUNGSPLANUNGRAUM'] = $raum;


		$_SESSION['VERTRETUNGSPLANUNGSTUNDE'] = $id;
		$_SESSION['VERTRETUNGSPLANUNGOPTION'] = 'x';
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
	cms_trennen($dbs);
}
else {
	echo "BERECHTIGUNG";
}
?>
