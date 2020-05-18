<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Bestellungen</h1>

<?php
if (cms_r("shop.bestellungen.*")) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Person</th><th>Besteller</th><th>Bedarf</th><th style="text-align:right">Preis</th><th>Status</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php

		$BESTELLUNGEN = array();
		$sql = $dbs->prepare("SELECT * FROM (SELECT ebestellung.id AS bid, AES_DECRYPT(ebestellung.vorname, '$CMS_SCHLUESSEL') AS bvorname, AES_DECRYPT(ebestellung.nachname, '$CMS_SCHLUESSEL') AS bnachname, AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL') AS pvorname, AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL') AS pnachname, AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL') AS ptitel, bedarf, status FROM ebestellung JOIN personen ON ebestellung.id = personen.id  WHERE bedarf != 0) AS ebestellung ORDER BY bedarf DESC, status ASC, pnachname ASC, pvorname ASC, ptitel ASC");
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($bid, $bvor, $bnach, $pvor, $pnach, $ptit, $bedarf, $status);
			while ($sql->fetch()) {
				$bestellung = array();
				$bestellung['id'] = $bid;
				$bestellung['person'] = cms_generiere_anzeigename($pvor, $pnach, $ptit);
				$bestellung['besteller'] = cms_generiere_anzeigename($bvor, $bnach, "");
				$bestellung['bedarf'] = $bedarf;
				$bestellung['status'] = $status;
				$bestellung['preis'] = 0;
				array_push($BESTELLUNGEN, $bestellung);
			}
		}
		$sql->close();

		$sql = $dbs->prepare("SELECT SUM(stueck), SUM(stueck*preis) FROM eposten JOIN egeraete ON eposten.geraet = egeraete.id WHERE bestellung = ?");
		foreach($BESTELLUNGEN AS $b) {
		$sql->bind_param("i", $b['id']);
			$sql->bind_result($geraete, $preis);
			$sql->execute();
			$sql->fetch();
			$ausgabe .= "<tr>";
				$ausgabe .= "<td><img src=\"res/icons/klein/bestellung.png\"></td>";
				$ausgabe .= "<td>".$b['person']."</td>";
				$ausgabe .= "<td>".$b['besteller']."</td>";
				if ($b['bedarf'] == 2) {
					$ausgabe .= "<td>Leihe</td>";
					$ausgabe .= "<td style=\"text-align: right\">0,00 €</td>";
				}
				else {
					if ($geraete == 1) {
					$ausgabe .= "<td>Kauf ($geraete Gerät)</td>";
					}
					else {
						$ausgabe .= "<td>Kauf ($geraete Geräte)</td>";
					}
					$ausgabe .= "<td style=\"text-align: right\">".cms_format_preis($preis/100)." €</td>";
				}

				$ausgabe .= "<td>";
				if ($status == 0) {$ausgabe .= "Eingegangen";}
				else if ($status == 1) {$ausgabe .= "Bezahlt";}
				else if ($status == 2) {$ausgabe .= "Übermittelt";}
				else if ($status == 3) {$ausgabe .= "Geliefert";}
				$ausgabe .= "</td>";

				// Aktionen
				$ausgabe .= "<td>";
				if (cms_r("shop.bestellungen.verarbeiten")) {
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_bestellung_sehen(".$b['id'].");\"><span class=\"cms_hinweis\">Bestelldetails ansehen</span><img src=\"res/icons/klein/sichtbar.png\"></span> ";
					if ($status != 0) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_bestellung_status(".$b['id'].", 0);\"><span class=\"cms_hinweis\">Als eingegangen markieren</span><img src=\"res/icons/klein/bestellt.png\"></span> ";
					}
					if ($status != 1) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_bestellung_status(".$b['id'].", 1);\"><span class=\"cms_hinweis\">Als bezahlt markieren</span><img src=\"res/icons/klein/bezahlt.png\"></span> ";
					}
					if ($status != 2) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_bestellung_status(".$b['id'].", 2);\"><span class=\"cms_hinweis\">Als übermittelt markieren</span><img src=\"res/icons/klein/aufgegeben.png\"></span> ";
					}
					if ($status != 3) {
						$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_bestellung_status(".$b['id'].", 3);\"><span class=\"cms_hinweis\">Als geliefert markieren</span><img src=\"res/icons/klein/geliefert.png\"></span> ";
					}

				}
				if (cms_r("shop.bestellungen.löschen")) {
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_bestellung_loeschen_anzeigen(".$b['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
				}

				$ausgabe .= "</td>";

			$ausgabe .= "</tr>";
		}
		$sql->close();

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"7\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		?>
		</tbody>
	</table>
<?php
	$sql = $dbs->prepare("SELECT COUNT(*) FROM ebestellung WHERE bedarf = 0");
	if ($sql->execute()) {
		$sql->bind_result($anzahl);
		$sql->fetch();
		if ($anzahl == 1) {echo "<p>1 Person hat keinen Bedarf gemeldet.</p>";}
		else if ($anzahl > 1) {echo "<p>$anzahl Personen aben keinen Bedarf gemeldet.</p>";}
	}
	$sql->close();

	if (cms_r("shop.bestellungen.löschen")) {
		echo "<p><span class=\"cms_button_nein\" onclick=\"cms_allebestellungen_loeschen_anzeigen();\">Alle Bestellungen löschen</span></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
