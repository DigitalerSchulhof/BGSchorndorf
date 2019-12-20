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
	if (preg_match("/^[\.\-a-zA-Z0-9äöüßÄÖÜ ]+$/", $titel) != 1) {
		return false;
	}
	else if (($titel == '.') || ($titel == '..')) {
		return false;
	}
	else return true;
}

function cms_check_url($url) {
	if (preg_match("/^[\.\-a-zA-Z0-9äöüßÄÖÜ\/_ ]+$/", $url) != 1) {
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


function cms_rechte_laden($aktiverbenutzer = '-') {
	global $CMS_SCHLUESSEL;

	// Verbindung zur Datenbank herstellen
	$BENUTZERIDTEST = "-";
	$BENUTZERARTTEST = "-";
	if (isset($_SESSION['BENUTZERID'])) {$BENUTZERIDTEST = $_SESSION['BENUTZERID'];}
	if ($aktiverbenutzer == '-') {$aktiverbenutzer = $BENUTZERIDTEST;}

	$dbs = cms_verbinden('s');

	$sql = $dbs->prepare("SELECT person AS wert, AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM rechte LEFT JOIN (SELECT person, recht FROM rechtzuordnung WHERE person = ? UNION SELECT DISTINCT rolle*0+? AS person, recht FROM rollenrechte WHERE rolle IN (SELECT rolle FROM rollenzuordnung WHERE person = ?)) AS rechtzuordnung ON rechte.id = rechtzuordnung.recht");
  $sql->bind_param("iii", $aktiverbenutzer, $aktiverbenutzer, $aktiverbenutzer);
  if ($sql->execute()) {
    $sql->bind_result($wert, $kategorie, $bezeichnung);
    while($sql->fetch()) {
			if ($wert == $aktiverbenutzer) {$CMS_RECHTE[$kategorie][$bezeichnung] = true;}
			else {$CMS_RECHTE[$kategorie][$bezeichnung] = false;}
    }
  }
  $sql->close();

	$CMS_BENUTZERART = "";

	if (isset($_SESSION['BENUTZERART'])) {$BENUTZERARTTEST = $_SESSION['BENUTZERART'];}

	// Benutzerart des gewählten Benutzers laden - falls eigene
	if ($aktiverbenutzer == $BENUTZERIDTEST) {
		$CMS_BENUTZERART = $BENUTZERARTTEST;
	}
	// Benutzerart des gewählten Benutzers laden - falls fremde
	else {
		$sql = $dbs->prepare("SELECT AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = ?;");
	  $sql->bind_param("i", $aktiverbenutzer);
	  if ($sql->execute()) {
	    $sql->bind_result($CMS_BENUTZERART);
	    $sql->fetch();
	  }
	  $sql->close();
	}

	// Rechte nach Benutzerart ändern
	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();
	if ($CMS_BENUTZERART != "") {
		if ($CMS_BENUTZERART == 's') {
			if ($CMS_EINSTELLUNGEN['Schüler dürfen Termine vorschlagen']) {$CMS_RECHTE['Website']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Schüler dürfen Blogeinträge vorschlagen']) {$CMS_RECHTE['Website']['Blogeinträge anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Schüler dürfen Galerien vorschlagen']) {$CMS_RECHTE['Website']['Galerien anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Schüler dürfen persönliche Termine anlegen']) {$CMS_RECHTE['Persönlich']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Schüler dürfen persönliche Notizen anlegen']) {$CMS_RECHTE['Persönlich']['Notizen anlegen'] = true;}
		}
		else if ($CMS_BENUTZERART == 'e') {
			if ($CMS_EINSTELLUNGEN['Eltern dürfen Termine vorschlagen']) {$CMS_RECHTE['Website']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Eltern dürfen Blogeinträge vorschlagen']) {$CMS_RECHTE['Website']['Blogeinträge anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Eltern dürfen Galerien vorschlagen']) {$CMS_RECHTE['Website']['Galerien anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Eltern dürfen persönliche Termine anlegen']) {$CMS_RECHTE['Persönlich']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Eltern dürfen persönliche Notizen anlegen']) {$CMS_RECHTE['Persönlich']['Notizen anlegen'] = true;}
		}
		else if ($CMS_BENUTZERART == 'l') {
			if ($CMS_EINSTELLUNGEN['Lehrer dürfen Termine vorschlagen']) {$CMS_RECHTE['Website']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Lehrer dürfen Blogeinträge vorschlagen']) {$CMS_RECHTE['Website']['Blogeinträge anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Lehrer dürfen Galerien vorschlagen']) {$CMS_RECHTE['Website']['Galerien anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Lehrer dürfen persönliche Termine anlegen']) {$CMS_RECHTE['Persönlich']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Lehrer dürfen persönliche Notizen anlegen']) {$CMS_RECHTE['Persönlich']['Notizen anlegen'] = true;}
			$CMS_RECHTE['Technik']['Geräte-Probleme melden'] = true;
			$CMS_RECHTE['Technik']['Hausmeisteraufträge erteilen'] = true;
			$CMS_RECHTE['Planung']['Buchungen sehen'] = true;
			$CMS_RECHTE['Planung']['Buchungen vornehmen'] = true;
			$CMS_RECHTE['Personen']['Personen sehen'] = true;
			$CMS_RECHTE['Zugriffe']['Lehrernetz'] = true;
			$CMS_RECHTE['Planung']['Klassenstundenpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Lehrerstundenpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Stufenstundenpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Räume sehen'] = true;
			$CMS_RECHTE['Planung']['Raumpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Leihgeräte sehen'] = true;
			$CMS_RECHTE['Planung']['Lehrervertretungsplan sehen'] = true;
			$CMS_RECHTE['Planung']['Schülervertretungsplan sehen'] = true;
		}
		else if ($CMS_BENUTZERART == 'v') {
			if ($CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen Termine vorschlagen']) {$CMS_RECHTE['Website']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen Blogeinträge vorschlagen']) {$CMS_RECHTE['Website']['Blogeinträge anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen Galerien vorschlagen']) {$CMS_RECHTE['Website']['Galerien anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen persönliche Termine anlegen']) {$CMS_RECHTE['Persönlich']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen persönliche Notizen anlegen']) {$CMS_RECHTE['Persönlich']['Notizen anlegen'] = true;}
			$CMS_RECHTE['Technik']['Geräte-Probleme melden'] = true;
			$CMS_RECHTE['Technik']['Hausmeisteraufträge erteilen'] = true;
			$CMS_RECHTE['Planung']['Buchungen sehen'] = true;
			$CMS_RECHTE['Planung']['Buchungen vornehmen'] = true;
			$CMS_RECHTE['Personen']['Personen sehen'] = true;
			$CMS_RECHTE['Planung']['Klassenstundenpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Lehrerstundenpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Stufenstundenpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Räume sehen'] = true;
			$CMS_RECHTE['Planung']['Raumpläne sehen'] = true;
			$CMS_RECHTE['Planung']['Leihgeräte sehen'] = true;
			$CMS_RECHTE['Planung']['Lehrervertretungsplan sehen'] = true;
			$CMS_RECHTE['Planung']['Schülervertretungsplan sehen'] = true;
		}
		else if ($CMS_BENUTZERART == 'x') {
			if ($CMS_EINSTELLUNGEN['Externe dürfen Termine vorschlagen']) {$CMS_RECHTE['Website']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Externe dürfen Blogeinträge vorschlagen']) {$CMS_RECHTE['Website']['Blogeinträge anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Externe dürfen Galerien vorschlagen']) {$CMS_RECHTE['Website']['Galerien anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Externe dürfen persönliche Termine anlegen']) {$CMS_RECHTE['Persönlich']['Termine anlegen'] = true;}
			if ($CMS_EINSTELLUNGEN['Externe dürfen persönliche Notizen anlegen']) {$CMS_RECHTE['Persönlich']['Notizen anlegen'] = true;}
		}
	}

	// Rechte nach Einstellungen überschreiben
	$CMS_EINSTELLUNGEN = cms_einstellungen_laden();

	cms_trennen($dbs);

	return $CMS_RECHTE;
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

	$CMS_RECHTE['dateiupload'] = false;
	$CMS_RECHTE['dateidownload'] = false;
	$CMS_RECHTE['dateiloeschen'] = false;
	$CMS_RECHTE['dateiumbenennen'] = false;
	$CMS_RECHTE['termine'] = false;
	$CMS_RECHTE['blogeintraege'] = false;
	$CMS_RECHTE['chatten'] = false;
	$CMS_RECHTE['nachrichtloeschen'] = false;
	$CMS_RECHTE['nutzerstummschalten'] = false;
	$CMS_RECHTE['mitglied'] = false;
	$CMS_RECHTE['sichtbar'] = false;
	$CMS_RECHTE['bearbeiten'] = false;
	$CMS_RECHTE['abonniert'] = 0;

	if (!cms_valide_gruppe($gruppe) && !cms_valide_kgruppe($gruppe)) {$fehler = true;}

	if (!$fehler) {
		$gk = cms_textzudb($gruppe);
		// Vorsitz / Aufsicht prüfen
		$sql = $dbs->prepare("SELECT SUM(anzahl) AS anzahl FROM ((SELECT COUNT(*) AS anzahl FROM $gk"."vorsitz WHERE gruppe = ? AND person = ?) UNION (SELECT COUNT(*) AS anzahl FROM $gk"."aufsicht WHERE gruppe = ? AND person = ?)) AS x");
	  $sql->bind_param("iiii", $gruppenid, $benutzer, $gruppenid, $benutzer);
	  if ($sql->execute()) {
	    $sql->bind_result($anzahl);
	    if ($sql->fetch()) {
				if ($anzahl > 0) {
					$CMS_RECHTE['dateiupload'] = true;
					$CMS_RECHTE['dateidownload'] = true;
					$CMS_RECHTE['dateiloeschen'] = true;
					$CMS_RECHTE['dateiumbenennen'] = true;
					$CMS_RECHTE['termine'] = true;
					$CMS_RECHTE['blogeintraege'] = true;
					$CMS_RECHTE['chatten'] = true;
					$CMS_RECHTE['nachrichtloeschen'] = true;
					$CMS_RECHTE['nutzerstummschalten'] = true;
					$CMS_RECHTE['mitglied'] = true;
					$CMS_RECHTE['sichtbar'] = true;
					$CMS_RECHTE['bearbeiten'] = true;
				}
			}
	  }
	  $sql->close();

		// Falls kein Vorsitz oder keine Aufsicht vorliegt, prüfe weiter
		if (!$CMS_RECHTE['bearbeiten']) {
			// Mitgliedschaft prüfen
			$sql = $dbs->prepare("SELECT dateiupload, dateidownload, dateiloeschen, dateiumbenennen, termine, blogeintraege, chatten, nachrichtloeschen, nutzerstummschalten FROM $gk"."mitglieder WHERE gruppe = ? AND person = ?");
			$sql->bind_param("ii", $gruppenid, $benutzer);
			if ($sql->execute()) {
		    $sql->bind_result($dateiupload, $dateidownload, $dateiloeschen, $dateiumbenennen, $termine, $blogeintraege, $chatten, $nachrichtloeschen, $nutzerstummschalten);
		    if ($sql->fetch()) {
					if ($dateiupload == '1') {$CMS_RECHTE['dateiupload'] = true;}
					if ($dateidownload == '1') {$CMS_RECHTE['dateidownload'] = true;}
					if ($dateiloeschen == '1') {$CMS_RECHTE['dateiloeschen'] = true;}
					if ($dateiumbenennen == '1') {$CMS_RECHTE['dateiumbenennen'] = true;}
					if ($termine == '1') {$CMS_RECHTE['termine'] = true;}
					if ($blogeintraege == '1') {$CMS_RECHTE['blogeintraege'] = true;}
					if ($chatten == '1') {$CMS_RECHTE['chatten'] = true;}
					if ($nachrichtloeschen == '1') {$CMS_RECHTE['nachrichtloeschen'] = true;}
					if ($nutzerstummschalten == '1') {$CMS_RECHTE['nutzerstummschalten'] = true;}
					$CMS_RECHTE['mitglied'] = true;
					$CMS_RECHTE['sichtbar'] = true;
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
				if (($sichtbar == 1) && ($benutzerart == 'l')) {$CMS_RECHTE['sichtbar'] = true;}
				else if (($sichtbar == 2) && (($benutzerart == 'l') || ($benutzerart == 'v'))) {$CMS_RECHTE['sichtbar'] = true;}
				else if ($sichtbar == 3) {$CMS_RECHTE['sichtbar'] = true;}
				if ($chataktiv == 0) {
					$CMS_RECHTE['chatten'] = false;
					$CMS_RECHTE['nachrichtloeschen'] = false;
					$CMS_RECHTE['nutzerstummschalten'] = false;
				}
			}
	  }
	  $sql->close();

		// Mögliche Einstellungen berücksichtigen
		if ($CMS_RECHTE['sichtbar']) {// && (!$CMS_RECHTE['mitglied'])) {
			if ($CMS_EINSTELLUNGEN['Download aus sichtbaren Gruppen']) {$CMS_RECHTE['dateidownload'] = true;}
		}

		if ($CMS_RECHTE['mitglied']) {
			// Abo prüfen
			$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM $gk"."notifikationsabo WHERE gruppe = ? AND person = ?");
		  $sql->bind_param("ii", $gruppenid, $benutzer);
		  if ($sql->execute()) {
		    $sql->bind_result($anzahl);
		    if ($sql->fetch()) {if ($anzahl == 1) {$CMS_RECHTE['abonniert'] = 1;}}
		  }
		  $sql->close();
		}
	}
	return $CMS_RECHTE;
}


function cms_internterminvorschlag($gruppenrechte) {
	global $CMS_BENUTZERART, $CMS_EINSTELLUNGEN;
	return $gruppenrechte['termine'] || ($gruppenrechte['sichtbar'] && $gruppenrechte['mitglied'] &&
																			(($CMS_BENUTZERART == 'l' && $CMS_EINSTELLUNGEN['Lehrer dürfen intern Termine vorschlagen'])
																	 || ($CMS_BENUTZERART == 's' && $CMS_EINSTELLUNGEN['Schüler dürfen intern Termine vorschlagen'])
																	 || ($CMS_BENUTZERART == 'e' && $CMS_EINSTELLUNGEN['Eltern dürfen intern Termine vorschlagen'])
																	 || ($CMS_BENUTZERART == 'v' && $CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen intern Termine vorschlagen'])
																	 || ($CMS_BENUTZERART == 'x' && $CMS_EINSTELLUNGEN['Externe dürfen intern Termine vorschlagen'])));
}


function cms_internblogvorschlag($gruppenrechte) {
	global $CMS_BENUTZERART, $CMS_EINSTELLUNGEN;
	return $gruppenrechte['blogeintraege'] || ($gruppenrechte['sichtbar'] && $gruppenrechte['mitglied'] &&
																						(($CMS_BENUTZERART == 'l' && $CMS_EINSTELLUNGEN['Lehrer dürfen intern Blogeinträge vorschlagen'])
																				 || ($CMS_BENUTZERART == 's' && $CMS_EINSTELLUNGEN['Schüler dürfen intern Blogeinträge vorschlagen'])
																				 || ($CMS_BENUTZERART == 'e' && $CMS_EINSTELLUNGEN['Eltern dürfen intern Blogeinträge vorschlagen'])
																				 || ($CMS_BENUTZERART == 'v' && $CMS_EINSTELLUNGEN['Verwaltungsangestellte dürfen intern Blogeinträge vorschlagen'])
																				 || ($CMS_BENUTZERART == 'x' && $CMS_EINSTELLUNGEN['Externe dürfen intern Blogeinträge vorschlagen'])));
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

function cms_einstellungen_laden() {
  global $CMS_SCHLUESSEL;
  $einstellungen = array();
	$dbs = cms_verbinden('s');
	$sql = $dbs->prepare("SELECT AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') AS inhalt, AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM allgemeineeinstellungen");
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
	global $CMS_RECHTE;
	$gruppenrechte['dateiupload'] = $CMS_RECHTE['Website']['Dateien hochladen'];
	$gruppenrechte['dateiumbenennen'] = $CMS_RECHTE['Website']['Dateien umbenennen'];
	$gruppenrechte['dateiloeschen'] = $CMS_RECHTE['Website']['Dateien löschen'];
	$gruppenrechte['dateidownload'] = true;
	$gruppenrechte['mitglied'] = true;
	$gruppenrechte['sichtbar'] = true;
	return $gruppenrechte;
}

function cms_titelbilderdateirechte_laden() {
	global $CMS_RECHTE;
	$gruppenrechte['dateiupload'] = $CMS_RECHTE['Website']['Titelbilder hochladen'];
	$gruppenrechte['dateiumbenennen'] = $CMS_RECHTE['Website']['Titelbilder umbenennen'];
	$gruppenrechte['dateiloeschen'] = $CMS_RECHTE['Website']['Titelbilder löschen'];
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
    $recht['l'] = $CMS_EINSTELLUNGEN['Postfach - '.$personart.' dürfen Lehrer schreiben'];
    $recht['e'] = $CMS_EINSTELLUNGEN['Postfach - '.$personart.' dürfen Eltern schreiben'];
    $recht['s'] = $CMS_EINSTELLUNGEN['Postfach - '.$personart.' dürfen Schüler schreiben'];
    $recht['v'] = $CMS_EINSTELLUNGEN['Postfach - '.$personart.' dürfen Verwaltungsangestellte schreiben'];
    $recht['x'] = $CMS_EINSTELLUNGEN['Postfach - '.$personart.' dürfen Externe schreiben'];

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

function postLesen($feld, $nullfehler = true) {
	if(is_array($feld)) {
		foreach($feld as $i => $f)
			postLesen($f, $nullfehler);
		return;
	}
	global $$feld;

	if(isset($_POST[$feld]))
		$$feld = $_POST[$feld];
	else
		if($nullfehler)
			die("FEHLER");
}

function getLesen($feld, $nullfehler = true) {
	if(is_array($feld)) {
		foreach($feld as $i => $f)
			getLesen($f, $nullfehler);
		return;
	}
	global $$feld;

	if(isset($_GET[$feld]))
		$$feld = $_GET[$feld];
	else
		if($nullfehler)
			die("FEHLER");
}

function sqlLesen($row, $feld) {
	if(is_array($feld)) {
		foreach($feld as $i => $f)
			sqlLesen($row, $f);
		return;
	}
	global $$feld;

	if(isset($row[$feld]))
		$$feld = $row[$feld];
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
