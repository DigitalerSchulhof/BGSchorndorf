<!DOCTYPE html>
<?php
	include_once("php/allgemein/funktionen/sql.php");
	include_once("php/website/seiten/seitenauswertung.php");
	include_once('php/website/seiten/navigationen/navigationen.php');
	include_once("php/schulhof/funktionen/texttrafo.php");
	include_once("php/schulhof/funktionen/config.php");
	include_once("php/schulhof/funktionen/check.php");
	include_once("php/schulhof/funktionen/meldungen.php");
	include_once("php/schulhof/funktionen/generieren.php");
	include_once("php/website/funktionen/datenschutz.php");
	include_once("php/website/funktionen/geraet.php");
	include_once("php/schulhof/funktionen/dateisystem.php");
	session_start();
	$CMS_ANGEMELDET = cms_angemeldet();
	if ($CMS_ANGEMELDET) {

		// Nutzerdaten laden
		$CMS_BENUTZERNAME = $_SESSION['BENUTZERNAME'];
		$CMS_SESSIONID = $_SESSION['SESSIONID'];
		$CMS_SESSIONTIMEOUT = $_SESSION['SESSIONTIMEOUT'];
		$CMS_SESSIONAKTIVITAET = $_SESSION['SESSIONAKTIVITAET'];
		$CMS_BENUTZERUEBERSICHTANZAHL = $_SESSION['BENUTZERUEBERSICHTANZAHL'];
		$CMS_BENUTZERTITEL = $_SESSION['BENUTZERTITEL'];
		$CMS_BENUTZERVORNAME = $_SESSION['BENUTZERVORNAME'];
		$CMS_BENUTZERNACHNAME = $_SESSION['BENUTZERNACHNAME'];
		$CMS_BENUTZERID = $_SESSION['BENUTZERID'];
		$CMS_BENUTZERART = $_SESSION['BENUTZERART'];
		$CMS_BENUTZERSCHULJAHR = $_SESSION['BENUTZERSCHULJAHR'];


		// Timeout verlängern, da der Nutzer aktiv war
		if ($_SESSION['SESSIONTIMEOUT'] > time()) {
			cms_timeout_verlaengern();
		}

		// Rechte des Benutzers laden
		cms_rechte_laden();
	}

	if (isset($_SESSION['IMLN'])) {
		if ($_SESSION['IMLN'] == 1) {
			$CMS_IMLN = true;
		}
		else {
			$CMS_IMLN = false;
		}
	}
	else {
		$CMS_IMLN = false;
	}
?>
<html>
<head>
	<meta charset="utf-8">
	<meta name="format-detection" content="address=no">
	<meta name="format-detection" content="date=no">
	<meta name="format-detection" content="email=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php echo "<link type=\"image/png\" href=\"res/logos/$CMS_FAVICON\" rel=\"shortcut icon\">"; ?>
	<title>Druckansicht – <?php echo $CMS_SCHULE." ".$CMS_ORT;?></title>

	<?php echo "<base href=\"$CMS_BASE\">";
	// <!-- Einbindung der Stylesheets -->
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/fonts.css\">";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/drucken.css\">";
	echo "<script src=\"js/allgemein/check.js\"></script>";
	echo "<script src=\"js/allgemein/anfragen.js\"></script>";
	if ($CMS_ANGEMELDET) {
		echo "<script src=\"js/lehrerzimmer/lehrernetz.js\"></script>";
		echo "<script src=\"js/lehrerzimmer/lehrernetz.js\"></script>";
	}
	?>
	<script><?php
		if ($CMS_ANGEMELDET) {
			if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) {
				echo "var CMS_LN_DA = '".$CMS_LN_DA."';\n";
				if (isset($_SESSION['IMLN'])) {
					if ($_SESSION['IMLN'] == 1) {
						echo "var CMS_IMLN = true;\n";
					}
					else {
						echo "var CMS_IMLN = false;\n";
					}
				}
				else {
					echo "var CMS_IMLN = false;\n";
				}
			}
			echo "var CMS_BENUTZERNAME = '".$_SESSION['BENUTZERNAME']."';\n";
			$iv = substr($CMS_SESSIONID, 0, 16);
			$nutzerid = openssl_encrypt ($CMS_BENUTZERID, 'aes128', $iv, 0, $iv);
			echo "var CMS_BENUTZERID = '".$nutzerid."';\n";
			echo "var CMS_SESSIONID = '".$_SESSION['SESSIONID']."';\n";
			echo "var CMS_SESSIONTIMEOUT = ".$_SESSION['SESSIONTIMEOUT'].";\n";
			echo "var CMS_SESSIONAKTIVITAET = ".$_SESSION['SESSIONAKTIVITAET'].";\n";
			echo "var CMS_BENUTZERTITEL = '".$_SESSION['BENUTZERTITEL']."';\n";
			echo "var CMS_BENUTZERVORNAME = '".$_SESSION['BENUTZERVORNAME']."';\n";
			echo "var CMS_BENUTZERNACHNAME = '".$_SESSION['BENUTZERNACHNAME']."';\n";
			echo "var CMS_BENUTZERART = '".$_SESSION['BENUTZERART']."';\n";
			echo "var CMS_MAX_DATEI = ".$CMS_MAX_DATEI.";\n";
			echo "var CMS_BEARBEITUNGSART = window.setInterval('cms_timeout_aktualisieren()', 30000);\n";
			$CMS_ONLOAD_EVENTS = "cms_timeout_aktualisieren();";
			if ($CMS_IMLN) {
				echo "CMS_IMLN = true;\n";
			}
			echo "var CMS_GRUPPEN = ['Gremien','Fachschaften','Klassen','Kurse','Stufen','Arbeitsgemeinschaften','Arbeitskreise','Fahrten','Wettbewerbe','Ereignisse','Sonstige Gruppen'];";
		}
	?>
	</script>
</head>
<body>
	<div class="cms_druckseite">
		<?php
		$dbs = cms_verbinden('s');
		$fehler = false;
		$code = "<div id=\"cms_druckkopf\">";
			$code .= "<span id=\"cms_logo\">";
				$code .= "<img id=\"cms_logo_bild\" src=\"res/logos/$CMS_LOGODRUCK\">";
				$code .= "<span id=\"cms_logo_schrift\">";
					$code .= "<span id=\"cms_logo_o\">$CMS_SCHULE</span>";
					$code .= "<span id=\"cms_logo_u\">$CMS_ORT</span>";
				$code .= "</span><div class=\"cms_clear\">";
				$code .= "</div>";
			$code .= "</span>";
		$code .= "</div>";

		if (isset($_SESSION['DRUCKANSICHT'])) {
			if (($_SESSION['DRUCKANSICHT'] == 'Schulanmeldung') && ($CMS_ANGEMELDET) && cms_r("schulhof.organisation.schulanmeldung.akzeptieren"))) {
				if (isset($_SESSION['ANMELDUNG BEARBEITEN'])) {
					include_once('php/website/seiten/schulanmeldung/navigation.php');
					$id = $_SESSION['ANMELDUNG BEARBEITEN'];

					if (cms_check_ganzzahl($id, 0)) {
						$jetzt = time();
						$code .= "<p class=\"cms_datum\">".cms_tagnamekomplett(date('w', $jetzt)).", den ".date('d', $jetzt).". ".cms_monatsnamekomplett(date('m', $jetzt))." ".date('Y', $jetzt)."</p>";
						$code .= "<h1>Anmeldung am $CMS_SCHULE</h1>";

						// DATENSÄTZE LADEN
						$ansprechpartner2 = 0;
						$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(rufname, '$CMS_SCHLUESSEL') AS rufname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geburtsdatum, '$CMS_SCHLUESSEL') AS geburtsdatum, AES_DECRYPT(geburtsort, '$CMS_SCHLUESSEL') AS geburtsort, AES_DECRYPT(geburtsland, '$CMS_SCHLUESSEL') AS geburtsland, AES_DECRYPT(muttersprache, '$CMS_SCHLUESSEL') AS muttersprache, AES_DECRYPT(verkehrssprache, '$CMS_SCHLUESSEL') AS verkehrssprache, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(religion, '$CMS_SCHLUESSEL') AS religion, AES_DECRYPT(religionsunterricht, '$CMS_SCHLUESSEL') AS religionsunterricht, AES_DECRYPT(staatsangehoerigkeit, '$CMS_SCHLUESSEL') AS staatsangehoerigkeit, AES_DECRYPT(zstaatsangehoerigkeit, '$CMS_SCHLUESSEL') AS zstaatsangehoerigkeit, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy1, '$CMS_SCHLUESSEL') AS handy1, AES_DECRYPT(handy2, '$CMS_SCHLUESSEL') AS handy2, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail, AES_DECRYPT(einschulung, '$CMS_SCHLUESSEL') AS einschulung, AES_DECRYPT(vorigeschule, '$CMS_SCHLUESSEL') AS vorigeschule, AES_DECRYPT(vorigeklasse, '$CMS_SCHLUESSEL') AS vorigeklasse, AES_DECRYPT(kuenftigesprofil, '$CMS_SCHLUESSEL') AS kuenftigesprofil, AES_DECRYPT(akzeptiert, '$CMS_SCHLUESSEL') AS akzeptiert FROM voranmeldung_schueler WHERE id = ?";
						$sql = $dbs->prepare($sql);
						$sql->bind_param("i", $id);
						if ($sql->execute()) {
							$r = $sql->get_result();
							if ($daten = $r->fetch_array(MYSQLI_NUM)) {
								$sakzeptiert = $daten['akzeptiert'];
								if ($sakzeptiert == 'ja') {$sakzeptiert = 1;} else {$sakzeptiert = 0;}
								$svorname = $daten['vorname'];
								$srufname = $daten['rufname'];
								$snachname = $daten['nachname'];
								$sgeburtsdatum = $daten['geburtsdatum'];
								$sgeburtsort = $daten['geburtsort'];
								$sgeburtsland = $daten['geburtsland'];
								$smuttersprache = $daten['muttersprache'];
								$sverkehrssprache = $daten['verkehrssprache'];
								$sgeschlecht = $daten['geschlecht'];
								$sreligion = $daten['religion'];
								$sreligionsunterricht = $daten['religionsunterricht'];
								$sland1 = $daten['staatsangehoerigkeit'];
								$sland2 = $daten['zstaatsangehoerigkeit'];
								$sstrasse = $daten['strasse'];
								$shausnummer = $daten['hausnummer'];
								$splz = $daten['plz'];
								$sort = $daten['ort'];
								$steilort = $daten['teilort'];
								$stelefon1 = $daten['telefon1'];
								$stelefon2 = $daten['telefon2'];
								$shandy1 = $daten['handy1'];
								$shandy2 = $daten['handy2'];
								$smail = $daten['mail'];
								$seinschulung = $daten['einschulung'];
								$svorigeschule = $daten['vorigeschule'];
								$svorigeklasse = $daten['vorigeklasse'];
								$sprofil = $daten['kuenftigesprofil'];
								$geburtsdatumgeladen = true;
								$einschulunggeladen = true;
							}
							$sql->close();
						}

						$sql = "SELECT id, AES_DECRYPT(nummer, '$CMS_SCHLUESSEL') AS nummer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(sorgerecht, '$CMS_SCHLUESSEL') AS sorgerecht, AES_DECRYPT(briefe, '$CMS_SCHLUESSEL') AS briefe, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy, '$CMS_SCHLUESSEL') AS handy, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail FROM voranmeldung_eltern WHERE schueler = ?";
						$sql = $dbs->prepare($dbs);
						$sql->bind_param("i", $id);
						if ($sql->execute()) {
							$r = $sql->get_result();
							while ($daten = $r->fetch_array(MYSQLI_NUM)) {
								if ($daten['nummer'] == 'zwei') {$ansprechpartner2 = 1;}
								$ansprechpartner[$daten['nummer']]['vorname'] = $daten['vorname'];
								$ansprechpartner[$daten['nummer']]['nachname'] = $daten['nachname'];
								$ansprechpartner[$daten['nummer']]['geschlecht'] = $daten['geschlecht'];
								$ansprechpartner[$daten['nummer']]['sorgerecht'] = $daten['sorgerecht'];
								$ansprechpartner[$daten['nummer']]['briefe'] = $daten['briefe'];
								$ansprechpartner[$daten['nummer']]['strasse'] = $daten['strasse'];
								$ansprechpartner[$daten['nummer']]['hausnummer'] = $daten['hausnummer'];
								$ansprechpartner[$daten['nummer']]['plz'] = $daten['plz'];
								$ansprechpartner[$daten['nummer']]['ort'] = $daten['ort'];
								$ansprechpartner[$daten['nummer']]['teilort'] = $daten['teilort'];
								$ansprechpartner[$daten['nummer']]['telefon1'] = $daten['telefon1'];
								$ansprechpartner[$daten['nummer']]['telefon2'] = $daten['telefon2'];
								$ansprechpartner[$daten['nummer']]['handy'] = $daten['handy'];
								$ansprechpartner[$daten['nummer']]['mail'] = $daten['mail'];
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
					    if (strlen($steilort) > 0) {$code .= "<br>$steilort";}
					    $code .= "</td></tr>";
					    $code .= "<tr><th>Kontaktmöglichkeiten</th><td>";
					    $kontakt = "";
					    if (strlen($stelefon1) > 0) {$kontakt .= "<br>Telefon: $stelefon1";}
					    if (strlen($stelefon2) > 0) {$kontakt .= "<br>Telefon: $stelefon2";}
					    if (strlen($shandy1) > 0) {$kontakt .= "<br>Handy: $shandy1";}
					    if (strlen($shandy2) > 0) {$kontakt .= "<br>Handy: $shandy2";}
					    if (strlen($smail) > 0) {$kontakt .= "<br>eMail: $smail";}
					    $code .= substr($kontakt, 4)."</td></tr>";
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
					    if (strlen($ansprechpartner['eins']['teilort']) > 0) {$code .= "<br>".$ansprechpartner['eins']['teilort'];}
					    $code .= "</td></tr>";
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
					  $code .= "</table>";

					  $code .= "<h2>Zweiter Ansprechpartner</h2>";
					  if ($ansprechpartner2 == '1') {
					    $code .= "<table class=\"cms_liste\">";
					      $code .= "<tr><th colspan=\"2\" class=\"cms_zwischenueberschrift\">Persönliches</th></tr>";
					      $code .= "<tr><th>Name</th><td>".$ansprechpartner['zwei']['vorname']." ".$ansprechpartner['zwei']['nachname']."</td></tr>";
					      $code .= "<tr><th>Geschlecht</th><td>".cms_bezeichnung_finden($ansprechpartner['zwei']['geschlecht'], 'geschlechter')."</td></tr>";
					      $code .= "<tr><th>Adresse</th><td>".$ansprechpartner['zwei']['strasse']." ".$ansprechpartner['zwei']['hausnummer']."<br>".$ansprechpartner['zwei']['plz']." ".$ansprechpartner['zwei']['ort']."";
					      if (strlen($ansprechpartner['zwei']['teilort']) > 0) {$code .= "<br>".$ansprechpartner['zwei']['teilort'];}
					      $code .= "</td></tr>";
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
					    $code .= "</table>";
					  }
					  else {$code .= "<p>Es wurde kein zweiter Ansprechpartner erfasst.</p>";}
					  $code .= "</div></div><div class=\"cms_clear\"></div></div>";

						$code .= "<p>Diese Angaben sind nach bestem Wissen und Gewissen aktuell und sachlich richtig.</p>";

						$code .= "<span class=\"cms_unterschrift\">$CMS_ORT, den ".date('d.m.Y', $jetzt)."</span>";
						$code .= "<p class=\"cms_unterschrift\">Ort, Datum, Unterschrift</p>";
					}
					else {$fehler = true;}
				}
				else {$fehler = true;}
			}
			else if (($_SESSION['DRUCKANSICHT'] == 'Vertretungsplan') && (isset($_SESSION['DRUCKVPLANDATUMV'])) && (isset($_SESSION['DRUCKVPLANDATUMB']))) {
				include_once('php/schulhof/seiten/verwaltung/vertretungsplanung/vplaninternausgeben.php');
				// Kennung laden
				$sql = $dbs->prepare("SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('VPlanL', '$CMS_SCHLUESSEL')");
				if ($sql->execute()) {
				  $sql->bind_result($kennung);
				  $sql->fetch();
				}
				$sql->close();

				$code .= "<h1>Vertretungsplan Lehreransicht</h1>";
				$code .= "<input type=\"hidden\" name=\"cms_lvplan_kennung\" id =\"cms_lvplan_kennung\" value=\"$kennung\">";
				$code .= cms_vertretungsplan_komplettansicht($dbs, 'l', $_SESSION['DRUCKVPLANDATUMV'], $_SESSION['DRUCKVPLANDATUMB'], '1', 'k');

				$code .= "<h1>Vertretungsplan Schüleransicht</h1>";
				$code .= cms_vertretungsplan_komplettansicht($dbs, 's', $_SESSION['DRUCKVPLANDATUMV'], $_SESSION['DRUCKVPLANDATUMB'], '1', 'k');
			}
			else {$fehler = true;}
		}
		else {$fehler = true;}

		if ($fehler) {
			$code .= "<p><i>Es wurde kein Dokument gewählt!</i></p>";
		}

		echo $code;
		cms_trennen($dbs);
		unset($_SESSION['DRUCKANSICHT']);
	?>

	</div>
</body>
</html>
