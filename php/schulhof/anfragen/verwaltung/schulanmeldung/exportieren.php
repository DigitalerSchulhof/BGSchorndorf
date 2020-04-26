<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");

session_start();

if (isset($_POST['gruppe'])) {$gruppe = $_POST['gruppe'];} else {echo "FEHLER"; exit;}
if (isset($_POST['klasse'])) {$klasse = $_POST['klasse'];} else {echo "FEHLER"; exit;}



if (cms_angemeldet() && cms_r("schulhof.organisation.schulanmeldung.exportieren")) {
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

		$anmeldedatum = time();
		$sql = $dbs->prepare("SELECT AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') FROM schulanmeldung WHERE wert = AES_ENCRYPT('Anmeldung persönlich bis', '$CMS_SCHLUESSEL')");
		if ($sql->execute()) {
			$sql->bind_result($anmeldedatum);
			$sql->fetch();
		}
		$sql->close();

		$export = "Klasse;Name;Vorname;Rufname;Geburtsname;Geburtstag;Geburtsort;Geburtsland;Geschlecht;Religion;RU;Land;Land2;Strasse;HausNr;PLZ;Ort;Staat;Teilort;Telefon1;Telefon2;Handy1;Handy2;email1;Muttersprache;Schuleintrittam;Anmeldung am;im Schriftverkehrverteiler;auskunftsberechtigt;Einschulungam;Erz1Name;Erz1Vorname;Erz1Geschlecht;Erz1Strasse;Erz1HausNr;Erz1PLZ;Erz1Ort;Erz1Teilort;Erz1Telefon;Erz1Telefon2;Erz1Handy;Erz1Email;Erz1Schriftverkehrverteiler;Erz1auskunftsberechtigt;Erz1Hauptansprechpartner;Erz1Art;Erz2Name;Erz2Vorname;Erz2Geschlecht;Erz2Strasse;Erz2HausNr;Erz2PLZ;Erz2Ort;Erz2Teilort;Erz2Telefon;Erz2Telefon2;Erz2Handy;Erz2Email;Erz2Schriftverkehrverteiler;Erz2auskunftsberechtigt;Erz2Hauptansprechpartner;Erz2Art;Fremdsprache1;Fremdsprache2;Fremdsprache3;Fremdsprache4;Profil1;Profil1von;Profil1bis;Profil2;Profil2von;Profil2bis;Zugangsart;AbgebendeSchule;Ausbildungsbetrieb;Ausbilder;Ausbild_beruf_id;Vorbildung\n";

		$sql = $dbs->prepare("SELECT s.id, AES_DECRYPT(s.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.rufname, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.geburtsdatum, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.geburtsort, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.geburtsland, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.muttersprache, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.verkehrssprache, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.geschlecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.religion, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.religionsunterricht, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.staatsangehoerigkeit, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.zstaatsangehoerigkeit, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.strasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.hausnummer, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.plz, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.ort, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.staat, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.teilort, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.telefon1, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.telefon2, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.handy1, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.handy2, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.mail, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.einschulung, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.vorigeschule, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.vorigeklasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.kuenftigesprofil, '$CMS_SCHLUESSEL'), AES_DECRYPT(s.akzeptiert, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.nummer, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.geschlecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.sorgerecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.briefe, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.haupt, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.rolle, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.strasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.hausnummer, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.plz, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.ort, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.teilort, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.telefon1, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.telefon2, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.handy, '$CMS_SCHLUESSEL'), AES_DECRYPT(e1.mail, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.nummer, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.geschlecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.sorgerecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.briefe, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.haupt, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.rolle, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.strasse, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.hausnummer, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.plz, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.ort, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.teilort, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.telefon1, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.telefon2, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.handy, '$CMS_SCHLUESSEL'), AES_DECRYPT(e2.mail, '$CMS_SCHLUESSEL') FROM voranmeldung_schueler AS s LEFT JOIN voranmeldung_eltern AS e1 ON s.id = e1.schueler AND e1.nummer = AES_ENCRYPT('eins', '$CMS_SCHLUESSEL') LEFT JOIN voranmeldung_eltern AS e2 ON s.id = e2.schueler AND e2.nummer = AES_ENCRYPT('zwei', '$CMS_SCHLUESSEL') $sqlwhere");

		if ($sql->execute()) {
			$sql->bind_result($sid, $svor, $sruf, $snach, $sgebd, $sgebo, $sgebl, $smutts, $sverks, $sgeschl, $sreli, $sreliu, $sstaats, $szstaats, $sstrasse, $shausnr, $splz, $sort, $sstaat, $steilort, $stel1, $stel2, $shandy1, $shandy2, $smail, $seinsch, $svorigs, $svorigk, $skuenft, $skzept, $e1nummer, $e1vor, $e1nach, $e1geschl, $e1sorge, $e1briefe, $e1haupt, $e1rolle, $e1strasse, $e1hausnr, $e1plz, $e1ort, $e1teilort, $e1tel1, $e1tel2, $e1handy, $e1mail, $e2nummer, $e2vor, $e2nach, $e2geschl, $e2sorge, $e2briefe, $e2haupt, $e2rolle, $e2strasse, $e2hausnr, $e2plz, $e2ort, $e2teilort, $e2tel1, $e2tel2, $e2handy, $e2mail);
			while ($sql->fetch()) {
				$sakzeptiert = $skzept;
				if ($sakzeptiert == 'ja') {$sakzeptiert = 1;} else {$sakzeptiert = 0;}
				$sverkehrssprache = str_replace(';', '‚', $sverks);

				$export .= $klasse.';';
				$export .= str_replace(';', '‚', $snach).';';
				$export .= str_replace(';', '‚', $svor).';';
				$export .= str_replace(';', '‚', $sruf).';;'; // Kein Geburtsname
				$export .= date('d.m.Y',str_replace(';', '‚', $sgebd)).';';
				$export .= str_replace(';', '‚', $sgebo).';';
				$export .= str_replace(';', '‚', $sgebl).';';
				$export .= str_replace(';', '‚', $sgeschl).';';
				$export .= str_replace(';', '‚', $sreli).';';
				$export .= str_replace(';', '‚', $sreliu).';';
				$export .= str_replace(';', '‚', $sstaats).';';
				$export .= str_replace(';', '‚', $szstaats).';';
				$export .= str_replace(';', '‚', $sstrasse).';';
				$export .= str_replace(';', '‚', $shausnr).';';
				$export .= str_replace(';', '‚', $splz).';';
				$export .= str_replace(';', '‚', $sort).';';
				$export .= str_replace(';', '‚', $sstaat).';';
				$export .= str_replace(';', '‚', $steilort).';';
				$export .= str_replace(';', '‚', $stel1).';';
				$export .= str_replace(';', '‚', $stel2).';';
				$export .= str_replace(';', '‚', $shandy1).';';
				$export .= str_replace(';', '‚', $shandy2).';';
				$export .= str_replace(';', '‚', $smail).';';
				$export .= str_replace(';', '‚', $smutts).';';
				$export .= date('d.m.Y').';'; // Schuleintritt
				$export .= date('d.m.Y', str_replace(';', '‚', $seinsch)).';';
				$export .= date('d.m.Y', $anmeldedatum).';'; // Anmeldedatum
				$export .= '0;0;'; // Briefe / Sorgerecht Schüler
				$export .= str_replace(';', '‚', $e1nach).';';
				$export .= str_replace(';', '‚', $e1vor).';';
				$export .= str_replace(';', '‚', $e1geschl).';';
				$export .= str_replace(';', '‚', $e1strasse).';';
				$export .= str_replace(';', '‚', $e1hausnr).';';
				$export .= str_replace(';', '‚', $e1plz).';';
				$export .= str_replace(';', '‚', $e1ort).';';
				$export .= str_replace(';', '‚', $e1teilort).';';
				$export .= str_replace(';', '‚', $e1tel1).';';
				$export .= str_replace(';', '‚', $e1tel2).';';
				$export .= str_replace(';', '‚', $e1handy).';';
				$export .= str_replace(';', '‚', $e1mail).';';
				$export .= str_replace(';', '‚', $e1briefe).';';
				$export .= str_replace(';', '‚', $e1sorge).';';
				$export .= str_replace(';', '‚', $e1haupt).';';
				$export .= str_replace(';', '‚', $e1rolle).';';
				if ($e2nummer !== null) {
					$export .= str_replace(';', '‚', $e2nach).';';
					$export .= str_replace(';', '‚', $e2vor).';';
					$export .= str_replace(';', '‚', $e2geschl).';';
					$export .= str_replace(';', '‚', $e2strasse).';';
					$export .= str_replace(';', '‚', $e2hausnr).';';
					$export .= str_replace(';', '‚', $e2plz).';';
					$export .= str_replace(';', '‚', $e2ort).';';
					$export .= str_replace(';', '‚', $e2teilort).';';
					$export .= str_replace(';', '‚', $e2tel1).';';
					$export .= str_replace(';', '‚', $e2tel2).';';
					$export .= str_replace(';', '‚', $e2handy).';';
					$export .= str_replace(';', '‚', $e2mail).';';
					$export .= str_replace(';', '‚', $e2briefe).';';
					$export .= str_replace(';', '‚', $e2sorge).';';
					$export .= str_replace(';', '‚', $e2haupt).';';
					$export .= str_replace(';', '‚', $e2rolle).';';
				}
				else {
					$export .= ';;;;;;;;;;;;;;;;';
				}
				$export .= ';'; // Fremdsprache 1
				$export .= ';'; // Fremdsprache 2
				$export .= ';'; // Fremdsprache 3
				$export .= ';'; // Fremdsprache 4
				$export .= str_replace(';', '‚', $skuenft).';';
				$export .= ';'; // Profil1von
				$export .= ';'; // Profil1bis
				$export .= ';'; // Profil2
				$export .= ';'; // Profil2von
				$export .= ';'; // Profil2bis
				$export .= ';'; // Zugangsart
				$export .= str_replace(';', '‚', $svorigs).' - '.str_replace(';', '‚', $svorigk).';';
				$export .= ';'; // Ausbildungsbetrieb
				$export .= ';'; // Ausbilder
				$export .= ';'; // Ausbilder_beruf_id
				$export .= ';'; // Vorbildung
				$export .= "\n";
			}
		}
		$sql->close();
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
