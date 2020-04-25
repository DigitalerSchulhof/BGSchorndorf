<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Postfach</h1>
</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
// PROFILDATEN LADEN
include_once("php/schulhof/seiten/nutzerkonto/postfach/postnavigation.php");
include_once("php/schulhof/seiten/nutzerkonto/postfach/nachrichtlesenfkt.php");
?>

</div>
</div>

<div class="cms_spalte_34">
<div class="cms_spalte_i">
	<h2>Nachricht lesen</h2>

	<?php
	echo cms_nachricht_lesen($dbs);
	?>

</div>
</div>

<div class="cms_clear"></div>
