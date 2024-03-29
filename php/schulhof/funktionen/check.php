<?php
function cms_check_mail($mail) {
	if(is_array($mail)) {
		$r = true;
		foreach ($mail as $i => $m)
			if(!cms_check_mail($m))
				$r = false;
		return $r;
	}
	if (preg_match('/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]{2,}$/', $mail) != 1) {
		return false;
	}
	else return true;
}

function cms_check_uhrzeit($uhrzeit) {
	if(is_array($uhrzeit)) {
		$r = true;
		foreach ($uhrzeit as $i => $u)
			if(!cms_check_uhrzeit($u))
				$r = false;
		return $r;
	}
	if (preg_match('/^[0-9]{1,2}:[0-9-]{1,2}$/', $uhrzeit) != 1) {
		return false;
	}
	else return true;
}

function cms_check_titel($titel) {
	if(is_array($titel)) {
		$r = true;
		foreach ($titel as $i => $t)
			if(!cms_check_titel($t))
				$r = false;
		return $r;
	}
	if (preg_match("/^[\.\-a-zA-Z0-9äöüßÄÖÜ! ]*[\-a-zA-Z0-9äöüßÄÖÜ!]+$/", $titel) != 1) {
		return false;
	}
	else if (($titel == '.') || ($titel == '..')) {
		return false;
	}
	else return true;
}

function cms_check_url($url) {
	if (preg_match("/^[\.\-a-zA-Z0-9äöüßÄÖÜ\/_! ]+$/", $url) != 1) {
		return false;
	}
	return true;
}

function cms_check_dateiname($datei) {
	if (preg_match("/^[\-\_a-zA-Z0-9]{1,244}\.((tar\.gz)|([a-zA-Z0-9]{2,10}))$/", $datei) != 1) {
		return false;
	}
	else return true;
}

function cms_check_nametitel($titel) {
	if(is_array($titel)) {
		$r = true;
		foreach ($titel as $i => $t)
			if(!cms_check_nametitel($t))
				$r = false;
		return $r;
	}
	if (preg_match("/^[\-0-9a-zA-ZÄÖÜäöüßáÁàÀâÂéÉèÈêÊíÍìÌîÎïÏóÓòÒôÔúÚùÙûÛçÇøØæÆœŒåÅ. ]*$/", $titel) != 1) {
		return false;
	}
	else return true;
}

function cms_check_name($name) {
	if(is_array($name)) {
		$r = true;
		foreach ($name as $i => $n)
			if(!cms_check_name($n))
				$r = false;
		return $r;
	}
	if (preg_match("/^[\-a-zA-ZÄÖÜäöüßáÁàÀâÂéÉèÈêÊíÍìÌîÎïÏóÓòÒôÔúÚùÙûÛçÇøØæÆœŒåÅ ]+$/", $name) != 1) {
		return false;
	}
	else return true;
}

function cms_check_suchtext($text) {
	if (preg_match("/^[0-9a-zA-ZÄÖÜäöüßáÁàÀâÂéÉèÈêÊíÍìÌîÎïÏóÓòÒôÔúÚùÙûÛçÇøØæÆœŒåÅ ]*$/", $text) != 1) {
		return false;
	}
	else return true;
}

function cms_check_buchstaben($text) {
	if (preg_match("/^[a-zA-ZÄÖÜäöüß]+$/", $text) != 1) {
		return false;
	}
	else return true;
}

function cms_check_toggle($wert) {
	if (($wert != 1) && ($wert != 0)) {return false;}
	else {return true;}
}

function cms_check_pfad($pfad) {
	if (preg_match("/\.\./", $pfad) == 1) {
		return false;
	}
	else return true;
}

function cms_check_ganzzahl($wert, $min = false, $max = false) {
	if(is_array($wert)) {
		$r = true;
		foreach ($wert as $i => $w)
			if(!cms_check_ganzzahl($w, $min, $max))
				$r = false;
		return $r;
	}
	if (preg_match("/^-{0,1}[0-9]+$/", $wert) == 1) {
		if ($min !== false) {if ($wert < $min) {return false;}}
		if ($max !== false) {if ($wert > $max) {return false;}}
		return true;
	}
	else {return false;}
}

function cms_check_idliste($text) {
	if (preg_match("/^\([0-9]+(,[0-9]+)*\)$/", $text) != 1) {return false;}
	else {return true;}
}

function cms_check_idfeld($text) {
	if ($text == '') {return true;}
	if (preg_match("/^(\|[0-9]+)+$/", $text) != 1) {return false;}
	else {return true;}
}

// Prüft, ob der Nutzer, der in der Session steht, angemeldet ist
function cms_angemeldet () {
	$angemeldet = false;

	$sessionfehler = !cms_check_sessionvars();

	if ($sessionfehler) {return false;}

  if (isset($_SESSION['BENUTZERNAME'])) {
    $jetzt = time();

    if ($_SESSION['SESSIONTIMEOUT'] > $jetzt) {
	    $dbs = cms_verbinden('s');

	    $benutzername = $_SESSION['BENUTZERNAME'];
	    $sessionid = $_SESSION['SESSIONID'];
	    $sessiontimeout = $_SESSION['SESSIONTIMEOUT'];
	    $titel  = $_SESSION['BENUTZERTITEL'];
	    $vorname  = $_SESSION['BENUTZERVORNAME'];
	    $nachname = $_SESSION['BENUTZERNACHNAME'];
	    $id = $_SESSION['BENUTZERID'];
	    $art = $_SESSION['BENUTZERART'];

	    global $CMS_SCHLUESSEL;

	    $benutzername = cms_texttrafo_e_db($benutzername);
	    $titel = cms_texttrafo_e_db($titel);
	    $vorname = cms_texttrafo_e_db($vorname);
	    $nachname = cms_texttrafo_e_db($nachname);

			$sql = $dbs->prepare("SELECT COUNT(personen.id) AS anzahl FROM personen JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE art = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND benutzername = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND sessionid = ? AND sessiontimeout = ? AND personen.id = ? AND titel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND vorname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND nachname = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");
		  $sql->bind_param("sssiisss", $art, $benutzername, $sessionid, $sessiontimeout, $id, $titel, $vorname, $nachname);
		  if ($sql->execute()) {
		    $sql->bind_result($anzahl);
		    if ($sql->fetch()) {if ($anzahl == 1) {$angemeldet = true;}}
		  }
		  $sql->close();

			cms_trennen($dbs);
    }
  }
  return $angemeldet;
}

include_once(dirname(__FILE__)."/../../allgemein/funktionen/rechte/rechte.php");
$geladen = false;
function cms_rechte_laden($aktiverbenutzer = '-', $dynamisch = true) {
	global $CMS_SCHLUESSEL, $geladen;
	if(!$geladen) {
		cms_allerechte_laden();

		cms_rechte_laden_nutzer($aktiverbenutzer);
		cms_rechte_laden_rollen($aktiverbenutzer);
		if($dynamisch) {
			cms_rechte_laden_bedingte_rechte();
			cms_rechte_laden_bedingte_rollen();
		}
		$geladen = true;
	}
}

function cms_gruppenrechte_laden($dbs, $gruppe, $gruppenid, $benutzer = "-") {
	global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERART, $CMS_EINSTELLUNGEN;
	if ($benutzer == '-') {
		$benutzer = $_SESSION['BENUTZERID'];
	}
	$fehler = true;

	if ($benutzer != $CMS_BENUTZERID) {
		// Benutzerart laden
		$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = ?");
	  $sql->bind_param("i", $benutzer);
	  if ($sql->execute()) {
	    $sql->bind_result($benutzerart);
	    if ($sql->fetch()) {$fehler = false;}
	  }
	  $sql->close();
	}
	else {
		$fehler = false;
		$benutzerart = $CMS_BENUTZERART;
	}

	$cms_gruppenrechte['gruppenart'] = $gruppe;
	$cms_gruppenrechte['dateiupload'] = false;
	$cms_gruppenrechte['dateidownload'] = false;
	$cms_gruppenrechte['dateiloeschen'] = false;
	$cms_gruppenrechte['dateiumbenennen'] = false;
	$cms_gruppenrechte['termine'] = false;
	$cms_gruppenrechte['blogeintraege'] = false;
	$cms_gruppenrechte['chatten'] = false;
	$cms_gruppenrechte['nachrichtloeschen'] = false;
	$cms_gruppenrechte['nutzerstummschalten'] = false;
	$cms_gruppenrechte['mitglied'] = false;
	$cms_gruppenrechte['sichtbar'] = false;
	$cms_gruppenrechte['bearbeiten'] = false;
	$cms_gruppenrechte['abonniert'] = 0;
	$cms_gruppenrechte['aufsicht'] = false;

	if (!cms_valide_gruppe($gruppe) && !cms_valide_kgruppe($gruppe)) {$fehler = true;}

	if (!$fehler) {
		$gk = cms_textzudb($gruppe);
		// Vorsitz
		$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk"."vorsitz WHERE gruppe = ? AND person = ?");
	  $sql->bind_param("ii", $gruppenid, $benutzer);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {
				if ($anzahl > 0) {
					$cms_gruppenrechte['dateiupload'] = true;
					$cms_gruppenrechte['dateidownload'] = true;
					$cms_gruppenrechte['dateiloeschen'] = true;
					$cms_gruppenrechte['dateiumbenennen'] = true;
					$cms_gruppenrechte['termine'] = true;
					$cms_gruppenrechte['blogeintraege'] = true;
					$cms_gruppenrechte['chatten'] = true;
					$cms_gruppenrechte['nachrichtloeschen'] = true;
					$cms_gruppenrechte['nutzerstummschalten'] = true;
					$cms_gruppenrechte['mitglied'] = true;
					$cms_gruppenrechte['sichtbar'] = true;
					$cms_gruppenrechte['bearbeiten'] = true;
				}
			}
	  }
	  $sql->close();

		// Falls kein Vorsitz oder keine Aufsicht vorliegt, prüfe weiter
		if (!$cms_gruppenrechte['bearbeiten']) {
			// Mitgliedschaft prüfen
			$sql = $dbs->prepare("SELECT dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten FROM $gk"."mitglieder WHERE gruppe = ? AND person = ?");
			$sql->bind_param("ii", $gruppenid, $benutzer);
			if ($sql->execute()) {
		    $sql->bind_result($dateiupload, $dateidownload, $dateiloeschen, $dateiumbenennen, $termine, $blogeintraege, $chatten, $nachrichtloeschen, $nutzerstummschalten);
		    if ($sql->fetch()) {
					if ($dateiupload == '1') {$cms_gruppenrechte['dateiupload'] = true;}
					if ($dateidownload == '1') {$cms_gruppenrechte['dateidownload'] = true;}
					if ($dateiloeschen == '1') {$cms_gruppenrechte['dateiloeschen'] = true;}
					if ($dateiumbenennen == '1') {$cms_gruppenrechte['dateiumbenennen'] = true;}
					if ($termine == '1') {$cms_gruppenrechte['termine'] = true;}
					if ($blogeintraege == '1') {$cms_gruppenrechte['blogeintraege'] = true;}
					if ($chatten == '1') {$cms_gruppenrechte['chatten'] = true;}
					if ($nachrichtloeschen == '1') {$cms_gruppenrechte['nachrichtloeschen'] = true;}
					if ($nutzerstummschalten == '1') {$cms_gruppenrechte['nutzerstummschalten'] = true;}
					$cms_gruppenrechte['mitglied'] = true;
					$cms_gruppenrechte['sichtbar'] = true;
				}
		  }
		  $sql->close();
		}

		if (!$cms_gruppenrechte['mitglied']) {
			// Aufsicht prüfen
			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk"."aufsicht WHERE gruppe = ? AND person = ?");
		  $sql->bind_param("ii", $gruppenid, $benutzer);
		  if ($sql->execute()) {
		    $sql->bind_result($anzahl);
		    if ($sql->fetch()) {
					if ($anzahl > 0) {
						$cms_gruppenrechte['mitglied'] = true;
						$cms_gruppenrechte['aufsicht'] = true;
						$cms_gruppenrechte['sichtbar'] = true;
					}
				}
		  }
		  $sql->close();
		}

		// Sichtbarkeit und Chat prüfen
		$sql = $dbs->prepare("SELECT sichtbar, chataktiv FROM $gk WHERE id = ?");
	  $sql->bind_param("i", $gruppenid);
	  if ($sql->execute()) {
	    $sql->bind_result($sichtbar, $chataktiv);
	    if ($sql->fetch()) {
				if (($sichtbar == 1) && ($benutzerart == 'l')) {$cms_gruppenrechte['sichtbar'] = true;}
				else if (($sichtbar == 2) && (($benutzerart == 'l') || ($benutzerart == 'v'))) {$cms_gruppenrechte['sichtbar'] = true;}
				else if ($sichtbar == 3) {$cms_gruppenrechte['sichtbar'] = true;}
				if ($chataktiv == 0) {
					$cms_gruppenrechte['chatten'] = false;
					$cms_gruppenrechte['nachrichtloeschen'] = false;
					$cms_gruppenrechte['nutzerstummschalten'] = false;
				}
			}
	  }
	  $sql->close();

		// Mögliche Einstellungen berücksichtigen
		if ($cms_gruppenrechte['sichtbar']) {// && (!$cms_gruppenrechte['mitglied'])) {
			if(is_null($CMS_EINSTELLUNGEN)) {
				$CMS_EINSTELLUNGEN = cms_einstellungen_laden('allgemeineeinstellungen');
			}
			if ($CMS_EINSTELLUNGEN['Download aus sichtbaren Gruppen']) {$cms_gruppenrechte['dateidownload'] = true;}
		}

		if ($cms_gruppenrechte['mitglied']) {
			// Abo prüfen
			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk"."notifikationsabo WHERE gruppe = ? AND person = ?");
		  $sql->bind_param("ii", $gruppenid, $benutzer);
		  if ($sql->execute()) {
		    $sql->bind_result($anzahl);
		    if ($sql->fetch()) {if ($anzahl == 1) {$cms_gruppenrechte['abonniert'] = 1;}}
		  }
		  $sql->close();
		}
	}
	return $cms_gruppenrechte;
}

function cms_internterminvorschlag($gruppenrechte) {
	global $CMS_BENUTZERART, $CMS_EINSTELLUNGEN;
	$gruppenart = cms_textzudb($gruppenrechte['gruppenart']);
	return $gruppenrechte['termine'] ||
	       ($gruppenrechte['sichtbar'] && $gruppenrechte['mitglied'] && !$gruppenrechte['aufsicht'] && cms_r("schulhof.verwaltung.gruppen.$gruppenart.artikel.termine.anlegen"));
}

function cms_internblogvorschlag($gruppenrechte) {
	global $CMS_BENUTZERART, $CMS_EINSTELLUNGEN;
	$gruppenart = cms_textzudb($gruppenrechte['gruppenart']);
	return $gruppenrechte['blogeintraege'] ||
	       ($gruppenrechte['sichtbar'] && $gruppenrechte['mitglied'] && !$gruppenrechte['aufsicht'] && cms_r("schulhof.verwaltung.gruppen.$gruppenart.artikel.blogeinträge.anlegen"));
}

function cms_timeout_verlaengern() {
	global $CMS_SESSIONAKTIVITAET;
	// Neues Timeout berechnen
	$neues_timeout = time()+$CMS_SESSIONAKTIVITAET*60;
	$_SESSION['SESSIONTIMEOUT'] = $neues_timeout;

	$dbs = cms_verbinden('s');
	// UPDATE durchführen
	$id = $_SESSION['BENUTZERID'];

	$sql = $dbs->prepare("UPDATE nutzerkonten SET sessiontimeout = ? WHERE id = ?");
  $sql->bind_param("ii", $neues_timeout, $id);
  $sql->execute();
  $sql->close();

	cms_trennen($dbs);
	return $neues_timeout;
}

function cms_alter_berechnen ($tag, $monat, $jahr) {
	$jahrj = date('Y');
	$monatj = date('n');
	$tagj = date('j');

	$jahrdiff = $jahrj - $jahr;
	$monatdiff = $monatj - $monat;
	$tagdiff = $tagj - $tag;

	if ($monatdiff < 0) {
		return $jahrdiff - 1;
	}
	else if ($monatdiff == 0) {
		if ($tagdiff < 0) {
			return $jahrdiff - 1;
		}
		else {
			return $jahrdiff;
		}
	}
	else return $jahrdiff;
}

function cms_check_ip ($ip) {
	// check auf IPv4
	$ipv4richtig = true;
	$ipv6richtig = true;

	$ipv4 = explode(".", $ip);
	// Vier stellen?
	if (count($ipv4) != 4) {$ipv4richtig = false;}
	// Jede Stelle Integer und zwischen 0 und 255
	else {
		for ($i=0; $i<4; $i++) {
			if ((is_int($ipv4[$i])) && ($ipv4 <= 255) && ($ipv4 >= 0)) {$ipv4richtig = false;}
		}
		if (!inet_pton($ip)) {$ipv4richtig = false;}
	}

	$ip = str_replace('#', '0', $ip);
	$ipv6 = explode(':', $ip);
	// Acht stellen?
	if (count($ipv6) != 8) {$ipv6richtig = false;}
	else {
		for ($i=0; $i<8; $i++) {
			// Jede Stelle hat 4 Zeichen?
			if (strlen($ipv6[$i]) != 4) {$ipv6richtig = false;}
			// Jede Stelle besteht nur aus den Zeichen 0-9 bzw. a-f/A-F
			if (preg_match('/^[a-fA-F0-9]+$/', $ipv6[$i]) != 1) {$ipv6richtig = false;}
		}
		if (!inet_pton($ip)) {$ipv6richtig = false;}
	}

	return $ipv4richtig || $ipv6richtig;
}

function cms_dezbin ($dez) {
	$div = $dez;
	$rest = 0;
	$bin = "";
	while (!($div == 0)) {
		$rest = $div % 2;
		$div = floor($div / 2);
		$bin = $rest.$bin;
	}
	return $bin;
}

function cms_anzahl_monate($beginn, $ende) {
	$beginn = explode("-", date("n-Y", $beginn));
	$ende = explode("-", date("n-Y", $ende));
	// Ganze Jahre
	$monate = ($ende[1]-$beginn[1]-1)*12;
	// Monate bisher + Anzahl Monate bis ende
	// + Rest des Beginnjahres + 1, denn der Beginnmonat selbst zählt dazu
	$monate = $monate + $ende[0] + 12 - $beginn[0] + 1;

	return $monate;
}

function cms_istmobil() {
	$browser = $_SERVER['HTTP_USER_AGENT'];
	if (preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $browser)) {
		return true;
	}
	else {
		return false;
	}
}

function cms_welches_betriebssystem() {
	$betriebssystem  = "-";
	$betriebssysteme = array(
    '/windows/i'      			=>  'Windows',
    '/macintosh|mac os x/i' =>  'MacOS',    // OS X
    '/mac_powerpc/i'        =>  'MacOS',    // OS 9
    '/linux/i'              =>  'Linux',    // Linux
    '/ubuntu/i'             =>  'Linux',    // Ubunut
    '/iphone/i'             =>  'iOS',      // iPhone
    '/ipod/i'               =>  'iOS',      // iPod
    '/ipad/i'               =>  'iOS',      // iPad
    '/android/i'            =>  'Android',
    //'/blackberry/i'         =>  'BlackBerry',
    //'/webos/i'              =>  'Mobile'
  );

	foreach ($betriebssysteme as $suchstring => $wert) {
    if (preg_match($suchstring, $_SERVER['HTTP_USER_AGENT'])) {
			$betriebssystem = $wert;
		}
	}
  return $betriebssystem;
}

function cms_einstellungen_laden($tabelle) {
  global $CMS_SCHLUESSEL;
  $einstellungen = array();
	if (($tabelle != 'allgemeineeinstellungen') && ($tabelle != 'wichtigeeinstellungen') && ($tabelle != 'maileinstellungen')) {
		return $einstellungen;
	}
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("SELECT AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') AS inhalt, AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM $tabelle");
	if ($sql->execute()) {
		$sql->bind_result($einhalt, $ewert);
		while ($sql->fetch()) {
			$einstellungen[$einhalt] = $ewert;
		}
	}
	$dbs->close();
	cms_trennen($dbs);
  return $einstellungen;
}

function cms_schulanmeldung_einstellungen_laden() {
  global $CMS_SCHLUESSEL;
  $einstellungen = array();
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("SELECT AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') AS inhalt, AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM schulanmeldung");
	if ($sql->execute()) {
		$sql->bind_result($einhalt, $ewert);
		while ($sql->fetch()) {
			$einstellungen[$einhalt] = $ewert;
		}
	}
	$dbs->close();
	cms_trennen($dbs);
  return $einstellungen;
}

function cms_ist_heute($heute, $pruefdatum) {
  $t = date('d', $pruefdatum) == date('d', $heute);
  $m = date('m', $pruefdatum) == date('m', $heute);
  $j = date('Y', $pruefdatum) == date('Y', $heute);
  return ($t && $m && $j);
}

function cms_websitedateirechte_laden() {
	$gruppenrechte['dateiupload'] 		= cms_r("website.dateien.hochladen");
	$gruppenrechte['dateiumbenennen'] = cms_r("website.dateien.umbenennen");
	$gruppenrechte['dateiloeschen'] 	= cms_r("website.dateien.löschen");
	$gruppenrechte['dateidownload'] = true;
	$gruppenrechte['mitglied'] = true;
	$gruppenrechte['sichtbar'] = true;
	return $gruppenrechte;
}

function cms_titelbilderdateirechte_laden() {
	$gruppenrechte['dateiupload'] 		= cms_r("website.titelbilder.hochladen");
	$gruppenrechte['dateiumbenennen'] = cms_r("website.titelbilder.umbenennen");
	$gruppenrechte['dateiloeschen'] 	= cms_r("website.titelbilder.löschen");
	$gruppenrechte['dateidownload'] = true;
	$gruppenrechte['mitglied'] = true;
	$gruppenrechte['sichtbar'] = true;
	return $gruppenrechte;
}

function cms_oeffentlich_sichtbar($dbs, $art, $daten) {
	global $CMS_BENUTZERART, $CMS_GRUPPEN, $CMS_ANGEMELDET;

	if ($daten['aktiv'] != 1) {return false;}

	if ($daten['oeffentlichkeit'] >= 3) {
		if (($CMS_ANGEMELDET) || ($daten['genehmigt'] == 1)) {return true;}
	}
	else if (!$CMS_ANGEMELDET) {return false;}
	else if (($daten['oeffentlichkeit'] == 2) && (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v'))) {return true;}
	else if (($daten['oeffentlichkeit'] == 1) && ($CMS_BENUTZERART == 'l')) {return true;}
	else if (($daten['oeffentlichkeit'] == 0)) {
		// Mitgliedschaften prüfen
		if ($art == 'termine') {
			$tabelle = 'termine';
			$spalte = 'termin';
		}
		else if ($art == 'blogeintraege') {
			$tabelle = 'blogeintraege';
			$spalte = 'blogeintrag';
		}

		foreach ($CMS_GRUPPEN as $g) {
			$erlaubt = false;
			$gk = cms_textzudb($g);

			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM ((SELECT person FROM $gk"."mitglieder WHERE gruppe IN (SELECT gruppe FROM $gk"."$tabelle WHERE $spalte = ?)) UNION (SELECT person FROM $gk"."aufsicht WHERE gruppe IN (SELECT gruppe FROM $gk"."$tabelle WHERE $spalte = ?))) AS y");
		  $sql->bind_param("ii", $daten['id'], $daten['id']);

			if ($sql->execute()) {
		    $sql->bind_result($anzahl);
		    if ($sql->fetch()) {if ($anzahl > 0) {$erlaubt = true;}}
				else {$fehler = true;}
		  }
		  else {$fehler = true;}
		  $sql->close();
			if ($erlaubt) {return true;}
		}
		return true;
	}
}

function cms_schreibeberechtigung($dbs, $zielperson) {
  global $CMS_BENUTZERID, $CMS_BENUTZERART, $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN;
  $zielpersonart = "";
	$nutzerkonto = null;

  // Hole Zielperson
	$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, nutzerkonten.id AS nutzerkonto FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id = ?");
  $sql->bind_param("i", $zielperson);
  if ($sql->execute()) {
    $sql->bind_result($zielpersonart, $nutzerkonto);
    $sql->fetch();
  }
  $sql->close();

  if (is_null($nutzerkonto)) {return false;}

  $personart = "";
  if ($CMS_BENUTZERART == 'l') {$personart = "Lehrer";}
  if ($CMS_BENUTZERART == 's') {$personart = "Schüler";}
  if ($CMS_BENUTZERART == 'e') {$personart = "Eltern";}
  if ($CMS_BENUTZERART == 'v') {$personart = "Verwaltungsangestellte";}
  if ($CMS_BENUTZERART == 'x') {$personart = "Externe";}

  if ((strlen($personart) > 0) && (strlen($zielpersonart) > 0)) {
    $recht['l'] = cms_r("schulhof.nutzerkonto.postfach.lehrer");
    $recht['e'] = cms_r("schulhof.nutzerkonto.postfach.eltern");
    $recht['s'] = cms_r("schulhof.nutzerkonto.postfach.schüler");
    $recht['v'] = cms_r("schulhof.nutzerkonto.postfach.verwaltungsangestellte");
    $recht['x'] = cms_r("schulhof.nutzerkonto.postfach.externe");

    if ($recht[$zielpersonart]) {return true;}
    else {
      $gruppenamt[0] = 'mitglieder';
      $gruppenamt[1] = 'vorsitz';
      $gruppenamt[2] = 'aufsicht';
      foreach ($gruppenamt as $amt) {
        $schreibberechtigung = cms_amtstraeger($dbs, $zielperson, $amt);
        if ($schreibberechtigung) {return true;}
      }
    }
  }

  return false;
}

function postLesen(...$felder) {
	$n = $felder[count($felder)-1];
	if($n === true || $n === false)
		$nullfehler = array_pop($felder);
	else
		$nullfehler = true;

	if(is_array($felder[0]))
		$felder = $felder[0];

	if(count($felder) > 1) {
		foreach($felder as $i => $f)
			postLesen($f, $nullfehler);
		return;
	}

	$feld = $felder[0];
	global $$feld;

	if(isset($_POST[$feld]))
		$$feld = $_POST[$feld];
	else
		if($nullfehler)
			die("FEHLER");
}

function getLesen(...$felder) {
	$n = $felder[count($felder)-1];
	if($n === true || $n === false)
		$nullfehler = array_pop($felder);
	else
		$nullfehler = true;

	if(is_array($felder[0]))
		$felder = $felder[0];

	if(count($felder) > 1) {
		foreach($felder as $i => $f)
			getLesen($f, $nullfehler);
		return;
	}

	$feld = $felder[0];
	global $$feld;

	if(isset($_GET[$feld]))
		$$feld = $_GET[$feld];
	else
		if($nullfehler)
			die("FEHLER");
}

function cms_check_sessionvars() {

	if (!isset($_SESSION['BENUTZERID'])) {return false;}
	if (!isset($_SESSION['BENUTZERART'])) {return false;}

	if (!cms_check_ganzzahl($_SESSION['BENUTZERID'])) {return false;}
	if (($_SESSION['BENUTZERSCHULJAHR'] !== null) && (!cms_check_ganzzahl($_SESSION['BENUTZERSCHULJAHR']))) {return false;}
	if (($_SESSION['BENUTZERART'] != 's') && ($_SESSION['BENUTZERART'] != 'l') && ($_SESSION['BENUTZERART'] != 'v') && ($_SESSION['BENUTZERART'] != 'e') && ($_SESSION['BENUTZERART'] != 'x')) {return false;}
	return true;
}
?>
