<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$bestellende = mktime (23, 59, 59, 5, 31, 2020);

echo "<h1>Sammelbestellung für Notebooks oder Tablets</h1>";
$teilgenommen = false;
$sql = $dbs->prepare("SELECT COUNT(*), bedarf, status, AES_DECRYPT(anrede, '$CMS_SCHLUESSEL'), AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(strasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(hausnr, '$CMS_SCHLUESSEL'), AES_DECRYPT(plz, '$CMS_SCHLUESSEL'), AES_DECRYPT(ort, '$CMS_SCHLUESSEL'), AES_DECRYPT(telefon, '$CMS_SCHLUESSEL'), AES_DECRYPT(email, '$CMS_SCHLUESSEL'), bedingungen, eingegangen FROM ebestellung WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($anzahl, $bedarf, $status, $anrede, $vorname, $nachname, $strasse, $hausnr, $plz, $ort,  $telefon, $mail, $bedingungen, $eingegangen);
  $sql->fetch();
}
$sql->close();

if ($status == NULL) {$status = 0;}

$sql = $dbs->prepare("SELECT bedarf FROM ebedarf WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($teilnahme);
  if ($sql->fetch()) {
		if (($teilnahme == 1) || ($teilnahme == 2)) {$teilgenommen = true;}
	}

}
$sql->close();

if ((time() <= $bestellende) && ($status < 2)) {
	$meldung = "<h4>Bestellprozess</h4>";
	$meldung .= "<p><b>Achtung!!</b> Die hier angebotenen <b>ASUS</b> Geräte sind Übergangsgeräte aus Restposten (Stangenware) für ganz dringende Fälle. Bei beiden Angeboten handelt es sich keineswegs um schlechte Geräte, jedoch ist es wahrscheinlich, dass die <b>DELL</b>-Geräte etwas langlebiger sein werden.</p>";
	$meldung .= "<p>Die Bestellmöglichkeit endet am ".cms_tagnamekomplett(date("w", $bestellende)).", den ".date("d", $bestellende).". ".cms_monatsnamekomplett(date("m", $bestellende))." ".date("Y", $bestellende)." um ".date("H:i:s", $bestellende)." Uhr</p><p><b>ASUS</b>-Bestellungen werden in Gruppen an den Händler weitergegeben. Eine Bearbeitung der Bestellung kann erst nach Zahlungseingang erfolgen. Alle Bestellungen, die <b>DELL</b>-Geräte enthalten, werden erst zum Bestellende übermittelt. <b>Jede Person kann insgesamt nur einmal bestellen!</b></p><p>Finazierungen/Ratenkäufe sind leider nicht möglich.</p>";
	echo cms_meldung("warnung", $meldung);

	$code = "<h2>";
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Bedarf:</th><td><select id=\"cms_ebestellung_bedarf\" name=\"cms_ebestellung_bedarf\" onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\">";
		$optionen = "<option value=\"0\">Es besteht kein Bedarf.</option><option value=\"1\">Ich möchte Geräte bestellen.</option><option value=\"2\">Es besteht Bedarf, aber im Moment ist aus finanziellen Gründen keine Anschaffung möglich. Ich will ein Gerät leihen!</option>";
		$code .= str_replace("value=\"$bedarf\"", "value=\"$bedarf\" selected=\"selected\"", $optionen);
		$code .= "</select></td><td></td></tr>";
	$code  .= "</table>";

	$bestellt = array();
	$sql = $dbs->prepare("SELECT SUM(stueck), geraet FROM eposten WHERE bestellung != ? GROUP BY geraet");
	$sql->bind_param("i", $CMS_BENUTZERID);
	if ($sql->execute()) {
		$sql->bind_result($b, $gid);
		while($sql->fetch()) {
			$bestellt[$gid] = $b;
		}
	}
	$sql->close();

	$reserviert = array();
	$sql = $dbs->prepare("SELECT stueck, geraet FROM eposten WHERE bestellung = ?");
	$sql->bind_param("i", $CMS_BENUTZERID);
	if ($sql->execute()) {
		$sql->bind_result($r, $gid);
		while($sql->fetch()) {
			$reserviert[$gid] = $r;
		}
	}
	$sql->close();

	$code .= "<div id=\"cms_ebestellung_geraete\" class=\"cms_bestellen_box\" style=\"display:none\">";
	$code .= "<h2>Geräteauswahl</h2>";
	$geraete = "";
		$gids = "";
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') as titel, AES_DECRYPT(bild, '$CMS_SCHLUESSEL'), AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), preis, stk, AES_DECRYPT(lieferzeit, '$CMS_SCHLUESSEL') FROM egeraete) AS x ORDER BY preis ASC, titel");
		if ($sql->execute()) {
			$sql->bind_result($gid, $titel, $bild, $beschreibung, $preis, $stk, $lieferzeit);
			while ($sql->fetch()) {
				$geraete .= "<div class=\"cms_blockwahl\">";
					$geraete .= "<h3>$titel</h3>";
					$geraete .= "<img src=\"$bild\">".$beschreibung;
					if (isset($reserviert[$gid])) {$wert = $reserviert[$gid];} else {$wert = 0;}
					$verfuegbar = $stk;
					if (isset($bestellt[$gid])) {$verfuegbar -= $bestellt[$gid];}
					$maximum = min(5, $verfuegbar);
					$geraete .= "<p>Anzahl: <input onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\" class=\"cms_klein\" id=\"cms_ebestellung_geraet_$gid\" name=\"cms_ebestellung_geraet_$gid\" value=\"$wert\" type=\"number\" min=\"0\" step=\"1\" max=\"$maximum\"> <span class=\"cms_preis\">".cms_format_preis($preis/100)." €</span></p>";
					$geraete .= "<p class=\"cms_notiz\">Verfügbar: $verfuegbar - Lieferzeit ab Bestellung voraussichtlich: $lieferzeit</p>";
					$gids .= "|".$gid;
				$geraete .= "</div>";
			}
		}
		$sql->close();

		if (strlen($geraete) > 0) {
			$code .= "<div class=\"cms_blockwahl_box\">$geraete</div>";
		}
		else {
			$code .= "<p class=\"cms_notiz\">Aktuell sind noch keine Geräte hinterlegt.</p>";
		}
		$code .= "<p><input type=\"hidden\" name=\"cms_bestellen_geraeteids\" id=\"cms_bestellen_geraeteids\" value=\"$gids\"></p>";

	$code .= "</div>";


	$code .= "<div id=\"cms_ebestellung_kontakt\" class=\"cms_bestellen_box\" style=\"display:none\">";
	$code .= "<h2>Kontaktdaten</h2>";
	$code .= "<p>Hier müssen die Kontaktdaten einer geschäftsfähigen Person (volljährig) eingegeben werden, die für den Kauf oder die Leihe verantwortlich ist.</p>";
	$code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Anrede:</th><td colspan=\"2\"><select id=\"cms_ebestellung_anrede\" name=\"cms_ebestellung_anrede\">";
		$optionen = "<option value=\"-\"></option><option value=\"Frau\">Frau</option><option value=\"Herr\">Herr</option>";
		$code .= str_replace("value=\"$anrede\"", "value=\"$anrede\" selected=\"selected\"", $optionen);
		$code .= "</select></td><td></td></tr>";

		$code .= "<tr><th>Vorname:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_vorname", $vorname)."</td></tr>";
		$code .= "<tr><th>Nachname:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_nachname", $nachname)."</td></tr>";
		$code .= "<tr><th>Straße:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_strasse", $strasse)."</td></tr>";
		$code .= "<tr><th>Hausnummer:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_hausnr", $hausnr)."</td></tr>";
		$code .= "<tr><th>Postleitzahl:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_plz", $plz)."</td></tr>";
		$code .= "<tr><th>Ort:</th><td colspan=\"2\">".cms_generiere_input("cms_ebestellung_ort", $ort)."</td></tr>";
		$code .= "<tr><th>Telefonnummer:</th><td><input name=\"cms_schulhof_ebestellung_telefon\" id=\"cms_schulhof_ebestellung_telefon\" type=\"text\" value=\"$telefon\"></td><td></td></tr>";
		$code .= "<tr><th>Telefonnummer wiederholen:</th><td><input name=\"cms_schulhof_ebestellung_telefon_wiederholen\" id=\"cms_schulhof_ebestellung_telefon_wiederholen\" type=\"text\" onkeyup=\"cms_check_passwort_gleich('ebestellung_telefon')\" value=\"$telefon\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebestellung_telefon_gleich_icon\"></span></td></tr>";
		$code .= "<tr><th>eMailadresse:</th><td><input name=\"cms_schulhof_ebestellung_mail\" id=\"cms_schulhof_ebestellung_mail\" type=\"text\" onkeyup=\"cms_check_mail_wechsel('cms_schulhof_ebestellung_mail');\" value=\"$mail\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebestellung_mail_icon\"></span></td></tr>";
		$code .= "<tr><th>eMailadresse wiederholen:</th><td><input name=\"cms_schulhof_ebestellung_mail_wiederholen\" id=\"cms_schulhof_ebestellung_mail_wiederholen\" type=\"text\" onkeyup=\"cms_check_passwort_gleich('ebestellung_mail')\" value=\"$mail\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_ebestellung_mail_gleich_icon\"></span></td></tr>";

		$meldung = "<h4>Bestellprozess</h4>";
		$meldung .= "<p><b>Achtung!!</b> Die hier angebotenen <b>ASUS</b> Geräte sind Übergangsgeräte aus Restposten (Stangenware) für ganz dringende Fälle. Bei beiden Angeboten handelt es sich keineswegs um schlechte Geräte, jedoch ist es wahrscheinlich, dass die <b>DELL</b>-Geräte etwas langlebiger sein werden.</p>";
		$meldung .= "<p>Die Bestellmöglichkeit endet am ".cms_tagnamekomplett(date("w", $bestellende)).", den ".date("d", $bestellende).". ".cms_monatsnamekomplett(date("m", $bestellende))." ".date("Y", $bestellende)." um ".date("H:i:s", $bestellende)." Uhr</p><p><b>ASUS</b>-Bestellungen werden in Gruppen an den Händler weitergegeben. Eine Bearbeitung der Bestellung kann erst nach Zahlungseingang erfolgen. Alle Bestellungen, die <b>DELL</b>-Geräte enthalten, werden erst zum Bestellende übermittelt. <b>Jede Person kann insgesamt nur einmal bestellen!</b></p><p>Finazierungen/Ratenkäufe sind leider nicht möglich.</p>";

		$meldung .= "<h4>Bestellbedingungen</h4>";
		$meldung .= "<p>Die Bestellung kann erst nach Zahlungseingang abgewickelt werden. Gegenüber der Schule können keine Garantie-Ansprüche geltend gemacht werden. Ansprechpartner hierfür ist der Händler bzw. der Herstellersupport. Im Falle der ASUS-Geräte ist das die ixsoft verion-GmbH (www.ixsoft.de - Details zu den ASUS-Geräten können hier eingesehen werden). Die DELL-Geräte werden über die Firma ETES GmbH (www.etes.de) bezogen.</p><p>Das hier gelistete Angebot wurde nach bestem Wissen und Gewissen, mit Sorgfalt und mehrfacher Kontrolle erstellt. Sollten einzelne Daten dennoch falsch von den Händlern übernommen worden sein, so trägt das Risiko der Käufer.</p>";

		$meldung .= "<h4>Datenschutz</h4><p>Für die Abwicklung der Bestellung wird die Schule ermächtigt die hier aufgeführten Bestell- und Kontaktdaten an den jeweiligen zuständigen oben genannten Händler weiterzugeben.</p>";

		$meldung .= "<h4>Zahlung</h4><p>Der in der Zusammenfassung genannte Kaufpreis ist an folgendes Treuhandkonto (Zugriff durch Herrn Wagner) zu überweisen:</p><p>IBAN: DE31 6025 0010 0015 1457 61<br>BIC: SOLADES1WBN<br>Verwendungszweck: «Bestellnummer» - «Name des Bestellers»</p><p>Eine finale Rechnung wird vom zuständigen Händer ausgestellt. Sollten genannte Beträge von der tatsächlichen Summe abweichen wird der Differenzbetrag nachgefordert oder erstattet. Einer Nachforderung muss nachgekommen werden, eine Erstattung erfolgt schnellstmöglich.</p>";

		$meldung = cms_meldung("info", $meldung);
		$code .= "<tr id=\"cms_ebestellung_bedingung\"><th>Bedingungen:</th><td colspan=\"2\">$meldung</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_bedingung_akzept\"><th>Bedingungen akezptiert:</th><td colspan=\"2\">".cms_generiere_schieber("bedingungen", 0)." Bedingungen gelesen, verstanden und akzeptiert</td></tr>";
	$code  .= "</table>";
	$code .= "</div>";


	$code .= "<h2>Zusammenfassung</h2>";
	$code .= "<p><b>Bestellnummer:</b> $CMS_BENUTZERID</p>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<tr><th>Artikel</th><th style=\"text-align: right\">Menge</th><th style=\"text-align: right\">Einzelpreis</th><th style=\"text-align: right\">Summe</th></tr>";
	$code .= "<tbody id=\"cms_bestellzusammenfassung\">";
	$code .= "</tbody></table>";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_ebestellung_speichern()\" id=\"cms_ebestellung_speichern\">Zahlungspflichtig bestellen</span> <span class=\"cms_button\" onclick=\"cms_ebestellung_aktualisieren()\">Verfügbarkeit prüfen</span> <a class=\"cms_button cms_button_nein\" href=\"Schulhof/Nutzerkonto\">Abbrechen</a></p>";

	$code .= "<script>cms_ebestellung_aktualisieren();</script>";
}


// Aktuelle Besetllung ausgeben
$code .= "<div class=\"cms_meldung\" style=\"margin-top: 50px;\"><h2>Aktuelle Bestellung</h2>";
// Bestellung laden, falls vorhanden
$sql = $dbs->prepare("SELECT COUNT(*), bedarf, status FROM ebestellung WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($anzahl, $bedarf, $status);
	$sql->fetch();
}
$sql->close();

if ($anzahl == 0) {
	$code .= "<p>Es wurde nicht bestellt.</p>";
}
else {
	if ($bedarf == '0') {
		$code .= "<p>Es wurde kein Bedarf gemeldet.</p>";
	}
	else {
		$code .= "<p><b>Bestellnummer:</b> $CMS_BENUTZERID</p>";
		if ($status == 0) {$statusmeldung = "Bestellung eingegangen"; if ($bedarf == '1') {$statusmeldung .= " - Bezahlung ausstehend";}}
		else if ($status == 1) {$statusmeldung = "Bezahlt";}
		else if ($status == 2) {$statusmeldung = "Übermittelt";}
		else if ($status == 3) {$statusmeldung = "Geliefert";}
		$code .= "<p><b>Bestellstatus:</b> $statusmeldung</p>";
		$code .= "<table class=\"cms_liste\">";
			$code .= "<tr><th>Artikel</th><th style=\"text-align: right\">Menge</th><th style=\"text-align: right\">Einzelpreis</th><th style=\"text-align: right\">Summe</th></tr>";
			if ($bedarf == '2') {
				$code .= "<tr><td>Leihgerät von der Schule</td><td style=\"text-align: right\">1</td><td style=\"text-align: right\">0,00 €</td><td style=\"text-align: right\">0,00 €</td></tr>";
		    $code .= "<tr><th colspan=\"3\">Gesamt</th><th style=\"text-align: right\">0,00 €</th></tr>";
			}
			else {
				$sql = $dbs->prepare("SELECT * FROM (SELECT eposten.stueck, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, preis FROM eposten JOIN egeraete ON geraet = egeraete.id WHERE bestellung = ?) AS x ORDER BY preis ASC, titel ASC");
				$sql->bind_param("i", $CMS_BENUTZERID);
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
$code .= "</div>";

echo $code;

?>
</div>
