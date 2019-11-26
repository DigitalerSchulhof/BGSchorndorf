<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Geräte verwalten</h1>";

$zugriff = $CMS_RECHTE['Technik']['Geräte verwalten'];
$fehler = false;

if ($fehler) {$zugriff = false;}
$angemeldet = cms_angemeldet();

if ($angemeldet && $zugriff) {
	$dbs = cms_verbinden('s');
	$code .= "<h2>Räume</h2>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Standort</th><th>Betroffene Ausstattung</th><th>Eingegangen</th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		// Alle Geräte ausgeben
		$sql = "SELECT * FROM (SELECT raeume.id AS id, AES_DECRYPT(raeume.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, MAX(zeit) AS zeit, MIN(statusnr) AS statusnr FROM raeumegeraete JOIN raeume ON raeumegeraete.standort = raeume.id WHERE statusnr > 0 GROUP BY id) AS defekt ORDER BY zeit DESC";
		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			$sqlgeraete = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeumegeraete WHERE standort = ? AND statusnr > 0) AS x ORDER BY bezeichnung ASC");
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					$status = cms_status_generieren($daten['statusnr']);
					$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$status['text']."</span><img src=\"res/icons/klein/".$status['icon']."\"></span></td>";
					$ort = "Raum";
					$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
					$ausstattung = "";
					$sqlgeraete->bind_param("i", $daten['id']);
					if ($sqlgeraete->execute()) {
						$sqlgeraete->bind_result($gbez);
						while($sqlgeraete->fetch()) {
							$ausstattung .= ', '.$gbez;
						}
					}
					if (strlen($ausstattung) > 0) {$ausstattung = substr($ausstattung, 2);}
					$ausgabe .= "<td>".$ausstattung."</td>";
					$ausgabe .= "<td>".date('d.m.Y', $daten['zeit'])." um ".date('H:i', $daten['zeit'])."</td>";
					// Aktionen
					$ausgabe .= "<td>";
					$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_geraete_problembericht_bearbeiten_vorbereiten(".$daten['id'].", 'r', '$bezeichnung');\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_geraete_problembericht_loeschen_anzeigen(".$daten['id'].", 'r', '$bezeichnung');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";

					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
			$sqlgeraete->close();
			$anfrage->free();
		}

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
		}

		$code .= $ausgabe;
		$code .= "</tbody>";
	$code .= "</table>";


	$code .= "<h2>Leihgeräte</h2>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<thead>";
			$code .= "<tr><th></th><th>Standort</th><th>Betroffene Ausstattung</th><th>Eingegangen</th><th>Aktionen</th></tr>";
		$code .= "</thead>";
		$code .= "<tbody>";
		// Alle Leihgeräte ausgeben
		$sql = "SELECT * FROM (SELECT leihen.id AS id, AES_DECRYPT(leihen.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, MAX(zeit) AS zeit, MIN(statusnr) AS statusnr FROM leihengeraete JOIN leihen ON leihengeraete.standort = leihen.id WHERE statusnr > 0 GROUP BY id) AS defekt ORDER BY zeit DESC";
		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			$sqlgeraete = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM leihengeraete WHERE standort = ? AND statusnr > 0) AS x ORDER BY bezeichnung ASC");
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
					$status = cms_status_generieren($daten['statusnr']);
					$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$status['text']."</span><img src=\"res/icons/klein/".$status['icon']."\"></span></td>";
					$ort = "Leihgeräte";
					$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
					$ausstattung = "";

				  $sqlgeraete->bind_param("i", $daten['id']);
				  if ($sqlgeraete->execute()) {
				    $sqlgeraete->bind_result($gbez);
				    while($sqlgeraete->fetch()) {
				      $ausstattung .= ', '.$gbez;
				    }
				  }
					if (strlen($ausstattung) > 0) {$ausstattung = substr($ausstattung, 2);}
					$ausgabe .= "<td>".$ausstattung."</td>";
					$ausgabe .= "<td>".date('d.m.Y', $daten['zeit'])." um ".date('H:i', $daten['zeit'])."</td>";
					// Aktionen
					$ausgabe .= "<td>";
					$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_geraete_problembericht_bearbeiten_vorbereiten(".$daten['id'].", 'l', '$bezeichnung');\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_geraete_problembericht_loeschen_anzeigen(".$daten['id'].", 'l', '$bezeichnung');\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";

					$ausgabe .= "</td>";

				$ausgabe .= "</tr>";
			}
			$sqlgeraete->close();
			$anfrage->free();
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
