<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Leihgeräte</h1>

<?php
$zugriff = $CMS_RECHTE['Organisation']['Leihgeräte anlegen'] || $CMS_RECHTE['Organisation']['Leihgeräte bearbeiten'] || $CMS_RECHTE['Organisation']['Leihgeräte löschen'];

if ($zugriff) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Bezeichnung</th><th>Ausstattung</th><th></th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');

		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, verfuegbar, buchbar, externverwaltbar FROM leihen) AS leihen ORDER BY bezeichnung ASC");
		$LEIHGERAETE = array();
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($lid, $lbez, $lverfuegbar, $lbuchbar, $lexternv);
			while ($sql->fetch()) {
				$LG = array();
				$LG['id'] = $lid;
				$LG['bezeichnung'] = $lbez;
				$LG['verfuegbar'] = $lverfuegbar;
				$LG['buchbar'] = $lbuchbar;
				$LG['externverwaltbar'] = $lexternv;
				array_push($LEIHGERAETE, $LG);
			}
		}
		$sql->close();

		$sql = $dbs->prepare("SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM leihengeraete WHERE standort = ?) AS x ORDER BY bezeichnung ASC");
		foreach ($LEIHGERAETE AS $daten) {
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
				$ausgabe .= "<td><img src=\"res/icons/klein/leihgeraete.png\"></td>";
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
				if ($CMS_RECHTE['Organisation']['Räume bearbeiten']) {
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_leihgeraet_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				}
				if ($CMS_RECHTE['Organisation']['Räume löschen']) {
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_leihgeraet_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
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
	if ($CMS_RECHTE['Organisation']['Leihgeräte anlegen']) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Leihgeräte/Neue_Leihgeräte_anlegen\">+ Neue Leihgeräte anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
