<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Detailansicht von Personen</h1>

<?php
// PROFILDATEN LADEN
if (!isset($_SESSION['PERSONENDETAILS'])) {
	echo cms_meldung_bastler();
}

?>

</div>

<div class="cms_spalte_2">
<div class="cms_spalte_i">

<?php
// PROFILDATEN LADEN
if (isset($_SESSION['PERSONENDETAILS'])) {
	include_once("php/schulhof/seiten/verwaltung/personen/personaldaten.php");
	cms_personaldaten_ausgeben ($_SESSION["PERSONENDETAILS"]);
}
?>

</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">
	<h2>Mitgliedschaften</h2>
	<?php
	// MITGLIEDSCHAFTEN LADEN
	if (isset($_SESSION['PERSONENDETAILS'])) {
		include_once('php/schulhof/seiten/gruppen/mitgliedschaftenausgeben.php');
		foreach ($CMS_GRUPPEN as $g) {
			$gruppencode = cms_gruppen_mitgliedschaften_anzeigen($dbs, $g, $CMS_BENUTZERART, $_SESSION['PERSONENDETAILS'], $CMS_BENUTZERSCHULJAHR);
			if (strlen($gruppencode) > 0) {$code .= "<h3>$g</h3>".str_replace('<ul>', '<ul class="cms_aktionen_liste">', $gruppencode);}
		}
		echo $code;
	}
	?>
</div>
</div>


<div class="cms_spalte_4">
<div class="cms_spalte_i">
	<h2>Ansprechpartner</h2>
	<?php
		cms_personaldaten_ansprechpartner_ausgeben($_SESSION['PERSONENDETAILS']);
	?>
</div>
</div>


<div class="cms_clear"></div>
