<?php
function cms_neue_nachricht($dbs, $app = "nein") {
	global $CMS_SCHLUESSEL, $CMS_BENUTZERART, $CMS_DBP_DB, $CMS_BENUTZERID, $CMS_DBS_DB;
	$code = "";
	$ordnergroesse = cms_dateisystem_ordner_info("dateien/schulhof/personen/$CMS_BENUTZERID/postfach");
	include("php/schulhof/seiten/nutzerkonto/postfach/postlimit.php");

	$tabellen[0] = "posteingang_$CMS_BENUTZERID";
	$tabellen[1] = "postausgang_$CMS_BENUTZERID";
	$tabellen[2] = "postentwurf_$CMS_BENUTZERID";
	$tabellen[3] = "postgetaggedeingang_$CMS_BENUTZERID";
	$tabellen[4] = "postgetaggedausgang_$CMS_BENUTZERID";
	$tabellen[5] = "postgetaggedentwurf_$CMS_BENUTZERID";
	$tabellen[6] = "posttags_$CMS_BENUTZERID";

	$dbgroesse = cms_db_tabellengroesse($CMS_DBP_DB, $tabellen);
	$POSTBELEGT = $ordnergroesse['groesse'] + $dbgroesse;

	$fehler = false;
	if (isset($_SESSION['POSTEMPFAENGER'])) {$POSTEMPFAENGER = $_SESSION['POSTEMPFAENGER'];} else {$fehler = true;}
	if (isset($_SESSION['POSTBETREFF'])) {$POSTBETREFF = $_SESSION['POSTBETREFF'];} else {$fehler = true;}
	if (isset($_SESSION['POSTNACHRICHT'])) {$POSTNACHRICHT = $_SESSION['POSTNACHRICHT'];} else {$fehler = true;}
	if (isset($_SESSION['POSTEMPFAENGERPOOL'])) {$POSTEMPFAENGERPOOL = $_SESSION['POSTEMPFAENGERPOOL'];} else {$fehler = true;}

	if (!$fehler) {
		$POSTANHANGORDNER = "dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp";

		$dateien = "";
		$anhangstyle = "display: table-row;";
		// Bereits hochgeladene Anhänge verwenden
		$vinhalt = scandir($POSTANHANGORDNER, 0);
		if (file_exists($POSTANHANGORDNER)) {
			$vinhalt = scandir($POSTANHANGORDNER, 0);
			for ($i=2; $i<count($vinhalt); $i++) {
				// Falls Datei
				if (is_file($POSTANHANGORDNER.'/'.$vinhalt[$i])) {
					$dateien .= "<span class=\"cms_postfach_anhang\">";
					$dateien .= "<span class=\"cms_button_nein\" onclick=\"cms_dateisystem_anhang_loeschen('".$vinhalt[$i]."')\"><span class=\"cms_hinweis\">Datei entfernen</span>×</span>";
					$dateiname = explode(".", $vinhalt[$i]);
					$icon = cms_dateisystem_icon($dateiname[count($dateiname)-1]);
					$dateien .= "<img src=\"res/dateiicons/klein/$icon\"> ".$vinhalt[$i];
					$groesse = filesize($POSTANHANGORDNER.'/'.$vinhalt[$i]);
					$dateien .= " (".cms_groesse_umrechnen($groesse).")";
					$dateien .= "</span>";
				}
			}
		}

		if(strlen($dateien) == 0) {$dateien = '<span class="cms_notiz">Diese Nachricht hat keinen Anhang.</span>';}

		if ($POSTBELEGT >= $POSTLIMIT) {
			$code .= cms_meldung('fehler', '<h4>Limit erreicht</h4><p>Solange das Speicherlimit überschritten ist, kann keine neue Nachricht verfasst werden.</p>');
		}
		else {
			include_once('php/schulhof/seiten/website/editor/editor.php');
			include_once('php/schulhof/seiten/personensuche/personensuche.php');
			$code .= "<p>";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_versenden\" onclick=\"cms_postfach_senden('$app')\">Versenden</span> ";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_speichern\" onclick=\"cms_postfach_entwurfspeichern('$app')\">Speichern</span> ";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_anhang\" onclick=\"cms_einblenden('cms_nutzerkonto_postfach_nachricht_anhang_aktionen_hochladen', 'table-row')\">Anhang</span>";
			$code .= "</p>";

			$code .= "<table class=\"cms_liste\">";
				$code .= "<tr><th>Empfänger:</th><td>".cms_personensuche_mail_generieren($dbs, 'cms_postfach_empfaenger', $POSTEMPFAENGERPOOL, $POSTEMPFAENGER)."</td></tr>";
				$code .= "<tr><th>Betreff:</th><td><input type=\"text\" name=\"cms_postfach_betreff\" id=\"cms_postfach_betreff\" value=\"$POSTBETREFF\"></td></tr>";
				$code .= "<tr><th>Anhang:</th><td><div id=\"cms_nutzerkonto_postfach_nachricht_schreiben_anhang_box\">$dateien</div>".cms_postfach_anhang_dateiupload()."</td></tr>";
			$code .= "</table>";

			if (count(explode('|',substr($POSTEMPFAENGER,1))) > 2) {$style = 'block';}
			else {$style = 'none';}
			$code .= "<div id=\"cms_bloghinweis\" style=\"display: $style;\">".cms_meldung('info', '<h4>Wirklich eine Nachricht?</h4><p>Blogeinträge sind leichter zu finden, verbrauchen für die Benutzer selbst keinen Speicherplatz und unterliegen keinem Speicherlimit.</p>')."</div>";

			$code .= "<h3>Nachricht</h3>";
			$code .= cms_webeditor('cms_postfach_nachricht', $POSTNACHRICHT);
		}
		return $code;
	}
	else {
		return cms_meldung_bastler();
	}
}
