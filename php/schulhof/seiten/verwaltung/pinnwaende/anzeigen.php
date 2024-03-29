<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";

$angemeldet = cms_angemeldet();
if ($angemeldet) {
	$fehler = false;
	// PINNWAND SUCHEN
	$bezeichnung = cms_linkzutext($CMS_URL[count($CMS_URL)-1]);

	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, id, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), sichtbars, sichtbarl, sichtbare, sichtbarv, sichtbarx, schreibens, schreibenl, schreibene, schreibenv, schreibenx FROM pinnwaende WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')");

	$sql->bind_param("s", $bezeichnung);
	if ($sql->execute()) {
	  $sql->bind_result($anzahl, $id, $beschreibung, $sichtbars, $sichtbarl, $sichtbare, $sichtbarv, $sichtbarx, $schreibens, $schreibenl, $schreibene, $schreibenv, $schreibenx);
	  if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
	}
	$sql->close();

	if (!$fehler) {
		$zugriff = false;
		$schreiben = false;

		if ($CMS_BENUTZERART == 's') {
			if ($sichtbars == '1') {$zugriff = true;}
			if ($schreibens == '1') {$schreiben = true;}
		}
		if ($CMS_BENUTZERART == 'e') {
			if ($sichtbare == '1') {$zugriff = true;}
			if ($schreibene == '1') {$schreiben = true;}
		}
		if ($CMS_BENUTZERART == 'l') {
			if ($sichtbarl == '1') {$zugriff = true;}
			if ($schreibenl == '1') {$schreiben = true;}
		}
		if ($CMS_BENUTZERART == 'v') {
			if ($sichtbarv == '1') {$zugriff = true;}
			if ($schreibenv == '1') {$schreiben = true;}
		}
		if ($CMS_BENUTZERART == 'x') {
			if ($sichtbarx == '1') {$zugriff = true;}
			if ($schreibenx == '1') {$schreiben = true;}
		}

		if ($zugriff) {
			$code .= "<h1>$bezeichnung</h1>";
			$code .= "</div><div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
			// Alte Anschläge löschen
			$jetzt = time();
			$sql = $dbs->prepare("DELETE FROM pinnwandanschlag WHERE ende < ?");
			$sql->bind_param("i", $jetzt);
			$sql->execute();

			include_once('php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php');
      $CMS_EMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);

			$sqlfelder = "pinnwandanschlag.id AS id, AES_DECRYPT(pinnwandanschlag.titel, '$CMS_SCHLUESSEL') AS atitel, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') AS inhalt, beginn, ende, pinnwandanschlag.idvon AS ersteller, pinnwandanschlag.idzeit AS perstellt, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL') AS ptitel, erstellt";
			$code .= "<div class=\"cms_pinnwand_anschlaege\">";
			$sql = $dbs->prepare("SELECT $sqlfelder FROM pinnwandanschlag LEFT JOIN personen ON pinnwandanschlag.idvon = personen.id LEFT JOIN nutzerkonten ON pinnwandanschlag.idvon = nutzerkonten.id WHERE pinnwand = ? AND beginn < ? ORDER BY ende ASC, beginn ASC");
			$sql->bind_param("ii", $id, $jetzt);
			if ($sql->execute()) {
				$sql->bind_result($aid, $atit, $ainhalt, $abeginn, $aende, $aersteller, $aerstellt, $avor, $anach, $atitel, $aerstellererstellt);
				while ($sql->fetch()) {
					$code .= "<div class=\"cms_pinnwand_anschlag_aussen\"><div class=\"cms_pinnwand_anschlag_innen\">";
						$code .= "<h3 class=\"cms_pinnwand_titel\">$atit</h3>";
						$code .= "<p class=\"cms_pinnwand_datum\">Angeschlagen von ".date("d.m.Y", $abeginn)." bis ".date("d.m.Y", $aende)."</p>";

						$code .= "<div class=\"cms_pinnwand_inhalt\">";
						$code .= cms_ausgabe_editor($ainhalt);
						$aktionen = "";
						if (($aersteller == $CMS_BENUTZERID) || cms_r("schulhof.information.pinnwände.anschläge.bearbeiten")) {
							$aktionen .= "<span class=\"cms_button\" onclick=\"cms_pinnwandanschlag_bearbeiten_vorbereiten($aid, '".cms_textzulink($bezeichnung)."')\">Bearbeiten</span> ";
						}
						if (($aersteller == $CMS_BENUTZERID) || cms_r("schulhof.information.pinnwände.anschläge.löschen")) {
							$aktionen .= "<span class=\"cms_button cms_button_nein\" onclick=\"cms_pinnwandanschlag_loeschen_anzeigen($aid, '".cms_textzulink($bezeichnung)."')\">Löschen</span> ";
						}
						if (strlen($aktionen) > 0) {
							$code .= "<p>$aktionen</p>";
						}
						$code .= "</div>";

						if (!is_null($avor) && ($aerstellt > $aerstellererstellt)) {
							$anzeigename = cms_generiere_anzeigename($avor, $anach, $atitel);
							if (in_array($aersteller, $CMS_EMPFAENGERPOOL)) {
								$code .= "<p class=\"cms_pinnwand_ersteller\">Erstellt von <span class=\"cms_link\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $aersteller)\">$anzeigename</span></p>";
							}
							else {
								$code .= "<p class=\"cms_pinnwand_ersteller\">Erstellt von $anzeigename</p>";
							}
						}


					$code .= "</div></div>";
				}
			}
			$sql->close();
			$code .= "</div>";

			$anschlaege = "";
			$sql = $dbs->prepare("SELECT $sqlfelder FROM pinnwandanschlag LEFT JOIN personen ON pinnwandanschlag.idvon = personen.id LEFT JOIN nutzerkonten ON pinnwandanschlag.idvon = nutzerkonten.id WHERE pinnwand = ? AND beginn > ? ORDER BY ende ASC, beginn ASC");
			$sql->bind_param("ii", $id, $jetzt);
			if ($sql->execute()) {
				$sql->bind_result($aid, $atit, $ainhalt, $abeginn, $aende, $aersteller, $aerstellt, $avor, $anach, $atitel, $aerstellererstellt);
				while ($sql->fetch()) {
					$anschlaege .= "<div class=\"cms_pinnwand_anschlag_aussen\"><div class=\"cms_pinnwand_anschlag_innen\">";
						$anschlaege .= "<h3 class=\"cms_pinnwand_titel\">$atit</h3>";
						$anschlaege .= "<p class=\"cms_pinnwand_datum\">Angeschlagen von ".date("d.m.Y", $abeginn)." bis ".date("d.m.Y", $aende)."</p>";

						$anschlaege .= "<div class=\"cms_pinnwand_inhalt\">";
						$anschlaege .= $ainhalt;
						$aktionen = "";
						if (($aersteller == $CMS_BENUTZERID) || cms_r("schulhof.information.pinnwände.anschläge.bearbeiten")) {
							$aktionen .= "<span class=\"cms_button\" onclick=\"cms_pinnwandanschlag_bearbeiten_vorbereiten($aid, '".cms_textzulink($bezeichnung)."')\">Bearbeiten</span> ";
						}
						if (($aersteller == $CMS_BENUTZERID) || cms_r("schulhof.information.pinnwände.anschläge.löschen")) {
							$aktionen .= "<span class=\"cms_button cms_button_nein\" onclick=\"cms_pinnwandanschlag_loeschen_anzeigen($aid, '".cms_textzulink($bezeichnung)."')\">Löschen</span> ";
						}
						if (strlen($aktionen) > 0) {
							$code .= "<p>$aktionen</p>";
						}
						$anschlaege .= "</div>";

						if (!is_null($avor) && ($aerstellt > $aerstellererstellt)) {
							$anzeigename = cms_generiere_anzeigename($avor, $anach, $atitel);
							if (in_array($aersteller, $CMS_EMPFAENGERPOOL)) {
								$anschlaege .= "<p class=\"cms_pinnwand_ersteller\">Erstellt von <span class=\"cms_link\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten ('vorgabe', '', '', $aersteller)\">$anzeigename</span></p>";
							}
							else {
								$anschlaege .= "<p class=\"cms_pinnwand_ersteller\">Erstellt von $anzeigename</p>";
							}
						}
					$anschlaege .= "</div></div>";
				}
			}
			$sql->close();

			if (strlen($anschlaege) > 0) {
				$anschlaege = "<div class=\"cms_pinnwand_anschlaege\">".$anschlaege."</div>";
				$code .= cms_toggleeinblenden_generieren ('cms_ausstehende_anschlaege', 'Ausstehende Anschläge einblenden', 'Ausstehende Anschläge ausblenden', $anschlaege, 0);
			}

			$code .= "</div></div><div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
			$code .= "<p>".cms_textaustextfeld_anzeigen($beschreibung)."</p>";

			$code .= "<p>";
			if ($sichtbarl == '1') {$code .= cms_generiere_hinweisicon('lehrer', 'Sichtbar für Lehrer')." ";}
			if ($sichtbars == '1') {$code .= cms_generiere_hinweisicon('schueler', 'Sichtbar für Schüler')." ";}
			if ($sichtbare == '1') {$code .= cms_generiere_hinweisicon('elter', 'Sichtbar für Eltern')." ";}
			if ($sichtbarv == '1') {$code .= cms_generiere_hinweisicon('verwaltung', 'Sichtbar für Verwaltungsnagestellte')." ";}
			if ($sichtbarx == '1') {$code .= cms_generiere_hinweisicon('extern', 'Sichtbar für Externe')." ";}
			$code .= "</p>";

			if ($schreiben) {
				$code .= "<h2>Aktionen</h2>";
				$code .= "<p><a class=\"cms_button cms_button_ja\" href=\"Schulhof/Pinnwände/".cms_textzulink($bezeichnung)."/Neuer_Anschlag\">+ Neuer Anschlag</a></p>";
			}
			$code .= "</div>";
		}
		else {
			$code .= "<h1>Pinnwand</h1>";
			$code .= cms_meldung_berechtigung();
		}
	}
	else {
		cms_fehler('Schulhof', '404');
		$code = "";
	}
}
else {
	$code .= "<h1>Pinnwand</h1>";
	$code .= cms_meldung_berechtigung();
}


$code .= "</div>";
$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
