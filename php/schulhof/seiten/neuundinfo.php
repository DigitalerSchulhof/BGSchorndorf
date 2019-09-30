<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$code = '<ul class="cms_systemvoraussetzung">';
		$code .= '<li><img src="res/icons/gross/version.png"><br><b>Version '.$CMS_VERSION.'</b></li>';
		$code .= '<li><img src="res/icons/gross/cookies.png"><br>Cookies aktiv?</li>';
		$code .= '<li><img src="res/icons/gross/javascript.png"><br>JavaScript aktiv?</li>';
		$code .= '<li><img src="res/icons/gross/multinutzer.png"><br>Nur ein Nutzer pro Browser zur selben Zeit!</li>';
	$code .= '</ul>';
	echo $code;
	?>
</div>
</div>

<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$code = "<h2>Externe Links</h2>";
	$code .= "<h3>Schüler und Lehrer</h3>";
	//$code .= '<p><span class="cms_button_passiv">Dateien im Schulnetzwerk<span class="cms_hinweis">Aktuell liegt ein Serverfehler vor. Der Dienst ist bald wieder erreichbar.</span></span></p>';
	$code .= '<p><a class="cms_button" href="https://filr-schulen.schorndorf.de" target="_blank">Dateien im Schulnetzwerk</a></p>';
	$code .= '<p><a href="http://www.mitte.mensa-pro.de" class="cms_button" target="_blank">Buchungssystem der Mensa Mitte</a></p>';
	$code .= "<h3>Lehrer</h3>";
	$code .= '<p><a href="https://webmail.all-inkl.com/index.php" class="cms_button" target="_blank">Webmail-Portal für Lehrer<span class="cms_hinweis">Demnächst auch im Schulhof!<br> Ziel: Osterferien</span></a></p>';
	echo $code;
	?>
</div>
</div>

<div class="cms_clear"></div>


<div class="cms_spalte_i">
	<h2>Neuerungen</h2>
	<?php
		include_once("../../funktionen/neuerungen.php");

		echo cms_neuerungen();
	?>
	</div>
</div>
