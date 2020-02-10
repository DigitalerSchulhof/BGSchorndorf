<?php
function cms_nachricht_lesen($dbs, $app = "nein") {
	global $CMS_SCHLUESSEL, $CMS_DBP_DB, $CMS_BENUTZERID, $CMS_DBS_DB;

	if ((isset($_SESSION["POSTLESENID"])) && ($_SESSION["POSTLESENMODUS"]) &&
	    (cms_check_ganzzahl($_SESSION["POSTLESENID"], 0))) {
		$modus = $_SESSION["POSTLESENMODUS"];
		$id = $_SESSION["POSTLESENID"];
		$fehler = false;
		$spalten = "";
		if ($modus == 'eingang') {$spalten = ", alle";}

		if(!cms_check_ganzzahl($id, 0)) {
			$code = cms_meldung_fehler();
			$fehler = true;
		}

		$absender = "";
		$anzeigename = "";
		$empfaenger = "";
		$zeit = "";
		$datum = "";
		$betreff = "";
		$nachricht = "";
		$papierkorb = "";
		$gefunden = false;

		if (!$fehler && ($modus == 'eingang') || ($modus == 'ausgang') || ($modus == 'entwurf')) {
			// Nachricht laden
			$db = cms_verbinden('ü');
			$sql = "SELECT absender, empfaenger, zeit, AES_DECRYPT(betreff, '$CMS_SCHLUESSEL') AS betreff, AES_DECRYPT(nachricht, '$CMS_SCHLUESSEL') AS nachricht, AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') AS papierkorb, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel,";
			$sql .= " erstellt$spalten FROM $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON $CMS_DBS_DB.personen.id = $CMS_DBS_DB.nutzerkonten.id WHERE $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID.id = ?";
			$sql = $dbs->prepare($sql);
			$sql->bind_param("i", $id);
			if ($sql->execute()) {
				if ($modus == 'eingang') {$sql->bind_result($absender, $empfaenger, $zeit, $betreff, $nachricht, $papierkorb, $pvor, $pnach, $ptitel, $perstellt, $ealle);}
				else {$sql->bind_result($absender, $empfaenger, $zeit, $betreff, $nachricht, $papierkorb, $pvor, $pnach, $ptitel, $perstellt);}
				if ($sql->fetch()) {
					$gefunden = true;
					$datum = date('d.m.Y H:i', $zeit);
					if ($zeit > $perstellt) {$anzeigename = cms_generiere_anzeigename($pvor, $pnach, $ptitel);}
					else {$anzeigename = "<i>existiert nicht mehr</i>";}
					if ($modus == 'eingang') {$empfaenger = $ealle;}
				}
			}
			$sql->close();
			cms_trennen($db);
		}

		if ($gefunden) {
			// Nachricht ausgeben
			$code = "<p class=\"cms_absender\">";
			$code .= "<b>$anzeigename</b><br>".cms_tagnamekomplett(date('w', $zeit)).", ".date('d', $zeit).". ".cms_monatsnamekomplett(date('m', $zeit))." ".date('Y', $zeit);
			$code .= "<br>".date('H:i', $zeit)." Uhr";
			$code .= "</p>";

			// Empfänger laden
			$empf = "(".str_replace('|', ',', substr($empfaenger, 1)).")";
			$empfaengercode = "";
			if (cms_check_idliste($empf)) {
				$sql = $dbs->prepare("SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id IN $empf");
				if ($sql->execute()) {
					$sql->bind_result($evor, $enach, $etitel, $eerstellt);
					while ($sql->fetch()) {
						if ($zeit > $eerstellt) {$empfaengercode .= ", ".cms_generiere_anzeigename($evor, $enach, $etitel);}
						else {$empfaengercode .= ", "."<i>existiert nicht mehr</i>";}
					}
				}
				$sql->close();
			}



			if (strlen($empfaengercode) > 0) {$empfaengercode = substr($empfaengercode,2);}

			// Aktionen
			$code .= "<p>";
			// Befindet sich die Nachricht derzeit im Papierkorb - ja: endgültig löschen & Zurücklegen - nein: Papierkorb
			if ($modus == 'eingang') {
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_antworten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('antworten', $id, '$modus', '', '-', '-', '$app')\">Antworten</span> ";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_allenantworten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('allenantworten', $id, '$modus', '', '-', '-', '$app')\">Allen antworten</span> ";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_weiterleiten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('weiterleiten', $id, '$modus', '', '-', '-', '$app')\">Weiterleiten</span> ";
			}
			else if ($modus == 'ausgang') {
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_erneutsenden\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('erneut', $id, '$modus', '', '-', '-', '$app')\">Erneut versenden</span> ";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_bearbeiten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', $id, '$modus', '', '-', '-', '$app')\">Bearbeiten</span> ";
			}
			else if ($modus == 'entwurf') {
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_bearbeiten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', $id, '$modus', '', '-', '-', '$app')\">Bearbeiten</span> ";
			}

			if ($papierkorb == "-") {
				$code .= "<span class=\"cms_iconbutton_nein\" id=\"cms_button_postfach_papierkorb\" onclick=\"cms_schulhof_postfach_nachricht_papierkorb_anzeige('$modus', '$betreff', '$datum', $id, '$app')\">Papierkorb</span> ";
			}
			else if ($papierkorb == "1") {
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_zuruecklegen\" onclick=\"cms_schulhof_postfach_nachricht_zuruecklegen('$modus', '$betreff', '$datum', $id, 'app')\">Zurücklegen</span> ";
				$code .= "<span class=\"cms_iconbutton_nein\" id=\"cms_button_postfach_loeschen\" onclick=\"cms_schulhof_postfach_nachricht_loeschen_anzeige('$modus', '$betreff', '$datum', $id, 'app')\">Löschen</span> ";
			}
			$code .= "</p>";
			$code .= "<p class=\"cms_empfaenger\"><b>Empfänger:</b> $empfaengercode</p>";


			// Tags
			$tagzahl = 0;
			// Verwendete Tags
			$code .= "<p>";
			$dbp = cms_verbinden('p');
			$sql = $dbp->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM posttags_$CMS_BENUTZERID WHERE id IN (SELECT tag AS id FROM postgetagged$modus"."_$CMS_BENUTZERID WHERE nachricht = ?)) AS tags ORDER BY titel ASC;");
			$sql->bind_param("i", $id);
			if ($sql->execute()) {
				$sql->bind_result($tid, $ttit);
				while ($sql->fetch()) {
					$code .= "<span class=\"cms_toggle cms_toggle_aktiv\" onclick=\"cms_postfach_nachricht_taggen(0, $tid)\">$ttit</span> ";
					$tagzahl++;
				}
			}
			$sql->close();
			// Nichtverwendete Tags
			$sql = $dbp->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM posttags_$CMS_BENUTZERID WHERE id NOT IN (SELECT tag AS id FROM postgetagged$modus"."_$CMS_BENUTZERID WHERE nachricht = ?)) AS tags ORDER BY titel ASC;");
			$sql->bind_param("i", $id);
			if ($sql->execute()) {
				$sql->bind_result($tid, $ttit);
				while ($sql->fetch()) {
					$code .= "<span class=\"cms_toggle\" onclick=\"cms_postfach_nachricht_taggen(1, $tid)\">$ttit</span> ";
					$tagzahl++;
				}
			}
			$sql->close();
			cms_trennen($dbp);
			$code .= "</p>";

			// Nachricht
			$code .= "<h3>$betreff</h3>";
			if (!preg_match("/^</", $nachricht)) {
				$nachricht = cms_textaustextfeld_anzeigen($nachricht);
				$nachricht = "<p>".$nachricht."</p>";
			}
			$code .= $nachricht;

			// Anhänge
			$anhangcode = "";
			if ($modus == 'entwurf') {$ordner = 'entwuerfe';} else {$ordner = $modus;}
			$pfad = "schulhof/personen/$CMS_BENUTZERID/postfach/$ordner/$id";
			if (file_exists('dateien/'.$pfad)) {
				$verzeichnis = scandir('dateien/'.$pfad);
				for ($i = 2; $i < count($verzeichnis); $i++) {
					$anhangcode .= "<span class=\"cms_button\" onclick=\"cms_herunterladen('s', 'Anhang', '-', '$pfad/".$verzeichnis[$i]."')\">";
					$anhangcode .= "<span class=\"cms_postfach_anhang\">";
					$dateiname = explode(".", $verzeichnis[$i]);
					$icon = cms_dateisystem_icon($dateiname[count($dateiname)-1]);
					$anhangcode .= "<img src=\"res/dateiicons/klein/$icon\"> ".$verzeichnis[$i];
					$groesse = filesize('dateien/'.$pfad.'/'.$verzeichnis[$i]);
					$anhangcode .= " (".cms_groesse_umrechnen($groesse).")";
					$anhangcode .= "</span></span> ";
				}
			}

			if (strlen($anhangcode) > 0) {$code .= "<h3 class=\"cms_anhangtitel\">Anhänge</h3><p>$anhangcode</p>";}

			return $code;
		}
		else {
			if(!$fehler) {
				return cms_meldung_bastler();
			}
		}
	}
	else {
		return cms_meldung_bastler();
	}
}
?>
