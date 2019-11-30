<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Postfach</h1>
</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
// PROFILDATEN LADEN
include_once("php/schulhof/seiten/nutzerkonto/postfach/postnavigation.php");
?>

</div>
</div>

<div class="cms_spalte_34">
<div class="cms_spalte_i">
	<h2>Nachricht lesen</h2>

	<?php
	if ((isset($_SESSION["POSTLESENID"])) && ($_SESSION["POSTLESENMODUS"]) &&
	    (cms_check_ganzzahl($_SESSION["POSTLESENID"], 0))) {
		$modus = $_SESSION["POSTLESENMODUS"];
		$id = $_SESSION["POSTLESENID"];
		$fehler = false;
		$spalten = "";
		if ($modus == 'eingang') {$spalten = ", alle";}

		if(!cms_check_ganzzahl($id, 0)) {
			echo cms_meldung_fehler();
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
			$sql .= " erstellt$spalten FROM $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID JOIN $CMS_DBS_DB.personen ON absender = $CMS_DBS_DB.personen.id LEFT JOIN $CMS_DBS_DB.nutzerkonten ON $CMS_DBS_DB.personen.id = $CMS_DBS_DB.nutzerkonten.id WHERE $CMS_DBP_DB.post$modus"."_$CMS_BENUTZERID.id = $id";
			if ($anfrage = $db->query($sql)) {	// Safe weil ID Check
				if ($daten = $anfrage->fetch_assoc()) {
					$gefunden = true;
					$absender = $daten['absender'];
					$empfaenger = $daten['empfaenger'];
					$zeit = $daten['zeit'];
					$datum = date('d.m.Y H:i', $zeit);
					$betreff = $daten['betreff'];
					$nachricht = $daten['nachricht'];
					$papierkorb = $daten['papierkorb'];
					if ($zeit > $daten['erstellt']) {$anzeigename = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);}
					else {$anzeigename = "<i>existiert nicht mehr</i>";}
					if ($modus == 'eingang') {$empfaenger = $daten['alle'];}
				}
				$anfrage->free();
			}
			cms_trennen($db);
		}

		if ($gefunden) {
			// Nachricht ausgeben
			$code = "<p class=\"cms_absender\">";
			$code .= "<b>$anzeigename</b><br>".cms_tagnamekomplett(date('w', $zeit)).", ".date('d', $zeit).". ".cms_monatsnamekomplett(date('m', $zeit))." ".date('Y', $zeit);
			$code .= "<br>".date('H:i', $zeit)." Uhr";
			$code .= "</p>";

			// Empfänger laden
			$empf = str_replace('|', ',', substr($empfaenger, 1));
			$empfaengercode = "";
			$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, erstellt FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id IN ($empf)";
			if ($anfrage = $dbs->query($sql)) {	// Safe weil ID Check
				while ($daten = $anfrage->fetch_assoc()) {
					if ($zeit > $daten['erstellt']) {$empfaengercode .= ", ".cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);}
					else {$empfaengercode .= ", "."<i>existiert nicht mehr</i>";}
				}
				$anfrage->free();
			}

			if (strlen($empfaengercode) > 0) {$empfaengercode = substr($empfaengercode,2);}

			// Aktionen
			echo "<p>";
			// Befindet sich die Nachricht derzeit im Papierkorb - ja: endgültig löschen & Zurücklegen - nein: Papierkorb
			if ($modus == 'eingang') {
				echo "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_antworten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('antworten', $id, '$modus')\">Antworten</span> ";
				echo "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_allenantworten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('allenantworten', $id, '$modus')\">Allen antworten</span> ";
				echo "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_weiterleiten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('weiterleiten', $id, '$modus')\">Weiterleiten</span> ";
			}
			else if ($modus == 'ausgang') {
				echo "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_erneutsenden\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('erneut', $id, '$modus')\">Erneut versenden</span> ";
				echo "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_bearbeiten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', $id, '$modus')\">Bearbeiten</span> ";
			}
			else if ($modus == 'entwurf') {
				echo "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_bearbeiten\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('bearbeiten', $id, '$modus')\">Bearbeiten</span> ";
			}

			if ($papierkorb == "-") {
				echo "<span class=\"cms_iconbutton_nein\" id=\"cms_button_postfach_papierkorb\" onclick=\"cms_schulhof_postfach_nachricht_papierkorb_anzeige('$modus', '$betreff', '$datum', $id)\">Papierkorb</span> ";
			}
			else if ($papierkorb == "1") {
				echo "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_zuruecklegen\" onclick=\"cms_schulhof_postfach_nachricht_zuruecklegen('$modus', '$betreff', '$datum', $id)\">Zurücklegen</span> ";
				echo "<span class=\"cms_iconbutton_nein\" id=\"cms_button_postfach_loeschen\" onclick=\"cms_schulhof_postfach_nachricht_loeschen_anzeige('$modus', '$betreff', '$datum', $id)\">Löschen</span> ";
			}
			echo "</p>";
			$code .= "<p class=\"cms_empfaenger\"><b>Empfänger:</b> $empfaengercode</p>";


			// Tags
			$tagzahl = 0;
			// Verwendete Tags
			$code .= "<p>";
			$dbp = cms_verbinden('p');
			$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM posttags_$CMS_BENUTZERID WHERE id IN (SELECT tag AS id FROM postgetagged$modus"."_$CMS_BENUTZERID WHERE nachricht = $id)) AS tags ORDER BY titel ASC;";
			if ($anfrage = $dbp->query($sql)) {	// Safe weil ID Check
				while ($daten = $anfrage->fetch_assoc()) {
					$code .= "<span class=\"cms_toggle cms_toggle_aktiv\" onclick=\"cms_postfach_nachricht_taggen(0, ".$daten['id'].")\">".$daten['titel']."</span> ";
					$tagzahl++;
				}
				$anfrage->free();
			}
			// Nichtverwendete Tags
			$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM posttags_$CMS_BENUTZERID WHERE id NOT IN (SELECT tag AS id FROM postgetagged$modus"."_$CMS_BENUTZERID WHERE nachricht = $id)) AS tags ORDER BY titel ASC;";
			if ($anfrage = $dbp->query($sql)) {	// Safe weil ID Check
				while ($daten = $anfrage->fetch_assoc()) {
					$code .= "<span class=\"cms_toggle\" onclick=\"cms_postfach_nachricht_taggen(1, ".$daten['id'].")\">".$daten['titel']."</span> ";
					$tagzahl++;
				}
				$anfrage->free();
			}
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

			echo $code;
		}
		else {
			if(!$fehler) {
				cms_meldung_bastler();
			}
		}
	}
	else {
		echo cms_meldung_bastler();
	}
	?>

</div>
</div>

<div class="cms_clear"></div>
