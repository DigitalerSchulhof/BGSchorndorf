<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$bestellende = mktime (23, 59, 59, 5, 6, 2020);
$zahlungsende = mktime (23, 59, 59, 5, 7, 2020);
$bestellnr = date("sH").$CMS_BENUTZERID.date("i");
$namelaptopubuntu = "Inspirion 14 3000 – Linux (Ubuntu)";
$namelaptopwindows = "Inspirion 14 3000 – Windows";
$namekombimittel = "Inspirion 14 5000 M Tablet und Laptop in einem – Windows";
$namekombigut = "Inspirion 14 5000 G Tablet und Laptop in einem – Windows";
$preisleihe = "0,00 €";
$preislaptopubuntu = "300,00 €";
$preislaptopwindows = "300,00 €";
$preiskombimittel = "500,00 €";
$preiskombigut = "700,00 €";
$bildlaptopubuntu = "https://i.dell.com/is/image/DellContent//content/dam/global-site-design/product_images/dell_client_products/notebooks/inspiron_notebooks/14_3482/pdp/notebook-inspiron-14-3482-na-pdp-gallery-504x350.jpg?fmt=jpg&wid=570&hei=400";
$bildlaptopwindows = "https://i.dell.com/is/image/DellContent//content/dam/global-site-design/product_images/dell_client_products/notebooks/inspiron_notebooks/14_3482/pdp/notebook-inspiron-14-3482-na-pdp-gallery-504x350.jpg?fmt=jpg&wid=570&hei=400";
$bildkombimittel = "https://i.dell.com/is/image/DellContent//content/dam/global-site-design/product_images/dell_client_products/notebooks/inspiron_notebooks/14_5490/pdp/notebook-inspiron-2-in-1-5490-14-pdp-gallery-504x350.jpg?fmt=jpg&wid=570&hei=400";
$bildkombigut = "https://i.dell.com/is/image/DellContent//content/dam/global-site-design/product_images/dell_client_products/notebooks/inspiron_notebooks/14_3482/pdp/notebook-inspiron-14-3482-na-pdp-gallery-504x350.jpg?fmt=jpg&wid=570&hei=400";

echo "<h1>Sammelbestellung für Notebooks oder Tablets</h1>";
$teilgenommen = false;
$sql = $dbs->prepare("SELECT COUNT(*), bedarf, leihe, laptopubuntu, laptopwindows, kombimittel, kombigut, AES_DECRYPT(anrede, '$CMS_SCHLUESSEL'), AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(strasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(hausnr, '$CMS_SCHLUESSEL'), AES_DECRYPT(plz, '$CMS_SCHLUESSEL'), AES_DECRYPT(ort, '$CMS_SCHLUESSEL'), AES_DECRYPT(telefon, '$CMS_SCHLUESSEL'), AES_DECRYPT(email, '$CMS_SCHLUESSEL'), bedingungen, AES_DECRYPT(bestellnr, '$CMS_SCHLUESSEL'), eingegangen FROM ebestellung WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($anzahl, $bedarf, $leihe, $laptopubuntu, $laptopwindows, $kombimittel, $kombigut, $anrede, $vorname, $nachname, $strasse, $hausnr, $plz, $ort,  $telefon, $mail, $bedingungen, $bestellnr, $eingegangen);
  $sql->fetch();
}
$sql->close();

if ($leihe === NULL) {$leihe = 1;}
if ($laptopubuntu === NULL) {$laptopubuntu = 0;}
if ($laptopwindows === NULL) {$laptopwindows = 0;}
if ($kombimittel === NULL) {$kombimittel = 0;}
if ($kombigut === NULL) {$kombigut = 0;}
if ($bestellnr === NULL) {$bestellnr = date("sH").$CMS_BENUTZERID.date("i");}

$pleihe = 0.00 * $leihe;
$plaptopubuntu = 300.00 * $laptopubuntu;
$plaptopwindows = 300.00 * $laptopwindows;
$pkombimittel = 500.00 * $kombimittel;
$pkombigut = 700.00 * $kombigut;
$pgesamt = $pleihe + $plaptopubuntu + $plaptopwindows + $pkombimittel + $pkombigut;

$sql = $dbs->prepare("SELECT bedarf FROM ebedarf WHERE id = ?");
$sql->bind_param("i", $CMS_BENUTZERID);
if ($sql->execute()) {
	$sql->bind_result($teilnahme);
  if ($sql->fetch()) {
		if (($teilnahme == 1) || ($teilnahme == 2)) {$teilgenommen = true;}
	}

}
$sql->close();

if (time() <= $bestellende) {
	$meldung = "<h4>Bestellung für Notebooks oder Tablets</h4>";
	$meldung .= "<p>Je höher die Stückzahlen, desto höher wird auch der Mengenrabatt sein. Wir haben uns um qualitätiv hochwertige Geräte im durch die Bedarfsabfrage ermittelten Preisrahmen bemüht. Auch die günstigsten Geräte sind keineswegs Ramsch. Wir können die folgenden Geräte anbieten.</p><p>Alle gemachten Angaben sind freiwillig, werden verschlüsselt gespeichert und vertraulich behandelt! Die Daten werden nach der Auslieferung der Bestellung gelöscht.</p><p><b>Es gibt keine weitere Abfrage! Bitte jetzt bestellen oder Leihbedarf anmelden!</b></p>";
	echo cms_meldung("info", $meldung);

	$code = "<h2>Diese Bestellmöglichkeit endet am ".cms_tagnamekomplett(date("w", $bestellende)).", den ".date("d", $bestellende).". ".cms_monatsnamekomplett(date("m", $bestellende))." ".date("Y", $bestellende)." um ".date("H:i:s", $bestellende)." Uhr</h2>";
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Bedarf:</th><td><select id=\"cms_ebestellung_bedarf\" name=\"cms_ebestellung_bedarf\" onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\">";

		$optionen = "<option value=\"0\">Es besteht kein Bedarf.</option><option value=\"1\">Ich möchte Geräte bestellen.</option><option value=\"2\">Es besteht Bedarf, aber im Moment ist aus finanziellen Gründen keine Anschaffung möglich. Ich will ein Gerät leihen!</option>";
		$code .= str_replace("value=\"$bedarf\"", "value=\"$bedarf\" selected=\"selected\"", $optionen);
		$code .= "</select></td><td></td></tr>";
	$code  .= "</table>";


	$code .= "<div id=\"cms_ebestellung_geraete\" class=\"cms_bestellen_box\" style=\"display:none\">";
	$code .= "<h2>Geräteauswahl</h2>";
	$code .= "<div class=\"cms_blockwahl_box\">";
		$code .= "<div class=\"cms_blockwahl\">";
			$code .= "<h3>$namelaptopubuntu</h3>";
			$code .= "<img src=\"$bildlaptopubuntu\">";
			$code .= "<p class=\"cms_notiz\">Abbildung von dell.com</p>";
			$code .= "<ul>";
				$code .= "<li>Prozessor: Intel® Pentium® Gold-Prozessor 5405U (2 MB Cache, 2,3 GHz)</li>";
				$code .= "<li>Beriebssystem: <b>Ubuntu 18.04</b></li>";
				$code .= "<li>Grafikkarte: Intel UHD-Grafikkarte 620 mit gemeinsamem Grafikspeicher</li>";
				$code .= "<li>Arbeitsspeicher: 4 GB, 4 GB x 1, DDR4-2666MHz</li>";
				$code .= "<li>Festplatte: M.2-PCIe-NVMe-Solid-State-Festplatte, 128 GB</li>";
				$code .= "<li>Bildschirm: 14,0 Zoll, FHD (1920 x 1080), IPS, LED-Hintergrundbeleuchtung, blendfrei, ohne Touchfunktion</li>";
			$code .= "</ul>";
			$code .= "<p>Anzahl: <input onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\" class=\"cms_klein\" id=\"cms_ebestellung_anz_laptopubuntu\" name=\"cms_ebestellung_anz_laptopubuntu\" value=\"$laptopubuntu\" type=\"number\" min=\"0\" step=\"\" max=\"5\"> <span class=\"cms_preis\">$preislaptopubuntu</span></p>";
		$code .= "</div>";

		$code .= "<div class=\"cms_blockwahl\">";
			$code .= "<h3>$namelaptopwindows</h3>";
			$code .= "<img src=\"$bildlaptopwindows\">";
			$code .= "<p class=\"cms_notiz\">Abbildung von dell.com</p>";
			$code .= "<ul>";
				$code .= "<li>Prozessor: Intel® Celeron®-Prozessor 4205U (2 MB Cache, 1,8 GHz)</li>";
				$code .= "<li>Beriebssystem: Windows 10 Home (64 Bit)</li>";
				$code .= "<li>Grafikkarte: Intel UHD-Grafikkarte 620 mit gemeinsamem Grafikspeicher</li>";
				$code .= "<li>Arbeitsspeicher: 4 GB, 4 GB x 1, DDR4-2666MHz</li>";
				$code .= "<li>Festplatte: M.2-PCIe-NVMe-Solid-State-Festplatte, 128 GB</li>";
				$code .= "<li>Bildschirm: 14,0 Zoll, HD (1366 x 768), LED-Hintergrundbeleuchtung, reflexionsarm, ohne Touchfunktion</li>";
			$code .= "</ul>";
			$code .= "<p>Anzahl: <input onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\" class=\"cms_klein\" id=\"cms_ebestellung_anz_laptopwindows\" name=\"cms_ebestellung_anz_laptopwindows\" value=\"$laptopwindows\" type=\"number\" min=\"0\" step=\"\" max=\"5\"> <span class=\"cms_preis\">$preislaptopwindows</span></p>";
		$code .= "</div>";

		$code .= "<div class=\"cms_blockwahl\">";
			$code .= "<h3>$namekombimittel</h3>";
			$code .= "<img src=\"https://i.dell.com/is/image/DellContent//content/dam/global-site-design/product_images/dell_client_products/notebooks/inspiron_notebooks/14_5490/pdp/notebook-inspiron-2-in-1-5490-14-pdp-gallery-504x350.jpg?fmt=jpg&wid=570&hei=400\">";
			$code .= "<p class=\"cms_notiz\">Abbildung von dell.com</p>";
			$code .= "<ul>";
				$code .= "<li>Prozessor: Intel® Core™ i3-10110U Prozessor der 10. Generation (4MB Cache, bis zu 4,1 GHz)</li>";
				$code .= "<li>Beriebssystem: Windows 10 Home (64 Bit)</li>";
				$code .= "<li>Grafikkarte: Intel® UHD-Grafik 620 mit gemeinsam genutztem Grafikspeicher</li>";
				$code .= "<li>Arbeitsspeicher: 4 GB, 1 x 4 GB, DDR4, 2.666 MHz</li>";
				$code .= "<li>Festplatte: M.2-PCIe-NVMe-Solid-State-Festplatte, 256GB</li>";
				$code .= "<li>Bildschirm: Touch-Display, 35,6 cm, FHD (1.920 x 1.080), LED-Hintergrundbeleuchtung, IPS, kompatibel mit Stift</li>";
			$code .= "</ul>";
			$code .= "<p>Anzahl: <input onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\" class=\"cms_klein\" id=\"cms_ebestellung_anz_kombimittel\" name=\"cms_ebestellung_anz_kombimittel\" value=\"$kombimittel\" type=\"number\" min=\"0\" step=\"\" max=\"5\"> <span class=\"cms_preis\">$preiskombimittel</span></p>";
		$code .= "</div>";

		$code .= "<div class=\"cms_blockwahl\">";
			$code .= "<h3>$namekombigut</h3>";
			$code .= "<img src=\"$bildkombimittel\">";
			$code .= "<p class=\"cms_notiz\">Abbildung von dell.com</p>";
			$code .= "<ul>";
				$code .= "<li>Prozessor: Intel® Core™ i5-10210U Prozessor der 10. Generation (6 MB Cache, bis zu 4,2 GHz)</li>";
				$code .= "<li>Beriebssystem: Windows 10 Home (64 Bit)</li>";
				$code .= "<li>Grafikkarte: Intel® UHD-Grafik 620 mit gemeinsam genutztem Grafikspeicher</li>";
				$code .= "<li>Arbeitsspeicher: 8 GB, 8Gx1, DDR4, 2.666 MHz</li>";
				$code .= "<li>Festplatte: M.2-PCIe-NVMe-SSD-Festplatte, 512 GB</li>";
				$code .= "<li>Bildschirm: Touch-Display, 35,6 cm, FHD (1.920 x 1.080), LED-Hintergrundbeleuchtung, IPS, kompatibel mit Stift</li>";
			$code .= "</ul>";
			$code .= "<p>Anzahl: <input onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\" class=\"cms_klein\" id=\"cms_ebestellung_anz_kombigut\" name=\"cms_ebestellung_anz_kombigut\" value=\"$kombigut\" type=\"number\" min=\"0\" step=\"\" max=\"5\"> <span class=\"cms_preis\">$preiskombigut</span></p>";
		$code .= "</div>";
	$code .= "</div>";
	$code .= "</div>";


	$code .= "<div id=\"cms_ebestellung_leihe\" class=\"cms_bestellen_box\" style=\"display:none\">";
	$code .= "<h2>Leihgeräte</h2>";
	$code .= cms_meldung("warnung", "<h4>Leihgeräte nur dann, wenn wirklich nötig!</h4><p>Die Schule kann nur in begrenztem Umfang Leihgeräte bereitstellen. Daher sollte diese Option wirklich nur dann gewählt werden, wenn eine eigene Anschaffung nicht möglich ist.</p><p>Eine Bedarfsprüfung von Seiten der Schule findet nicht statt, dennoch sollte dieses Angebot nur von Familien genutzt werden, die es wirklich benötigen, damit für diese Familien auch genügend Geräte vorhanden sind!</p>");
	$code .= "<div class=\"cms_blockwahl_box\">";
		$code .= "<div class=\"cms_blockwahl\">";
			$code .= "<h3>$namelaptopubuntu</h3>";
			$code .= "<img src=\"$bildlaptopubuntu\">";
			$code .= "<p class=\"cms_notiz\">Abbildung von dell.com</p>";
			$code .= "<ul>";
				$code .= "<li>Prozessor: Intel® Pentium® Gold-Prozessor 5405U (2 MB Cache, 2,3 GHz)</li>";
				$code .= "<li>Beriebssystem: <b>Ubuntu 18.04</b></li>";
				$code .= "<li>Grafikkarte: Intel UHD-Grafikkarte 620 mit gemeinsamem Grafikspeicher</li>";
				$code .= "<li>Arbeitsspeicher: 4 GB, 4 GB x 1, DDR4-2666MHz</li>";
				$code .= "<li>Festplatte: M.2-PCIe-NVMe-Solid-State-Festplatte, 128 GB</li>";
				$code .= "<li>Bildschirm: 14,0 Zoll, FHD (1920 x 1080), IPS, LED-Hintergrundbeleuchtung, blendfrei, ohne Touchfunktion</li>";
			$code .= "</ul>";
			$code .= "<p>Anzahl: <input onchange=\"cms_ebestellung_aktualisieren()\" onkeyup=\"cms_ebestellung_aktualisieren()\" class=\"cms_klein\" id=\"cms_ebestellung_anz_leiheubuntu\" name=\"cms_ebestellung_anz_leiheubuntu\" value=\"$leihe\" type=\"number\" min=\"0\" step=\"\" max=\"1\"> <span class=\"cms_preis\">$preisleihe</span></p>";
		$code .= "</div>";
	$code .= "</div>";
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

		$meldung = cms_meldung("info", "<p>Die einzige akzeptierte <b>Zahlungsmethode</b> ist die Vorkasse. Bitte überweisen Sie den fälligen Betrag unter der Nennung der Bestellnummer und des Familiennamens auf das folgende Konto:</p><p><b>Begünstigter: Burg-Gymnasium Schorndorf - Sammelbestellung</b></p><p><b>Verwendungszweck: Bestellnr $bestellnr - »FAMILIENNAME«</b></p><p><b>IBAN: DE31 6025 0010 0015 1457 61</b></p><p><b>BIC: SOLADES1WBN</b></p><p>Bei der Bankverbindung handelt es sich um ein Treuhandkonto von Herrn Wagner das ursprünglich für Klassenfahrten eingerichtet wurde.</p><p>Die Bestellung gilt als <b>storniert</b>, wenn bis <b>".cms_tagnamekomplett(date("w", $zahlungsende)).", den ".date("d", $zahlungsende).". ".cms_monatsnamekomplett(date("m", $zahlungsende))." ".date("Y", $zahlungsende)." um ".date("H:i:s", $zahlungsende)." Uhr</b> keine Zahlung eingeganen ist. Sobald die Bestellung aufgegeben ist, sind <b>keine Nachbestellungen</b> mehr möglich.</p><p>Im Falle weiterer Vergünstigungen aufgrund eines höheren Auftragsvolumens als hochgerechnet findet <b>eine umgehende Rückerstattung des überschüssigen Betrages</b> statt.</p>");
		$code .= "<tr id=\"cms_ebestellung_bedingung\"><th>Bestellbedinungen:</th><td colspan=\"2\">$meldung".cms_generiere_input("cms_ebestellung_bestellnr", $bestellnr, "hidden")."</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_bedingung_akzept\"><th>Bedingungen akezptiert:</th><td colspan=\"2\">".cms_generiere_schieber("bedingungen", 0)." Bedingungen gelesen, verstanden und akzeptiert</td></tr>";
	$code  .= "</table>";
	$code .= "</div>";


	$code .= "<h2>Zusammenfassung</h2>";
	$code .= "<p><b>Bestellnummer:</b> $bestellnr</p>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<tr><th>Artikel</th><th style=\"text-align: right\">Menge</th><th style=\"text-align: right\">Einzelpreis</th><th style=\"text-align: right\">Summe</th></tr>";
		$code .= "<tr id=\"cms_ebestellung_kauf_ulap\" style=\"display:none\"><td>$namelaptopubuntu</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_ulap\" style=\"display:none\">$laptopubuntu</td><td style=\"text-align: right\">$preislaptopubuntu</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_ulap\">".cms_format_preis($plaptopubuntu)." €</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_kauf_wlap\" style=\"display:none\"><td>$namelaptopwindows</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_wlap\">$laptopwindows</td><td style=\"text-align: right\">$preislaptopwindows</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_wlap\">".cms_format_preis($plaptopwindows)." €</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_kauf_mkombi\" style=\"display:none\"><td>$namekombimittel</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_mkombi\">$kombimittel</td><td style=\"text-align: right\">$preiskombimittel</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_mkombi\">".cms_format_preis($pkombimittel)." €</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_kauf_gkombi\" style=\"display:none\"><td>$namekombigut</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_gkombi\">$kombigut</td><td style=\"text-align: right\">$preiskombigut</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_gkombi\">".cms_format_preis($pkombigut)." €</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_leihe_ulap\" style=\"display:none\"><td>Leihe: $namelaptopubuntu</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_leihe\">$leihe</td><td style=\"text-align: right\">$preisleihe</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_leihe\">".cms_format_preis($pleihe)." €</td></tr>";
		$code .= "<tr id=\"cms_ebestellung_kein\"><td colspan=\"4\">Es besteht kein Bedarf</td></tr>";
		$code .= "<tr><th colspan=\"3\">Gesamt:</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe\">".cms_format_preis($pgesamt)." €</td></tr>";
	$code .= "</table>";
	$code .= "<p><span class=\"cms_button\" onclick=\"cms_ebestellung_speichern()\" id=\"cms_ebestellung_speichern\">Zahlungspflichtig bestellen</span> <a class=\"cms_button cms_button_nein\" href=\"Schulhof/Nutzerkonto\">Abbrechen</a></p>";

	$code .= "<script>cms_ebestellung_aktualisieren();</script>";
}
else {
	$code = "<h2>Folgende Bestellung wurde abgegeben...</h2>";
	$code .= "<p><b>Bestellnummer:</b> $bestellnr</p>";
	$code .= "<p><b>Datum:</b> ".cms_tagnamekomplett(date("w", $eingegangen)).", den ".date("d", $eingegangen).". ".cms_monatsnamekomplett(date("m", $eingegangen))." ".date("Y", $eingegangen)." um ".date("H:i", $eingegangen)." Uhr</p>";
	$code .= "<table class=\"cms_liste\">";
		$code .= "<tr><th>Artikel</th><th style=\"text-align: right\">Menge</th><th style=\"text-align: right\">Einzelpreis</th><th style=\"text-align: right\">Summe</th></tr>";
	if ($bedarf == 1) {
		if ($laptopubuntu > 0) {
			$code .= "<tr id=\"cms_ebestellung_kauf_ulap\"><td>$namelaptopubuntu</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_ulap\" style=\"display:none\">$laptopubuntu</td><td style=\"text-align: right\">$preislaptopubuntu</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_ulap\">".cms_format_preis($plaptopubuntu)." €</td></tr>";
		}
		if ($laptopwindows > 0) {
			$code .= "<tr id=\"cms_ebestellung_kauf_wlap\"><td>$namelaptopwindows</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_wlap\">$laptopwindows</td><td style=\"text-align: right\">$preislaptopwindows</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_wlap\">".cms_format_preis($plaptopwindows)." €</td></tr>";
		}
		if ($kombimittel > 0) {
			$code .= "<tr id=\"cms_ebestellung_kauf_mkombi\"><td>$namekombimittel</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_mkombi\">$kombimittel</td><td style=\"text-align: right\">$preiskombimittel</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_mkombi\">".cms_format_preis($pkombimittel)." €</td></tr>";
		}
		if ($kombigut > 0) {
			$code .= "<tr id=\"cms_ebestellung_kauf_gkombi\"><td>$namekombigut</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_gkombi\">$kombigut</td><td style=\"text-align: right\">$preiskombigut</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_gkombi\">".cms_format_preis($pkombigut)." €</td></tr>";
		}
		$meldung = "<p>Die einzige akzeptierte <b>Zahlungsmethode</b> ist die Vorkasse. Bitte überweisen Sie den fälligen Betrag unter der Nennung der Bestellnummer und des Familiennamens auf das folgende Konto:</p><p><b>Begünstigter: Burg-Gymnasium Schorndorf - Sammelbestellung</b></p><p><b>Verwendungszweck: Bestellnr $bestellnr - »FAMILIENNAME«</b></p><p><b>IBAN: DE31 6025 0010 0015 1457 61</b></p><p><b>BIC: SOLADES1WBN</b></p><p>Bei der Bankverbindung handelt es sich um ein Treuhandkonto von Herrn Wagner das ursprünglich für Klassenfahrten eingerichtet wurde.</p><p>Die Bestellung gilt als <b>storniert</b>, wenn bis <b>".cms_tagnamekomplett(date("w", $zahlungsende)).", den ".date("d", $zahlungsende).". ".cms_monatsnamekomplett(date("m", $zahlungsende))." ".date("Y", $zahlungsende)." um ".date("H:i:s", $zahlungsende);
		$meldung .= " Uhr</b> keine Zahlung eingeganen ist. Sobald die Bestellung aufgegeben ist, sind <b>keine Nachbestellungen</b> mehr möglich.</p><p>Im Falle weiterer Vergünstigungen aufgrund eines höheren Auftragsvolumens als hochgerechnet findet <b>eine umgehende Rückerstattung des überschüssigen Betrages</b> statt.</p>";
	}
	else if ($bedarf == 2) {
		$code .= "<tr id=\"cms_ebestellung_leihe_ulap\"><td>Leihe: $namelaptopubuntu</td><td style=\"text-align: right\" id=\"cms_ebestellung_menge_leihe\">$leihe</td><td style=\"text-align: right\">$preisleihe</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe_leihe\">".cms_format_preis($pleihe)." €</td></tr>";
		$meldung = "<p>Sobald die Bestellung aufgegeben ist, sind <b>keine Nachbestellungen</b> mehr möglich.</p>";
	}
	else {
		$code .= "<tr id=\"cms_ebestellung_kein\"><td colspan=\"4\">Es besteht kein Bedarf</td></tr>";
		$meldung = "<p>Sobald die Bestellung aufgegeben ist, sind <b>keine Nachbestellungen</b> mehr möglich.</p>";
	}
		$code .= "<tr><th colspan=\"3\">Gesamt:</td><td style=\"text-align: right\" id=\"cms_ebestellung_summe\">".cms_format_preis($pgesamt)." €</td></tr>";
	$code .= "</table>";

	$meldung .= "<p>Nach Auslieferung der Bestellung werden die Bestelldaten im Digitalen Schulhof gelöscht.</p>";
	$code .= cms_meldung("info", "<h4>Die Bestellung kann nicht mehr geändert werden</h4>".$meldung);
}

echo $code;

?>
</div>
