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
	<h2>Tags</h2>
	<table class="cms_liste">
		<tr>
			<th></th><th>Titel</th><th>Aktionen</th>
		</tr>


		<?php
		$dbp = cms_verbinden('p');
		$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, farbe FROM posttags_$CMS_BENUTZERID) AS tags ORDER BY titel";
		$anfrage = $dbp->query($sql);	// Safe weil keine Eingabe

		$code = "";
		if ($anfrage) {
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= "<tr>";
				$code .= "<td><span class=\"cms_tag_gross cms_farbbeispiel_".$daten['farbe']."\"></span></td>";
				$code .= "<td>".$daten['titel']."</td>";
				$code .= "<td>";
				$code .= "<span class=\"cms_aktion_klein cms_aktion\" onclick=\"cms_postfach_tag_bearbeiten_vorbereiten('".$daten['id']."', '".$daten['titel']."')\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				$code .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_postfach_tag_loeschen_anzeigen('".$daten['id']."', '".$daten['titel']."')\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";
				$code .= "</td><tr>";
			}
			$anfrage->free();
		}

		echo $code;
		cms_trennen($dbp);
		?>
	</table>

	<?php echo "<p><a class=\"cms_button_ja\" href=\"Schulhof/Nutzerkonto/Postfach/Tags/Neuen_Tag_anlegen\">+ Neuen Tag anlegen</a></p>";?>

</div>
</div>


<div class="cms_clear"></div>
