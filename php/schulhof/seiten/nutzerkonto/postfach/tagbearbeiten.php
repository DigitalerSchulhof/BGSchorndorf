<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Postfach</h1>

</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
include_once("php/schulhof/seiten/nutzerkonto/postfach/postnavigation.php");

$fehler = false;

if (isset($_SESSION["TAGBEARBEITEN"])) {
	$tagid = $_SESSION["TAGBEARBEITEN"];
}
else $fehler = true;

if(!cms_check_ganzzahl($tagid, 0)) {$fehler = true;}

// TAGADTEN laden
if (!$fehler) {
	$dbp = cms_verbinden('p');
	$sql = $dbp->prepare("SELECT farbe, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel FROM posttags_$CMS_BENUTZERID WHERE id = ?");
	$sql->bind_param("i", $tagid);
	if ($sql->execute()) {
		$sql->bind_result($farbe, $titel);
		$sql->fetch();
	}
	else {
		$fehler = true;
	}
	$sql->close();
	cms_trennen($dbp);
}
?>

</div>
</div>


<div class="cms_spalte_34">
<div class="cms_spalte_i">
	<h2>Tag bearbeiten</h2>

	<?php
	if (!$fehler) {
		echo "<table class=\"cms_formular\">";
			echo "<tr><th>Titel:</th><td><input type=\"text\" name=\"cms_postach_tag_titel\" id=\"cms_postach_tag_titel\" value=\"$titel\"></td></tr>";
			echo "<tr><th>Farbe:</th><td>";
					$pause = 0;
					for ($i=0; $i<16*4; $i++) {
						if ($pause == 16) {
							echo "<br>";
							$pause = 0;
						}
						$pause++;
						if ($i == $farbe) {
							echo "<span class=\"cms_farbbeispiel_aktiv cms_farbbeispiel_".$i."\" id=\"cms_farbbeispiel_".$i."\" onclick=\"cms_farbbeispiel_waehlen($i, 'cms_postach_tag_farbe')\"></span>";
						}
						else  {
							echo "<span class=\"cms_farbbeispiel cms_farbbeispiel_".$i."\" id=\"cms_farbbeispiel_".$i."\" onclick=\"cms_farbbeispiel_waehlen($i, 'cms_postach_tag_farbe')\"></span>";
						}
					}
				echo "<input type=\"hidden\" name=\"cms_postach_tag_farbe\" id=\"cms_postach_tag_farbe\" value=\"$farbe\">";
			echo "</td></tr>";
		echo "</table>";

		echo "<p><span class=\"cms_button\" onclick=\"cms_postfach_tag_bearbeiten();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Nutzerkonto/Postfach/Tags\">Abbrechen</a></p>";
	}
	else {
		echo cms_meldung_bastler ();
	}
	?>
</div>
</div>


<div class="cms_clear"></div>
