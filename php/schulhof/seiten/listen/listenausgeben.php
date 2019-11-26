<?php
function cms_listen_personenliste_ausgeben($dbs, $schreibenpool, $art, $postfach = '0', $leer = '0', $eltern = '0', $kinder = '0', $klassen = '0', $reli = '0', $adresse  = '0', $kontaktdaten = '0', $geburtsdatum = '0', $konfession = '0', $profil = '0') {
	global $CMS_SCHLUESSEL, $CMS_BENUTZERSCHULJAHR;
	$spalten = 2;
	$code = "<tr><th></th><th>Name</th>";
	if ($postfach == '1') {$code .= "<th>Postfach</th>"; $spalten ++;}
	if ($art == 's' || $art == 'sv') {
		if ($eltern == '1') {$code .= "<th>Eltern</th>"; $spalten ++;}
	}
	if ($art == 's' || $art == 'sv' || $art == 'l') {
			if ($klassen == '1') {$code .= "<th>Klassen</th>"; $spalten ++;}
	}
	if ($art == 'e' || $art == 'ev') {
		if ($kinder == '1') {$code .= "<th>Kinder</th>"; $spalten ++;}
	}
	if ($leer == '1') {$code .= "<th>______________________________</th>"; $spalten ++;}
	$code .= "</tr>";
	$tabelle = "";
	$nr = 0;
  $allenschreiben = "";
	if ($art != 'ev' && $art != 'sv') {
		$sqlspalten = "";
		$sqljoin = "";

		if ($postfach == '1') {
			$sqlspalten .= ", nutzerkonten.id AS nutzerkonto";
			$sqljoin .= " LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id";
		}

		$sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel$sqlspalten FROM personen$sqljoin WHERE art = AES_ENCRYPT('$art', '$CMS_SCHLUESSEL')) AS x ORDER BY nachname ASC, vorname ASC, titel ASC";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				$nr ++;
        $allenschreiben .= "|".$daten['id'];
				$tabelle .= "<tr>";
					$tabelle .= "<td>$nr</td>";
					$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
					$tabelle .= "<td>$anzeigename</td>";
					if ($postfach == '1') {
						if (is_null($daten['nutzerkonto'])) {
							$tabelle .= "<td><span class=\"cms_button_passiv\" onclick=\"cms_meldung_keinkonto()\">Nachricht schreiben</span></td>";
						}
						else if (in_array($daten['id'], $schreibenpool)) {
							$tabelle .= "<td><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', ".$daten['id'].")\">Nachricht schreiben</span></td>";
						}
						else {
							$tabelle .= "<td><span class=\"cms_button_passivda\" onclick=\"cms_meldung_nichtschreiben()\">Nachricht schreiben</span></td>";
						}
					}

					if ($art == 's' || $art == 'sv') {
						if ($eltern == '1') {
							$elterntext = "";
							$sqlinnen = "SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN schuelereltern ON personen.id = schuelereltern.eltern WHERE schuelereltern.schueler = ".$daten['id'].") AS x ORDER BY nachname ASC, vorname ASC, titel ASC";
							if ($anfrageinnen = $dbs->query($sqlinnen)) {	// Safe weil interne ID
								while ($dateninnen = $anfrageinnen->fetch_assoc()) {
									$elterntext .= ", ".cms_generiere_anzeigename($dateninnen['vorname'], $dateninnen['nachname'], $dateninnen['titel']);
								}
								$anfrageinnen->free();
							}
							$tabelle .= "<td>".substr($elterntext, 2)."</td>";
						}
					}
					if ($art == 's' || $art == 'sv' || $art == 'l') {
							if ($klassen == '1') {
                $klassentext = "";
  							$sqlinnen = "SELECT * FROM (SELECT reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez FROM klassenmitglieder JOIN klassen ON klassenmitglieder.gruppe = klassen.id JOIN stufen ON klassen.stufe = stufen.id WHERE klassenmitglieder.person = ".$daten['id'].") AS x ORDER BY reihenfolge ASC, klassenbez ASC";
  							if ($anfrageinnen = $dbs->query($sqlinnen)) {	// Safe weil interne ID
  								while ($dateninnen = $anfrageinnen->fetch_assoc()) {
  									$klassentext .= ", ".$dateninnen['klassenbez'];
  								}
  								$anfrageinnen->free();
  							}
  							$tabelle .= "<td>".substr($klassentext, 2)."</td>";
              }
					}
					if ($art == 'e' || $art == 'ev') {
            if ($kinder == '1') {
							$kindertext = "";
							$sqlinnen = "SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez FROM personen JOIN schuelereltern ON personen.id = schuelereltern.schueler LEFT JOIN stufenmitglieder ON schuelereltern.schueler = stufenmitglieder.person LEFT JOIN stufen ON stufenmitglieder.gruppe = stufen.id WHERE schuelereltern.eltern = ".$daten['id']." AND (stufen.schuljahr IS NULL OR stufen.schuljahr = $CMS_BENUTZERSCHULJAHR)) AS x ORDER BY nachname ASC, vorname ASC, titel ASC";
							if ($anfrageinnen = $dbs->query($sqlinnen)) {	// Safe weil interne ID
								while ($dateninnen = $anfrageinnen->fetch_assoc()) {
									$kindertext .= ", ".cms_generiere_anzeigename($dateninnen['vorname'], $dateninnen['nachname'], $dateninnen['titel']);
                  if (!is_null($dateninnen['stufenbez'])) {$kindertext .= " (".$dateninnen['stufenbez'].")";}
								}
								$anfrageinnen->free();
							}
							$tabelle .= "<td>".substr($kindertext, 2)."</td>";
						}
					}

					if ($leer == '1') {$tabelle .= "<td></td>";}
				$tabelle .= "</tr>";
			}
			$anfrage->free();
		}
	}

	if (strlen($tabelle) > 0) {$code .= $tabelle;}

	$pers = "Personen";
	if ($nr == 1) {$pers = "Person";}
	$code .= "<tr><td class=\"cms_notiz\" colspan=\"$spalten\">$nr $pers in der Liste – <span class=\"cms_link\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($allenschreiben,1)."')\">Dieser Liste schreiben</span></td></tr>";

	return $code;
}




function cms_listen_gruppenliste_ausgeben($dbs, $gruppenart, $gruppenid, $personengruppen, $gruppenraenge, $schreibenpool, $postfach = '0', $leer = '0', $eltern = '0', $kinder = '0', $klassen = '0', $reli = '0', $adresse  = '0', $kontaktdaten = '0', $geburtsdatum = '0', $konfession = '0', $profil = '0') {

	if (preg_match("/m/", $gruppenraenge)) {
	  $mitgliederliste = cms_listen_gruppenliste_gruppenraenge_ausgeben($dbs, 'mitglieder', $gruppenart, $gruppenid, $personengruppen, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$mitgliederliste = cms_listen_gruppenliste_leererueckgabe();}
	if (preg_match("/v/", $gruppenraenge)) {
		$vorsitzliste = cms_listen_gruppenliste_gruppenraenge_ausgeben($dbs, 'vorsitz', $gruppenart, $gruppenid, $personengruppen, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$vorsitzliste = cms_listen_gruppenliste_leererueckgabe();}
		if (preg_match("/a/", $gruppenraenge)) {
		$aufsichtsliste = cms_listen_gruppenliste_gruppenraenge_ausgeben($dbs, 'aufsicht', $gruppenart, $gruppenid, $personengruppen, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$aufsichtsliste = cms_listen_gruppenliste_leererueckgabe();}

  $code = "";
  $allenschreiben = "";
	$knoepfe = "";
  if (strlen($mitgliederliste['tabelle']) > 0) {
    $code .= "<h2>Mitglieder</h2>".$mitgliederliste['tabelle'];
    $allenschreiben .= "|".$mitgliederliste['schreiben'];
  }
  if (strlen($vorsitzliste['tabelle']) > 0) {
    $code .= "<h2>Vorsitz</h2>".$vorsitzliste['tabelle'];
    $allenschreiben .= "|".$vorsitzliste['schreiben'];
  }
  if (strlen($aufsichtsliste['tabelle']) > 0) {
    $code .= "<h2>Aufsicht</h2>".$aufsichtsliste['tabelle'];
    $allenschreiben .= "|".$aufsichtsliste['schreiben'];
  }

  if (strlen($allenschreiben) > 0) {$knoepfe = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($allenschreiben,1)."')\">Allen schreiben</span> ";}

  $rueckgabe['tabelle'] = $code;
  $rueckgabe['knoepfe'] = $knoepfe;

	return $rueckgabe;
}


function cms_listen_gruppenliste_gruppenraenge_ausgeben($dbs, $personengruppe, $gruppenart, $gruppenid, $personengruppen, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil) {
	if (preg_match("/s/", $personengruppen)) {
		$schuelertabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 's', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$schuelertabelle = cms_listen_gruppenliste_leererueckgabe();}
	if (preg_match("/l/", $personengruppen)) {
	  $lehrertabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'l', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$lehrertabelle = cms_listen_gruppenliste_leererueckgabe();}
	if (preg_match("/e/", $personengruppen)) {
		$elterntabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'e', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$elterntabelle = cms_listen_gruppenliste_leererueckgabe();}
	if (preg_match("/v/", $personengruppen)) {
		$verwaltungstabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'v', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$verwaltungstabelle = cms_listen_gruppenliste_leererueckgabe();}
	if (preg_match("/x/", $personengruppen)) {
		$externentabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'x', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil);
	}
	else {$externentabelle = cms_listen_gruppenliste_leererueckgabe();}

  $code = "";
  $allenschreiben = "";
  $knoepfe = "";
  if (strlen($schuelertabelle['tabelle']) > 0) {
    $code .= "<h3>Schüler</h3><table class=\"cms_liste\">".$schuelertabelle['tabelle']."</table>";
    $allenschreiben .= "|".$schuelertabelle['schreiben'];
  }
  if (strlen($lehrertabelle['tabelle']) > 0) {
    $code .= "<h3>Lehrer</h3><table class=\"cms_liste\">".$lehrertabelle['tabelle']."</table>";
    $allenschreiben .= "|".$lehrertabelle['schreiben'];
  }
  if (strlen($elterntabelle['tabelle']) > 0) {
    $code .= "<h3>Eltern</h3><table class=\"cms_liste\">".$elterntabelle['tabelle']."</table>";
    $allenschreiben .= "|".$elterntabelle['schreiben'];
  }
  if (strlen($verwaltungstabelle['tabelle']) > 0) {
    $code .= "<h3>Verwaltungsangestellte</h3><table class=\"cms_liste\">".$verwaltungstabelle['tabelle']."</table>";
    $allenschreiben .= "|".$verwaltungstabelle['schreiben'];
  }
  if (strlen($externentabelle['tabelle']) > 0) {
    $code .= "<h3>Externe</h3><table class=\"cms_liste\">".$externentabelle['tabelle']."</table>";
    $allenschreiben .= "|".$externentabelle['schreiben'];
  }

  $rueckgabe['tabelle'] = $code;
  $rueckgabe['schreiben'] = $allenschreiben;

	return $rueckgabe;
}




function cms_listen_gruppenliste_personen_ausgeben($dbs, $art, $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession, $profil) {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERSCHULJAHR;
  $spalten = 2;

  $code = "<tr><th></th><th>Name</th>";
	if ($postfach == '1') {$code .= "<th>Postfach</th>"; $spalten ++;}
	if ($art == 's') {
		if ($eltern == '1') {$code .= "<th>Eltern</th>"; $spalten ++;}
	}
	if ($art == 's' || $art == 'l') {
			if ($klassen == '1') {$code .= "<th>Klassen</th>"; $spalten ++;}
	}
	if ($art == 'e') {
		if ($kinder == '1') {$code .= "<th>Kinder</th>"; $spalten ++;}
	}
	if ($leer == '1') {$code .= "<th>______________________________</th>"; $spalten ++;}

	$code .= "</tr>";
	$tabelle = "";
	$nr = 0;
  $allenschreiben = "";

	$sqlspalten = "";
	$sqljoin = "JOIN $gruppenart"."$personengruppe ON personen.id = $gruppenart"."$personengruppe.person JOIN $gruppenart ON $gruppenart.id = $gruppenart"."$personengruppe.gruppe";

	if ($postfach == '1') {
		$sqlspalten .= ", nutzerkonten.id AS nutzerkonto";
		$sqljoin .= " LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id";
	}

	$sql = "SELECT * FROM (SELECT personen.id AS id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel$sqlspalten FROM personen $sqljoin WHERE art = AES_ENCRYPT('$art', '$CMS_SCHLUESSEL') AND $gruppenart.id = $gruppenid) AS x ORDER BY nachname ASC, vorname ASC, titel ASC";
	if ($anfrage = $dbs->query($sql)) {	// TODO: Irgendwie sicher machen
		while ($daten = $anfrage->fetch_assoc()) {
			$nr ++;
      $allenschreiben .= "|".$daten['id'];
			$tabelle .= "<tr>";
				$tabelle .= "<td>$nr</td>";
				$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
				$tabelle .= "<td>$anzeigename</td>";
				if ($postfach == '1') {
					if (is_null($daten['nutzerkonto'])) {
						$tabelle .= "<td><span class=\"cms_button_passiv\" onclick=\"cms_meldung_keinkonto()\">Nachricht schreiben</span></td>";
					}
					else if (in_array($daten['id'], $schreibenpool)) {
						$tabelle .= "<td><span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', ".$daten['id'].")\">Nachricht schreiben</span></td>";
					}
					else {
						$tabelle .= "<td><span class=\"cms_button_passivda\" onclick=\"cms_meldung_nichtschreiben()\">Nachricht schreiben</span></td>";
					}
				}

				if ($art == 's' || $art == 'sv') {
					if ($eltern == '1') {
						$elterntext = "";
						$sqlinnen = "SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN schuelereltern ON personen.id = schuelereltern.eltern WHERE schuelereltern.schueler = ".$daten['id'].") AS x ORDER BY nachname ASC, vorname ASC, titel ASC";
						if ($anfrageinnen = $dbs->query($sqlinnen)) {	// Safe weil interne ID
							while ($dateninnen = $anfrageinnen->fetch_assoc()) {
								$elterntext .= ", ".cms_generiere_anzeigename($dateninnen['vorname'], $dateninnen['nachname'], $dateninnen['titel']);
							}
							$anfrageinnen->free();
						}
						$tabelle .= "<td>".substr($elterntext, 2)."</td>";
					}
				}
				if ($art == 's' || $art == 'sv' || $art == 'l') {
						if ($klassen == '1') {
              $klassentext = "";
							$sqlinnen = "SELECT * FROM (SELECT reihenfolge, AES_DECRYPT(klassen.bezeichnung, '$CMS_SCHLUESSEL') AS klassenbez FROM klassenmitglieder JOIN klassen ON klassenmitglieder.gruppe = klassen.id JOIN stufen ON klassen.stufe = stufen.id WHERE klassenmitglieder.person = ".$daten['id'].") AS x ORDER BY reihenfolge ASC, klassenbez ASC";
							if ($anfrageinnen = $dbs->query($sqlinnen)) {	// Safe weil interne ID
								while ($dateninnen = $anfrageinnen->fetch_assoc()) {
									$klassentext .= ", ".$dateninnen['klassenbez'];
								}
								$anfrageinnen->free();
							}
							$tabelle .= "<td>".substr($klassentext, 2)."</td>";
            }
				}
				if ($art == 'e' || $art == 'ev') {
          if ($kinder == '1') {
						$kindertext = "";
						$sqlinnen = "SELECT * FROM (SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS stufenbez FROM personen JOIN schuelereltern ON personen.id = schuelereltern.schueler LEFT JOIN stufenmitglieder ON schuelereltern.schueler = stufenmitglieder.person LEFT JOIN stufen ON stufenmitglieder.gruppe = stufen.id WHERE schuelereltern.eltern = ".$daten['id']." AND (stufen.schuljahr IS NULL OR stufen.schuljahr = $CMS_BENUTZERSCHULJAHR)) AS x ORDER BY nachname ASC, vorname ASC, titel ASC";
						if ($anfrageinnen = $dbs->query($sqlinnen)) {	// Safe weil interne ID
							while ($dateninnen = $anfrageinnen->fetch_assoc()) {
								$kindertext .= ", ".cms_generiere_anzeigename($dateninnen['vorname'], $dateninnen['nachname'], $dateninnen['titel']);
                if (!is_null($dateninnen['stufenbez'])) {$kindertext .= " (".$dateninnen['stufenbez'].")";}
							}
							$anfrageinnen->free();
						}
						$tabelle .= "<td>".substr($kindertext, 2)."</td>";
					}
				}

				if ($leer == '1') {$tabelle .= "<td></td>";}
			$tabelle .= "</tr>";
		}
		$anfrage->free();
	}

	if (strlen($tabelle) > 0) {$code .= $tabelle;}
  else {$code = "";}

	$pers = "Personen";
	if ($nr == 1) {$pers = "Person";}
  if ($nr > 0) {
  	$code .= "<tr><td class=\"cms_notiz\" colspan=\"$spalten\">$nr $pers in der Liste – <span class=\"cms_link\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($allenschreiben,1)."')\">Dieser Liste schreiben</span></td></tr>";
  }

  $rueckgabe['tabelle'] = $code;
  $rueckgabe['schreiben'] = $allenschreiben;

  return $rueckgabe;
}


function cms_listen_gruppenliste_leererueckgabe() {
	$rueckgabe['tabelle'] = "";
  $rueckgabe['schreiben'] = "";
  $rueckgabe['knoepfe'] = "";
	return $rueckgabe;
}

?>
