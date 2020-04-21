<?php
if (isset($_SESSION['ANMELDUNG BEARBEITEN'])) {
	include_once('php/website/seiten/schulanmeldung/navigation.php');
	$id = $_SESSION['ANMELDUNG BEARBEITEN'];

	if (cms_check_ganzzahl($id, 0) || ($id == 'alle')) {
		$druckfehler = false;
		$jetzt = time();

		$anmeldeids = array();
		if (cms_check_ganzzahl($id, 0)) {array_push($anmeldeids,$id);}
		else {
			$sql = $dbs->prepare("SELECT id FROM (SELECT id, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname FROM voranmeldung_schueler) AS x ORDER BY nachname ASC, vorname ASC");
			if ($sql->execute()) {
				$sql->bind_result($id);
				while ($sql->fetch()) {
					array_push($anmeldeids,$id);
				}
			}
		}
		$nr = 0;
		foreach ($anmeldeids AS $id) {
			if ($nr > 0) {
				$code .= "</div>";
				$code .= "<div class=\"cms_seitenumbruch\"></div>";
				echo $code;

				$code = "<div class=\"cms_druckseite\">";
				$code .= cms_druckkopf();
			}
			$nr++;

			$code .= "<h1>Anmeldung am $CMS_SCHULE</h1>";
			$code .= "<p class=\"cms_datum\">".cms_tagnamekomplett(date('w', $jetzt)).", den ".date('d', $jetzt).". ".cms_monatsnamekomplett(date('m', $jetzt))." ".date('Y', $jetzt)."</p>";

			// DATENSÄTZE LADEN
			$ansprechpartner2 = 0;
			$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(rufname, '$CMS_SCHLUESSEL') AS rufname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geburtsdatum, '$CMS_SCHLUESSEL') AS geburtsdatum, AES_DECRYPT(geburtsort, '$CMS_SCHLUESSEL') AS geburtsort, AES_DECRYPT(geburtsland, '$CMS_SCHLUESSEL') AS geburtsland, AES_DECRYPT(muttersprache, '$CMS_SCHLUESSEL') AS muttersprache, AES_DECRYPT(verkehrssprache, '$CMS_SCHLUESSEL') AS verkehrssprache, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(religion, '$CMS_SCHLUESSEL') AS religion, AES_DECRYPT(religionsunterricht, '$CMS_SCHLUESSEL') AS religionsunterricht, AES_DECRYPT(staatsangehoerigkeit, '$CMS_SCHLUESSEL') AS staatsangehoerigkeit, AES_DECRYPT(zstaatsangehoerigkeit, '$CMS_SCHLUESSEL') AS zstaatsangehoerigkeit, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(staat, '$CMS_SCHLUESSEL') AS staat, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy1, '$CMS_SCHLUESSEL') AS handy1, AES_DECRYPT(handy2, '$CMS_SCHLUESSEL') AS handy2, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail, AES_DECRYPT(einschulung, '$CMS_SCHLUESSEL') AS einschulung, AES_DECRYPT(vorigeschule, '$CMS_SCHLUESSEL') AS vorigeschule, AES_DECRYPT(vorigeklasse, '$CMS_SCHLUESSEL') AS vorigeklasse, AES_DECRYPT(kuenftigesprofil, '$CMS_SCHLUESSEL') AS kuenftigesprofil, AES_DECRYPT(geimpft, '$CMS_SCHLUESSEL'), AES_DECRYPT(akzeptiert, '$CMS_SCHLUESSEL') AS akzeptiert FROM voranmeldung_schueler WHERE id = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $id);
			if ($sql->execute()) {
				$sql->bind_result($svorname, $srufname, $snachname, $sgeburtsdatum, $sgeburtsort, $sgeburtsland, $smuttersprache, $sverkehrssprache, $sgeschlecht, $sreligion, $sreligionsunterricht, $sland1, $sland2, $sstrasse, $shausnummer, $splz, $sort, $sstaat, $steilort, $stelefon1, $stelefon2, $shandy1, $shandy2, $smail, $seinschulung, $svorigeschule, $svorigeklasse, $sprofil, $simpfung, $sakzeptiert);
				if ($sql->fetch()) {
					if ($sakzeptiert == 'ja') {$sakzeptiert = 1;} else {$sakzeptiert = 0;}
					$geburtsdatumgeladen = true;
					$einschulunggeladen = true;
				}
				$sql->close();
			}

			$sql = "SELECT id, AES_DECRYPT(nummer, '$CMS_SCHLUESSEL') AS nummer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(sorgerecht, '$CMS_SCHLUESSEL') AS sorgerecht, AES_DECRYPT(briefe, '$CMS_SCHLUESSEL') AS briefe, AES_DECRYPT(haupt, '$CMS_SCHLUESSEL') AS haupt, AES_DECRYPT(rolle, '$CMS_SCHLUESSEL') AS rolle, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy, '$CMS_SCHLUESSEL') AS handy, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail FROM voranmeldung_eltern WHERE schueler = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $id);
			if ($sql->execute()) {
				$sql->bind_result($aid, $anummer, $avorname, $anachname, $ageschlecht, $asorgerecht, $abriefe, $ahaupt, $arolle, $astrasse, $ahausnummer, $aplz, $aort, $ateilort, $atelefon1, $atelefon2, $ahandy, $amail);
				while ($sql->fetch()) {
					if ($anummer == 'zwei') {$ansprechpartner2 = 1;}
					$ansprechpartner[$anummer]['vorname'] = $avorname;
					$ansprechpartner[$anummer]['nachname'] = $anachname;
					$ansprechpartner[$anummer]['geschlecht'] = $ageschlecht;
					$ansprechpartner[$anummer]['sorgerecht'] = $asorgerecht;
					$ansprechpartner[$anummer]['briefe'] = $abriefe;
					$ansprechpartner[$anummer]['haupt'] = $ahaupt;
					$ansprechpartner[$anummer]['rolle'] = $arolle;
					$ansprechpartner[$anummer]['strasse'] = $astrasse;
					$ansprechpartner[$anummer]['hausnummer'] = $ahausnummer;
					$ansprechpartner[$anummer]['plz'] = $aplz;
					$ansprechpartner[$anummer]['ort'] = $aort;
					$ansprechpartner[$anummer]['teilort'] = $ateilort;
					$ansprechpartner[$anummer]['telefon1'] = $atelefon1;
					$ansprechpartner[$anummer]['telefon2'] = $atelefon2;
					$ansprechpartner[$anummer]['handy'] = $ahandy;
					$ansprechpartner[$anummer]['mail'] = $amail;
				}
				$sql->close();
			}

			$code .= "<div class=\"cms_spalten\"><div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h2>Schülerdaten</h2>";
			$code .= "<table class=\"cms_liste\">";
				$code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Persönliches</th></tr>";
				$code .= "<tr><th>Name</th><td>$svorname $snachname<br><i>$srufname</i></td></tr>";
				$code .= "<tr><th>Geschlecht</th><td>".cms_bezeichnung_finden($sgeschlecht, 'geschlechter')."</td></tr>";
				$code .= "<tr><th>Geburtsdaten</th><td>Geboren am ".date('d.m.Y', $sgeburtsdatum)."<br>in $sgeburtsort (".cms_bezeichnung_finden($sgeburtsland, 'laender').")</td></tr>";
				$code .= "<tr><th>Muttersprache</th><td>".cms_bezeichnung_finden($smuttersprache, 'sprachen')."</td></tr>";
				$code .= "<tr><th>Verkehrssprache</th><td>".cms_bezeichnung_finden($sverkehrssprache, 'sprachen')."</td></tr>";
				$code .= "<tr><th>Staatsangehörigkeit</th><td>".cms_bezeichnung_finden($sland1, 'laender');
				if (strlen($sland2) > 0) {$code .= "<br>".cms_bezeichnung_finden($sland2, 'laender');}
				$code .= "</td></tr>";
				$code .= "<tr><th>Religion</th><td>".cms_bezeichnung_finden($sreligion, 'religionen')."</td></tr>";
				$code .= "<tr><th>Adresse</th><td>$sstrasse $shausnummer<br>$splz $sort";
				if (strlen($steilort) > 0) {$code .= " - $steilort";}
				$code .= "<br>".cms_bezeichnung_finden($sstaat, 'laender');
				$code .= "</td></tr>";
				$code .= "<tr><th>Kontaktmöglichkeiten</th><td>";
				$kontakt = "";
				if (strlen($stelefon1) > 0) {$kontakt .= "<br>Telefon: $stelefon1";}
				if (strlen($stelefon2) > 0) {$kontakt .= "<br>Telefon: $stelefon2";}
				if (strlen($shandy1) > 0) {$kontakt .= "<br>Handy: $shandy1";}
				if (strlen($shandy2) > 0) {$kontakt .= "<br>Handy: $shandy2";}
				if (strlen($smail) > 0) {$kontakt .= "<br>eMail: $smail";}
				$code .= substr($kontakt, 4)."</td></tr>";
				$code .= "<tr><th>Vollständige Masernimpfung</th><td>";
				if ($simpfung == 1) {$code .= "ja";} else {$code .= "nein";}
				$code .= "</td></tr>";
				$code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Alte Schule</th></tr>";
				$code .= "<tr><th>Name</th><td>$svorigeschule</td></tr>";
				$code .= "<tr><th>Klasse</th><td>$svorigeklasse</td></tr>";
				$code .= "<tr><th>Einschulung</th><td>am ".date('d.m.Y', $seinschulung)."</td></tr>";
				$code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Unterricht an der neuen Schule</th></tr>";
				$code .= "<tr><th>Religion</th><td>".cms_bezeichnung_finden($sreligionsunterricht, 'reliunterrichtangebot')."</td></tr>";
				$code .= "<tr><th>Profil</th><td>".cms_bezeichnung_finden($sprofil, 'profile')."</td></tr>";
			$code .= "</table>";
			$code .= "</div></div>";

			$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h2>Erster Ansprechpartner</h2>";
			$code .= "<table class=\"cms_liste\">";
				$code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Persönliches</th></tr>";
				$code .= "<tr><th>Name</th><td>".$ansprechpartner['eins']['vorname']." ".$ansprechpartner['eins']['nachname']."</td></tr>";
				$code .= "<tr><th>Geschlecht</th><td>".cms_bezeichnung_finden($ansprechpartner['eins']['geschlecht'], 'geschlechter')."</td></tr>";
				$code .= "<tr><th>Adresse</th><td>".$ansprechpartner['eins']['strasse']." ".$ansprechpartner['eins']['hausnummer']."<br>".$ansprechpartner['eins']['plz']." ".$ansprechpartner['eins']['ort']."";
				if (strlen($ansprechpartner['eins']['teilort']) > 0) {$code .= " - ".$ansprechpartner['eins']['teilort'];}
				$code .= "</td></tr>";
				$code .= "<tr><th>Rolle</th><td>".cms_bezeichnung_finden($ansprechpartner['eins']['rolle'], 'rollen')."</td></tr>";
				$code .= "<tr><th>Kontaktmöglichkeiten</th><td>";
				$kontakt = "";
				if (strlen($ansprechpartner['eins']['telefon1']) > 0) {$kontakt .= "<br>Telefon: ".$ansprechpartner['eins']['telefon1'];}
				if (strlen($ansprechpartner['eins']['telefon2']) > 0) {$kontakt .= "<br>Telefon: ".$ansprechpartner['eins']['telefon2'];}
				if (strlen($ansprechpartner['eins']['handy']) > 0) {$kontakt .= "<br>Handy: ".$ansprechpartner['eins']['handy'];}
				if (strlen($ansprechpartner['eins']['mail']) > 0) {$kontakt .= "<br>eMail: ".$ansprechpartner['eins']['mail'];}
				$code .= substr($kontakt, 4)."</td></tr>";
				$code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Berechtigungen</th></tr>";
				$code .= "<tr><th>Sorgerecht</th><td>";
					if ($ansprechpartner['eins']['sorgerecht'] == 1) {$code .= "ja";}
					else {$code .= "<b><i>nein</i></b>";}
				$code .= "</td></tr>";
				$code .= "<tr><th>In Briefen einbezogen</th><td>";
					if ($ansprechpartner['eins']['briefe'] == 1) {$code .= "ja";}
					else {$code .= "<b><i>nein</i></b>";}
				$code .= "</td></tr>";
				$code .= "<tr><th>Hauptansprechpartner</th><td>";
					if ($ansprechpartner['eins']['haupt'] == 1) {$code .= "ja";}
					else {$code .= "<b><i>nein</i></b>";}
				$code .= "</td></tr>";
			$code .= "</table>";

			$code .= "<h2>Zweiter Ansprechpartner</h2>";
			if ($ansprechpartner2 == '1') {
				$code .= "<table class=\"cms_liste\">";
					$code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Persönliches</th></tr>";
					$code .= "<tr><th>Name</th><td>".$ansprechpartner['zwei']['vorname']." ".$ansprechpartner['zwei']['nachname']."</td></tr>";
					$code .= "<tr><th>Geschlecht</th><td>".cms_bezeichnung_finden($ansprechpartner['zwei']['geschlecht'], 'geschlechter')."</td></tr>";
					$code .= "<tr><th>Adresse</th><td>".$ansprechpartner['zwei']['strasse']." ".$ansprechpartner['zwei']['hausnummer']."<br>".$ansprechpartner['zwei']['plz']." ".$ansprechpartner['zwei']['ort']."";
					if (strlen($ansprechpartner['zwei']['teilort']) > 0) {$code .= " - ".$ansprechpartner['zwei']['teilort'];}
					$code .= "</td></tr>";
					$code .= "<tr><th>Rolle</th><td>".cms_bezeichnung_finden($ansprechpartner['zwei']['rolle'], 'rollen')."</td></tr>";
					$code .= "<tr><th>Kontaktmöglichkeiten</th><td>";
					$kontakt = "";
					if (strlen($ansprechpartner['zwei']['telefon1']) > 0) {$kontakt .= "<br>Telefon: ".$ansprechpartner['zwei']['telefon1'];}
					if (strlen($ansprechpartner['zwei']['telefon2']) > 0) {$kontakt .= "<br>Telefon: ".$ansprechpartner['zwei']['telefon2'];}
					if (strlen($ansprechpartner['zwei']['handy']) > 0) {$kontakt .= "<br>Handy: ".$ansprechpartner['zwei']['handy'];}
					if (strlen($ansprechpartner['zwei']['mail']) > 0) {$kontakt .= "<br>eMail: ".$ansprechpartner['zwei']['mail'];}
					$code .= substr($kontakt, 4)."</td></tr>";
					$code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Berechtigungen</th></tr>";
					$code .= "<tr><th>Sorgerecht</th><td>";
						if ($ansprechpartner['zwei']['sorgerecht'] == 1) {$code .= "ja";}
						else {$code .= "<b><i>nein</i></b>";}
					$code .= "</td></tr>";
					$code .= "<tr><th>In Briefen einbezogen</th><td>";
						if ($ansprechpartner['zwei']['briefe'] == 1) {$code .= "ja";}
						else {$code .= "<b><i>nein</i></b>";}
					$code .= "</td></tr>";
					$code .= "<tr><th>Hauptansprechpartner</th><td>";
						if ($ansprechpartner['zwei']['haupt'] == 1) {$code .= "ja";}
						else {$code .= "<b><i>nein</i></b>";}
					$code .= "</td></tr>";
				$code .= "</table>";
			}
			else {$code .= "<p>Es wurde kein zweiter Ansprechpartner erfasst.</p>";}
			$code .= "</div></div><div class=\"cms_clear\"></div></div>";

			$code .= "<p>Diese Angaben sind nach bestem Wissen und Gewissen aktuell und sachlich richtig.</p>";

			$code .= "<span class=\"cms_unterschrift\">$CMS_ORT, den ".date('d.m.Y', $jetzt)."</span>";
			$code .= "<p class=\"cms_unterschrift\">Ort, Datum, Unterschrift</p>";
		}
	}
}


?>
