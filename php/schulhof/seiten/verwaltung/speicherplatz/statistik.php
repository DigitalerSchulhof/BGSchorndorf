<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Speicherplatzstatistik</h1>

<?php
if (cms_r("statistik.speicherplatz")) {
	$gesamtspeicherplatz = $CMS_EINSTELLUNGEN['Gesamtspeicherplatz'];
	$gesamtkurz = cms_groesse_umrechnen($gesamtspeicherplatz);

	$code = "<h2>Schulhofserver</h2>";

	$code .= "<h3>Auslastung</h3>";
	$code .= "<div id=\"cms_speicherplatz_frei\">";
		$code .= "<div id=\"cms_speicherplatz_system_balken\"></div>";
		$code .= "<div id=\"cms_speicherplatz_website_balken\"></div>";
		$code .= "<div id=\"cms_speicherplatz_schulhof_balken\"></div>";
		$code .= "<div id=\"cms_speicherplatz_gruppen_balken\"></div>";
		$code .= "<div id=\"cms_speicherplatz_personen_balken\"></div>";
	$code .= "</div>";
	$code .= "<p class=\"cms_notiz\"><span id=\"cms_speicherplatz_belegt_absolut\">0 B</span> (<span id=\"cms_speicherplatz_belegt_prozentual\">0 %</span>) von $gesamtkurz belegt. <span id=\"cms_speicherplatz_frei_absolut\">0 B</span> (<span id=\"cms_speicherplatz_frei_prozentual\">0 %</span>) von $gesamtkurz frei.</p>";

	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_liste\" id=\"cms_speicherplatz\">";
	$code .= "<tr><th></th><th>Bereich</th><th colspan=\"2\">Datenbank</th><th colspan=\"2\">Dateien</th><th colspan=\"2\">Gesamt</th></tr>";
	$code .= "<tr><td><div id=\"cms_speicherplatz_system_icon\"></div></td><td>System</td>";
	$code .= "<td id=\"cms_speicherplatz_system_db_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_system_db_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_system_dat_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_system_dat_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_system_ges_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_system_ges_prozentual\">".cms_ladeicon()."</td>";
	$code .= "</tr>";

	$code .= "<tr><td><div id=\"cms_speicherplatz_website_icon\"></div></td><td>Website</td>";
	$code .= "<td id=\"cms_speicherplatz_website_db_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_website_db_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_website_dat_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_website_dat_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_website_ges_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_website_ges_prozentual\">".cms_ladeicon()."</td>";
	$code .= "</tr>";

	$code .= "<tr><td><div id=\"cms_speicherplatz_schulhof_icon\"></div></td><td>Schulhof</td>";
	$code .= "<td id=\"cms_speicherplatz_schulhof_db_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_schulhof_db_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_schulhof_dat_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_schulhof_dat_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_schulhof_ges_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_schulhof_ges_prozentual\">".cms_ladeicon()."</td>";
	$code .= "</tr>";

	$code .= "<tr><td><div id=\"cms_speicherplatz_gruppen_icon\"></div></td><td>Gruppen</td>";
	$code .= "<td id=\"cms_speicherplatz_gruppen_db_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_gruppen_db_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_gruppen_dat_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_gruppen_dat_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_gruppen_ges_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_gruppen_ges_prozentual\">".cms_ladeicon()."</td>";
	$code .= "</tr>";

	$code .= "<tr><td><div id=\"cms_speicherplatz_personen_icon\"></div></td><td>Personen</td>";
	$code .= "<td id=\"cms_speicherplatz_personen_db_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_personen_db_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_personen_dat_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_personen_dat_prozentual\">".cms_ladeicon()."</td>";
	$code .= "<td id=\"cms_speicherplatz_personen_ges_absolut\">".cms_ladeicon()."</td><td id=\"cms_speicherplatz_personen_ges_prozentual\">".cms_ladeicon()."</td>";
	$code .= "</tr>";

	$code .= "<tr><th></th><th>Belegt</th>";
	$code .= "<th id=\"cms_speicherplatz_belegt_db_absolut\">0 B</th><th id=\"cms_speicherplatz_belegt_db_prozentual\">0 %</th>";
	$code .= "<th id=\"cms_speicherplatz_belegt_dat_absolut\">0 B</th><th id=\"cms_speicherplatz_belegt_dat_prozentual\">0 %</th>";
	$code .= "<th id=\"cms_speicherplatz_belegt_ges_absolut\">0 B</th><th id=\"cms_speicherplatz_belegt_ges_prozentual\">0 %</th>";
	$code .= "</tr>";

	$code .= "<tr><th></th><th colspan=\"5\">Frei</th>";
	$code .= "<th id=\"cms_speicherplatz_frei_ges_absolut\">$gesamtkurz</th><th id=\"cms_speicherplatz_frei_ges_prozentual\">100 %</th>";
	$code .= "</tr>";

	$code .= "<tr><th></th><th colspan=\"5\">Gesamt</th>";
	$code .= "<th>$gesamtkurz</th><th>100 %</th>";
	$code .= "</tr>";
	$code .= "</table>";

	$code .= "<script>cms_speicherplatzstatistik('system', $gesamtspeicherplatz); cms_speicherplatzstatistik('website', $gesamtspeicherplatz); ";
	$code .= "cms_speicherplatzstatistik('schulhof', $gesamtspeicherplatz); cms_speicherplatzstatistik('gruppen', $gesamtspeicherplatz); ";
	$code .= "cms_speicherplatzstatistik('personen', $gesamtspeicherplatz);</script>";
	echo $code;
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
