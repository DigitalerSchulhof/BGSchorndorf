<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Bestellung ansehen</h1>

<?php
if (cms_r("shop.bestellungen.verarbeiten")) {

	if (isset($_SESSION["BESTELLUNGSEHEN"])) {

		$code = "";


		$sql = $dbs->prepare("SELECT COUNT(*), ebestellung.id AS bid, AES_DECRYPT(ebestellung.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.anrede, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.strasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.hausnr, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.plz, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.ort, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.telefon, '$CMS_SCHLUESSEL'), AES_DECRYPT(ebestellung.email, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(personen.titel, '$CMS_SCHLUESSEL') AS ptitel, bedarf, status FROM ebestellung JOIN personen ON ebestellung.id = personen.id WHERE ebestellung.id = ?");
		$ausgabe = "";
		$sql->bind_param("i", $_SESSION["BESTELLUNGSEHEN"]);
		if ($sql->execute()) {
			$sql->bind_result($anzahl, $bid, $bvor, $bnach, $banrede, $bstrasse, $bhausnr, $bplz, $bort, $btel, $bmail, $pvor, $pnach, $ptit, $bedarf, $status);
			$sql->fetch();
		}
		$sql->close();


		if (($anzahl == 0) || ($anzahl == null)) {
			$code .= "<p>Es wurde nicht bestellt.</p>";
		}
		else {
			if ($bedarf == '0') {
				$code .= "<p>Es wurde kein Bedarf gemeldet.</p>";
			}
			else {
				$code .= "<table class=\"cms_formular\">";
				$code .= "<tr><td><b>Empfänger:</b> ".cms_generiere_anzeigename($pvor, $pnach, $ptit)."<br>";
				$code .= "<b>Bestellnummer:</b> $bid<br>";
				if ($status == 0) {$statusmeldung = "Bestellung eingegangen"; if ($bedarf == '1') {$statusmeldung .= " - Bezahlung ausstehend";}}
				else if ($status == 1) {$statusmeldung = "Bezahlt";}
				else if ($status == 2) {$statusmeldung = "Übermittelt";}
				else if ($status == 3) {$statusmeldung = "Geliefert";}
				$code .= "<b>Bestellstatus:</b> $statusmeldung<br><b>Status ändern:</b><br>";
				if ($status != 0) {
					$code .= "<span class=\"cms_button\" onclick=\"cms_bestellung_status(".$bid.", 0, '/Bestellung_ansehen');\">Eingegangen</span> ";
				}
				if ($status != 1) {
					$code .= "<span class=\"cms_button\" onclick=\"cms_bestellung_status(".$bid.", 1, '/Bestellung_ansehen');\">Bezahlt</span> ";
				}
				if ($status != 2) {
					$code .= "<span class=\"cms_button\" onclick=\"cms_bestellung_status(".$bid.", 2, '/Bestellung_ansehen');\">Übermittelt</span> ";
				}
				if ($status != 3) {
					$code .= "<span class=\"cms_button\" onclick=\"cms_bestellung_status(".$bid.", 3, '/Bestellung_ansehen');\">Geliefert</span> ";
				}
				if (cms_r("shop.bestellungen.löschen")) {
					$code .= "<span class=\"cms_button_nein\" onclick=\"cms_bestellung_loeschen_anzeigen(".$bid.");\">Löschen</span> ";
				}
				$code .= "</td>";
				$code .= "<td><b>Besteller:</b> ";
				$code .= cms_generiere_anzeigename($bvor, $bnach, "")."<br>";
				$code .= $bstrasse." ".$bhausnr."<br>";
				$code .= $bplz." ".$bort."<br>";
				$code .= $btel."<br>";
				$code .= "<a href=\"mailto:$bmail\">$bmail</a><br>";
				$code .= "</td></tr>";
				$code .= "</table>";

				$code .= "<p></p>";
				$code .= "<table class=\"cms_liste\">";
					$code .= "<tr><th>Artikel</th><th style=\"text-align: right\">Menge</th><th style=\"text-align: right\">Einzelpreis</th><th style=\"text-align: right\">Summe</th></tr>";
					if ($bedarf == '2') {
						$code .= "<tr><td>Leihgerät von der Schule</td><td style=\"text-align: right\">1</td><td style=\"text-align: right\">0,00 €</td><td style=\"text-align: right\">0,00 €</td></tr>";
				    $code .= "<tr><th colspan=\"3\">Gesamt</th><th style=\"text-align: right\">0,00 €</th></tr>";
					}
					else {
						$sql = $dbs->prepare("SELECT * FROM (SELECT eposten.stueck, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, preis FROM eposten JOIN egeraete ON geraet = egeraete.id WHERE bestellung = ?) AS x ORDER BY preis ASC, titel ASC");
						$sql->bind_param("i", $bid);
						if ($sql->execute()) {
							$sql->bind_result($anzahl, $titel, $preis);
							$summe = 0;
							while ($sql->fetch()) {
								$zwischensumme = $preis * $anzahl;
					      if ($anzahl > 0) {
					        $code .= "<tr><td>$titel</td><td style=\"text-align: right\">$anzahl";
					        $code .= "</td><td style=\"text-align: right\">".cms_format_preis($preis/100)." €</td><td style=\"text-align: right\">".cms_format_preis($zwischensumme/100)." €</td></tr>";
					        $summe += $zwischensumme;
					      }
							}
							if ($summe == 0) {
					      $code .= "<tr><td colspan=\"4\" class=\"cms_zentriert\"><i>Kein Gerät gewählt</i></td></tr>";
					    }
					    $code .= "<tr><th colspan=\"3\">Gesamt</th><th style=\"text-align: right\">".cms_format_preis($summe/100)." €</th></tr>";
						}
						$sql->close();
					}
				$code .= "</table>";
			}
		}

		echo $code;

		}
	else {
		echo cms_meldung_bastler();
	}
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>

<div class="cms_clear"></div>
