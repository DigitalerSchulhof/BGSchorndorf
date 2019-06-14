<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Schüler und Eltern verknüpfen</h1>

<?php
// PROFILDATEN LADEN
$fehler = true;
if (!isset($_SESSION['PERSONENDETAILS'])) {
	echo cms_meldung_bastler();
}
else {
	include_once("php/schulhof/seiten/verwaltung/personen/personensuche.php");
	$id = $_SESSION['PERSONENDETAILS'];
	$zugriff = $CMS_RECHTE['Personen']['Schüler und Eltern verknüpfen'];
	if ($zugriff) {
		// Person laden, für die die Rechte geändert werden sollen
		$dbs = cms_verbinden('s');
		$sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = $id";

		$fehler = false;
		if ($anfrage = $dbs->query($sql)) {
			if ($daten = $anfrage->fetch_assoc()) {
				$id = $daten['id'];
				$art = $daten['art'];

				$anzeigename = $daten['vorname']." ".$daten['nachname'];
				if (strlen($daten['titel']) > 0) {$anzeigename = $daten['titel']." ".$anzeigename;}
			}
			else {$fehler = false;}
			$anfrage->free();
		}
		else {$fehler = false;}

		if (($art != 's') && ($art != 'e')) {
			$fehler = true;
		}

		if ($fehler) {
			echo cms_meldung_unbekannt();
		}

		cms_trennen($dbs);
	}
	else {
		echo cms_meldung_berechtigung();
	}


	if (!$fehler) {
		$dbs = cms_verbinden('s');

		$code = "<table class=\"cms_liste\">";

		if ($art == 's') {
			$auswahl = "<tr><th>Schüler:</th><td>$anzeigename";
			$zugeordnet = "<tr><th>Eltern:</th>";
			$join = "schuelereltern.eltern";
			$where = "schueler = $id";
		}
		else {
			$auswahl = "<tr><th>Eltern:</th><td>$anzeigename";
			$zugeordnet = "<tr><th>Schüler:</th>";
			$join = "schuelereltern.schueler";
			$where = "eltern = $id";
		}

		$auswahl .= "</td>";

		// Zugeordnete Personen suchen
		$zugeordnetperson = "";
		$zugeordnethidden = "";
		$sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen JOIN schuelereltern ON personen.id = $join WHERE $where";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$id = $daten['id'];
				$art = $daten['art'];

				$anzeigename = $daten['vorname']." ".$daten['nachname'];
				if (strlen($daten['titel']) > 0) {$anzeigename = $daten['titel']." ".$anzeigename;}

				$zugeordnetperson .= cms_personensuche_personerzeugen ('schulhof_personen_schuelereltern', 1, $daten['id'], $daten['art'], $daten['vorname'], $daten['nachname'], $daten['titel'], true);
				$zugeordnethidden .= "|".$daten['id'];
			}
			$anfrage->free();
		}

		$zugeordnet.= "<td class=\"cms_personensuche_feld_aussen\" id=\"cms_schulhof_personen_schuelereltern2Fo\"><span id=\"cms_schulhof_personen_schuelerelternF\">".$zugeordnetperson."</span>";
		if ($art == 's') {
			$zugeordnet .= cms_personensuche('schulhof_personen_schuelereltern', 'Person hinzufügen', $zugeordnethidden, false, false, true, false, false, 1);
		}
		else if ($art == 'e') {
			$zugeordnet .= cms_personensuche('schulhof_personen_schuelereltern', 'Person hinzufügen', $zugeordnethidden, true, false, false, false, false, 1);
		}
		$zugeordnet.= "</td>";


		$auswahl .= "</tr>";
		$zugeordnet .= "</tr>";

		$code .= $auswahl.$zugeordnet."</table>";
		cms_trennen($dbs);
		echo $code;

		echo "<p><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_personen_verknuepfung();\">Zuordnung speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung/Personen/\">Abbrechen</a></p>";
	}
}
?>
</div>
