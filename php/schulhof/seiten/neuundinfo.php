<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$code = '<ul class="cms_systemvoraussetzung">';
		$code .= '<li><img src="res/icons/gross/version.png"><br><b>'.s('schulhof.seite.anmeldung.hinweis.version', array("%v%" => $CMS_VERSION)).'</b></li>';
		$code .= '<li><img src="res/icons/gross/cookies.png"><br>'.s('schulhof.seite.anmeldung.hinweis.cookies').'</li>';
		$code .= '<li><img src="res/icons/gross/javascript.png"><br>'.s('schulhof.seite.anmeldung.hinweis.javascript').'</li>';
		$code .= '<li><img src="res/icons/gross/multinutzer.png"><br>'.s('schulhof.seite.anmeldung.hinweis.nutzer').'</li>';
	$code .= '</ul>';
	echo $code;
	?>
</div>
</div>

<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$code = "<h2>".s('schulhof.seite.anmeldung.links.ueberschrift')."</h2>";
	$code .= "<h3>".s('schulhof.seite.anmeldung.links.schuelerlehrer.ueberschrift')."</h3>";
	// $code .= '<p><span class="cms_button_passiv">Dateien im Schulnetzwerk<span class="cms_hinweis">Aktuell liegt ein Serverfehler vor. Der Dienst ist bald wieder erreichbar.</span></span></p>';
	$code .= '<p><a class="cms_button" href="https://filr-schulen.schorndorf.de" target="_blank">'.s('schulhof.seite.anmeldung.links.schuelerlehrer.schulnetzwerk').'</a></p>';
	$code .= '<p><a href="http://www.mitte.mensa-pro.de" class="cms_button" target="_blank">'.s('schulhof.seite.anmeldung.links.schuelerlehrer.mensa').'</a></p>';
	$code .= "<h3>".s('schulhof.seite.anmeldung.links.lehrer.ueberschrift')."</h3>";
	$code .= '<p><a href="https://webmail.all-inkl.com/index.php" class="cms_button" target="_blank">'.s('schulhof.seite.anmeldung.links.lehrer.webmail.text').'<span class="cms_hinweis">'.s('schulhof.seite.anmeldung.links.lehrer.webmail.hinweis').'</span></a></p>';
	echo $code;
	?>
</div>
</div>

<div class="cms_clear"></div>


<div class="cms_spalte_i">
	<?php
	echo "<h2>".s("schulhof.seite.anmeldung.neuerungen.ueberschrift")."</h2>";
	$aeltere = "";

	// Neuerungen einfach in die Sprachdatei einfügen!
	// Der oberste Eintrag wird als aktuelle Version gewertet.

	$versionen = s("schulhof.seite.anmeldung.neuerungen.version");
	echo cms_neuerung(str_replace("_", ".", array_keys($versionen)[0]), 1);
	array_shift($versionen);
	foreach(array_keys($versionen) as $version) {
		$aeltere .= cms_neuerung(str_replace("_", ".", $version));
	}

	echo cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_aeltere', s("schulhof.seite.anmeldung.neuerungen.einblenden.aeltere"), s("schulhof.seite.anmeldung.neuerungen.ausblenden.aeltere"), $aeltere, 0);

	function cms_neuerung($version, $sichtbar = 0) {
		$code = "<h4>".s("schulhof.seite.anmeldung.neuerungen.version.".str_replace(".", "_", $version).".ueberschrift")."</h4>";
		$code .= "<ul>";
			foreach(s("schulhof.seite.anmeldung.neuerungen.version.".str_replace(".", "_", $version).".neuerungen") as $n)
				$code .= "<li>$n</li>";
		$code .= "</ul>";
		return cms_toggleeinblenden_generieren ("cms_neuerungenverlaufknopf_".str_replace(".", "_", $version), s("schulhof.seite.anmeldung.neuerungen.einblenden.version", array("%v%" => $version)), s("schulhof.seite.anmeldung.neuerungen.ausblenden.version", array("%v%" => $version)), $code, $sichtbar);
	}
	?>
	</div>
</div>
