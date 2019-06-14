<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Postfach</h1>

</div>

<div class="cms_spalte_4">
<div class="cms_spalte_i">

<?php
// PROFILDATEN LADEN
include_once("php/schulhof/seiten/nutzerkonto/postfach/postnavigation.php");
include_once("php/schulhof/seiten/nutzerkonto/postfach/postfilter.php");
?>

</div>
</div>


<div class="cms_spalte_34">
<div class="cms_spalte_i">
	<h2>Postausgang</h2>

	<?php
	$heutet = mktime(23,59,59,date("m"), date("d"), date("Y"));
	//$letztenMonatt = mktime(0,0,0,date("m")-1, date("d"), date("Y"));
	$letztenMonatt = mktime(0,0,0,0, 0, 2000);
	echo cms_postfach_filter_ausgeben('ausgang', $letztenMonatt, $heutet, '-', '');
	?>

	<table class="cms_liste cms_postfach_liste">
		<tr>
			<th></th><th>Empfänger</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th><th>Aktionen</th>
		</tr>
	<tbody id="cms_postfach_gesendet_liste">
		<?php
		echo cms_postfach_nachrichten_listen ('ausgang', '-', 0, $heutet, '', '', '', '', 0, 25);
		?>
	</tbody>
	</table>
</div>
</div>


<div class="cms_clear"></div>
