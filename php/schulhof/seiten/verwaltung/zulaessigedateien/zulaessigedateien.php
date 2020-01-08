<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Zulässige Dateien</h1>

<?php
if (cms_r("technik.server.dateienerlaubnis"))) {
	$code = "<h3>Limit</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Maximale Dateigröße:</th><td><input type=\"text\" name=\"cms_schulhof_zulaessig_groesse\" id=\"cms_schulhof_zulaessig_groesse\" value=\"$CMS_MAX_DATEI\"> ";
		$code .= "<select class=\"cms_einheit\" name=\"cms_schulhof_zulaessig_einheit\" id=\"cms_schulhof_zulaessig_einheit\">";
			$code .= "<option value=\"B\">Byte</option>";
			$code .= "<option value=\"KB\">KB</option>";
			$code .= "<option value=\"MB\">MB</option>";
			$code .= "<option value=\"GB\">GB</option>";
			$code .= "<option value=\"TB\">TB</option>";
			$code .= "<option value=\"PB\">PB</option>";
			$code .= "<option value=\"EB\">EB</option>";
		$code .= "</select></td></tr>";
	$code .= "</table>";

	$dbs = cms_verbinden('s');
	$kategorie = "";
	$datentypen = "";
	$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(endung, '$CMS_SCHLUESSEL') AS endung, AES_DECRYPT(kategorie, '$CMS_SCHLUESSEL') AS kategorie, AES_DECRYPT(zulaessig, '$CMS_SCHLUESSEL') AS zulaessig FROM zulaessigedateien) AS zulaessigedateien ORDER BY kategorie ASC, endung ASC;";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		while ($daten = $anfrage->fetch_assoc()) {
			if ($daten['kategorie'] != $kategorie) {
				if ($kategorie == "Anwendungen") {
					$datentypen .= cms_meldung('warnung', '<h4>Sicherheitshinweis</h4><p>Aus Sicherheitsgründen sollten möglichst keine Anwendungen auf dem Server erlaubt werden.</p>');
				}
				$kategorie = $daten['kategorie'];
				if ($kategorie == "XSonstige") {$daten['kategorie'] = substr($daten['kategorie'], 1);}
				$datentypen .= "</p><h4>".$daten['kategorie']."</h4>";
				$datentypen .= "<p>";
			}
			// Endungswahl aufbauen
			$vorsilbe = "in";
			if ($daten['zulaessig'] == 1) {$vorsilbe = "";}
			$datentypen .= "<div class=\"cms_datentypwahl\"><p><img src=\"res/dateiicons/gross/".$daten['endung'].".png\"><br>".$daten['endung']."</p><p><span class=\"cms_schieber_o_".$vorsilbe."aktiv\" id=\"cms_schieber_schulhof_zulaessig_".$daten['id']."\" onclick=\"cms_schieber('schulhof_zulaessig_".$daten['id']."')\"><span class=\"cms_schieber_i\"></span></span><input type=\"hidden\" name=\"cms_schulhof_zulaessig_".$daten['id']."\" id=\"cms_schulhof_zulaessig_".$daten['id']."\" value=\"".$daten['zulaessig']."\"></p></div> ";
		}
		$anfrage->free();
	}

	$max = "";
	$sql = "SELECT MAX(id) AS max FROM zulaessigedateien";
	if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		if ($daten = $anfrage->fetch_assoc()) {
			$max = $daten['max'];
			if ($max == null) {$max = 0;}
			$datentypen .= "<p><input type=\"hidden\" name=\"cms_schulhof_zulaessig_max\" id=\"cms_schulhof_zulaessig_max\" value=\"$max\"></p>";
		}
		$anfrage->free();
	}
	cms_trennen($dbs);

	if ($max > 0) {
		$code = $code.substr($datentypen, 4);
	}

	$code .= "<p><span class=\"cms_button\" onclick=\"cms_schulhof_verwaltung_zulaessig();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p></div>";

	echo $code;
}
else {
	cms_meldung_berechtigung();
}
?>
</div>
