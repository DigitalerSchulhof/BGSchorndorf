<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['stufen'])) {$stufen = $_POST['stufen'];} else {echo "FEHLER";exit;}
if (isset($_POST['faecher'])) {$faecher = $_POST['faecher'];} else {echo "FEHLER";exit;}

if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$neuschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}

cms_rechte_laden();

if (!cms_check_idfeld($stufen) || !cms_check_idfeld($faecher)) {echo "FEHLER";exit;}

$dbs = cms_verbinden('s');
if (cms_angemeldet() && r("schulhof.planung.schuljahre.fabrik")) {
	$fehler = false;

	$FAECHERINFO = array();
	$STUFENINFO = array();

	// Prüfen, ob alle Stufen im neuen Schuljahr liegen
	if (strlen($stufen) > 0) {
		$stufenids = cms_generiere_sqlidliste($stufen);
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM stufen WHERE id IN $stufenids AND schuljahr != ?");
		$sql->bind_param("i", $neuschuljahr);
		if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
				if ($anzahl > 0) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();

		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM stufen WHERE id IN $stufenids AND schuljahr = ?");
		$sql->bind_param("i", $neuschuljahr);
		if ($sql->execute()) {
      $sql->bind_result($sid, $sbez, $sicon);
      while ($sql->fetch()) {
				$stufe = array();
				$stufe['id'] = $sid;
				$stufe['bez'] = $sbez;
				$stufe['icon'] = $sicon;
				$stufe['klassenids'] = "";
				$stufe['klassen'] = array();
				array_push($STUFENINFO, $stufe);
      }
    } else {$fehler = true;}
    $sql->close();
	}

	// Prüfen, ob alle Fächer im neuen Schuljahr liegen
	if (strlen($faecher) > 0) {
		$faecherids = cms_generiere_sqlidliste($faecher);
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM faecher WHERE id IN $faecherids AND schuljahr != ?");
		$sql->bind_param("i", $neuschuljahr);
		if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
				if ($anzahl > 0) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();

		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kurz, farbe, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM faecher WHERE id IN $faecherids AND schuljahr = ?");
		$sql->bind_param("i", $neuschuljahr);
		if ($sql->execute()) {
      $sql->bind_result($fid, $fbez, $fkurz, $ffarbe, $ficon);
      while ($sql->fetch()) {
				$fach = array();
				$fach['id'] = $fid;
				$fach['bez'] = $fbez;
				$fach['kurz'] = $fkurz;
				$fach['farbe'] = $ffarbe;
				$fach['icon'] = $ficon;
				array_push($FAECHERINFO, $fach);
      }
    } else {$fehler = true;}
    $sql->close();
	}

	// Klassen in der Stufen und Schuljahrzugehörigkeit prüfen
	for ($s=0; $s<count($STUFENINFO); $s++) {
		if (!$fehler) {
			if (!isset($_POST['stufenklassen_'.$STUFENINFO[$s]['id']])) {$fehler = true;}
			else {
				$klassen = $_POST['stufenklassen_'.$STUFENINFO[$s]['id']];
				if (!cms_check_idfeld($klassen)) {$fehler = true;}
				else {
					// Prüfen, ob diese Klassen alle in der richtige Stufe und im richtigen Schuljahr liegen
					$klassenids = cms_generiere_sqlidliste($klassen);
					if (strlen($klassenids) > 2) {
						$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM klassen WHERE id IN $klassenids AND schuljahr != ? AND stufe != ?");
						$sql->bind_param("ii", $neuschuljahr, $STUFENINFO[$s]['id']);
						if ($sql->execute()) {
							$sql->bind_result($anzahl);
							if ($sql->fetch()) {
								if ($anzahl > 0) {$fehler = true;}
								else {
									$STUFENINFO[$s]['klassenids'] = $klassen;
								}
							} else {$fehler = true;}
						} else {$fehler = true;}
						$sql->close();
					}
				}
			}
		}
	}

	// KLASSEN LADEN
	if (!$fehler) {
		for ($s=0; $s<count($STUFENINFO); $s++) {
			$klassenids = cms_generiere_sqlidliste($STUFENINFO[$s]['klassenids']);
			if (strlen($klassenids) > 2) {
				$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez FROM klassen WHERE id IN $klassenids");
				if ($sql->execute()) {
					$sql->bind_result($kid, $kbez);
					while ($sql->fetch()) {
						$klasse = array();
						$klasse['id'] = $kid;
						$klasse['bez'] = $kbez;
						array_push($STUFENINFO[$s]['klassen'], $klasse);
					}
				} else {$fehler = true;}
				$sql->close();
			}
		}
	}

	// Neue Kurse vorbereiten
	$NEUEKURSE = array();
	// Kurse nach Klassen
	if (!$fehler) {
		foreach ($STUFENINFO as $s) {
			foreach ($s['klassen'] as $k) {
				foreach ($FAECHERINFO as $f) {
					if (!isset($_POST['kursenachklassen_'.$k['id'].'_'.$f['id']])) {$fehler = true; echo 'kursenachklassen_'.$k['id'].'_'.$f['id']."<br>";}
					else {
						if ($_POST['kursenachklassen_'.$k['id'].'_'.$f['id']] == '1') {
							$kurs = array();
							$kurs['bez'] = $k['bez']." ".$f['bez'];
							$kurs['icon'] = $f['icon'];
							$kurs['stufe'] = $s['id'];
							$kurs['kurz'] = $k['bez']." ".$f['kurz'];
							$kurs['fach'] = $f['id'];
							$kurs['klasse'] = $k['id'];
							array_push($NEUEKURSE, $kurs);
						}
					}
				}
			}
		}
	}

	if (!$fehler) {
		// Dateisystem alter Kurse löschen
		$sql = $dbs->prepare("SELECT id FROM kurse WHERE schuljahr = ? AND id IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT id FROM klassen WHERE schuljahr = ?))");
		$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
		if ($sql->execute()) {
			$sql->bind_result($kursid);
			while ($sql->fetch()) {
				// Dateisystem erzeugen
				$pfad = '../../../dateien/schulhof/gruppen/kurse/'.$kursid;
				if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
				mkdir($pfad);
				chmod($pfad, 0775);
			}
		}
		$sql->close();

		// Alte Kurse löschen
		$sql = $dbs->prepare("DELETE FROM kurse WHERE schuljahr = ? AND id IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT id FROM klassen WHERE schuljahr = ?))");
		$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
		$sql->execute();
		$sql->close();

		// Kurse anlegen
		$sql = $dbs->prepare("UPDATE kurse SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stufe = ?, kurzbezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), fach = ?, kursbezextern = AES_ENCRYPT('', '$CMS_SCHLUESSEL'), sichtbar = 0, schuljahr = ?, chataktiv = 0 WHERE id = ?");
		for ($i=0; $i<count($NEUEKURSE); $i++) {
			$id = cms_generiere_kleinste_id('kurse');
			$NEUEKURSE[$i]['neuid'] = $id;
			$sql->bind_param("ssisiii", $NEUEKURSE[$i]['bez'], $NEUEKURSE[$i]['icon'], $NEUEKURSE[$i]['stufe'], $NEUEKURSE[$i]['kurz'], $NEUEKURSE[$i]['fach'], $neuschuljahr, $id);
			$sql->execute();
		}
		$sql->close();

		// Kurse mit Klassen verknüpfen
		$sql = $dbs->prepare("INSERT INTO kurseklassen (kurs, klasse) VALUES (?, ?)");
		foreach ($NEUEKURSE as $n) {
			$sql->bind_param("ii", $n['neuid'], $n['klasse']);
			$sql->execute();
		}
		$sql->close();

		$uebertragungsid = cms_generiere_sessionid();
		$_SESSION['SCHULJAHRFABRIKUEBERTRAGUNGSID'] = $uebertragungsid;

		echo "ERFOLG".$uebertragungsid;
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
cms_trennen($dbs);
?>
