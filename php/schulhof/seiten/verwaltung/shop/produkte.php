<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Produkte</h1>

<?php
if (cms_r("shop.produkte.*")) {
?>
	<table class="cms_liste">
		<thead>
			<tr><th></th><th>Titel</th><th>Lieferzeit</th><th style="text-align: right;">Verfügbarkeit</th><th style="text-align: right;">Preis</th><th>Aktionen</th></tr>
		</thead>
		<tbody>
		<?php
		// Alle Rollen ausgeben
		$dbs = cms_verbinden('s');

		$PRODUKTE = array();
		$sql = $dbs->prepare("SELECT * FROM (SELECT id AS gid, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, preis, stk, AES_DECRYPT(lieferzeit, '$CMS_SCHLUESSEL') FROM egeraete) AS egeraete ORDER BY preis, titel ASC");
		$ausgabe = "";
		if ($sql->execute()) {
			$sql->bind_result($gid, $titel, $preis, $stk, $lieferzeit);
			while ($sql->fetch()) {
				$geraet = array();
				$geraet['id'] = $gid;
				$geraet['titel'] = $titel;
				$geraet['preis'] = $preis;
				$geraet['stk'] = $stk;
				$geraet['bestellt'] = 0;
				$geraet['lieferzeit'] = $lieferzeit;
				array_push($PRODUKTE, $geraet);
			}
		}
		$sql->close();

		$sql = $dbs->prepare("SELECT SUM(stueck) FROM eposten WHERE geraet = ?");
		foreach($PRODUKTE AS $p) {
		$sql->bind_param("i", $p['id']);
			$sql->bind_result($bestellt);
			$sql->execute();
			if ($sql->fetch()) {
				$p['bestellt'] = $bestellt;
			}
			$ausgabe .= "<tr>";
				$ausgabe .= "<td><img src=\"res/icons/klein/produkt.png\"></td>";
				$ausgabe .= "<td>".$p['titel']."</td>";
				$ausgabe .= "<td>".$p['lieferzeit']."</td>";
				$ausgabe .= "<td style=\"text-align: right;\">".($p['stk']-$p['bestellt'])."/".$p['stk']."</td>";
				$ausgabe .= "<td style=\"text-align: right;\">".cms_format_preis($p['preis']/100)." €</td>";
				// Aktionen
				$ausgabe .= "<td>";
				if (cms_r("shop.produkte.bearbeiten")) {
					$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_produkt_bearbeiten_vorbereiten(".$p['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				}
				if (cms_r("shop.produkte.löschen")) {
					$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_produkt_loeschen_anzeigen(".$p['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
				}

				$ausgabe .= "</td>";

			$ausgabe .= "</tr>";
		}
		$sql->close();

		if ($ausgabe == "") {
			$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
		}

		echo $ausgabe;
		cms_trennen($dbs);
		?>
		</tbody>
	</table>
<?php
	if (cms_r("shop.produkte.anlegen")) {
		echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Produkte/Neues_Produkt_anlegen\">+ Neues Produkt anlegen</a></p>";
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
