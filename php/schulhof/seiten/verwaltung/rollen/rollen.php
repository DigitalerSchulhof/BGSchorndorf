<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Rollen</h1>

<?php
$zugriff = $CMS_RECHTE['Personen']['Rollen anlegen'] || $CMS_RECHTE['Personen']['Rollen bearbeiten'] || $CMS_RECHTE['Personen']['Rollen löschen'];
if ($zugriff) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Rechte</th><th>Personen</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$ROLLEN = array();
		$dbs = cms_verbinden('s');
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(personenart, '$CMS_SCHLUESSEL') AS personenart FROM rollen) AS rollen ORDER BY bezeichnung ASC");
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($rid, $rbez, $rpart);
			while ($sql->fetch()) {
				$R = array();
				$R['id'] = $rid;
				$R['bezeichnung'] = $rbez;
				$R['personenart'] = $rpart;
				$R['kategorien'] = "";
				$R['kategorienanzahl'] = 0;
				$R['rechtezahl'] = 0;
				$R['personen'] = "";
				array_push($ROLLEN, $R);
			}
		}
		$sql->close();

		$sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie FROM rechte, rollenrechte WHERE rechte.id = rollenrechte.recht AND rolle = ?) AS rechte ORDER BY kategorie ASC");
		for ($i=0; $i<count($ROLLEN); $i++) {
			if ($ROLLEN[$i]['id'] != 0) {
				$sql->bind_param("i", $ROLLEN[$i]['id']);
				if ($sql->execute()) {
					$sql->bind_result($rkat);
					while ($sql->fetch()) {
						$ROLLEN[$i]['kategorien'] .= ", ".$rkat;
						$ROLLEN[$i]['kategorienanzahl'] ++;
					}
				}
				if (strlen($ROLLEN[$i]['kategorien']) > 0) {$ROLLEN[$i]['kategorien'] = substr($ROLLEN[$i]['kategorien'], 2);}

			}
			else {$ROLLEN[$i]['kategorien'] = "alle";}
		}
		$sql->close();

		$sql = $dbs->prepare("SELECT COUNT(*) as anzahl FROM rechte, rollenrechte WHERE rechte.id = rollenrechte.recht AND rolle = ?");
		for ($i=0; $i<count($ROLLEN); $i++) {
			if ($ROLLEN[$i]['id'] != 0) {
				$sql->bind_param("i", $ROLLEN[$i]['id']);
				if ($sql->execute()) {
					$sql->bind_result($ranzahl);
					if ($sql->fetch()) {
						$RECHTE[$i]['rechtezahl'] = $ranzahl;
					}
				}
				if ($RECHTE[$i]['rechtezahl'] != 1) {$RECHTE[$i]['rechtezahl'] .= " Rechte aus ";}
				else {$RECHTE[$i]['rechtezahl'] .= " Recht aus ";}

				if ($ROLLEN[$i]['kategorienanzahl'] > 1) {$ROLLEN[$i]['kategorien'] = $RECHTE[$i]['rechtezahl']."den Kategorien ".$ROLLEN[$i]['kategorien'];}
				else {$ROLLEN[$i]['kategorien'] = $RECHTE[$i]['rechtezahl']."der Kategorie ".$ROLLEN[$i]['kategorien'];}
			}
		}
		$sql->close();


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

		if (strlen($ausgabe) == 0) {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if ($CMS_RECHTE['Personen']['Rollen anlegen']) {echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Rollen/Neue_Rolle_anlegen\">+ Neue Rolle anlegen</a></p>";}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>

<div class="cms_clear"></div>
