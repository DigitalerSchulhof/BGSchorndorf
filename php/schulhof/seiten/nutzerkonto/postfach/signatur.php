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
	<h2>Signatur</h2>

	<?php
	$fehler = false;
	$dbs = cms_verbinden('s');

	$sql = "SELECT AES_DECRYPT(signatur, '$CMS_SCHLUESSEL') AS signatur FROM personen_signaturen WHERE person = $CMS_BENUTZERID";
	$anfrage = $dbs->query($sql);	// Safe weil keine Eingabe

	if ($anfrage) {
		if ($daten = $anfrage->fetch_assoc()) {
			$signatur = $daten['signatur'];
			$anfrage->free();

		}
		else {$fehler = true;}
	}

	cms_trennen($dbs);


	if ($fehler) {
		echo cms_meldung_unbekannt();
	}
	else {
		echo "<table class=\"cms_formular\">";
			echo "<tr>";
				echo "<th>Signatur</th>";
				echo "<td><textarea cols=\"3\" rows=\"10\" name=\"cms_postfach_signatur\" id=\"cms_postfach_signatur\">$signatur</textarea></td>";
			echo "</tr>";
		echo "</table>";

		echo "<p><span class=\"cms_button\" onclick=\"cms_postfach_signatur_aendern();\">Änderungen speichern</span></p>";
	}
	?>
</div>
</div>


<div class="cms_clear"></div>
