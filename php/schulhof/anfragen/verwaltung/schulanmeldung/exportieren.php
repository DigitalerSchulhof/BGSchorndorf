<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Organisation']['Schulanmeldungen exportieren'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;

	if (($gruppe != 'alle') && ($gruppe != 'auf') && ($gruppe != 'aufohne') && ($gruppe != 'aufbili') && ($gruppe != 'abgelehnt')) {$fehler = true;}
	if (!cms_check_titel($klasse)) {$fehler = false;}

	if (!$fehler) {
		$sqlwhere = "";
		if ($gruppe == "auf") {$sqlwhere = " WHERE akzeptiert = AES_ENCRYPT('ja', '$CMS_SCHLUESSEL')";}
		else if ($gruppe == "abgelehnt") {$sqlwhere = " WHERE akzeptiert = AES_ENCRYPT('nein', '$CMS_SCHLUESSEL')";}
		else if ($gruppe == "aufohne") {$sqlwhere = " WHERE akzeptiert = AES_ENCRYPT('ja', '$CMS_SCHLUESSEL') AND kuenftigesprofil = AES_ENCRYPT('keines', '$CMS_SCHLUESSEL')";}
		else if ($gruppe == "aufbili") {$sqlwhere = " WHERE akzeptiert = AES_ENCRYPT('ja', '$CMS_SCHLUESSEL') AND kuenftigesprofil = AES_ENCRYPT('bilingual', '$CMS_SCHLUESSEL')";}

		$dbs = cms_verbinden('s');
		$export = "Klasse;Name;Vorname;Rufname;Geburtstag;Geburtsort;Geburtsland;Geschlecht;Religion;RU;Land;Land2;Strasse;Hausnr;PLZ;Ort;Teilort;Telefon1;Telefon2;Handy1;Handy2;Email1;Muttersprache;Schuleintrittam;Einschulungam;Erz1Name;Erz1Vorname;Erz1Geschlecht;Erz1strasse;Erz1Hausnr;Erz1PLZ;Erz1Ort;Erz1Teilort;Erz1Telefon;Erz1Telefon3;Erz1Handy1;Erz1Email;Erz2Name;Erz2Vorname;Erz2Geschlecht;Erz2strasse;Erz2Hausnr;Erz2PLZ;Erz2Ort;Erz2Teilort;Erz2Telefon;Erz2Telefon3;Erz2Handy1;Erz2Email;Fremdsprache1;Fremdsprache2;Fremdsprache3;Fremdsprache4;Profil1;Profil2;Asylbewerber;Aussiedler;AbgebendeSchule;Ausbildungsbetrieb;Ausbild_beruf_id;VorigeKlasse;Erz1Sorgerecht;Erz1Briefe;Erz2Sorgerecht;Erz2Briefe\n";

		$sql = "SELECT id, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(rufname, '$CMS_SCHLUESSEL') AS rufname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geburtsdatum, '$CMS_SCHLUESSEL') AS geburtsdatum, AES_DECRYPT(geburtsort, '$CMS_SCHLUESSEL') AS geburtsort, AES_DECRYPT(geburtsland, '$CMS_SCHLUESSEL') AS geburtsland, AES_DECRYPT(muttersprache, '$CMS_SCHLUESSEL') AS muttersprache, AES_DECRYPT(verkehrssprache, '$CMS_SCHLUESSEL') AS verkehrssprache, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(religion, '$CMS_SCHLUESSEL') AS religion, AES_DECRYPT(religionsunterricht, '$CMS_SCHLUESSEL') AS religionsunterricht, AES_DECRYPT(staatsangehoerigkeit, '$CMS_SCHLUESSEL') AS staatsangehoerigkeit, AES_DECRYPT(zstaatsangehoerigkeit, '$CMS_SCHLUESSEL') AS zstaatsangehoerigkeit, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy1, '$CMS_SCHLUESSEL') AS handy1, AES_DECRYPT(handy2, '$CMS_SCHLUESSEL') AS handy2, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail, AES_DECRYPT(einschulung, '$CMS_SCHLUESSEL') AS einschulung, AES_DECRYPT(vorigeschule, '$CMS_SCHLUESSEL') AS vorigeschule, AES_DECRYPT(vorigeklasse, '$CMS_SCHLUESSEL') AS vorigeklasse, AES_DECRYPT(kuenftigesprofil, '$CMS_SCHLUESSEL') AS kuenftigesprofil, AES_DECRYPT(akzeptiert, '$CMS_SCHLUESSEL') AS akzeptiert FROM voranmeldung_schueler$sqlwhere";
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$sakzeptiert = $daten['akzeptiert'];
				if ($sakzeptiert == 'ja') {$sakzeptiert = 1;} else {$sakzeptiert = 0;}
				$svorname = str_replace(';', '‚', $daten['vorname']);
				$srufname = str_replace(';', '‚', $daten['rufname']);
				$snachname = str_replace(';', '‚', $daten['nachname']);
				$sgeburtsdatum = str_replace(';', '‚', $daten['geburtsdatum']);
				$sgeburtsort = str_replace(';', '‚', $daten['geburtsort']);
				$sgeburtsland = str_replace(';', '‚', $daten['geburtsland']);
				$smuttersprache = str_replace(';', '‚', $daten['muttersprache']);
				$sverkehrssprache = str_replace(';', '‚', $daten['verkehrssprache']);
				$sgeschlecht = str_replace(';', '‚', $daten['geschlecht']);
				$sreligion = str_replace(';', '‚', $daten['religion']);
				$sreligionsunterricht = str_replace(';', '‚', $daten['religionsunterricht']);
				$sland1 = str_replace(';', '‚', $daten['staatsangehoerigkeit']);
				$sland2 = str_replace(';', '‚', $daten['zstaatsangehoerigkeit']);
				$sstrasse = str_replace(';', '‚', $daten['strasse']);
				$shausnummer = str_replace(';', '‚', $daten['hausnummer']);
				$splz = str_replace(';', '‚', $daten['plz']);
				$sort = str_replace(';', '‚', $daten['ort']);
				$steilort = str_replace(';', '‚', $daten['teilort']);
				$stelefon1 = str_replace(';', '‚', $daten['telefon1']);
				$stelefon2 = str_replace(';', '‚', $daten['telefon2']);
				$shandy1 = str_replace(';', '‚', $daten['handy1']);
				$shandy2 = str_replace(';', '‚', $daten['handy2']);
				$smail = str_replace(';', '‚', $daten['mail']);
				$seinschulung = str_replace(';', '‚', $daten['einschulung']);
				$svorigeschule = str_replace(';', '‚', $daten['vorigeschule']);
				$svorigeklasse = str_replace(';', '‚', $daten['vorigeklasse']);
				$sprofil = str_replace(';', '‚', $daten['kuenftigesprofil']);

				$ansprechpartner2 = 1;
				$ansprechpartner['eins']['vorname'] = "";
				$ansprechpartner['eins']['nachname'] = "";
				$ansprechpartner['eins']['geschlecht'] = "w";
				$ansprechpartner['eins']['sorgerecht'] = 1;
				$ansprechpartner['eins']['briefe'] = 1;
				$ansprechpartner['eins']['strasse'] = "";
				$ansprechpartner['eins']['hausnummer'] = "";
				$ansprechpartner['eins']['plz'] = "";
				$ansprechpartner['eins']['ort'] = "";
				$ansprechpartner['eins']['teilort'] = "";
				$ansprechpartner['eins']['telefon1'] = "";
				$ansprechpartner['eins']['telefon2'] = "";
				$ansprechpartner['eins']['handy'] = "";
				$ansprechpartner['eins']['mail'] = "";
				$ansprechpartner['zwei']['vorname'] = "";
				$ansprechpartner['zwei']['nachname'] = "";
				$ansprechpartner['zwei']['geschlecht'] = "m";
				$ansprechpartner['zwei']['sorgerecht'] = 1;
				$ansprechpartner['zwei']['briefe'] = 1;
				$ansprechpartner['zwei']['strasse'] = "";
				$ansprechpartner['zwei']['hausnummer'] = "";
				$ansprechpartner['zwei']['plz'] = "";
				$ansprechpartner['zwei']['ort'] = "";
				$ansprechpartner['zwei']['teilort'] = "";
				$ansprechpartner['zwei']['telefon1'] = "";
				$ansprechpartner['zwei']['telefon2'] = "";
				$ansprechpartner['zwei']['handy'] = "";
				$ansprechpartner['zwei']['mail'] = "";

				$sql2 = "SELECT id, AES_DECRYPT(nummer, '$CMS_SCHLUESSEL') AS nummer, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(sorgerecht, '$CMS_SCHLUESSEL') AS sorgerecht, AES_DECRYPT(briefe, '$CMS_SCHLUESSEL') AS briefe, AES_DECRYPT(strasse, '$CMS_SCHLUESSEL') AS strasse, AES_DECRYPT(hausnummer, '$CMS_SCHLUESSEL') AS hausnummer, AES_DECRYPT(plz, '$CMS_SCHLUESSEL') AS plz, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, AES_DECRYPT(teilort, '$CMS_SCHLUESSEL') AS teilort, AES_DECRYPT(telefon1, '$CMS_SCHLUESSEL') AS telefon1, AES_DECRYPT(telefon2, '$CMS_SCHLUESSEL') AS telefon2, AES_DECRYPT(handy, '$CMS_SCHLUESSEL') AS handy, AES_DECRYPT(mail, '$CMS_SCHLUESSEL') AS mail FROM voranmeldung_eltern WHERE schueler = ".$daten['id'];
				if ($anfrage2 = $dbs->query($sql2)) {
					while ($daten2 = $anfrage2->fetch_assoc()) {
						if ($daten2['nummer'] == 'zwei') {$ansprechpartner2 = 1;}
						$ansprechpartner[$daten2['nummer']]['vorname'] = str_replace(';', '‚', $daten2['vorname']);
						$ansprechpartner[$daten2['nummer']]['nachname'] = str_replace(';', '‚', $daten2['nachname']);
						$ansprechpartner[$daten2['nummer']]['geschlecht'] = str_replace(';', '‚', $daten2['geschlecht']);
						$ansprechpartner[$daten2['nummer']]['sorgerecht'] = str_replace(';', '‚', $daten2['sorgerecht']);
						$ansprechpartner[$daten2['nummer']]['briefe'] = str_replace(';', '‚', $daten2['briefe']);
						$ansprechpartner[$daten2['nummer']]['strasse'] = str_replace(';', '‚', $daten2['strasse']);
						$ansprechpartner[$daten2['nummer']]['hausnummer'] = str_replace(';', '‚', $daten2['hausnummer']);
						$ansprechpartner[$daten2['nummer']]['plz'] = str_replace(';', '‚', $daten2['plz']);
						$ansprechpartner[$daten2['nummer']]['ort'] = str_replace(';', '‚', $daten2['ort']);
						$ansprechpartner[$daten2['nummer']]['teilort'] = str_replace(';', '‚', $daten2['teilort']);
						$ansprechpartner[$daten2['nummer']]['telefon1'] = str_replace(';', '‚', $daten2['telefon1']);
						$ansprechpartner[$daten2['nummer']]['telefon2'] = str_replace(';', '‚', $daten2['telefon2']);
						$ansprechpartner[$daten2['nummer']]['handy'] = str_replace(';', '‚', $daten2['handy']);
						$ansprechpartner[$daten2['nummer']]['mail'] = str_replace(';', '‚', $daten2['mail']);
					}
					$anfrage2->free();
				}

				$export .= $klasse.';';
				$export .= $snachname.';';
				$export .= $svorname.';';
				$export .= $srufname.';';
				$export .= date('d.m.Y',$sgeburtsdatum).';';
				$export .= $sgeburtsort.';';
				$export .= $sgeburtsland.';';
				$export .= $sgeschlecht.';';
				$export .= $sreligion.';';
				$export .= $sreligionsunterricht.';';
				$export .= $sland1.';';
				$export .= $sland2.';';
				$export .= $sstrasse.';';
				$export .= $shausnummer.';';
				$export .= $splz.';';
				$export .= $sort.';';
				$export .= $steilort.';';
				$export .= $stelefon1.';';
				$export .= $stelefon2.';';
				$export .= $shandy1.';';
				$export .= $shandy2.';';
				$export .= $smail.';';
				$export .= $smuttersprache.';';
				$export .= date('d.m.Y').';'; // Schuleintritt
				$export .= date('d.m.Y', $seinschulung).';';
				$export .= $ansprechpartner['eins']['nachname'].';';
				$export .= $ansprechpartner['eins']['vorname'].';';
				$export .= $ansprechpartner['eins']['geschlecht'].';';
				$export .= $ansprechpartner['eins']['strasse'].';';
				$export .= $ansprechpartner['eins']['hausnummer'].';';
				$export .= $ansprechpartner['eins']['plz'].';';
				$export .= $ansprechpartner['eins']['ort'].';';
				$export .= $ansprechpartner['eins']['teilort'].';';
				$export .= $ansprechpartner['eins']['telefon1'].';';
				$export .= $ansprechpartner['eins']['telefon2'].';';
				$export .= $ansprechpartner['eins']['handy'].';';
				$export .= $ansprechpartner['eins']['mail'].';';
				if ($ansprechpartner2 == 1) {
					$export .= $ansprechpartner['zwei']['nachname'].';';
					$export .= $ansprechpartner['zwei']['vorname'].';';
					$export .= $ansprechpartner['zwei']['geschlecht'].';';
					$export .= $ansprechpartner['zwei']['strasse'].';';
					$export .= $ansprechpartner['zwei']['hausnummer'].';';
					$export .= $ansprechpartner['zwei']['plz'].';';
					$export .= $ansprechpartner['zwei']['ort'].';';
					$export .= $ansprechpartner['zwei']['teilort'].';';
					$export .= $ansprechpartner['zwei']['telefon1'].';';
					$export .= $ansprechpartner['zwei']['telefon2'].';';
					$export .= $ansprechpartner['zwei']['handy'].';';
					$export .= $ansprechpartner['zwei']['mail'].';';
				}
				else {
					$export .= ';;;;;;;;;;;;';
				}
				$export .= ';'; // Fremdsprache 1
				$export .= ';'; // Fremdsprache 2
				$export .= ';'; // Fremdsprache 3
				$export .= ';'; // Fremdsprache 4
				$export .= $sprofil.';';
				$export .= ';'; // Profil 2
				$export .= ';'; // Asylbewerber
				$export .= ';'; // Aussiedler
				$export .= $svorigeschule.';';
				$export .= ';'; // Ausbildungsbetrieb
				$export .= ';'; // Ausbild_beruf_id
				$export .= $svorigeklasse.';';
				$export .= $ansprechpartner['eins']['sorgerecht'].';';
				$export .= $ansprechpartner['eins']['briefe'].';';
				if ($ansprechpartner2 == 1) {
					$export .= $ansprechpartner['zwei']['sorgerecht'].';';
					$export .= $ansprechpartner['zwei']['briefe'];
				}
				else {
					$export .= ';';
				}
				$export .= "\n";
			}
			$anfrage->free();
		}
		echo $export;
		cms_trennen($dbs);
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
