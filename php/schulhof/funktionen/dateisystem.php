<?php
function cms_dateisystem_generieren ($stammverzeichnis, $pfad, $feldid, $netz, $bereich, $id, $rechte) {
	$code = "";
	$code .= "<div class=\"cms_dateisystem_box\" id=\"".$feldid."_box\">";
	$code .= "<div class=\"cms_dateisystem_pfad\" id=\"".$feldid."_pfad\">";
		$code .= "<span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_ordnerwechsel('$netz', '$bereich', '$id', '$stammverzeichnis', '$feldid')\"><img src=\"res/dateiicons/klein/stammverzeichnis.png\"> Stammverzeichnis</span>";
		// Verzeichnispfad anzeigen
		$erweiterung = substr($pfad, strlen($stammverzeichnis)+1);
		$erweiterung = explode("/", $erweiterung);
		$zwischenpfad = $stammverzeichnis;
		for ($i=0; $i<count($erweiterung); $i++) {
			if (strlen($erweiterung[$i]) > 0) {
				$zwischenpfad .= "/".$erweiterung[$i];
				$code .= " » <span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_ordnerwechsel('$netz', '$bereich', '$id', '$zwischenpfad', '$feldid')\"><img src=\"res/dateiicons/klein/ordner.png\"> ".$erweiterung[$i]."</span>";
			}
		}
	$code .= "</div>";

	$code .= "<div class=\"cms_dateisystem_aktionen\" id=\"".$feldid."_aktionen\">";
		if ($rechte['dateiupload']) {
			$code .= "<span class=\"cms_dateisystem_pfad_aktionen\" onclick=\"cms_dateisystem_ordnererstellen_anzeigen('".$feldid."')\"><span class=\"cms_hinweis\">Neuer Ordner</span><img src=\"res/dateiicons/gross/neuer_ordner.png\"></span>";
			$code .= "<span class=\"cms_dateisystem_pfad_aktionen\" onclick=\"cms_dateisystem_hochladen_anzeigen('".$feldid."')\"><span class=\"cms_hinweis\">Hochladen</span><img src=\"res/dateiicons/gross/upload.png\"></span>";
			$code .= "<div class=\"cms_dateisystem_aktionen_neuerordner\" id=\"".$feldid."_aktionen_neuerordner\">";
			$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th>Neuer Ordner:</th><td><input type=\"text\" name=\"".$feldid."_aktionen_neuerordner_eingabe\" id=\"".$feldid."_aktionen_neuerordner_eingabe\"></td></tr>";
			$code .= "<tr><th></th><td><span class=\"cms_button_ja\" onclick=\"cms_ordnererstellen('$netz', '$bereich', '$id', '$feldid')\">+ Neuen Ordner erstellen</span> <span class=\"cms_button_nein\" onclick=\"cms_ausblenden('".$feldid."_aktionen_neuerordner')\">Abbrechen</span></td></tr>";
			$code .= "</table>";
			$code .= "</div>";
			$code .= "<div class=\"cms_dateisystem_aktionen_hochladen\" id=\"".$feldid."_aktionen_hochladen\">";
			$code .= "<table class=\"cms_formular\">";
			$schieberid = substr($feldid, 4);
			$code .= "<tr><th>Dateien auswählen:</th><td><input type=\"file\" name=\"".$feldid."_aktionen_hochladen_eingabe\" id=\"".$feldid."_aktionen_hochladen_eingabe\" multiple onchange=\"cms_dateisystem_aktionen_input_nutzen('$feldid');\">";
			// UPLOADZONE NUR FÜR PCs
			if (!cms_istmobil() && false) {
				$code .= "<div class=\"cms_dateisystem_uploadzone\" id=\"".$feldid."_aktionen_hochladen_zone\"><p>Dateien hier her ziehen</p></div>";
    	}
			$code .= "</td></tr>";
			$code .= "<tr><th>Dateien hochladen:</th><td><ul class=\"cms_dateisystem_hochladen_dateiliste\" id=\"".$feldid."_aktionen_hochladen_liste\"><li>Keine Dateien ausgewählt</li></ul><p class=\"cms_notiz\" id=\"".$feldid."_aktionen_hochladen_gesamtgroesse\">Gesamtgröße: 0 B • Anzahl Dateien: 0</p></td></tr>";

			$code .= "<tr><th>Ich besitze das Urheberrecht:</th><td>".cms_schieber_generieren(substr($feldid,4)."_hochladen_urheberrecht", '0')."</td></tr>";

			$code .= "<tr><th>Bilder verkleinern:</th><td><p>".cms_schieber_generieren(substr($feldid,4)."_bilderskalieren", '1', "cms_dateisystem_hochladen_bilderskalieren('$feldid')")."</p><p style=\"display: block;\" id=\"".$feldid."_slalieren_groesse_feld\"><input class=\"cms_klein\" type=\"number\" id=\"".$feldid."_skalieren_groesse\" name=\"".$feldid."_skalieren_groesse\" value=\"1000\"> Pixel</p></td></tr>";


			$code .= "<tr><th></th><td><span class=\"cms_button_ja\" onclick=\"cms_dateisystem_aktionen_hochladen('$netz', '$bereich', '$id', '$feldid')\">+ Dateien hochladen</span> <span class=\"cms_button_nein\" onclick=\"cms_ausblenden('".$feldid."_aktionen_hochladen')\">Abbrechen</span></td></tr>";
			$code .= "</table>";
			$code .= "</div>";
		}
	$code .= "</div>";

	$code .= "<div class=\"cms_dateisystem_inhalt\" id=\"".$feldid."_inhalt\">";
		$code .= cms_dateisystem_ordner_ausgeben($pfad, $netz, $bereich, $id, $rechte, $feldid);
	$code .= "</div>";
	$code .= "<input type=\"hidden\" name=\"".$feldid."_pfad_feld\" id=\"".$feldid."_pfad_feld\" value=\"$pfad\">";
	$code .= "<input type=\"hidden\" name=\"".$feldid."_pfad_feld\" id=\"".$feldid."_stammverzeichnis_feld\" value=\"$stammverzeichnis\">";

	$code .= "</div>";

	return $code;
}

function cms_dateiwaehler_generieren ($stammverzeichnis, $pfad, $feldid, $netz, $bereich, $id, $art = 'download', $ext = false) {
	$code = "";
	$code .= "<div class=\"cms_dateisystem_box\" id=\"".$feldid."_box\">";

	$code .= "<div class=\"cms_dateisystem_pfad\" id=\"".$feldid."_pfad\">";
		$code .= "<span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_dateiwaehler_ordnerwechsel('$netz', '$bereich', '$id', '$stammverzeichnis', '$feldid', '$art')\"><img src=\"res/dateiicons/klein/stammverzeichnis.png\"> Stammverzeichnis</span>";
		// Verzeichnispfad anzeigen
		$erweiterung = substr($pfad, strlen($stammverzeichnis)+1);
		$erweiterung = explode("/", $erweiterung);
		$zwischenpfad = $stammverzeichnis;
		for ($i=0; $i<count($erweiterung); $i++) {
			if (strlen($erweiterung[$i]) > 0) {
				$zwischenpfad .= "/".$erweiterung[$i];
				$code .= " » <span class=\"cms_dateisystem_pfad_icon\" onclick=\"cms_dateiwaehler_ordnerwechsel('$netz', '$bereich', '$id', '$zwischenpfad', '$feldid', $art)\"><img src=\"res/dateiicons/klein/ordner.png\"> ".$erweiterung[$i]."</span>";
			}
		}
	$code .= "</div>";

	$code .= "<div class=\"cms_dateisystem_inhalt\" id=\"".$feldid."_inhalt\">";
	$code .= cms_dateiwaehler_ordner($pfad, $netz, $bereich, $id, $feldid, $art, $ext);
	$code .= "</div>";
	$code .= "<input type=\"hidden\" name=\"".$feldid."_pfad_feld\" id=\"".$feldid."_pfad_feld\" value=\"$pfad\">";
	$code .= "<input type=\"hidden\" name=\"".$feldid."_pfad_feld\" id=\"".$feldid."_stammverzeichnis_feld\" value=\"$stammverzeichnis\">";
	$code .= "<input type=\"hidden\" name=\"".$feldid."_auswahl\" id=\"".$feldid."_auswahl\" value=\"$art\">";

	$code .= "</div>";
	return $code;
}

function cms_dateiwaehler_ordner($pfad, $netz, $bereich, $id, $feldid, $art, $ext = false) {
	global $CMS_SCHLUESSEL;
	$code = "";

	// Zulässige Dateien ermitteln
	$zulaessig = array();
	if (($art == 'bilder') || ($art == 'vorschaubild')) {$sql = "SELECT AES_DECRYPT(endung, '$CMS_SCHLUESSEL') AS endung FROM zulaessigedateien WHERE zulaessig = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND kategorie = AES_ENCRYPT('Bilder', '$CMS_SCHLUESSEL')";}
	else if ($art == 'video') {$sql = "SELECT AES_DECRYPT(endung, '$CMS_SCHLUESSEL') AS endung FROM zulaessigedateien WHERE zulaessig = AES_ENCRYPT('1', '$CMS_SCHLUESSEL') AND kategorie = AES_ENCRYPT('Multimedia', '$CMS_SCHLUESSEL')";}
	else {$sql = "SELECT AES_DECRYPT(endung, '$CMS_SCHLUESSEL') AS endung FROM zulaessigedateien WHERE zulaessig = AES_ENCRYPT('1', '$CMS_SCHLUESSEL')";}
	$dbs = cms_verbinden('s');
	if ($anfrage = $dbs->query($sql)) {
		while ($daten = $anfrage->fetch_assoc()) {
			array_push($zulaessig, $daten['endung']);
		}
		$anfrage->free();
	}
	cms_trennen($dbs);

	$anzeigepfad = $pfad;
	$pfad = 'dateien/'.$pfad;
	$verzeichnis['ocode'] = "";
	$verzeichnis['dcode'] = "";
	$verzeichnis['dateien'] = 0;
	$verzeichnis['ordner'] = 0;
	$code = "<table class=\"cms_dateiwahl_tabelle\" id=\"".$feldid."_inhalt_tabelle\">";

	// Falls per ajax nachladen, anderer Pfad
	if ($ext) {$pfad = "../../../".$pfad;}

	if (is_dir($pfad)) {
		// Aufsteigend sortieren
		$vinhalt = scandir($pfad, 0);
		for ($i=2; $i<count($vinhalt); $i++) {
			// Falls Ordner
			if (is_dir($pfad.'/'.$vinhalt[$i])) {
				$verzeichnis['ocode'] .= "<tr>";
				$verzeichnis['ocode'] .= "<td><img src=\"res/dateiicons/klein/ordner.png\"></td>";
				$verzeichnis['ocode'] .= "<td class=\"cms_dateisystem_ordner\" onclick=\"cms_dateiwaehler_ordnerwechsel('$netz', '$bereich', '$id', '$anzeigepfad/".$vinhalt[$i]."', '$feldid', '$art')\" id=\"$feldid"."_ordner".$verzeichnis['ordner']."\">".$vinhalt[$i]."</td>";
				$vinfo = cms_dateisystem_ordner_info($pfad.'/'.$vinhalt[$i], true);
				$verzeichnis['ocode'] .= "</tr>";
				$verzeichnis['ordner']++;
			}
			// Falls Datei
			if (is_file($pfad.'/'.$vinhalt[$i])) {
				$dateiname = explode(".", $vinhalt[$i]);
				if (in_array(strtolower($dateiname[count($dateiname)-1]), $zulaessig)) {
					$verzeichnis['dcode'] .= "<tr>";
					$icon = cms_dateisystem_icon($dateiname[count($dateiname)-1]);
					$verzeichnis['dcode'] .= "<td><img src=\"res/dateiicons/klein/$icon\"></td>";
					$link = $pfad.'/'.$vinhalt[$i];
					if ($ext) {$link = substr($link, 9);}
					$verzeichnis['dcode'] .= "<td class=\"cms_dateisystem_dateiwahl\" id=\"$feldid"."_datei".$verzeichnis['dateien']."\" onclick=\"cms_datei_waehlen('$feldid', '".$link."')\">".$vinhalt[$i]."</td>";
					$verzeichnis['dcode'] .= "</tr>";
					$verzeichnis['dateien']++;
				}
			}
		}
		$code .= $verzeichnis['ocode'].$verzeichnis['dcode'];
		if (strlen($verzeichnis['ocode'].$verzeichnis['dcode']) < 1) {
			$code .= "<tr><td colspan=\"5\" class=\"cms_dateisystem_meldung\">leer</td></tr>";
		}
	}
	else {
		$code .= "<tr><td colspan=\"5\" class=\"cms_dateisystem_meldung\">ungültig</td></tr>";
	}
	$code .= "</table>";
	return $code;
}

function cms_dateisystem_ordner_ausgeben($pfad, $netz, $bereich, $id, $rechte, $feldid, $ext = false) {
	$anzeigepfad = $pfad;
	$pfad = 'dateien/'.$pfad;
	$verzeichnis['ocode'] = "";
	$verzeichnis['dcode'] = "";
	$verzeichnis['groesse'] = 0;
	$verzeichnis['dateien'] = 0;
	$verzeichnis['ordner'] = 0;
	$code = "<table class=\"cms_dateisystem_tabelle\" id=\"".$feldid."_inhalt_tabelle\">";

	// Falls per ajax nachladen, anderer Pfad
	if ($ext) {$pfad = "../../../".$pfad;}


	if (is_dir($pfad)) {
		// Aufsteigend sortieren
		$vinhalt = scandir($pfad, 0);
		for ($i=2; $i<count($vinhalt); $i++) {
			// Falls Ordner
			if (is_dir($pfad.'/'.$vinhalt[$i])) {
				$verzeichnis['ocode'] .= "<tr>";
				$verzeichnis['ocode'] .= "<td><img src=\"res/dateiicons/klein/ordner.png\"></td>";
				$verzeichnis['ocode'] .= "<td class=\"cms_dateisystem_ordner\" onclick=\"cms_ordnerwechsel('$netz', '$bereich', '$id', '$anzeigepfad/".$vinhalt[$i]."', '$feldid')\" id=\"$feldid"."_ordner".$verzeichnis['ordner']."\">".$vinhalt[$i]."</td>";
				$vinfo = cms_dateisystem_ordner_info($pfad.'/'.$vinhalt[$i], true);
				$verzeichnis['groesse'] += $vinfo['groesse'];
				$verzeichnis['ocode'] .= "<td>".$vinfo['dateien']." Dateien und ".$vinfo['ordner']." Ordner</td>";
				$verzeichnis['ocode'] .= "<td style=\"text-align: right;\">".cms_groesse_umrechnen($vinfo['groesse'])."</td>";
				$verzeichnis['ocode'] .= "<td>";
				if (($rechte['dateidownload']) && (($vinfo['dateien']+$vinfo['ordner']) > 0)) {$verzeichnis['ocode'] .= "<span class=\"cms_aktion_klein\" onclick=\"cms_herunterladen('$netz', '$bereich', '$id', '$anzeigepfad/".$vinhalt[$i]."')\"><span class=\"cms_hinweis\">Ordner herunterladen</span><img src=\"res/icons/klein/download.png\"></span> ";}
				if ($rechte['dateiumbenennen']) {$verzeichnis['ocode'] .= "<span class=\"cms_aktion_klein\" onclick=\"cms_ordnerumbenennen_anzeigen('$netz', '$bereich', '$id', '$anzeigepfad', '".$verzeichnis['ordner']."', '$feldid')\"><span class=\"cms_hinweis\">Ordner umbenennen</span><img src=\"res/icons/klein/umbenennen.png\"></span> ";}
				if ($rechte['dateiloeschen']) {$verzeichnis['ocode'] .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_ordnerloeschen_anzeigen('$netz', '$bereich', '$id', '$anzeigepfad', '".$vinhalt[$i]."', '$feldid')\"><span class=\"cms_hinweis\">Ordner löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";}
				$verzeichnis['ocode'] .= "</td>";
				$verzeichnis['ocode'] .= "</tr>";
				$verzeichnis['ordner']++;
			}
			// Falls Datei
			if (is_file($pfad.'/'.$vinhalt[$i])) {
				$verzeichnis['dcode'] .= "<tr>";
				$dateiname = explode(".", $vinhalt[$i]);
				$icon = cms_dateisystem_icon($dateiname[count($dateiname)-1]);
				$verzeichnis['dcode'] .= "<td><img src=\"res/dateiicons/klein/$icon\"></td>";
				$verzeichnis['dcode'] .= "<td id=\"$feldid"."_datei".$verzeichnis['dateien']."\">".$vinhalt[$i]."</td>";
				$dinfo = getimagesize($pfad.'/'.$vinhalt[$i]);
				$info = "";
				$groesse = filesize($pfad.'/'.$vinhalt[$i]);
				$verzeichnis['groesse'] += $groesse;
				$verzeichnis['dcode'] .= "<td>".$info."</td>";
				$verzeichnis['dcode'] .= "<td style=\"text-align: right;\">".cms_groesse_umrechnen($groesse)."</td>";
				$verzeichnis['dcode'] .= "<td>";
				if ($rechte['dateidownload']) {$verzeichnis['dcode'] .= "<span class=\"cms_aktion_klein\" onclick=\"cms_herunterladen('$netz', '$bereich', '$id', '$anzeigepfad/".$vinhalt[$i]."')\"><span class=\"cms_hinweis\">Datei herunterladen</span><img src=\"res/icons/klein/download.png\"></span> ";}
				if ($rechte['dateiumbenennen']) {$verzeichnis['dcode'] .= "<span class=\"cms_aktion_klein\" onclick=\"cms_dateiumbenennen_anzeigen('$netz', '$bereich', '$id', '$anzeigepfad', '".$verzeichnis['dateien']."', '$feldid')\"><span class=\"cms_hinweis\">Datei umbenennen</span><img src=\"res/icons/klein/umbenennen.png\"></span> ";}
				if ($rechte['dateiloeschen']) {$verzeichnis['dcode'] .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_dateiloeschen_anzeigen('$netz', '$bereich', '$id', '$anzeigepfad', '".$vinhalt[$i]."', '$feldid')\"><span class=\"cms_hinweis\">Datei löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";}
				$verzeichnis['dcode'] .= "</td>";
				$verzeichnis['dcode'] .= "</tr>";
				$verzeichnis['dateien']++;
			}
		}
		$code .= $verzeichnis['ocode'].$verzeichnis['dcode'];
		if (strlen($verzeichnis['ocode'].$verzeichnis['dcode']) < 1) {
			$code .= "<tr><td colspan=\"5\" class=\"cms_dateisystem_meldung\">leer</td></tr>";
		}
	}
	else {
		$code .= "<tr><td colspan=\"5\" class=\"cms_dateisystem_meldung\">ungültig</td></tr>";
	}
	$code .= "</table>";
	$code .= "<div class=\"cms_dateisystem_status\" id=\"".$feldid."_status\">";
		$code .= "<p>Ordnergröße: ".cms_groesse_umrechnen($verzeichnis['groesse'])." - Enthält ".$verzeichnis['dateien']." Dateien und ".$verzeichnis['ordner']." Ordner</p>";
	$code .= "</div>";
	return $code;
}



function cms_dateisystem_ordner_loeschen($pfad) {
  $dateien = "";
  $ordner = "";
  $groesse = 0;
  if (is_dir ($pfad)) {
    $verzeichnis = scandir($pfad);
    // einlesen der Verzeichnisses
		foreach ($verzeichnis as $v) {
			if (($v != "..") && ($v != ".")) {
				if (is_file($pfad."/".$v)) {
          unlink($pfad."/".$v);
        }
				if (is_dir($pfad."/".$v)) {
          cms_dateisystem_ordner_loeschen($pfad."/".$v);
        }
			}
		}
		rmdir($pfad);
		return true;
  }
  return false;
}

function cms_dateisystem_ordner_verschluesseln($pfad) {
  $dateien = "";
  $ordner = "";
  $groesse = 0;
  if (is_dir ($pfad)) {
    $verzeichnis = scandir($pfad);
    // einlesen der Verzeichnisses
		foreach ($verzeichnis as $v) {
			if (($v != "..") && ($v != ".")) {
				if (is_file($pfad."/".$v)) {
					echo "verschlüssle ".$pfad."/".$v;
          cms_dateisystem_datei_verschluesseln($pfad."/".$v);
	        cms_dateisystem_datei_verschluesseln_aufraeumen($pfad."/".$v);
        }
				if (is_dir($pfad."/".$v)) {
          cms_dateisystem_ordner_verschluesseln($pfad."/".$v);
        }
			}
		}
		return true;
  }
  return false;
}

function cms_dateisystem_datei_verschluesseln_aufraeumen($pfad) {
	unlink($pfad);
	rename($pfad.".e.n.c", $pfad);
}


function cms_dateisystem_datei_verschluesseln($quelle) {
	global $CMS_SCHLUESSEL;
	// Zielpfad erstellen
	$ziel = $quelle.".e.n.c";
  $schluessel = substr(sha1($CMS_SCHLUESSEL, true), 0, 16);
  $ivektor = openssl_random_pseudo_bytes(16);
	$fehler = false;

	if ($zieldatei = fopen($ziel, 'w')) {
		// Vektor an den Anfang der verschlüsselten Datei schreiebn
		fwrite($zieldatei, $ivektor);
		if ($quelldatei = fopen($quelle, 'rb')) {
			// Datei verschlüssseln
			while (!feof($quelldatei)) {
				$klartext = fread($quelldatei, 16 * 10000);
				$schluesseltext = openssl_encrypt($klartext, 'AES-128-CBC', $schluessel, OPENSSL_RAW_DATA, $ivektor);
				$ivektor = substr($schluesseltext, 0, 16);
				fwrite($zieldatei, $schluesseltext);
			}
			// Klartextdatei schließen
			fclose($quelldatei);
		}
		else {$fehler = true;}
		// Verschlüsselte Datei schließen
		fclose($zieldatei);
	}
	else {$fehler = true;}

	if ($fehler) {return false;}
	else {return $ziel;}
}

function cms_dateisystem_datei_entschluesseln($quelle, $ziel) {
	global $CMS_SCHLUESSEL;
  $schluessel = substr(sha1($CMS_SCHLUESSEL, true), 0, 16);
	$fehler = false;

	if ($zieldatei = fopen($ziel, 'w')) {
    if ($quelldatei = fopen($quelle, 'rb')) {
      // Vektor laden
      $ivektor = fread($quelldatei, 16);
      while (!feof($quelldatei)) {
        // Entschlüsseln (Berücksichtigen, dass vorne der Vektor angefügt wurde)
        $schluesseltext = fread($quelldatei, 16 * (10001));
        $klartext = openssl_decrypt($schluesseltext, 'AES-128-CBC', $schluessel, OPENSSL_RAW_DATA, $ivektor);
        $ivektor = substr($schluesseltext, 0, 16);
        fwrite($zieldatei, $klartext);
      }
			// Verschlüsselte Datei schließen
      fclose($quelldatei);
    }
		else {$fehler = true;}
		// Entschlüsselte Datei schließen
    fclose($zieldatei);
  } else {$fehler = true;}

	if ($fehler) {return false;}
	else {return $ziel;}
}


function cms_dateisystem_ordner_kopieren($ursprung, $ziel) {
  $dateien = "";
  $ordner = "";
  $groesse = 0;
	if (!file_exists($ziel)) {
		mkdir($ziel, 777);
	}
  if (is_dir ($ursprung)) {
    chmod($ursprung, 0777);
    // einlesen der Verzeichnisses
		$verzeichnis = opendir($ursprung);
    while ($datei = readdir($verzeichnis)) {
      if (($datei != "..") && ($datei != ".")) {
        if (is_file($ursprung."/".$datei)) {
          chmod($ursprung."/".$datei, 0777);
					copy($ursprung."/".$datei, $ziel."/".$datei);
					chmod($ziel."/".$datei, 0777);
          chmod($ursprung."/".$datei, 0777);
        }
        if (is_dir($ursprung."/".$datei)) {
					mkdir($ziel."/".$datei, 777);
          cms_dateisystem_ordner_kopieren($ursprung."/".$datei, $ziel."/".$datei);
        }
      }
    }
	  chmod($ursprung, 0777);
		return true;
  }
  return false;
}



function cms_dateicheck($pfad) {
	$CMS_RECHTE = cms_rechte_laden();
	$dateiteile = explode('/', $pfad);

	if (isset($_SESSION['BENUTZERID'])) {$CMS_BENUTZERID = $_SESSION['BENUTZERID'];}
	else {$CMS_BENUTZERID = '-';}

	$fehler = false;
	// Rechte für diesen Bereich prüfen
	if (($dateiteile[4] == 'website') || ($dateiteile[4] == 'titelbilder')) {
		$bereich = $dateiteile[4];
		$id = $dateiteile[5];
		$datei = $dateiteile[count($dateiteile) - 1];
		$bereich = strtoupper(substr($bereich, 0, 1)).strtolower(substr($bereich, 1));
		$gruppenrechte['dateidownload'] = true;
	}
	else if (($dateiteile[4] == "schulhof") && ($dateiteile[5] == "gruppen")) {
		$bereich = $dateiteile[6];
		$id = $dateiteile[7];
		$datei = $dateiteile[count($dateiteile) - 1];
		$bereich = cms_vornegross($bereich);
		if ($bereich == "Sonstigegruppen") {$bereich = "Sonstige Gruppen";}
		$dbs = cms_verbinden('s');
		$gruppenrechte = cms_gruppenrechte_laden($dbs, $bereich, $id);
		cms_trennen($dbs);
	}
	else if (($dateiteile[4] == "schulhof") && ($dateiteile[5] == "personen") && ($dateiteile[6] == $CMS_BENUTZERID) && ($dateiteile[7] == "postfach")
	         && (($dateiteile[8] == "entwuerfe") || ($dateiteile[8] == "eingang") || ($dateiteile[8] == "ausgang"))) {
		$gruppenrechte['dateidownload'] = true;
	}

	// Falls keine Dateien heruntergeladen werden dürfen, sperren
	if (!$gruppenrechte['dateidownload']) {return false;}
	else {
		// Existiert die gesuchte Dtei?
		// Falls ja, Datei für den Download bereitstellen
		if (is_file($pfad)) {return 'datei';}
		else if (is_dir($pfad)) {return 'ordner';}
		// Falls nicht fehler
		else {return false;}
	}
}

function cms_groesse_umrechnen($bytes) {
    if ($bytes/1024 >= 1) {
        $bytes = $bytes/1024;
        if ($bytes/1024 >= 1) {
            $bytes = $bytes/1024;
            if ($bytes/1024 >= 1) {
                $bytes = $bytes/1024;
                if ($bytes/1024 >= 1) {
                    $bytes = $bytes/1024;
                    if ($bytes/1024 >= 1) {
                        $bytes = $bytes/1024;
                        if ($bytes/1024 >= 1) {
                            $bytes = $bytes/1024;
                            $bytes = str_replace('.', ',', round($bytes, 2));
                            return $bytes." EB";
                        }
                        $bytes = str_replace('.', ',', round($bytes, 2));
                        return $bytes." PB";
                    }
                    $bytes = str_replace('.', ',', round($bytes, 2));
                    return $bytes." TB";
                }
                $bytes = str_replace('.', ',', round($bytes, 2));
                return $bytes." GB";
            }
            $bytes = str_replace('.', ',', round($bytes, 2));
            return $bytes." MB";
        }
        $bytes = str_replace('.', ',', round($bytes, 2));
        return $bytes." KB";
    }
    return $bytes." B";
}

function cms_dateisystem_icon($kuerzel) {
  $kuerzel = strtolower($kuerzel);
	$unterstuetzt = array("3gp", "7z", "ace", "ai", "aif", "aiff", "amr", "asf", "asx", "bat", "bin", "bmp", "bup", "cab", "cbr", "cda", "cdl", "cdr",
	                      "chm", "dat", "deb", "divx", "dll", "dmg", "doc", "docx", "dss", "dvf", "dwg", "eml", "eps", "exe", "fla", "flv", "gif",
											  "gpn", "gz", "hqx", "htm", "html", "ifo", "indd", "iso", "jar", "jpeg", "jpg", "lnk", "log", "m4a", "m4b", "m4p", "m4v", "mcd",
											  "mdb", "midi", "mov", "mp2", "mp3", "mp4", "mpeg", "mpg", "msi", "msw", "odp", "ods", "odt", "ogg", "pdf", "pkg", "png", "ppt",
											  "pptx", "ps", "psd", "pst", "ptb", "pub", "pubx", "qbb", "qbw", "qxd", "ram", "rar", "rm", "rmvb", "rtf", "sea", "ses", "sit",
												"sitx", "ss", "swf", "tgz", "thm", "tif", "tmp", "torrent", "ttf", "txt", "typ", "vcd", "vob", "wav", "wma", "wmv", "wps", "xls",
												"xlsx", "xpi", "zip");
	if (in_array($kuerzel, $unterstuetzt)) {
		return $kuerzel.".png";
	}
	else {
		return "typ.png";
	}
}

// Sammelt Informationen über den Ordner
// Ordner und Dateien nur im ersten Aufruf zählen
function cms_dateisystem_ordner_info ($pfad, $erster = false) {
	$info['dateien'] = 0;
	$info['ordner'] = 0;
	$info['groesse'] = 0;

	if (is_dir($pfad)) {
		$verzeichnis = scandir($pfad);
		// einlesen der Verzeichnisses
		for ($i=2; $i<count($verzeichnis); $i++) {
			// Ordner
			if (is_dir($pfad."/".$verzeichnis[$i])) {
				if ($erster) {$info['ordner']++;}
				$unterordner = cms_dateisystem_ordner_info($pfad."/".$verzeichnis[$i]);
				$info['groesse'] = $info['groesse'] + $unterordner['groesse'];
			}
			if (is_file($pfad."/".$verzeichnis[$i])) {
				if ($erster) {$info['dateien']++;}
				$info['groesse'] = $info['groesse'] + filesize($pfad."/".$verzeichnis[$i]);
			}
		}
	}

	return $info;
}

function cms_generiereZip ($pfadaussen, $pfadinnen, $abstand, $zip) {
	$vorverzeichnis = "";
	for ($i=0; $i<$abstand; $i++) {
		$vorverzeichnis .= "../";
	}
	$verzeichnis = scandir($vorverzeichnis.$pfadaussen);
	unset($verzeichnis[0], $verzeichnis[1]);
	foreach ($verzeichnis as $eintrag) {
		if (is_file($vorverzeichnis.$pfadaussen.'/'.$eintrag)) {
			$zip->addFile($vorverzeichnis.$pfadaussen.'/'.$eintrag, $pfadinnen.$eintrag);
		}
		if (is_dir($vorverzeichnis.$pfadaussen.'/'.$eintrag)) {
			$zip->addEmptyDir($pfadinnen.$eintrag);
			cms_generiereZip($pfadaussen.'/'.$eintrag, $eintrag.'/', $abstand, $zip);
		}
	}
}

function cms_generiereZip_entschluesselt ($pfadaussen, $pfadinnen, $abstand, $zip, $zwischenverzeichnis) {
	$vorverzeichnis = "";
	for ($i=0; $i<$abstand; $i++) {
		$vorverzeichnis .= "../";
	}
	mkdir($vorverzeichnis.$zwischenverzeichnis);
	$verzeichnis = scandir($vorverzeichnis.$pfadaussen);
	unset($verzeichnis[0], $verzeichnis[1]);
	foreach ($verzeichnis as $eintrag) {
		if (is_file($vorverzeichnis.$pfadaussen.'/'.$eintrag)) {
			$entschluesselt = $vorverzeichnis.$zwischenverzeichnis.'/'.$eintrag;
			cms_dateisystem_datei_entschluesseln($vorverzeichnis.$pfadaussen.'/'.$eintrag, $entschluesselt);
			$zip->addFile($entschluesselt, $pfadinnen.$eintrag);
		}
		if (is_dir($vorverzeichnis.$pfadaussen.'/'.$eintrag)) {
			$zip->addEmptyDir($pfadinnen.$eintrag);
			$zwischenverzeichnis = $zwischenverzeichnis.'/'.$eintrag;
			cms_generiereZip_entschluesselt($pfadaussen.'/'.$eintrag, $eintrag.'/', $abstand, $zip, $zwischenverzeichnis);
		}
	}
}

function cms_generiere_dateiname () {
  $pool = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ_-";
  $passwort = "";
  srand ((double)microtime()*1000000);
  for($i = 0; $i < 20; $i++) {
      $passwort .= substr($pool,(rand()%(strlen ($pool))), 1);
  }
  return $passwort;
}

function cms_dateiwahl_knopf ($stammverzeichnis, $feldid, $netz, $bereich, $id, $art = "download", $wert = "", $gruppe = '-', $gruppenid = '-') {
	$code = "<input id=\"$feldid\" name=\"$feldid\" type=\"hidden\" value=\"$wert\">";
	if (strlen($wert) < 1) {$vorschau = 'Keine Datei ausgewählt';}
	else {
		$dateiname = explode('/', $wert);
		$dateiname = $dateiname[count($dateiname)-1];
		$endung = explode('.', $dateiname);
		$endung = $endung[count($endung)-1];
		$icon = cms_dateisystem_icon($endung);
		if ($art == 'vorschaubild') {
			$vorschau = '<img src="'.$wert.'">';
		}
		else {
			$vorschau = '<span class="cms_datei_gewaehlt"><img src="res/dateiicons/klein/'.$icon.'"> '.$dateiname.'</span>';
		}
	}
	$code .= "<p class=\"cms_notiz cms_vorschau\" id=\"$feldid"."_vorschau\">$vorschau</p>";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_dateiwahl('$netz', '$bereich', '$id', '$stammverzeichnis', '$feldid', '$art', '$gruppe', '$gruppenid')\">";
		if ($art == 'download') {$code .= "Datei";}
		else if ($art == 'bilder') {$code .= "Bild";}
		else if ($art == 'video') {$code .= "Video";}
		else if ($art == 'vorschaubild') {$code .= "Bild";}
		$code.= " auswählen</span></p>";
	$code .= "<p id=\"$feldid"."_verzeichnis\"></p>";
	return $code;
}


function cms_bild_skalieren ($datei, $ziel, $max) {
  $erfolg = false;
  if (is_file($datei)) {
    // Prüfen, ob ein Bild vorliegt
    if ($dateidetails = getimagesize($datei)) {
      $bildbreite = $dateidetails[0];
      $bildhoehe = $dateidetails[1];
      $bildtyp = $dateidetails[2];

			// Nur verkleinern
			if (($bildbreite > $max) || ($bildhoehe > $max)) {
				// Neue Bildgrößen festlegen
				$faktor = $max/max($bildbreite, $bildhoehe);
				$breite = $bildbreite * $faktor;
				$hoehe = $bildhoehe * $faktor;

				$unterstuetzt = false;
				if ($bildtyp == 1) {$bildkopie = imagecreatefromgif($datei); $unterstuetzt = true;}
				if ($bildtyp == 2) {$bildkopie = imagecreatefromjpeg($datei); $unterstuetzt = true;}
				if ($bildtyp == 3) {$bildkopie = imagecreatefrompng($datei); $unterstuetzt = true;}

				if ($unterstuetzt) {
					$skaliert = imagecreatetruecolor($breite, $hoehe);
					$test = imagecopyresampled(
		          $skaliert,
		          $bildkopie,
		          0, 0, 0, 0, // Startposition des Ausschnittes
		          $breite, $hoehe,
		          $bildbreite, $bildhoehe
		      );
					if ($bildtyp == 1) {imagegif($skaliert, $ziel); $erfolg = true;}
					if ($bildtyp == 2) {imagejpeg($skaliert, $ziel); $erfolg = true;}
					if ($bildtyp == 3) {imagepng($skaliert, $ziel); $erfolg = true;}
					imagedestroy($skaliert);
				}
				imagedestroy($bildkopie);
			}
    }
  }
  return $erfolg;
}



function cms_postfach_anhang_dateiupload() {
	global $CMS_BENUTZERID;
	$feldid = "cms_nutzerkonto_postfach_nachricht_anhang";
	$code = "<div id=\"$feldid"."_aktionen_hochladen\" style=\"display: none;\">";
	$code .= "<table class=\"cms_formular\">";
	$schieberid = substr($feldid, 4);
	$code .= "<tr><th>Anhang auswählen:</th><td><input type=\"file\" name=\"".$feldid."_aktionen_hochladen_eingabe\" id=\"".$feldid."_aktionen_hochladen_eingabe\" multiple onchange=\"cms_dateisystem_aktionen_input_nutzen('$feldid');\">";
	// UPLOADZONE NUR FÜR PCs
	if (!cms_istmobil() && false) {
		$code .= "<div class=\"cms_dateisystem_uploadzone\" id=\"".$feldid."_aktionen_hochladen_zone\"><p>Dateien hier her ziehen</p></div>";
	}
	$code .= "</td></tr>";
	$code .= "<tr><th>Anhang hochladen:</th><td><ul class=\"cms_dateisystem_hochladen_dateiliste\" id=\"".$feldid."_aktionen_hochladen_liste\"><li>Keine Dateien ausgewählt</li></ul><p class=\"cms_notiz\" id=\"".$feldid."_aktionen_hochladen_gesamtgroesse\">Gesamtgröße: 0 B • Anzahl Dateien: 0</p></td></tr>";

	$code .= "<tr><th>Ich besitze das Urheberrecht:</th><td>".cms_schieber_generieren(substr($feldid,4)."_hochladen_urheberrecht", '0')."</td></tr>";

	$code .= "<tr><th></th><td><span class=\"cms_button_ja\" onclick=\"cms_dateisystem_aktionen_hochladen('s', 'anhang', '-', '$feldid')\">+ Anhang hochladen</span> <span class=\"cms_button_nein\" onclick=\"cms_ausblenden('".$feldid."_aktionen_hochladen')\">Abbrechen</span>";
	$code .= "<input id=\"".$feldid."_bilderskalieren\" name=\"".$feldid."_bilderskalieren\" type=\"hidden\" value=\"0\"><input id=\"".$feldid."_skalieren_groesse\" name=\"".$feldid."_skalieren_groesse\" type=\"hidden\" value=\"1000\"><input id=\"".$feldid."_pfad_feld\" name=\"".$feldid."_pfad_feld\" type=\"hidden\" value=\"schulhof/personen/$CMS_BENUTZERID/postfach/temp\">";
	$code .= "</td></tr>";
	$code .= "</table>";
	$code .= "</div>";
	return $code;
}

function cms_dateiname_erzeugen($text) {
	$text = str_replace(chr(204).chr(136), "ae", $text);

	$text = str_replace(chr(195).chr(164), "ae", $text);
	$text = str_replace(chr(195).chr(182), "oe", $text);
	$text = str_replace(chr(195).chr(188), "ue", $text);
	$text = str_replace(chr(195).chr(132), "Ae", $text);
	$text = str_replace(chr(195).chr(150), "Oe", $text);
	$text = str_replace(chr(195).chr(156), "Ue", $text);
	$text = str_replace(chr(195).chr(159), "ss", $text);

	$text = str_replace('ä', "ae", $text);
	$text = str_replace('ö', "oe", $text);
	$text = str_replace('ü', "ue", $text);
	$text = str_replace('Ä', "Ae", $text);
	$text = str_replace('Ö', "Oe", $text);
	$text = str_replace('Ü', "Ue", $text);
	$text = str_replace('ß', "ss", $text);

	$text = str_replace("\u00e4", "ae", $text);
	$text = str_replace("\u00f6", "oe", $text);
	$text = str_replace("\u00fc", "ue", $text);
	$text = str_replace("\u00c4", "Ae", $text);
	$text = str_replace("\u00d6", "Oe", $text);
	$text = str_replace("\u00dc", "Ue", $text);
	$text = str_replace("\u00df", "ss", $text);
	$text = str_replace(' ', "_", $text);

	return $text;
}

function cms_db_tabellengroesse($datenbank, $tabellen) {
	$groesse = 0;
	$tabellencode = "";
	foreach ($tabellen as $t) {
		$tabellencode .= " OR table_name = '$t'";
	}
	if (strlen($tabellencode) > 0) {
		$tabellencode = '('.substr($tabellencode, 4).')';
		$db = cms_verbinden('ü');
		$sql = "SELECT SUM(data_length + index_length) AS groesse FROM information_schema.tables WHERE table_schema = '$datenbank' AND $tabellencode";
		if ($anfrage = $db->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$groesse = $daten['groesse'];
			}
			$anfrage->free();
		}
	}
	cms_trennen($db);
	return $groesse;
}
?>
