<div class="cms_spalte_i">
	<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
	<?php
		if (cms_r("schulhof.verwaltung.rechte.rollen.[|sehen,erstellen,bearbeiten,löschen]")) {
	?>

<h1>Rollen</h1>

	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Personen</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM rollen) AS rollen ORDER BY id ASC";
		$ausgabe = "";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$ausgabe .= "<tr>";
				$icon = "";
				if($daten["id"] == 0) {
					$icon = " <span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/code_xml.png\"></span>";
				}
				$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/rollen.png\"></span>$icon</td>";
				$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
			}
		}

		$sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM rechte, rollenrechte WHERE rechte.id = rollenrechte.recht AND rolle = ?");
		for ($i=0; $i<count($ROLLEN); $i++) {
			if ($ROLLEN[$i]['id'] != 0) {
				$sql->bind_param("i", $ROLLEN[$i]['id']);
				if ($sql->execute()) {
					$sql->bind_result($ranzahl);
					if ($sql->fetch()) {
						$RECHTE[$i]['rechtezahl'] = $ranzahl;
					}
					if ($personen != "") {
						$ausgabe .= "<td>".(substr($personen, 2))."</td>";
					}
					else {$ausgabe .= "<td>Nicht zugeordnet</td>";}

					// Aktionen
					$ausgabe .= "<td>";
					if ($daten['id'] != 0) {
						$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
						if (cms_r("schulhof.verwaltung.rechte.rollen.bearbeiten")) {
							$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_rolle_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
						}
						if (cms_r("schulhof.verwaltung.rechte.rollen.löschen")) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_rolle_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
						}
					}
					$ausgabe .= "</td>";
				}
			}
		}


		$sql = $dbs->prepare("SELECT vorname, nachname, titel FROM (SELECT DISTINCT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM personen JOIN rollenzuordnung ON personen.id = rollenzuordnung.person WHERE rolle = ?) AS personen ORDER BY nachname, vorname");
		for ($i=0; $i<count($ROLLEN); $i++) {
			$sql->bind_param("i", $ROLLEN[$i]['id']);
			if ($sql->execute()) {
				$sql->bind_result($rvor, $rnach, $rtitel);
				while ($sql->fetch()) {
					$ROLLEN[$i]['personen'] .= ", ".cms_generiere_anzeigename($rvor, $rnach, $rtitel);
				}
			}
			if (strlen($ROLLEN[$i]['personen']) > 0) {$ROLLEN[$i]['personen'] = substr($ROLLEN[$i]['personen'],2);}
			else {$ROLLEN[$i]['personen'] = "nicht zugeordnet";}
		}
		$sql->close();


		foreach ($ROLLEN AS $daten) {
			$ausgabe .= "<tr>";
				//icon
				$icon = "";
				if ($daten['personenart'] == 'l') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Lehrer</span><img src="res/icons/klein/lehrer.png"></span>';}
				else if ($daten['personenart'] == 's') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Schüler</span><img src="res/icons/klein/schueler.png"></span>';}
				else if ($daten['personenart'] == 'e') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Eltern</span><img src="res/icons/klein/elter.png"></span>';}
				else if ($daten['personenart'] == 'v') {$icon = '<span class="cms_icon_klein_o"><span class="cms_hinweis">Verwaltung</span><img src="res/icons/klein/verwaltung.png"></span>';}
				$ausgabe .= "<td><span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/rollen.png\"></span> ".$icon."</td>";
				$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
				$ausgabe .= "<td>".$daten['kategorien']."</td>";
				$ausgabe .= "<td>".$daten['personen']."</td>";

				// Aktionen
				$ausgabe .= "<td>";
				if ($daten['id'] != 0) {
					$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
					if ($CMS_RECHTE['Personen']['Rollen bearbeiten']) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_rolle_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					if ($CMS_RECHTE['Personen']['Rollen löschen']) {
						$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_rolle_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
					}
				}
				$ausgabe .= "</td>";

			$ausgabe .= "</tr>";
		}

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"4\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if (cms_r("schulhof.verwaltung.rechte.rollen.erstellen"))
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Rollen/Neue_Rolle_anlegen\">+ Neue Rolle anlegen</a></p>";

	} else
		echo cms_meldung_berechtigung();
?>
</div>

<div class="cms_clear"></div>
