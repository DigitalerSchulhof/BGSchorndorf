<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
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
if (!cms_valide_gruppe($art)) {echo "FEHLER"; exit;}
if (isset($_SESSION['Gruppen']['bearbeiten']['id'])) {$id = $_SESSION['Gruppen']['bearbeiten']['id'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];} else {echo "FEHLER"; exit;}

if ($art == 'Stufen') {
	if (isset($_POST['reihenfolge'])) {$reihenfolge = $_POST['reihenfolge'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['tagebuch'])) {$tagebuch = $_POST['tagebuch'];} else {echo "FEHLER"; exit;}
}
if ($art == 'Klassen') {
	if (isset($_POST['stundenplanextern'])) {$stundenplanextern = $_POST['stundenplanextern'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['klassenbezextern'])) {$klassenbezextern = $_POST['klassenbezextern'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['stufenbezextern'])) {$stufenbezextern = $_POST['stufenbezextern'];} else {echo "FEHLER"; exit;}
}
if ($art == 'Kurse') {
	if (isset($_POST['kurzbezeichnung'])) {$kurzbezeichnung = $_POST['kurzbezeichnung'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['fach'])) {$fach = $_POST['fach'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['klassen'])) {$klassen = $_POST['klassen'];} else {echo "FEHLER"; exit;}
	if (isset($_POST['kursbezextern'])) {$kursbezextern = $_POST['kursbezextern'];} else {echo "FEHLER"; exit;}
}

$dbs = cms_verbinden('s');
$CMS_RECHTE = cms_rechte_laden();
$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
$CMS_GRUPPENRECHTE = cms_gruppenrechte_laden($dbs, $art, $id, $CMS_BENUTZERID);

if (isset($CMS_RECHTE['Gruppen'][$art.' bearbeiten'])) {$zugriff = $CMS_RECHTE['Gruppen'][$art.' bearbeiten'] || $CMS_GRUPPENRECHTE['bearbeiten'];}
else {$zugriff = false;}

$artk = cms_textzudb($art);
$artg = cms_vornegross($art);

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	// Pflichteingaben prüfen
	if ($art == 'Kurse') {if (!cms_check_titel($kurzbezeichnung)) {$fehler = true;}}
	if ($art == 'Stufen') {
		if (!cms_check_ganzzahl($reihenfolge,1)) {$fehler = true;}
		if (!cms_check_toggle($tagebuch)) {$fehler = true;}
	}

	if (!cms_check_titel($bezeichnung)) {$fehler = true;}

	if (!cms_check_toggle($chat)) {$fehler = true;}

	if (($sichtbar != 0) && ($sichtbar != 1) && ($sichtbar != 2) && ($sichtbar != 3)) {
		$fehler = true;
	}

	if (!$fehler) {

		// Schuljahr ermitteln
		$sql = $dbs->prepare("SELECT schuljahr FROM $artk WHERE id = ?");
	  $sql->bind_param("i", $id);
	  if ($sql->execute()) {
	    $sql->bind_result($schuljahr);
	    if ($sql->fetch()) {
				if (is_null($schuljahr)) {
					$schuljahr = '-';
					$schuljahrtest = "schuljahr IS NULL";
				}
				else {
					$schuljahrtest = "schuljahr = ".$schuljahr;
				}
			}
			else {$fehler = true;}
	  }
	  else {$fehler = true;}
	  $sql->close();

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
					$sql = "SELECT COUNT(id) AS anzahl FROM klassen WHERE id IN $klassentext";
		      $anfrage = $dbs->query($sql);
		  		if ($anfrage) {
		  			if ($daten = $anfrage->fetch_assoc()) {
		  				if ($daten['anzahl'] != count($klassen)) {
		  					$fehler = true;
		  					echo "KLASSEN";
		  				}
		  			}
		  			else {$fehler = true;}
		  			$anfrage->free();
		  		}
		      else {$fehler = true;}
				}
	      else {$fehler = true;}
	    }
		}

		// Prüfen, ob es in diesem Schuljahr schon eine Gruppe mit dieser Bezeichnung gibt
		$bezeichnung = cms_texttrafo_e_db($bezeichnung);

		$sql = $dbs->prepare("SELECT COUNT(id) AS anzahl FROM $artk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
		$sql->bind_param("si", $bezeichnung, $id);
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
      if ($eltern) {$sqlwherem .= " AND art != AES_ENCRYPT('e', '$CMS_SCHLUESSEL')";}
      if ($schueler) {$sqlwherem .= " AND art != AES_ENCRYPT('s', '$CMS_SCHLUESSEL')";}
      if ($lehrer) {$sqlwherem .= " AND art != AES_ENCRYPT('l', '$CMS_SCHLUESSEL')";}
      if ($verwaltung) {$sqlwherem .= " AND art != AES_ENCRYPT('v', '$CMS_SCHLUESSEL')";}
      if ($extern) {$sqlwherem .= " AND art != AES_ENCRYPT('x', '$CMS_SCHLUESSEL')";}

      if (strlen($sqlwherem) == 0) {
        $fehler = true;
        echo "MITGLIEDER";
      }
      else {
        $pruefids = "(".substr(str_replace('|', ',', $mitglieder),1).")";
				if (cms_check_idliste($pruefids)) {
					$sql = "SELECT COUNT(id) AS anzahl FROM personen WHERE id IN $pruefids $sqlwherem";
	        $anfrage = $dbs->query($sql);
	        if ($anfrage) {
	          if ($daten = $anfrage->fetch_assoc()) {
	            if ($daten['anzahl'] != 0) {
	              $fehler = true;
	              echo "MITGLIEDER";
	            }
	          }
	          else {$fehler = true;}
	          $anfrage->free();
	        }
	        else {$fehler = true;}
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
					$sql = "SELECT COUNT(id) AS anzahl FROM personen WHERE id IN $pruefids $sqlwherea";
	        $anfrage = $dbs->query($sql);
	        if ($anfrage) {
	          if ($daten = $anfrage->fetch_assoc()) {
	            if ($daten['anzahl'] != 0) {
	              $fehler = true;
	              echo "AUFSICHT";
	            }
	          }
	          else {$fehler = true;}
	          $anfrage->free();
	        }
	        else {$fehler = true;}
				}
        else {$fehler = true;}
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
        if (isset($_POST['mitglieder'.$i.'chattenabT']) && isset($_POST['mitglieder'.$i.'chattenabM']) && isset($_POST['mitglieder'.$i.'chattenabJ']) &&
          isset($_POST['mitglieder'.$i.'chattenabs']) && isset($_POST['mitglieder'.$i.'chattenabm'])) {
          $M[$anzahl]['chattenab'] = mktime($_POST['mitglieder'.$i.'chattenabs'], $_POST['mitglieder'.$i.'chattenabm'],0,$_POST['mitglieder'.$i.'chattenabM'],$_POST['mitglieder'.$i.'chattenabT'],$_POST['mitglieder'.$i.'chattenabJ']);
        } else {$fehler = true;}
        $anzahl++;
      }
    }

  }

	if (!$fehler) {
		// Gruppe eintragen
    $schuljahrsetzen = "NULL";
    if ($schuljahr != '-') {$schuljahrsetzen = $schuljahr;}
		$sql = $dbs->prepare("UPDATE $artk SET bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), icon = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), sichtbar = ?, chataktiv = ? WHERE id = ?");
	  $sql->bind_param("ssiii", $bezeichnung, $icon, $sichtbar, $chat, $id);
	  $sql->execute();
	  $sql->close();

		if ($art == 'Stufen') {
			// Vorige Position ermitteln
			$gefunden = false;
			$sql = $dbs->prepare("SELECT reihenfolge FROM stufen WHERE id = ?");
		  $sql->bind_param("i", $id);
		  if ($sql->execute()) {
		    $sql->bind_result($reihenfolgealt);
		    if ($sql->fetch()) {$gefunden = true;}
		  }
		  $sql->close();

			if ($gefunden) {
				// Nachfolgende Elemente verschieben
				if ($schuljahr != '-') {$schuljahrbedingung = "= ".$schuljahr;} else {$schuljahrbedingung = "IS NULL";}
				$sql = $dbs->prepare("UPDATE stufen SET reihenfolge = reihenfolge-1 WHERE reihenfolge >= ? AND schuljahr $schuljahrbedingung");
				$sql->bind_param("i", $reihenfolgealt);
				$sql->execute();
				$sql->close();

				$sql = $dbs->prepare("UPDATE stufen SET reihenfolge = reihenfolge+1 WHERE reihenfolge >= ? AND schuljahr $schuljahrbedingung");
				$sql->bind_param("i", $reihenfolge);
				$sql->execute();
				$sql->close();

				$sql = $dbs->prepare("UPDATE stufen SET reihenfolge = ?, tagebuch = ? WHERE id = ?");
				$sql->bind_param("iii", $reihenfolge, $tagebuch, $id);
				$sql->execute();
				$sql->close();
			}
		}

		if ($art == 'Klassen') {
			$klassenbezextern = cms_texttrafo_e_db($klassenbezextern);
			$stufenbezextern = cms_texttrafo_e_db($stufenbezextern);

			$sql = $dbs->prepare("UPDATE klassen SET stundenplanextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), klassenbezextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), stufenbezextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') WHERE id = ?");
		  $sql->bind_param("sssi", $stundenplanextern, $klassenbezextern, $stufenbezextern, $id);
		  $sql->execute();
		  $sql->close();
		}
		if ($art == 'Kurse') {
			$kursbezextern = cms_texttrafo_e_db($kursbezextern);
			$sql = $dbs->prepare("UPDATE kurse SET kurzbezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), kursbezextern = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), fach = ? WHERE id = ?");
			$sql->bind_param("ssii", $kurzbezeichnung, $kursbezextern, $fach, $id);
		  $sql->execute();
		  $sql->close();

			$sql = $dbs->prepare("DELETE FROM kurseklassen WHERE kurs = ?");
		  $sql->bind_param("i", $id);
		  $sql->execute();
		  $sql->close();


			$sql = $dbs->prepare("INSERT INTO kurseklassen (kurs, klasse) VALUES (?, ?)");
			foreach ($klassen as $k) {
				$sql->bind_param("ii", $id, $k);
			  $sql->execute();
			}
			$sql->close();
		}

		$sql = $dbs->prepare("DELETE FROM $artk"."mitglieder WHERE gruppe = ?");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		$sql = $dbs->prepare("DELETE FROM $artk"."vorsitz WHERE gruppe = ?");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

		$sql = $dbs->prepare("DELETE FROM $artk"."aufsicht WHERE gruppe = ?");
		$sql->bind_param("i", $id);
		$sql->execute();
		$sql->close();

    if (strlen($mitglieder) > 0) {
      // Mitglieder eintragen
			$sql = $dbs->prepare("INSERT INTO $artk"."mitglieder (gruppe, person, dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, chattenab) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      foreach ($M as $i) {
				$sql->bind_param("iiiiiiiiii", $id, $i['id'], $i['dateiupload'], $i['dateidownload'], $i['dateiloeschen'], $i['dateiumbenennen'], $i['termine'], $i['blogeintraege'], $i['chatten'], $i['chattenab']);
				$sql->execute();
      }
			$sql->close();

			if (strlen($vorsitz) > 0) {
	      // Vorsitz eintragen
				$sql = $dbs->prepare("INSERT INTO $artk"."vorsitz (gruppe, person) VALUES (?, ?)");
	      foreach ($VOR as $i) {
					$sql->bind_param("ii", $id, $i);
					$sql->execute();
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
