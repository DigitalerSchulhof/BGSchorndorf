<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../schulhof/funktionen/dateisystem.php");
include_once("../../schulhof/anfragen/verwaltung/gruppen/initial.php");
session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['bezeichnung'])) {$bezeichnung = $_POST['bezeichnung'];} else {echo "FEHLER"; exit;}
if (isset($_POST['sichtbar'])) {$sichtbar = $_POST['sichtbar'];} else {echo "FEHLER"; exit;}
if (isset($_POST['chat'])) {$chat = $_POST['chat'];} else {echo "FEHLER"; exit;}
if (isset($_POST['schuljahr'])) {$schuljahr = $_POST['schuljahr'];} else {echo "FEHLER"; exit;}
if (isset($_POST['icon'])) {$icon = $_POST['icon'];} else {echo "FEHLER"; exit;}
if (isset($_POST['mitglieder'])) {$mitglieder = $_POST['mitglieder'];} else {echo "FEHLER"; exit;}
if (isset($_POST['vorsitz'])) {$vorsitz = $_POST['vorsitz'];} else {echo "FEHLER"; exit;}
if (isset($_POST['aufsicht'])) {$aufsicht = $_POST['aufsicht'];} else {echo "FEHLER"; exit;}
if (isset($_POST['art'])) {$art = $_POST['art'];} else {echo "FEHLER"; exit;}
if (isset($_POST['import'])) {$import = $_POST['import'];} else {echo "FEHLER"; exit;}
if (!cms_valide_gruppe($art)) {echo "FEHLER"; exit;}
if (($import != 'j') && ($import != 'n')) {echo "FEHLER"; exit;}

if ($art == 'Stufen') {
	if (isset($_POST['reihenfolge'])) {$reihenfolge = $_POST['reihenfolge'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['tagebuch'])) {$tagebuch = $_POST['tagebuch'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['gfs'])) {$gfs = $_POST['gfs'];} else {echo "FEHLER"; exit;}
}
if ($art == 'Klassen') {
	if (isset($_POST['stundenplanextern'])) {$stundenplanextern = $_POST['stundenplanextern'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['klassenbezextern'])) {$klassenbezextern = $_POST['klassenbezextern'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['stufenbezextern'])) {$stufenbezextern = $_POST['stufenbezextern'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER"; exit;}
	if ((!cms_check_ganzzahl($stufe,0)) && ($stufe != '-')) {echo "FEHLER";exit;}
	if (isset($_POST['faecher'])) {$faecher = $_POST['faecher'];} else {echo "FEHLER"; exit;}
}
if ($art == 'Kurse') {
	if (isset($_POST['kurzbezeichnung'])) {$kurzbezeichnung = $_POST['kurzbezeichnung'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['stufe'])) {$stufe = $_POST['stufe'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['fach'])) {$fach = $_POST['fach'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['kursbezextern'])) {$kursbezextern = $_POST['kursbezextern'];} else {echo "FEHLER"; exit;}
	if ($import == 'j') {
		if (isset($_POST['schiene'])) {$kursschienen = $_POST['schiene'];} else {echo "FEHLER"; exit;}
		if (!cms_check_idfeld($kursschienen)) {echo "FEHLER"; exit;}
		else {
			$SCHIENEN = array();
			$kursschienen = str_replace("|null|", "|", $kursschienen."|");
			$kursschienen = substr($kursschienen, 1, -1);
			if (strlen($kursschienen) > 0) {$SCHIENEN = explode("|", $kursschienen);}
		}
	}
}

$dbs = cms_verbinden('s');

$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

$zugriff = cms_r("schulhof.gruppen.$art.anlegen");

$artk = cms_textzudb($art);
$artg = cms_vornegross($art);

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if ($art == 'Kurse') {if (!cms_check_titel($kurzbezeichnung)) {$fehler = true;}}
	if ($art == 'Stufen') {
		if (!cms_check_ganzzahl($reihenfolge,1)) {$fehler = true;}
		if (!cms_check_toggle($tagebuch)) {$fehler = true;}
		if (!cms_check_toggle($gfs)) {$fehler = true;}
	}

	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	if (!cms_check_toggle($chat)) {$fehler = true;}

	if (($sichtbar != 0) && ($sichtbar != 1) && ($sichtbar != 2) && ($sichtbar != 3)) {
		$fehler = true;
	}

	if (!$fehler) {

    // Prüfen, ob es das Schuljahr gibt
		if (cms_check_ganzzahl($schuljahr)) {
      $schuljahrtest = "schuljahr = ".$schuljahr;
			$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM schuljahre WHERE id = ?");
		  $sql->bind_param("i", $schuljahr);
		  if ($sql->execute()) {
		    $sql->bind_result($anzahl);
		    if ($sql->fetch()) {if ($anzahl != 1) {echo "SCHULJAHR"; $fehler = true;}}
				else {$fehler = true;}
		  }
		  else {$fehler = true;}
		  $sql->close();
    }
    else {
      $schuljahrtest = "schuljahr IS NULL";
    }

		// Bei Klassen und Kursen prüfen, ob die Stufe existiert
		if (($art == 'Klassen') || ($art == 'Kurse')) {
			if (($stufe != '-') && (cms_check_ganzzahl($stufe))) {
				$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM stufen WHERE id = ? AND $schuljahrtest");
				$sql->bind_param("i", $stufe);
				if ($sql->execute()) {
					$sql->bind_result($anzahl);
					if ($sql->fetch()) {if ($anzahl != 1) {echo "STUFEN"; $fehler = true;}}
					else {$fehler = true;}
				}
				else {$fehler = true;}
				$sql->close();
	    }
		}

		// Bei Klassen prüfen, ob die Fächer existieren
		if ($art == 'Klassen') {
			$faecher = explode('|', $faecher);
			$faecher = array_splice($faecher, 1);
			$faecheranzahl = count($faecher);
			if ($faecheranzahl != 0) {
				$faechertext = "(".implode(',', $faecher).")";
				if (cms_check_idliste($faechertext)) {
					$faecher = array();
					$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM faecher WHERE id IN $faechertext AND $schuljahrtest");
		  		if ($sql->execute()) {
						$sql->bind_param($fid, $fbez, $fkurz, $ficon);
		  			while ($sql->fetch()) {
							$f = array();
							$f['id'] = $fid;
							$f['bezeichnung'] = $fbez;
							$f['kuerzel'] = $fkurz;
							$f['icon'] = $ficon;
		  				array_push($faecher, $f);
		  			}
		  		}
		      else {$fehler = true;}
					$sql->close();
				}
				else {$fehler = true;}
			}
			if (count($faecher) != $faecheranzahl) {
				$fehler = true;
				echo "FAECHER";
			}
		}

		// Bei Kursen prüfen, ob die Klassen und das Fach existieren
		if ($art == 'Kurse') {
			$kurzbezeichnung = cms_texttrafo_e_db($kurzbezeichnung);
			if ($fach != '-') {
				$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM faecher WHERE id = ?");
			  $sql->bind_param("i", $fach);
			  if ($sql->execute()) {
			    $sql->bind_result($anzahl);
			    if ($sql->fetch()) {if ($anzahl != 1) {echo "FAECHER"; $fehler = true;}}
					else {$fehler = true;}
			  }
			  else {$fehler = true;}
			  $sql->close();
	    }

			$klassen = explode('|', $klassen);
			$klassen = array_splice($klassen, 1);

			if (count($klassen) != 0) {
				$klassentext = "(".implode(',', $klassen).")";
				if (cms_check_idliste($klassentext)) {
					$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM klassen WHERE id IN $klassentext");
		  		if ($sql->execute()) {
						$sql->bind_result($checkanzahl);
		  			if ($sql->fetch()) {
		  				if ($checkanzahl != count($klassen)) {
		  					$fehler = true;
		  					echo "KLASSEN";
		  				}
		  			}
		  			else {$fehler = true;}
		  		}
		      else {$fehler = true;}
					$sql->close();
				}
	      else {$fehler = true;}
	    }

			if ($import == 'j') {
				print_r($SCHIENEN);
				if (count($SCHIENEN) > 0) {
					$schienenmuster = "(".implode(',', $SCHIENEN).")";
					$sql = $dbs->prepare("SELECT COUNT(*) FROM schienen WHERE id IN $schienenmuster");
					if ($sql->execute()) {
						$sql->bind_result($checkanzahl);
						if ($sql->fetch()) {
							if ($checkanzahl != count($SCHIENEN)) {$fehler = true;}
						}
						else {$fehler = true;}
					}
					else {$fehler = true;}
					$sql->close();
				}
			}
		}

		// Prüfen, ob es in diesem Schuljahr schon eine Gruppe mit dieser Bezeichnung gibt
		$bezeichnung = cms_texttrafo_e_db($bezeichnung);

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM $artk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND $schuljahrtest");
	  $sql->bind_param("s", $bezeichnung);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

    // Prüfen ob die Mitglieder in die richtige Personengruppe gehören
    if (strlen($mitglieder) > 0) {
      $eltern = $CMS_EINSTELLUNGEN['Mitglieder '.$art.' Eltern'];
      $schueler = $CMS_EINSTELLUNGEN['Mitglieder '.$art.' Schüler'];
      $lehrer = $CMS_EINSTELLUNGEN['Mitglieder '.$art.' Lehrer'];
      $verwaltung = $CMS_EINSTELLUNGEN['Mitglieder '.$art.' Verwaltungsangestellte'];
      $extern = $CMS_EINSTELLUNGEN['Mitglieder '.$art.' Externe'];
      $sqlwherem = "";
      if ($eltern == 1) {$sqlwherem .= " AND art != AES_ENCRYPT('e', '$CMS_SCHLUESSEL')";}
      if ($schueler == 1) {$sqlwherem .= " AND art != AES_ENCRYPT('s', '$CMS_SCHLUESSEL')";}
      if ($lehrer == 1) {$sqlwherem .= " AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL')";}
      if ($verwaltung == 1) {$sqlwherem .= " AND art != AES_ENCRYPT('v', '$CMS_SCHLUESSEL')";}
      if ($extern == 1) {$sqlwherem .= " AND art != AES_ENCRYPT('x', '$CMS_SCHLUESSEL')";}

      if (strlen($sqlwherem) == 0) {
        $fehler = true;
        echo "MITGLIEDER";
      }
      else {
        $pruefids = "(".substr(str_replace('|', ',', $mitglieder),1).")";

				if (cms_check_idliste($pruefids)) {
					$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM personen WHERE id IN $pruefids $sqlwherem");
	        if ($sql->execute()) {
						$sql->bind_result($checkanzahl);
	          if ($sql->fetch()) {
	            if ($checkanzahl != 0) {
	              $fehler = true;
	              echo "MITGLIEDER";
	            }
	          }
	          else {$fehler = true;}
	        }
	        else {$fehler = true;}
					$sql->close();
				}
        else {$fehler = true;}
      }
    }

    // Prüfen ob die Aufsichten in die richtige Personengruppe gehören
    if (strlen($aufsicht) > 0) {
			$eltern = $CMS_EINSTELLUNGEN['Aufsicht '.$art.' Eltern'];
      $schueler = $CMS_EINSTELLUNGEN['Aufsicht '.$art.' Schüler'];
      $lehrer = $CMS_EINSTELLUNGEN['Aufsicht '.$art.' Lehrer'];
      $verwaltung = $CMS_EINSTELLUNGEN['Aufsicht '.$art.' Verwaltungsangestellte'];
      $extern = $CMS_EINSTELLUNGEN['Aufsicht '.$art.' Externe'];
      $sqlwherea = "";
      if ($eltern) {$sqlwherea .= " AND art != AES_ENCRYPT('e', '$CMS_SCHLUESSEL')";}
      if ($schueler) {$sqlwherea .= " AND art != AES_ENCRYPT('s', '$CMS_SCHLUESSEL')";}
      if ($lehrer) {$sqlwherea .= " AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL')";}
      if ($verwaltung) {$sqlwherea .= " AND art != AES_ENCRYPT('v', '$CMS_SCHLUESSEL')";}
      if ($extern) {$sqlwherea .= " AND art != AES_ENCRYPT('x', '$CMS_SCHLUESSEL')";}

      if (strlen($sqlwherea) == 0) {
        $fehler = true;
        echo "AUFSICHT";
      }
      else {
        $pruefids = "(".substr(str_replace('|', ',', $aufsicht),1).")";
				if (cms_check_idliste($pruefids)) {
					$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM personen WHERE id IN $pruefids $sqlwherea");
	        if ($sql->execute()) {
						$sql->bind_param($checkanzahl);
	          if ($sql->fetch()) {
	            if ($checkanzahl != 0) {
	              $fehler = true;
	              echo "AUFSICHT";
	            }
	          }
	          else {$fehler = true;}
	        }
	        else {$fehler = true;}
					$sql->close();
				}
      }
    }


    // Prüfen, ob alle Vorsitzenden Mitglieder sind
    $VOR = explode('|', substr($vorsitz, 1));
    $MIT = explode('|', substr($mitglieder, 1));
    $AUF = explode('|', substr($aufsicht, 1));

		if (strlen($vorsitz) > 0) {
	    foreach ($VOR as $i) {
	      if (!in_array($i, $MIT)) {
	        $fehler = true;
	      }
	    }
		}

    // Mitgliederinformationen laden
    if (strlen($mitglieder) > 0) {
      $M = array();
      $anzahl = 0;
      foreach ($MIT as $i) {
        $M[$anzahl]['id'] = $i;
        if (isset($_POST['mitglieder'.$i.'dateiupload'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'dateiupload'])) {
            $M[$anzahl]['dateiupload'] = $_POST['mitglieder'.$i.'dateiupload'];
          } else {$fehler = true;}
        } else {$fehler = true;}
        if (isset($_POST['mitglieder'.$i.'dateidownload'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'dateidownload'])) {
            $M[$anzahl]['dateidownload'] = $_POST['mitglieder'.$i.'dateidownload'];
          } else {$fehler = true;}
        } else {$fehler = true;}
        if (isset($_POST['mitglieder'.$i.'dateiloeschen'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'dateiloeschen'])) {
            $M[$anzahl]['dateiloeschen'] = $_POST['mitglieder'.$i.'dateiloeschen'];
          } else {$fehler = true;}
        } else {$fehler = true;}
        if (isset($_POST['mitglieder'.$i.'dateiumbenennen'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'dateiumbenennen'])) {
            $M[$anzahl]['dateiumbenennen'] = $_POST['mitglieder'.$i.'dateiumbenennen'];
          } else {$fehler = true;}
        } else {$fehler = true;}
        if (isset($_POST['mitglieder'.$i.'termine'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'termine'])) {
            $M[$anzahl]['termine'] = $_POST['mitglieder'.$i.'termine'];
          } else {$fehler = true;}
        } else {$fehler = true;}
        if (isset($_POST['mitglieder'.$i.'blogeintraege'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'blogeintraege'])) {
            $M[$anzahl]['blogeintraege'] = $_POST['mitglieder'.$i.'blogeintraege'];
          } else {$fehler = true;}
        } else {$fehler = true;}
				if (isset($_POST['mitglieder'.$i.'chatten'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'chatten'])) {
            $M[$anzahl]['chatten'] = $_POST['mitglieder'.$i.'chatten'];
          } else {$fehler = true;}
        } else {$fehler = true;}
				if (isset($_POST['mitglieder'.$i.'nachrichtloeschen'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'nachrichtloeschen'])) {
            $M[$anzahl]['nachrichtloeschen'] = $_POST['mitglieder'.$i.'nachrichtloeschen'];
          } else {$fehler = true;}
        } else {$fehler = true;}
				if (isset($_POST['mitglieder'.$i.'nutzerstummschalten'])) {
          if (cms_check_toggle($_POST['mitglieder'.$i.'nutzerstummschalten'])) {
            $M[$anzahl]['nutzerstummschalten'] = $_POST['mitglieder'.$i.'nutzerstummschalten'];
          } else {$fehler = true;}
        } else {$fehler = true;}
        $anzahl++;
      }
    }

  }

	if (!$fehler) {
		// NÄCHSTE FREIE ID SUCHEN
		$id = cms_generiere_kleinste_id($artk);
	}

	if (!$fehler) {
		// Gruppe eintragen
    $schuljahrsetzen = "NULL";
    if (($schuljahr != '-') && (cms_check_ganzzahl($schuljahr))) {$schuljahrsetzen = $schuljahr;}

		$sql = $dbs->prepare("UPDATE $artk SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, schuljahr = $schuljahrsetzen, chataktiv = ? WHERE id = ?");
		$sql->bind_param("ssiii", $bezeichnung, $icon, $sichtbar, $chat, $id);
		$sql->execute();
		$sql->close();

		if ($art == 'Stufen') {
			// Nachfolgende Elemente verschieben
			if ($schuljahr != '-') {$schuljahrbedingung = "= ".$schuljahr;} else {$schuljahrbedingung = "IS NULL";}
			$sql = $dbs->prepare("UPDATE stufen SET reihenfolge = reihenfolge+1 WHERE reihenfolge >= ? AND schuljahr $schuljahrbedingung");
			$sql->bind_param("i", $reihenfolge);
			$sql->execute();
			$sql->close();

			$sql = $dbs->prepare("UPDATE stufen SET reihenfolge = ?, tagebuch = ?, gfs = ? WHERE id = ?");
			$sql->bind_param("iiii", $reihenfolge, $tagebuch, $gfs, $id);
			$sql->execute();
			$sql->close();
		}

		if ($art == 'Klassen') {
			$klassenbezextern = cms_texttrafo_e_db($klassenbezextern);
			$stufenbezextern = cms_texttrafo_e_db($stufenbezextern);
			if ($stufe == '-') {$stufe = "NULL";}
			$sql = $dbs->prepare("UPDATE klassen SET stundenplanextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), klassenbezextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stufenbezextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stufe = $stufe WHERE id = ?");
			$sql->bind_param("sssi", $stundenplanextern, $klassenbezextern, $stufenbezextern, $id);
			$sql->execute();
			$sql->close();

			$faecherkurse = array();
			foreach ($faecher AS $f) {
				// Neuen Kurs anlegen
				$kursid = cms_generiere_kleinste_id('kurse');
				array_push($faecherkurse, $kursid);
				$kursbezeichnung = $bezeichnung." ".$f['bezeichnung'];
				$kursicon = $f['icon'];
				$kurskurzbezeichnung = $bezeichnung." ".$f['kuerzel'];
				$kursbezextern = $f['kuerzel'];
				$kursfach = $f['id'];
				$sql = $dbs->prepare("UPDATE kurse SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, schuljahr = $schuljahrsetzen, chataktiv = ?, kurzbezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kursbezextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), fach = ?, stufe = $stufe WHERE id = ?");

				$sql->bind_param("ssiissii", $kursbezeichnung, $kursicon, $sichtbar, $chat, $kurskurzbezeichnung, $kursbezextern, $kursfach, $kursid);
				$sql->execute();
				$sql->close();

				$sql = $dbs->prepare("INSERT INTO kurseklassen (kurs, klasse) VALUES (?, ?)");
				$sql->bind_param("ii", $kursid, $id);
				$sql->execute();
				$sql->close();

				// Dateisystem erzeugen
				$pfad = '../../../dateien/schulhof/gruppen/kurse/'.$kursid;
				if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
				mkdir($pfad);
				chmod($pfad, 0775);
			}
		}

		if ($art == 'Kurse') {
			$kursbezextern = cms_texttrafo_e_db($kursbezextern);
			if ($stufe == '-') {$stufe = "NULL";}

			$sql = $dbs->prepare("UPDATE kurse SET kurzbezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kursbezextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), fach = ?, stufe = ? WHERE id = ?");
			$sql->bind_param("ssiii", $kurzbezeichnung, $kursbezextern, $fach, $stufe, $id);
			$sql->execute();

			$sql = $dbs->prepare("INSERT INTO kurseklassen (kurs, klasse) VALUES (?, ?)");
			foreach ($klassen as $k) {
				$sql->bind_param("ii", $id, $k);
				$sql->execute();
			}
			$sql->close();

			$sql = $dbs->prepare("INSERT INTO schienenkurse (schiene, kurs) VALUES (?, ?)");
			foreach ($SCHIENEN as $s) {
				$sql->bind_param("ii", $s, $id);
				$sql->execute();
			}
			$sql->close();
		}

    if (strlen($mitglieder) > 0) {
      // Mitglieder eintragen
			$sql = $dbs->prepare("INSERT INTO $artk"."mitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      foreach ($M as $i) {
				$sql->bind_param("iiiiiiiiiii", $id, $i['id'], $i['dateiupload'], $i['dateidownload'], $i['dateiloeschen'], $i['dateiumbenennen'], $i['termine'], $i['blogeintraege'], $i['chatten'], $i['nachrichtloeschen'], $i['nutzerstummschalten']);
				$sql->execute();
      }
			$sql->close();

      // Vorsitz eintragen
			if (strlen($vorsitz) > 0) {
				$sql = $dbs->prepare("INSERT INTO $artk"."vorsitz (gruppe, person) VALUES (?, ?)");
				foreach ($VOR as $i) {
					$sql->bind_param("ii", $id, $i);
					$sql->execute();
	      }
				$sql->close();
			}

			if (($art == 'Klassen') && (count($faecherkurse) > 0)) {
				$sql = $dbs->prepare("INSERT INTO kursemitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				foreach ($faecherkurse as $k) {
					foreach ($M as $i) {
						$sql->bind_param("iiiiiiiiiii", $k, $i['id'], $i['dateiupload'], $i['dateidownload'], $i['dateiloeschen'], $i['dateiumbenennen'], $i['termine'], $i['blogeintraege'], $i['chatten'], $i['nachrichtloeschen'], $i['nutzerstummschalten']);
						$sql->execute();
		      }
				}
				$sql->close();
			}
    }

    if (strlen($aufsicht) > 0) {
      // Aufsicht eintragen
			$sql = $dbs->prepare("INSERT INTO $artk"."aufsicht (gruppe, person) VALUES (?, ?)");
			foreach ($AUF as $i) {
				$sql->bind_param("ii", $id, $i);
				$sql->execute();
      }
			$sql->close();
    }

		// Dateisystem erzeugen
		$pfad = '../../../dateien/schulhof/gruppen/'.$artk."/".$id;
		if (file_exists($pfad)) {cms_dateisystem_ordner_loeschen($pfad);}
		mkdir($pfad);
		chmod($pfad, 0775);

		echo "ERFOLG|".$id;
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
