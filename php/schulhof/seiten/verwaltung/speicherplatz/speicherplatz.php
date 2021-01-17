<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Speicherplatz</h1>

<?php
$code = "";

if (cms_r("statistik.speicherplatz")) {
	$code .= "<a class=\"cms_button\" href=\"Schulhof/Verwaltung/Speicherplatz/Statistik\">Statisitk</a> ";
}

if (strlen($code) > 0) {
	echo "<p>$code</p>";
}
else {
	echo cms_meldung_berechtigung();
}
?>
</div>
<div class="cms_clear"></div>
