<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ); ?></p>

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
	<h2>Neuen Tag anlegen</h2>

	<table class="cms_formular">
		<tr><th>Titel:</th><td><input type="text" name="cms_postach_tag_neu_titel" id="cms_postach_tag_neu_titel"></td></tr>
		<tr><th>Farbe:</th><td>
			<?php
				echo "<span class=\"cms_farbbeispiel_aktiv cms_farbbeispiel_0\" id=\"cms_farbbeispiel_0\" onclick=\"cms_postfach_tag_farbbeispiel_waehlen(0)\"></span>";
				$pause = 1;
				for ($i=1; $i<16*4; $i++) {
					if ($pause == 16) {
						echo "<br>";
						$pause = 0;
					}
					$pause++;
					echo "<span class=\"cms_farbbeispiel cms_farbbeispiel_".$i."\" id=\"cms_farbbeispiel_".$i."\" onclick=\"cms_postfach_tag_farbbeispiel_waehlen($i)\"></span>";
				}
			?>
			<input type="hidden" name="cms_postach_tag_neu_farbe" id="cms_postach_tag_neu_farbe" value="0">
		</td></tr>
	</table>

	<p><span class="cms_button" onclick="cms_postfach_neuertag();">Speichern</span> <a class="cms_button_nein" href="Schulhof/Nutzerkonto/Postfach/Tags">Abbrechen</a></p>
</div>
</div>


<div class="cms_clear"></div>
