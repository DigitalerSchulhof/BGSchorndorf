<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$raumbezeichnung = str_replace('_', ' ', $CMS_URL[3]);
$code .= "<h1>Raum »".$raumbezeichnung."«</h1>";

$fehler = false;

// RAUM LADEN
$sql = $dbs->prepare("SELECT id, buchbar, externverwaltbar, AES_DECRYPT(stundenplan, '$CMS_SCHLUESSEL') FROM raeume WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND verfuegbar = 1");
$sql->bind_param("s", $raumbezeichnung);
if ($sql->execute()) {
	$sql->bind_result($raumid, $buchbar, $extern, $stundenplan);
	if (!$sql->fetch()) {$fehler = true;}
}
else {$fehler = true;}
$sql->close();

if (cms_angemeldet() && r("schulhof.organisation.räume.sehen")) {

	$geraete = array();
	$anzahl = 0;
	$id = "";

	if (!$fehler) {
		$code .= "</div>";

		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, statusnr, AES_DECRYPT(meldung, '$CMS_SCHLUESSEL') AS meldung, AES_DECRYPT(kommentar, '$CMS_SCHLUESSEL') AS kommentar, absender, zeit FROM raeumegeraete WHERE standort = ?) AS x ORDER BY bezeichnung ASC");
	  $sql->bind_param("i", $raumid);
	  if ($sql->execute()) {
	    $sql->bind_result($gaeraeteid, $geraetebez, $geraetestatus, $geraetemeldung, $geraetekommentar, $geraeteabsender, $geraetezeit);
	    while($sql->fetch()) {
				$geraete[$anzahl]['id'] = $gaeraeteid;
				$geraete[$anzahl]['bezeichnung'] = $geraetebez;
				$geraete[$anzahl]['statusnr'] = $geraetestatus;
				$geraete[$anzahl]['meldung'] = $geraetemeldung;
				$geraete[$anzahl]['kommentar'] = $geraetekommentar;
				$geraete[$anzahl]['absender'] = $geraeteabsender;
				$geraete[$anzahl]['zeit'] = $geraetezeit;
				$anzahl++;
	    }
	  }
	  $sql->close();

		$code .= "<div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
		include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');

		if (r("schulhof.information.pläne.stundenpläne.räume")) {
			if ($CMS_EINSTELLUNGEN['Stundenplan Raum extern'] == '1') {
				$code .= "<h3>Regulärer Raumplan</h3>";
				if (strlen($stundenplan) == 0) {$code .= cms_meldung("info", "<h4>Kein Raumplan verfügbar</h4><p>Für diesen Raum wurde kein Raumplan hinterlegt.</p>");}
				else {
					include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdatei.php');
					$code .= cms_raumplan_aus_datei($stundenplan);
				}
			}
			else {
				include_once('php/schulhof/seiten/verwaltung/stundenplanung/planausdb.php');
				if (isset($_SESSION['RAUMSTUNDENPLANZEITRAUM'])) {$zeitraum = $_SESSION['RAUMSTUNDENPLANZEITRAUM'];}
				else {$zeitraum = '-';}

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
						$zeitraumwahl .= cms_togglebutton_generieren ('cms_zeitraumwahl_'.$zid, $zbez, $wert, "cms_stundenplan_vorbereiten('r', '$raumid', '$zid')")." ";
					}
				}
				$sql->close();
				if (strlen($zeitraumwahl) == 0) {"<p class=\"cms_notiz\">Keine Zeiträume gefunden</p>";}
					else {$zeitraumwahl = "<p>".$zeitraumwahl."</p>";}
				$code .= $zeitraumwahl;
				$code .= cms_raumregelplan_aus_db($dbs, $raumid, $zeitraum);
			}
		}
		else {
		}

		$jetzt = time();

		if ($buchbar == 1) {
			if (r("schulhof.organisation.buchungen.räume.[|anonymisiert,sehen]")) {
				include_once('php/schulhof/seiten/plaene/buchungen/ausgabe.php');
				if (r("schulhof.organisation.buchungen.räume.sehen")) {$anonymisiert = false;}
				else {$anonymisiert = true;}
				$code .= cms_buchungen_ausgeben('r', $raumid, date("d"), date("m"), date("Y"), $anonymisiert);
			}
		}

		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
		if (count($geraete) > 0) {
			$code .= "<h3>Gerätestatus</h3>";
			$code .= "<table class=\"cms_liste cms_raumstatus\">";
			$gids = '';
			foreach ($geraete as $g) {
				$status = cms_status_generieren($g['statusnr']);
				if (strlen($g['kommentar']) > 0) {$status['text'] .= ' - '.$g['kommentar'];}
				$code .= "<tr><td>".$g['bezeichnung']."</td><td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$status['text']."</span><img src=\"res/icons/klein/".$status['icon']."\"></span></td></tr>";
				$gids .= '|'.$g['id'];
			}
			$code .= "</table>";

			if (r("schulhof.technik.geräte.probleme")) {
				$code .= "<p><span id=\"cms_gerateproblemknopf\" class=\"cms_button\" onclick=\"cms_togglebutton_anzeigen('cms_geraeteproblem', 'cms_gerateproblemknopf', 'Problem melden', 'Problemmeldung abbrechen')\">Problem melden</span></p>";

				$code .= "<div id=\"cms_geraeteproblem\" class=\"cms_versteckt\" style=\"display: none;\">";

				$geraetecode = "";
				$kommentarcode = "";
				foreach ($geraete as $g) {
					$geraetecode .= "<span class=\"cms_toggle\" id=\"cms_geraete_".$g['id']."\" onclick=\"cms_toggle_klasse('cms_geraete_".$g['id']."', 'cms_toggle_aktiv', 'cms_geraete_".$g['id']."_id', 'true');cms_toggle_anzeigen('cms_geraete_meldung_".$g['id']."');cms_geraete_problembericht_aktiv()\">".$g['bezeichnung']."</span>";
					$geraetecode .= "<input name=\"cms_geraete_".$g['id']."_id\" id=\"cms_geraete_".$g['id']."_id\" type=\"hidden\" value=\"0\"> ";

					$kommentarcode .= "<div class=\"cms_geraeteproblem_meldung\" id=\"cms_geraete_meldung_".$g['id']."\" style=\"display: none;\">";
					$kommentarcode .= "<h4>Problembeschreibung für ".$g['bezeichnung'].":</h4>";
					$kommentarcode .= "<p class=\"cms_notiz\" id=\"cms_geraete_meldunga_".$g['id']."\">".$g['meldung']."</p>";
					$kommentarcode .= "<p><textarea class=\"cms_textarea\" cols=\"20\" rows=\"5\" name=\"cms_geraete_meldungn_".$g['id']."\" id=\"cms_geraete_meldungn_".$g['id']."\"></textarea></p>";
					$kommentarcode .= "</div>";
				}

				if (strlen($geraetecode) != 0) {$geraetecode = "<h4>Betroffene Geräte</h4><p>".$geraetecode."</p><p><input type=\"hidden\" name=\"cms_geraete_meldung_ids\" id=\"cms_geraete_meldung_ids\" value=\"$gids\"></p>";}

				$code .= $geraetecode;
				$code .= $kommentarcode;

				$code .= "<p><span class=\"cms_button\" onclick=\"cms_geraete_problembericht('$raumid', 'r', '$CMS_URLGANZ');\" id=\"cms_geraete_bericht\" style=\"display: none;\">Bericht abschicken</span> <span class=\"cms_button_nein\" onclick=\"cms_togglebutton_anzeigen('cms_geraeteproblem', 'cms_gerateproblemknopf', 'Problem melden', 'Problemmeldung abbrechen')\">Abbrechen</span></p>";
				$code .= "</div>";
			}
		}

		$code .= "</div></div>";
	}
	else {
		$code .= cms_meldung('info', '<h4>Raum nicht gefunden</h4><p>Der gesuchte Raum existiert nicht oder nicht mehr.</p>');
		$code .= "</div>";
	}
}
else {
	$code .= cms_meldung_berechtigung();
	$code .= "</div>";
}



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
