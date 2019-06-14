<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Postfach</h1>

<?php
if ($CMS_ANGEMELDET) {
	$fehler = false;
	if (isset($_SESSION['POSTEMPFAENGER'])) {$POSTEMPFAENGER = $_SESSION['POSTEMPFAENGER'];} else {$fehler = true;}
	if (isset($_SESSION['POSTBETREFF'])) {$POSTBETREFF = $_SESSION['POSTBETREFF'];} else {$fehler = true;}
	if (isset($_SESSION['POSTNACHRICHT'])) {$POSTNACHRICHT = $_SESSION['POSTNACHRICHT'];} else {$fehler = true;}
	if (isset($_SESSION['POSTEMPFAENGERPOOL'])) {$POSTEMPFAENGERPOOL = $_SESSION['POSTEMPFAENGERPOOL'];} else {$fehler = true;}

	if (!$fehler) {
		$POSTANHANGORDNER = "dateien/schulhof/personen/$CMS_BENUTZERID/postfach/temp";

		echo "</div>";
		echo "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
		include_once("php/schulhof/seiten/nutzerkonto/postfach/postnavigation.php");
		echo "</div></div>";

		$code .= "<div class=\"cms_spalte_34\"><div class=\"cms_spalte_i\">";
		$code .= "<h2>Neue Nachricht</h2>";

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
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_versenden\" onclick=\"cms_postfach_senden()\">Versenden</span> ";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_speichern\" onclick=\"cms_postfach_entwurfspeichern()\">Speichern</span> ";
				$code .= "<span class=\"cms_iconbutton\" id=\"cms_button_postfach_anhang\" onclick=\"cms_einblenden('cms_nutzerkonto_postfach_nachricht_anhang_aktionen_hochladen', 'table-row')\">Anhang</span>";
			$code .= "</p>";

			$code .= "<table class=\"cms_liste\">";
				$code .= "<tr><th>Empfänger:</th><td>".cms_personensuche_mail_generieren($dbs, 'cms_postfach_empfaenger', $POSTEMPFAENGERPOOL, $POSTEMPFAENGER)."</td></tr>";
				$code .= "<tr><th>Betreff:</th><td><input type=\"text\" name=\"cms_postfach_betreff\" id=\"cms_postfach_betreff\" value=\"$POSTBETREFF\"></td></tr>";
				$code .= "<tr><th>Anhang:</th><td><div id=\"cms_nutzerkonto_postfach_nachricht_schreiben_anhang_box\">$dateien</div>".cms_postfach_anhang_dateiupload()."</td></tr>";
			$code .= "</table>";

			if (count(explode('|',substr($POSTEMPFAENGER,1))) > 2) {$style = 'block';}
			else {$style = 'none';}
			$code .= "<div id=\"cms_bloghinweis\" style=\"display: $style;\">".cms_meldung('info', '<h4>Wirklich eine Nachricht?</h4><p>Blogeinträge sind leichert zu finden, verbrauchen für die Benutzer selbst keinen Speicherplatz und unterliegen keinem Speicherlimit.</p>')."</div>";

			$code .= "<h3>Nachricht</h3>";
			$code .= cms_webeditor('cms_postfach_nachricht', $POSTNACHRICHT);
		}

		$code .= "</div></div>";

		$code .= "<div class=\"cms_clear\"></div>";
		echo $code;
	}
	else {
		echo cms_meldung_bastler();
		echo "</div>";
	}
}
else {
	echo cms_meldung_berechtigung();
	echo "</div>";
}
