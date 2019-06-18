<?php
function cms_listen_personenliste_ausgeben($dbs, $schreibenpool, $art, $postfach = '0', $leer = '0', $eltern = '0', $kinder = '0', $klassen = '0', $reli = '0', $adresse  = '0', $kontaktdaten = '0', $geburtsdatum = '0', $konfession = '0') {
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
		if ($anfrage = $dbs->query($sql)) {
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
							if ($anfrageinnen = $dbs->query($sqlinnen)) {
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
  							if ($anfrageinnen = $dbs->query($sqlinnen)) {
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
							if ($anfrageinnen = $dbs->query($sqlinnen)) {
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




function cms_listen_gruppenliste_ausgeben($dbs, $gruppenart, $gruppenid, $schreibenpool, $postfach = '0', $leer = '0', $eltern = '0', $kinder = '0', $klassen = '0', $reli = '0', $adresse  = '0', $kontaktdaten = '0', $geburtsdatum = '0', $konfession = '0') {

  $mitgliederliste = cms_listen_gruppenliste_personengruppen_ausgeben($dbs, 'mitglieder', $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);
  $vorsitzliste = cms_listen_gruppenliste_personengruppen_ausgeben($dbs, 'vorsitz', $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);
  $aufsichtsliste = cms_listen_gruppenliste_personengruppen_ausgeben($dbs, 'aufsicht', $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);

  $code = "";
  $allenschreiben = "";
  $rueckgabe['schreiben']['s'] = "";
  $rueckgabe['schreiben']['l'] = "";
  $rueckgabe['schreiben']['e'] = "";
  $rueckgabe['schreiben']['v'] = "";
  $rueckgabe['schreiben']['x'] = "";
  $knoepfe = $mitgliederliste['knoepfe'].$vorsitzliste['knoepfe'].$aufsichtsliste['knoepfe'];

  if (strlen($mitgliederliste['tabelle']) > 0) {
    $code .= "<h2>Mitglieder</h2>".$mitgliederliste['tabelle'];
    $allenschreiben .= "|".$mitgliederliste['schreiben']['alle'];
    if (strlen($mitgliederliste['schreiben']['s']) > 0) {$rueckgabe['schreiben']['s'] .= $mitgliederliste['schreiben']['s'];}
    if (strlen($mitgliederliste['schreiben']['l']) > 0) {$rueckgabe['schreiben']['l'] .= $mitgliederliste['schreiben']['l'];}
    if (strlen($mitgliederliste['schreiben']['e']) > 0) {$rueckgabe['schreiben']['e'] .= $mitgliederliste['schreiben']['e'];}
    if (strlen($mitgliederliste['schreiben']['v']) > 0) {$rueckgabe['schreiben']['v'] .= $mitgliederliste['schreiben']['v'];}
    if (strlen($mitgliederliste['schreiben']['x']) > 0) {$rueckgabe['schreiben']['x'] .= $mitgliederliste['schreiben']['x'];}
  }
  if (strlen($vorsitzliste['tabelle']) > 0) {
    $code .= "<h2>Vorsitz</h2>".$vorsitzliste['tabelle'];
    $allenschreiben .= "|".$vorsitzliste['schreiben']['alle'];
    if (strlen($vorsitzliste['schreiben']['s']) > 0) {$rueckgabe['schreiben']['s'] .= $vorsitzliste['schreiben']['s'];}
    if (strlen($vorsitzliste['schreiben']['l']) > 0) {$rueckgabe['schreiben']['l'] .= $vorsitzliste['schreiben']['l'];}
    if (strlen($vorsitzliste['schreiben']['e']) > 0) {$rueckgabe['schreiben']['e'] .= $vorsitzliste['schreiben']['e'];}
    if (strlen($vorsitzliste['schreiben']['v']) > 0) {$rueckgabe['schreiben']['v'] .= $vorsitzliste['schreiben']['v'];}
    if (strlen($vorsitzliste['schreiben']['x']) > 0) {$rueckgabe['schreiben']['x'] .= $vorsitzliste['schreiben']['x'];}
  }
  if (strlen($aufsichtsliste['tabelle']) > 0) {
    $code .= "<h2>Aufsicht</h2>".$aufsichtsliste['tabelle'];
    $allenschreiben .= "|".$aufsichtsliste['schreiben']['alle'];
    if (strlen($aufsichtsliste['schreiben']['s']) > 0) {$rueckgabe['schreiben']['s'] .= $aufsichtsliste['schreiben']['s'];}
    if (strlen($aufsichtsliste['schreiben']['l']) > 0) {$rueckgabe['schreiben']['l'] .= $aufsichtsliste['schreiben']['l'];}
    if (strlen($aufsichtsliste['schreiben']['e']) > 0) {$rueckgabe['schreiben']['e'] .= $aufsichtsliste['schreiben']['e'];}
    if (strlen($aufsichtsliste['schreiben']['v']) > 0) {$rueckgabe['schreiben']['v'] .= $aufsichtsliste['schreiben']['v'];}
    if (strlen($aufsichtsliste['schreiben']['x']) > 0) {$rueckgabe['schreiben']['x'] .= $aufsichtsliste['schreiben']['x'];}
  }

  if (strlen($allenschreiben) > 0) {$knoepfe = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($allenschreiben,1)."')\">Allen schreiben</span> ".$knoepfe;}

  if (strlen($rueckgabe['schreiben']['s']) > 0) {$knoepfe .= "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($rueckgabe['schreiben']['s'],1)."')\">Allen Schülern schreiben</span> ";}

  if (strlen($rueckgabe['schreiben']['l']) > 0) {$knoepfe .= "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($rueckgabe['schreiben']['l'],1)."')\">Allen Lehrern schreiben</span> ";}

  if (strlen($rueckgabe['schreiben']['e']) > 0) {$knoepfe .= "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($rueckgabe['schreiben']['e'],1)."')\">Allen Eltern schreiben</span> ";}

  if (strlen($rueckgabe['schreiben']['v']) > 0) {$knoepfe .= "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($rueckgabe['schreiben']['v'],1)."')\">Allen Verwaltungsangestellten schreiben</span> ";}

  if (strlen($rueckgabe['schreiben']['x']) > 0) {$knoepfe .= "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($rueckgabe['schreiben']['x'],1)."')\">Allen Externen schreiben</span> ";}

  $rueckgabe['tabelle'] = $code;
  $rueckgabe['knoepfe'] = $knoepfe;

	return $rueckgabe;
}


function cms_listen_gruppenliste_personengruppen_ausgeben($dbs, $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession) {
  $schuelertabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 's', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);
  $lehrertabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'l', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);
  $elterntabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'e', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);
  $verwaltungstabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'v', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);
  $externentabelle = cms_listen_gruppenliste_personen_ausgeben($dbs, 'x', $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession);

  $code = "";
  $allenschreiben = "";
  $knoepfe = "";
  $rueckgabe['schreiben']['s'] = "";
  $rueckgabe['schreiben']['l'] = "";
  $rueckgabe['schreiben']['e'] = "";
  $rueckgabe['schreiben']['v'] = "";
  $rueckgabe['schreiben']['x'] = "";
  if (strlen($schuelertabelle['tabelle']) > 0) {
    $code .= "<h3>Schüler</h3><table class=\"cms_liste\">".$schuelertabelle['tabelle']."</table>";
    $allenschreiben .= "|".$schuelertabelle['schreiben'];
    $rueckgabe['schreiben']['s'] .= $schuelertabelle['schreiben'];
  }
  if (strlen($lehrertabelle['tabelle']) > 0) {
    $code .= "<h3>Lehrer</h3><table class=\"cms_liste\">".$lehrertabelle['tabelle']."</table>";
    $allenschreiben .= "|".$lehrertabelle['schreiben'];
    $rueckgabe['schreiben']['l'] .= $lehrertabelle['schreiben'];
  }
  if (strlen($elterntabelle['tabelle']) > 0) {
    $code .= "<h3>Eltern</h3><table class=\"cms_liste\">".$elterntabelle['tabelle']."</table>";
    $allenschreiben .= "|".$elterntabelle['schreiben'];
    $rueckgabe['schreiben']['e'] .= $elterntabelle['schreiben'];
  }
  if (strlen($verwaltungstabelle['tabelle']) > 0) {
    $code .= "<h3>Verwaltungsangestellte</h3><table class=\"cms_liste\">".$verwaltungstabelle['tabelle']."</table>";
    $allenschreiben .= "|".$verwaltungstabelle['schreiben'];
    $rueckgabe['schreiben']['v'] .= $verwaltungstabelle['schreiben'];
  }
  if (strlen($externentabelle['tabelle']) > 0) {
    $code .= "<h3>Externe</h3><table class=\"cms_liste\">".$externentabelle['tabelle']."</table>";
    $allenschreiben .= "|".$externentabelle['schreiben'];
    $rueckgabe['schreiben']['x'] .= $externentabelle['schreiben'];
  }

  if (strlen($code) > 0) {
    if ($personengruppe == 'vorsitz') {$text = 'Vorsitzenden';}
    else if ($personengruppe == 'aufsicht') {$text = 'Aufsichten';}
    else {$text = 'Mitgliedern';}
    $knoepfe .= "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', '".substr($allenschreiben,1)."')\">Allen $text schreiben</span> ";
  }

  $rueckgabe['tabelle'] = $code;
  $rueckgabe['schreiben']['alle'] = $allenschreiben;
  $rueckgabe['knoepfe'] = $knoepfe;

	return $rueckgabe;
}




function cms_listen_gruppenliste_personen_ausgeben($dbs, $art, $personengruppe, $gruppenart, $gruppenid, $schreibenpool, $postfach, $leer, $eltern, $kinder, $klassen, $reli, $adresse, $kontaktdaten, $geburtsdatum, $konfession) {
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
	if ($anfrage = $dbs->query($sql)) {
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
						if ($anfrageinnen = $dbs->query($sqlinnen)) {
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
							if ($anfrageinnen = $dbs->query($sqlinnen)) {
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
						if ($anfrageinnen = $dbs->query($sqlinnen)) {
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

?>
