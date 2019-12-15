<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Rollen und Rechte vergeben</h1>

<?php
// PROFILDATEN LADEN
$fehler = true;
if (!isset($_SESSION['PERSONENDETAILS'])) {
	echo cms_meldung_bastler();
}
else {
	$id = $_SESSION['PERSONENDETAILS'];
	$zugriff = $CMS_RECHTE['Personen']['Rechte und Rollen zuordnen'];
	if ($zugriff) {
		// Person laden, für die die Rechte geändert werden sollen
		$dbs = cms_verbinden('s');
		$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);
		$sql->bind_result($vorname, $nachname, $personart);

		$fehler = false;
		if ($sql->execute()) {
			if ($sql->fetch()) {
			}
			else {$fehler = false;}
		}
		else {$fehler = false;}

		if ($fehler) {
			echo cms_meldung_unbekannt();
		}

		cms_trennen($dbs);
	}
	else {
		echo cms_meldung_berechtigung();
	}
}
?>
</div>

<?php
if (!$fehler) {
	$dbs = cms_verbinden('s');
?>

<div class="cms_spalte_2">
<div class="cms_spalte_i">
	<?php
	echo "<h3>Für $vorname $nachname verfügbare Rollen</h3>";

	$rollencode = "";
	$sql = "SELECT * FROM (SELECT person, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, rollen.id AS rolle FROM rollen LEFT JOIN (SELECT person, rolle FROM rollenzuordnung WHERE person = $id) AS rollenzuordnung ON rollen.id = rollenzuordnung.rolle WHERE personenart = AES_ENCRYPT('$personart', '$CMS_SCHLUESSEL')) AS rollen ORDER BY bezeichnung ASC";

	if ($anfrage = $dbs->query($sql)) {	// Safe weil interne ID
		while ($daten = $anfrage->fetch_assoc()) {
			if ($daten['person'] == $id) {$rollencode .= "<span class=\"cms_toggle cms_toggle_aktiv\" onclick=\"cms_schulhof_verwaltung_personen_rolle_vergeben(0, ".$daten['rolle'].")\">".$daten['bezeichnung']."</span> ";}
			else {$rollencode .= "<span class=\"cms_toggle\" onclick=\"cms_schulhof_verwaltung_personen_rolle_vergeben(1, ".$daten['rolle'].")\">".$daten['bezeichnung']."</span> ";}
		}
		$anfrage->free();
	}

	if ($rollencode == "") {
		$rollencode = "<p class=\"cms_notiz\">Keine Rollen verfügbar</p>";
	}

	echo $rollencode;
	?>
</div>
</div>

<div class="cms_spalte_2">
<div class="cms_spalte_i">
	<h3>In den zugeordneten Rollen enthaltene Rechte</h3>
	<?php

	$rechtecode = "";
	$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM rechte WHERE id IN (SELECT recht AS id FROM rollenrechte WHERE rolle IN (SELECT rolle FROM rollenzuordnung WHERE person = $id))) AS rechte ORDER BY kategorie ASC, bezeichnung ASC";
	$altekategorie = "";

	if ($anfrage = $dbs->query($sql)) {	// Safe weil interne ID
		while ($daten = $anfrage->fetch_assoc()) {
			if ($altekategorie != $daten['kategorie']) {
				$rechtecode .= "</p><h4>".$daten['kategorie']."</h4><p>";
				$altekategorie = $daten['kategorie'];
			}
			$rechtecode .= "<span class=\"cms_toggle_aktiv_fest\">".$daten['bezeichnung']."</span> ";
		}
		$rechtecode .= "</p>";
		$rechtecode = substr($rechtecode, 4);;
		$anfrage->free();
	}

	if ($rechtecode == "") {
		$rechtecode = "<p class=\"cms_notiz\">Keine Rollen zugeordnet</p>";
	}

	echo $rechtecode;
	?>
</div>
</div>

<div class="cms_clear"></div>

<div class="cms_spalte_i">
	<?php
	echo "<h3>Zusätzliche verfügbare Rechte für $vorname $nachname</h3>";

	$rechtecode = "";
	$sql = "SELECT id AS recht, kategorie, bezeichnung, person FROM (SELECT id, AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM rechte WHERE id NOT IN (SELECT recht AS id FROM rollenrechte WHERE rolle IN (SELECT rolle FROM rollenzuordnung WHERE person = $id))) AS rechte LEFT JOIN (SELECT person, recht FROM rechtzuordnung WHERE person = $id) AS rechtzuordnung ON rechte.id = rechtzuordnung.recht ORDER BY kategorie ASC, bezeichnung ASC";

	$altekategorie = "";

	if ($anfrage = $dbs->query($sql)) {	// Safe weil interne ID
		while ($daten = $anfrage->fetch_assoc()) {
			if ($altekategorie != $daten['kategorie']) {
				$rechtecode .= "</p><h4>".$daten['kategorie']."</h4><p>";
				$altekategorie = $daten['kategorie'];
			}
			if ($daten['person'] == $id) {$rechtecode .= "<span class=\"cms_toggle cms_toggle_aktiv\" onclick=\"cms_schulhof_verwaltung_personen_recht_vergeben(0, ".$daten['recht'].")\">".$daten['bezeichnung']."</span> ";}
			else {$rechtecode .= "<span class=\"cms_toggle\" onclick=\"cms_schulhof_verwaltung_personen_recht_vergeben(1, ".$daten['recht'].")\">".$daten['bezeichnung']."</span> ";}
		}
		$rechtecode .= "</p>";
		$rechtecode = substr($rechtecode, 4);;
		$anfrage->free();
	}

	if ($rechtecode == "") {
		$rechtecode = "<p class=\"cms_notiz\">Keine zusätzlichen Rechte verfügbar</p>";
	}

	echo $rechtecode;
	?>


<p><span class="cms_button" onclick="cms_schulhof_verwaltung_personen_rollenundrechtevergabe();">Speichern</span> <a class="cms_button_nein" href="Schulhof/Verwaltung/Personen/Details">Abbrechen</a></p>
</div>

<?php
	cms_trennen($dbs);
}
?>
