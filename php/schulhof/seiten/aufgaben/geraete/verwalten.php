<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Geräte verwalten</h1>";

if (cms_angemeldet() && cms_r("schulhof.technik.geräte.verwalten")) {
	$dbs = cms_verbinden('s');
	$code .= "<h2>Räume</h2>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Standort</th><th>Betroffene Ausstattung</th><th>Eingegangen</th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";

		$RGERAETE = array();
		$sql = $dbs->prepare("SELECT raeume.id, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL'), MAX(zeit), MIN(statusnr) FROM raeumegeraete JOIN raeume ON raeumegeraete.standort = raeume.id WHERE statusnr > 0 GROUP BY id ORDER BY zeit DESC");
		if ($sql->execute()) {
			$sql->bind_result($rid, $rbez, $rzeit, $rstatusnr);
			while ($sql->fetch()) {
				$G = array();
				$G['id'] = $rid;
				$G['bezeichnung'] = $rbez;
				$G['zeit'] = $rzeit;
				$G['statusnr'] = $rstatusnr;
				$G['ausstattung'] = "";
				array_push($RGERAETE, $G);
			}
		}
		$sql->close();

		$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeumegeraete WHERE standort = ? AND statusnr > 0) AS x ORDER BY bezeichnung ASC");
		for ($i=0; $i<count($RGERAETE); $i++) {
			$sql->bind_param("i", $RGERAETE[$i]['id']);
			if ($sql->execute()) {
				$sql->bind_result($gbez);
				while($sql->fetch()) {
					$RGERAETE[$i]['ausstattung'] .= ', '.$gbez;
				}
			}
		}
		$sql->close();

		// Alle Geräte ausgeben
		$ausgabe = "";
		foreach ($RGERAETE as $daten) {
			$ausgabe .= "<tr>";
				$status = cms_status_generieren($daten['statusnr']);
				$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$status['text']."</span><img src=\"res/icons/klein/".$status['icon']."\"></span></td>";
				$ort = "Raum";
				$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
				if (strlen($daten['ausstattung']) > 0) {$daten['ausstattung'] = substr($daten['ausstattung'], 2);}
				$ausgabe .= "<td>".$daten['ausstattung']."</td>";
				$ausgabe .= "<td>".date('d.m.Y', $daten['zeit'])." um ".date('H:i', $daten['zeit'])."</td>";
				// Aktionen
				$ausgabe .= "<td>";
				$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
				$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_geraete_problembericht_bearbeiten_vorbereiten(".$daten['id'].", 'r', '$bezeichnung');\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_geraete_problembericht_loeschen_anzeigen(".$daten['id'].", 'r', '$bezeichnung');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";

				$ausgabe .= "</td>";

			$ausgabe .= "</tr>";
		}
		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
		}
		$code .= $ausgabe;
		$code .= "</tbody>";
	$code .= "</table>";




	$LGERAETE = array();
	$sql = $dbs->prepare("SELECT leihen.id AS id, AES_DECRYPT(leihen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, MAX(zeit) AS zeit, MIN(statusnr) AS statusnr FROM leihengeraete JOIN leihen ON leihengeraete.standort = leihen.id WHERE statusnr > 0 GROUP BY id ORDER BY zeit DESC");
	if ($sql->execute()) {
		$sql->bind_result($lid, $lbez, $lzeit, $lstatusnr);
		while ($sql->fetch()) {
			$G = array();
			$G['id'] = $lid;
			$G['bezeichnung'] = $lbez;
			$G['zeit'] = $lzeit;
			$G['statusnr'] = $lstatusnr;
			$G['ausstattung'] = "";
			array_push($LGERAETE, $G);
		}
	}
	$sql->close();

	$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM leihengeraete WHERE standort = ? AND statusnr > 0) AS x ORDER BY bezeichnung ASC");
	for ($i=0; $i<count($LGERAETE); $i++) {
		$sql->bind_param("i", $LGERAETE[$i]['id']);
		if ($sql->execute()) {
			$sql->bind_result($gbez);
			while($sql->fetch()) {
				$LGERAETE[$i]['ausstattung'] .= ', '.$gbez;
			}
		}
	}
	$sql->close();


	$code .= "<h2>Leihgeräte</h2>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Standort</th><th>Betroffene Ausstattung</th><th>Eingegangen</th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		// Alle Leihgeräte ausgeben
		$ausgabe = "";
		foreach ($LGERAETE AS $daten) {
			$ausgabe .= "<tr>";
				$status = cms_status_generieren($daten['statusnr']);
				$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$status['text']."</span><img src=\"res/icons/klein/".$status['icon']."\"></span></td>";
				$ort = "Leihgeräte";
				$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
				if (strlen($daten['ausstattung']) > 0) {$daten['ausstattung'] = substr($daten['ausstattung'], 2);}
				$ausgabe .= "<td>".$daten['ausstattung']."</td>";
				$ausgabe .= "<td>".date('d.m.Y', $daten['zeit'])." um ".date('H:i', $daten['zeit'])."</td>";
				// Aktionen
				$ausgabe .= "<td>";
				$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
				$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_geraete_problembericht_bearbeiten_vorbereiten(".$daten['id'].", 'l', '$bezeichnung');\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_geraete_problembericht_loeschen_anzeigen(".$daten['id'].", 'l', '$bezeichnung');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";

				$ausgabe .= "</td>";

			$ausgabe .= "</tr>";
		}

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
		}

		$code .= $ausgabe;
		$code .= "</tbody>";
	$code .= "</table>";

	cms_trennen($dbs);
	$code .= "</div>";
}
else {
	$code .= cms_meldung_berechtigung();
	$code .= "</div>";
}



$code .= "<div class=\"cms_clear\"></div>";

echo $code;
?>
