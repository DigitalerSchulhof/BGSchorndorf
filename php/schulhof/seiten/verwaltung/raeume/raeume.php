<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Räume</h1>

<?php
if (cms_r("schulhof.planung.räume.*")) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Ausstattung</th><th></th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, verfuegbar, buchbar, externverwaltbar FROM raeume) AS raeume ORDER BY bezeichnung ASC");

		$RAEUME = array();
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($rid, $rbez, $rver, $rbuch, $rext);
			while ($sql->fetch()) {
				$R = array();
				$R['id'] = $rid;
				$R['bezeichnung'] = $rbez;
				$R['verfuegbar'] = $rver;
				$R['buchbar'] = $rbuch;
				$R['externverwaltbar'] = $rext;
				array_push($RAEUME, $R);
			}
		}
		$sql->close();

		$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeumegeraete WHERE standort = ?) AS x ORDER BY bezeichnung ASC");
		foreach ($RAEUME as $daten) {
			$ausstattung = "";
			$sql->bind_param("i", $daten['id']);
			if ($sql->execute()) {
				$sql->bind_result($gbezeichnung);
				while($sql->fetch()) {
					$ausstattung .= ', '.$gbezeichnung;
				}
			}

			if (strlen($ausstattung) > 0) {$ausstattung = substr($ausstattung, 2);}

			$ausgabe .= "<tr>";
				$ausgabe .= "<td><img src=\"res/icons/klein/raum.png\"></td>";
				$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
				$ausgabe .= "<td>".$ausstattung."</td>";
				$ausgabe .= "<td>";
				if ($daten['verfuegbar'] == 1) {$icon = "gruen"; $hinweis = "verfügbar";} else {$icon = "rot"; $hinweis = "nicht verfügbar";}
				$ausgabe .= "<span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/$icon.png\"><span class=\"cms_hinweis\">$hinweis</span></span> ";
				if ($daten['buchbar'] == 1) {$icon = "gruen"; $hinweis = "buchbar";} else {$icon = "rot"; $hinweis = "nicht buchbar";}
				$ausgabe .= "<span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/$icon.png\"><span class=\"cms_hinweis\">$hinweis</span></span> ";
				if ($daten['externverwaltbar'] == 1) {$icon = "gruen"; $hinweis = "extern verwaltbar";} else {$icon = "rot"; $hinweis = "nicht extern verwaltbar";}
				$ausgabe .= "<span class=\"cms_icon_klein_o\"><img src=\"res/icons/klein/$icon.png\"><span class=\"cms_hinweis\">$hinweis</span></span>";
				$ausgabe .= "</td>";
				// Aktionen
				$ausgabe .= "<td>";
				$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
				if (cms_r("schulhof.planung.räume.bearbeiten")) {
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_raum_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				}
				if (cms_r("schulhof.planung.räume.löschen")) {
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_raum_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
				}

				$ausgabe .= "</td>";

			$ausgabe .= "</tr>";
		}
		$sql->close();

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"5\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if (cms_r("schulhof.planung.räume.anlegen")) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Räume/Neuen_Raum_anlegen\">+ Neuen Raum anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
