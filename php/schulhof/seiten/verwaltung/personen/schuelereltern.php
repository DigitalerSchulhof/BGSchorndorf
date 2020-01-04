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
	include_once("php/schulhof/seiten/personensuche/personensuche.php");
	$id = $_SESSION['PERSONENDETAILS'];
	if (r("schulhof.verwaltung.personen.schülereltern")) {
		// Person laden, für die die Rechte geändert werden sollen
		$dbs = cms_verbinden('s');
		$sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);
		$fehler = false;
		if ($sql->execute()) {
			$sql->bind_result($id, $vorname, $nachname, $titel, $art);
			if ($sql->fetch()) {
				$anzeigename = "$vorname $nachname";
				if (strlen($titel) > 0) {$anzeigename = "$titel $anzeigename";}
			}
			else {$fehler = false;}
			$sql->close();
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
			$where = "schueler = ?";
		}
		else {
			$auswahl = "<tr><th>Eltern:</th><td>$anzeigename";
			$zugeordnet = "<tr><th>Schüler:</th>";
			$join = "schuelereltern.schueler";
			$where = "eltern = ?";
		}

		$auswahl .= "</td>";

		// Zugeordnete Personen suchen
		$zugeordnethidden = "";
		$sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen JOIN schuelereltern ON personen.id = $join WHERE $where";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);
		if ($sql->execute()) {
			$sql->bind_result($zid);
			while ($sql->fetch()) {
				$zugeordnethidden .= "|$zid";
			}
			$sql->close();
		}

		$zugeordnet.= "<td>";
		if ($art == 's') {
			$zugeordnet .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuereltern_zuordnung', 'e', $zugeordnethidden);
		}
		else if ($art == 'e') {
			$zugeordnet .= cms_personensuche_personhinzu_generieren($dbs, 'cms_schuereltern_zuordnung', 's', $zugeordnethidden);
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
