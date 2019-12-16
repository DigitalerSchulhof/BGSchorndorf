<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Mein Stundenplan</h1>

<?php
// PROFILDATEN LADEN
if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 's')) {
	$dbs = cms_verbinden();
	if ((($CMS_EINSTELLUNGEN['Stundenplan Lehrer extern'] == '1') && ($CMS_BENUTZERART == 'l')) ||
	 		(($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '1') && ($CMS_BENUTZERART == 's'))) {
		$stundenplan = "";
		if ($CMS_BENUTZERART == 'l') {
			$sql = $dbs->prepare("SELECT AES_DECRYPT(stundenplan, '$CMS_SCHLUESSEL') AS stundenplan FROM lehrer WHERE id = ?");
			$sql->bind_param("i", $CMS_BENUTZERID);
			if ($sql->execute()) {
				$sql->bind_result($studenplan);
				$sql->fetch();
			}
			$sql->close();
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdatei.php');
			$code .= cms_lehrerplan_aus_datei($stundenplan);
		}
		else if ($CMS_BENUTZERART == 's') {
			$sql = $dbs->prepare("SELECT AES_DECRYPT(stundenplanextern, '$CMS_SCHLUESSEL') AS stundenplan FROM klassen JOIN klassenmitglieder ON klassen.id = klassenmitglieder.gruppe WHERE person = ? AND schuljahr = ?");
			$sql->bind_param("ii", $CMS_BENUTZERID, $schuljahr);
			if ($sql->execute()) {
				$sql->bind_result($studenplan);
				$sql->fetch();
			}
			$sql->close();
			include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdatei.php');
			$code .= cms_klassenplan_aus_datei($stundenplan);
		}
		$code .= "</div>";
	}
	else if ((($CMS_EINSTELLUNGEN['Stundenplan Lehrer extern'] != '1') && ($CMS_BENUTZERART == 'l')) ||
	 				 (($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] != '1') && ($CMS_BENUTZERART == 's'))) {
		if (cms_check_ganzzahl($CMS_BENUTZERSCHULJAHR)) {
			$zeitraum = "-";
			if (isset($_SESSION['MEINSTUNDENPLANZEITRAUM']) && (cms_check_ganzzahl($_SESSION['MEINSTUNDENPLANZEITRAUM'],0) || ($_SESSION['MEINSTUNDENPLANZEITRAUM'] == '-'))) {
				$zeitraum = $_SESSION['MEINSTUNDENPLANZEITRAUM'];
			}
			else {
				$jetzt = time();
				$sql = "SELECT id FROM zeitraeume WHERE schuljahr = ? AND aktiv = 1 AND beginn <= ? AND ende >= ?";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("iii", $CMS_BENUTZERSCHULJAHR, $jetzt, $jetzt);
				if ($sql->execute()) {
					$sql->bind_result($z);
					if ($sql->fetch()) {
						$zeitraum = $z;
					}
				}
				$sql->close();
			}

			if (cms_check_ganzzahl($zeitraum,0) || ($zeitraum == '-')) {
				$zeitraumwahl = "";
				// Alle aktiven Zeiträume dieses Schuljahres laden
				$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM zeitraeume WHERE schuljahr = ? AND aktiv = 1 ORDER BY beginn DESC";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("i", $CMS_BENUTZERSCHULJAHR);
				if ($sql->execute()) {
					$sql->bind_result($zid, $zbez);
					while ($sql->fetch()) {
						if ($zeitraum == '-') {$zeitraum = $zid;}
						if ($zeitraum == $zid) {$wert = 1;} else {$wert = 0;}
						$zeitraumwahl .= cms_togglebutton_generieren ('cms_zeitraumwahl_'.$zid, $zbez, $wert, "cms_stundenplan_vorbereiten('m', '$CMS_BENUTZERID', '$zid')")." ";
					}
				}
				$sql->close();
				if (strlen($zeitraumwahl) == 0) {"<p class=\"cms_notiz\">Keine Zeiträume gefunden</p>";}
					else {$zeitraumwahl = "<p>".$zeitraumwahl."</p>";}

				if (strlen($zeitraumwahl) > 0) {
					include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
					if ($CMS_BENUTZERART == 'l') {
						$code .= $zeitraumwahl;
						$code .= cms_lehrerregelplan_aus_db($dbs, $CMS_BENUTZERID, $zeitraum);
					}
					else {
						$code .= $zeitraumwahl;
						$code .= cms_personenregelplan_aus_db($dbs, $CMS_BENUTZERID, $zeitraum);
					}
					$code .= "<div class=\"cms_clear\"></div>";
				}
				else {
					$code .= "<p class=\"cms_notiz\">Im gewählten Schuljahr stehen im Moment keine Stundenpläne zur Verfügung.</p>";
					$code .= "</div>";
				}
			}
			else {
				$code .= cms_meldung_bastler();
			}
		}
		else {
			$code .= cms_meldung('info', '<h4>Kein Schuljahr ausgewählt</h4><p>In diesem Nutzerkonto wurde kein Schuljahr ausgewählt.</p><p><a class="cms_button" href="Schulhof/Nutzerkonto/Mein_Profil">Profildaten</a></p>');
		}
	}
	cms_trennen($dbs);
	echo $code;
}
else {
	echo cms_meldung_bastler();
}
?>
<div class="cms_clear"></div>
