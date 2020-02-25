<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Mein Profil</h1>

</div>

<div class="cms_spalte_2">
<div class="cms_spalte_i">

<?php
// PROFILDATEN LADEN
include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
include_once("php/schulhof/anfragen/verwaltung/gruppen/initial.php");
include_once('php/schulhof/seiten/gruppen/mitgliedschaftenausgeben.php');
cms_personaldaten_ausgeben ($_SESSION["BENUTZERID"]);
?>

<h2>Aktivität</h2>
<div id="cms_aktivitaet_out_profil"><div id="cms_aktivitaet_in_profil"></div></div>
<p class="cms_notiz" id="cms_aktivitaet_text_profil">Berechnung läuft ...</p>
<ul class="cms_aktionen_liste">
	<li><span class="cms_button_ja" onclick="cms_timeout_verlaengern()">Verlängern</span></li>
	<li><span class="cms_button_nein" onclick="cms_abmelden_frage();">Abmelden</span></li>
</ul>

<h2>Datenschutz</h2>
<ul class="cms_aktionen_liste">
	<li><a class="cms_button" href="Schulhof/Nutzerkonto/Mein_Profil/Gespeicherte_Daten">Gespeicherte Daten</a></li>
	<li><a class="cms_button" href="Schulhof/Nutzerkonto/Mein_Profil/Meine_Rechte">Meine Rechte</a></li>
</ul>

</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">
	<?php
	$dbs = cms_verbinden('s');
	foreach ($CMS_GRUPPEN as $g) {
		$gruppencode = cms_gruppen_mitgliedschaften_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR);
		if (strlen($gruppencode) > 0) {$code .= "<h3>$g</h3>".str_replace('<ul>', '<ul class="cms_aktionen_liste">', $gruppencode);}
	}
	cms_trennen($dbs);
	if (strlen($code) > 0) {echo "<h2>Mitgliedschaften</h2>".$code;}
	else {echo "<h2>Mitgliedschaften</h2><p class=\"cms_notiz\">Keine Mitgliedschaften gefunden</p>";}
	?>
</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">
	<?php
	if (@$CMS_RECHTE['Personen']['Ansprechpartner sehen']) {
		echo "<h2>Ansprechpartner</h2>";
		cms_personaldaten_ansprechpartner_ausgeben($_SESSION['BENUTZERID']);
	}
	?>
</div>
</div>


<div class="cms_clear"></div>
