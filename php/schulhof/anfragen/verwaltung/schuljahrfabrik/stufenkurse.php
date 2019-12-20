<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER";exit;}
if (isset($_POST['altestufen'])) {$altestufen = $_POST['altestufen'];} else {echo "FEHLER";exit;}
if (isset($_POST['faecher'])) {$faecher = $_POST['faecher'];} else {echo "FEHLER";exit;}
if (isset($_POST['erster'])) {$erster = $_POST['erster'];} else {echo "FEHLER";exit;}

if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$neuschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];} else {echo "FEHLER";exit;}
if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$altschuljahr = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];} else {echo "FEHLER";exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Planung']['Schuljahrfabrik'];

if (!cms_check_ganzzahl($stufe,0) || !cms_check_idfeld($altestufen) || !cms_check_idfeld($faecher) || (($erster != 'n') && ($erster != 'j'))) {echo "FEHLER";exit;}

$dbs = cms_verbinden('s');
if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	$FAECHERINFO = array();
	$FAECHERICONS = array();
	$FAECHERBEZ = array();
	$FAECHERKURZ = array();
	$FAECHERALTINFO = array();
	$STUFENINFO = array();
	$STUFENALTINFO = array();

	// Prüfen, ob die Stufe im neuen Schuljahr liegen
	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM stufen WHERE id = ? AND schuljahr != ?");
	$sql->bind_param("ii", $stufe, $neuschuljahr);
	if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {
			if ($anzahl > 0) {$fehler = true;}
    } else {$fehler = true;}
  } else {$fehler = true;}
  $sql->close();

	$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM stufen WHERE id = ? AND schuljahr = ?");
	$sql->bind_param("ii", $stufe, $neuschuljahr);
	if ($sql->execute()) {
    $sql->bind_result($sid, $sbez, $sicon);
    if ($sql->fetch()) {
			$STUFENINFO['id'] = $sid;
			$STUFENINFO['bez'] = $sbez;
			$STUFENINFO['icon'] = $sicon;
    }
  } else {$fehler = true;}
  $sql->close();

	// Prüfen, ob alle alten Stufen im alten Schuljahr liegen
	if (strlen($altestufen) > 0) {
		$astufenids = cms_generiere_sqlidliste($altestufen);
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM stufen WHERE id IN $astufenids AND schuljahr != ?");
		$sql->bind_param("i", $altschuljahr);
		if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {
				if ($anzahl > 0) {$fehler = true;}
      } else {$fehler = true;}
    } else {$fehler = true;}
    $sql->close();

		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM stufen WHERE id IN $astufenids AND schuljahr = ?");
		$sql->bind_param("i", $altschuljahr);
		if ($sql->execute()) {
      $sql->bind_result($asid, $asbez, $asicon);
      while ($sql->fetch()) {
				$astufe = array();
				$astufe['id'] = $asid;
				$astufe['bez'] = $asbez;
				$astufe['icon'] = $asicon;
				array_push($STUFENALTINFO, $astufe);
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
				$FAECHERNEUINFO[$fid]['icon'] = $ficon;
				$FAECHERNEUINFO[$fid]['bez'] = $fbez;
				$FAECHERNEUINFO[$fid]['kurz'] = $fkurz;
				array_push($FAECHERINFO, $fach);
      }
    } else {$fehler = true;}
    $sql->close();
	}

	// Alte Fächer
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kurz, farbe, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM faecher WHERE schuljahr = ?");
		$sql->bind_param("i", $altschuljahr);
		if ($sql->execute()) {
			$sql->bind_result($afid, $afbez, $afkurz, $affarbe, $aficon);
			while ($sql->fetch()) {
				$FAECHERALTINFO[$afid] = null;
				foreach ($FAECHERINFO as $f) {
					if (($f['bez'] == $afbez) && ($f['kurz'] == $afkurz)) {
						$FAECHERALTINFO[$afid] = $f['id'];
					}
				}
			}
		} else {$fehler = true;}
		$sql->close();
	}

	// Neue Kurse vorbereiten
	$NEUEKURSE = array();
	// Kurse nach Stufen
	if (!$fehler) {
		foreach ($FAECHERINFO as $f) {
			for ($a=1; $a<=3; $a++) {
				if (!isset($_POST['kursenachstufen_'.$STUFENINFO['id'].'_'.$f['id'].'_anzahl'.$a]) || !isset($_POST['kursenachstufen_'.$STUFENINFO['id'].'_'.$f['id'].'_zusatz'.$a])) {$fehler = true;}
				else if (!cms_check_ganzzahl($_POST['kursenachstufen_'.$STUFENINFO['id'].'_'.$f['id'].'_anzahl'.$a], 0)) {$fehler = true;}
				else {
					if ($_POST['kursenachstufen_'.$STUFENINFO['id'].'_'.$f['id'].'_anzahl'.$a] > 0) {
						for ($k=1; $k<=$_POST['kursenachstufen_'.$STUFENINFO['id'].'_'.$f['id'].'_anzahl'.$a]; $k++) {
							$kurs = array();
							$kurs['bez'] = $f['bez']." ".$STUFENINFO['bez']." ".$_POST['kursenachstufen_'.$STUFENINFO['id'].'_'.$f['id'].'_zusatz'.$a].$k;
							$kurs['icon'] = $f['icon'];
							$kurs['stufe'] = $STUFENINFO['id'];
							$kurs['kurz'] = $f['kurz']." ".$STUFENINFO['bez']." ".$_POST['kursenachstufen_'.$STUFENINFO['id'].'_'.$f['id'].'_zusatz'.$a].$k;
							$kurs['fach'] = $f['id'];
							$kurs['id'] = null;
							array_push($NEUEKURSE, $kurs);
						}
					}
				}
			}
		}
	}


	$KURSEUEBERTRAGEN = array();
	// Kurse übertragen
	if (!$fehler) {
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bez, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, AES_DECRYPT(kurzbezeichnung, '$CMS_SCHLUESSEL') AS kurz, fach FROM kurse WHERE stufe = ? AND schuljahr = ?");
		foreach ($STUFENALTINFO AS $a) {
			$kursnr = 1;
			$s = $STUFENINFO;
			if (!isset($_POST['kurseuebertragen_'.$a['id'].'_'.$s['id']])) {$fehler = true;}
			else if (!cms_check_toggle($_POST['kurseuebertragen_'.$a['id'].'_'.$s['id']])) {$fehler = true;}
			else {
				if ($_POST['kurseuebertragen_'.$a['id'].'_'.$s['id']] == '1') {
					// Kurse dieser Stufe laden
					$sql->bind_param("ii", $a['id'], $altschuljahr);
					if ($sql->execute()) {
						$sql->bind_result($kid, $kbez, $kicon, $kkurz, $kfach);
						while ($sql->fetch()) {
							if (!is_null($FAECHERALTINFO[$kfach])) {
								$kurs = array();
								$kurs['id'] = $kid;
								$kurs['bez'] = $kbez;
								$kurs['icon'] = $kicon;
								$kurs['kurz'] = $kkurz;
								$kurs['fachid'] = $FAECHERALTINFO[$kfach];
								$kurs['stufeid'] = $s['id'];
								array_push($KURSEUEBERTRAGEN, $kurs);
							}
						}
					}
				}
			}
		}
		$sql->close();
	}

	if (!$fehler) {
		$kursnr = 1;
		foreach ($KURSEUEBERTRAGEN as $k) {
			$neuekursnr = false;
			$kurs = array();
			$kurs['bez'] = $FAECHERNEUINFO[$k['fachid']]['bez']." ";
			if (preg_match("/^[a-zA-Z0-9ÄÖÜäöüß]+ [a-zA-Z0-9ÄÖÜäöüß]+ [a-zA-ZÄÖÜäöüß]+[0-9]+$/", $k['bez']) != 1) {
				$kurs['bez'] .= $kursnr;
				$neuekursnr = true;
			}
			else {
				$kbez = explode(" ", $k['bez']);
				$kurs['bez'] .= $kbez[count($kbez)-1];
			}
			$kurs['icon'] = $FAECHERNEUINFO[$k['fachid']]['icon'];
			$kurs['stufe'] = $k['stufeid'];
			$kurs['kurz'] = $FAECHERNEUINFO[$k['fachid']]['kurz']." ";
			if (preg_match("/^[a-zA-Z0-9ÄÖÜäöüß]+ [a-zA-Z0-9ÄÖÜäöüß]+ [a-zA-ZÄÖÜäöüß]+[0-9]+$/", $k['kurz']) != 1) {
				$kurs['kurz'] .= $kursnr;
				$neuekursnr = true;
			}
			else {
				$kkurz = explode(" ", $k['kurz']);
				$kurs['kurz'] .= $kkurz[count($kkurz)-1];
			}
			$kurs['fach'] = $k['fachid'];
			$kurs['id'] = $k['id'];
			array_push($NEUEKURSE, $kurs);
			if (!$neuekursnr) {$kursnr++;}
		}
	}

	if (!$fehler) {
		if ($erster == 'j') {
			// Dateisystem alter Kurse löschen
			$sql = $dbs->prepare("SELECT id FROM kurse WHERE schuljahr = ? AND id NOT IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT id FROM klassen WHERE schuljahr = ?))");
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
			$sql = $dbs->prepare("DELETE FROM kurse WHERE schuljahr = ? AND id NOT IN (SELECT kurs FROM kurseklassen WHERE klasse IN (SELECT id FROM klassen WHERE schuljahr = ?))");
			$sql->bind_param("ii", $neuschuljahr, $neuschuljahr);
			$sql->execute();
			$sql->close();
		}

		// Kurse anlegen
		$sql = $dbs->prepare("UPDATE kurse SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stufe = ?, kurzbezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), fach = ?, kursbezextern = AES_ENCRYPT('', '$CMS_SCHLUESSEL'), sichtbar = 0, schuljahr = ?, chataktiv = 0 WHERE id = ?");
		for ($i=0; $i<count($NEUEKURSE); $i++) {
			$id = cms_generiere_kleinste_id('kurse');
			$NEUEKURSE[$i]['neuid'] = $id;
			$sql->bind_param("ssisiii", $NEUEKURSE[$i]['bez'], $NEUEKURSE[$i]['icon'], $NEUEKURSE[$i]['stufe'], $NEUEKURSE[$i]['kurz'], $NEUEKURSE[$i]['fach'], $neuschuljahr, $id);
			$sql->execute();
		}
		$sql->close();

		// Mitglieder übertragen
		$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT ? AS gruppe, person, dateiupload, dateidownload, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon FROM kursemitglieder WHERE gruppe = ?");
		foreach ($NEUEKURSE AS $n) {
			if (!is_null($n['id'])) {
				$sql->bind_param("si", $n['neuid'], $n['id']);
				$sql->execute();
				$sql->close();
			}
		}
		$sql = $dbs->prepare("INSERT INTO kursevorsitz (gruppe, person) SELECT ? AS gruppe, person FROM kursevorsitz WHERE gruppe = ?");
		foreach ($NEUEKURSE AS $n) {
			if (!is_null($n['id'])) {
				$sql->bind_param("si", $n['neuid'], $n['id']);
				$sql->execute();
				$sql->close();
			}
		}
		$sql = $dbs->prepare("INSERT INTO kurseaufsicht (gruppe, person) SELECT ? AS gruppe, person FROM kurseaufsicht WHERE gruppe = ?");
		foreach ($NEUEKURSE AS $n) {
			if (!is_null($n['id'])) {
				$sql->bind_param("si", $n['neuid'], $n['id']);
				$sql->execute();
				$sql->close();
			}
		}

		// Personen der Klassen in die jeweilige Stufen übernehmen
		$sql = $dbs->prepare("INSERT INTO stufenmitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon) SELECT stufe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten, chatbannbis, chatbannvon FROM kursemitglieder JOIN kurse ON kursemitglieder.gruppe = kurse.id WHERE schuljahr = ?");
		$sql->bind_param("i", $neuschuljahr);
		$sql->close();

		echo "ERFOLG";
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
