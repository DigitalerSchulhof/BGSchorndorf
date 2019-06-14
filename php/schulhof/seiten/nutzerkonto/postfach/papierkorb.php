<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Postfach</h1>

<?php
if ($CMS_RECHTE['verwaltung'] || $CMS_RECHTE['lehrer']) {
?>

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
	<h2>Papierkorb</h2>

	<?php
	$heutet = mktime(23,59,59,date("m"), date("d"), date("Y"));
	//$letztenMonatt = mktime(0,0,0,date("m")-1, date("d"), date("Y"));
	$letztenMonatt = mktime(0,0,0,0, 0, 2000);
	?>


	<ul class="cms_reitermenue">
		<li><span id="cms_reiter_postfach_papierkorb_0" class="cms_reiter_aktiv" onclick="javascript:cms_reiter('postfach_papierkorb', 0,2)">Posteingang</a></li>
		<li><span id="cms_reiter_postfach_papierkorb_1" class="cms_reiter" onclick="javascript:cms_reiter('postfach_papierkorb', 1,2)">Entwürfe</a></li>
		<li><span id="cms_reiter_postfach_papierkorb_2" class="cms_reiter" onclick="javascript:cms_reiter('postfach_papierkorb', 2,2)">Postausgang</a></li>
	</ul>

	<div class="cms_reitermenue_o" id="cms_reiterfenster_postfach_papierkorb_0" style="display: block;">
	<div class="cms_reitermenue_i">
		<?php echo cms_postfach_filter_ausgeben ('eingang', $letztenMonatt, $heutet, '1', '0');?>
		<table class="cms_liste cms_postfach_liste">
			<tr>
				<th></th><th>Absender</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th><th>Aktionen</th>
			</tr>
		<tbody id="cms_postfach_eingang_liste">
			<?php
			echo cms_postfach_nachrichten_listen ('eingang', '1', 0, $heutet, '', '', '', '', 0, 25);
			?>
		</tbody>
		</table>

		<p><span class="cms_button_nein" onclick="cms_postfach_papierkorb_leeren_anzeigen('eingang')">Papierkorb »Posteingang« leeren</span></p>
	</div>
	</div>

	<div class="cms_reitermenue_o" id="cms_reiterfenster_postfach_papierkorb_1">
	<div class="cms_reitermenue_i">
		<?php echo cms_postfach_filter_ausgeben ('entwurf', $letztenMonatt, $heutet, '1', '1');?>
		<table class="cms_liste cms_postfach_liste">
			<tr>
				<th></th><th>Empfänger</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th><th>Aktionen</th>
			</tr>
		<tbody id="cms_postfach_entwurf_liste">
			<?php
			echo cms_postfach_nachrichten_listen ('entwurf', '1', 0, $heutet, '', '', '', '', 0, 25);
			?>
		</tbody>
		</table>

		<p><span class="cms_button_nein" onclick="cms_postfach_papierkorb_leeren_anzeigen('entwurf')">Papierkorb »Entwürfe« leeren</span></p>
	</div>
	</div>

	<div class="cms_reitermenue_o" id="cms_reiterfenster_postfach_papierkorb_2">
	<div class="cms_reitermenue_i">
		<?php echo cms_postfach_filter_ausgeben ('ausgang', $letztenMonatt, $heutet, '1', '2');?>
		<table class="cms_liste cms_postfach_liste">
			<tr>
				<th></th><th>Empfänger</th><th>Betreff</th><th>Datum</th><th>Uhrzeit</th><th>Aktionen</th>
			</tr>
		<tbody id="cms_postfach_gesendet_liste">
			<?php
			echo cms_postfach_nachrichten_listen ('ausgang', '1', 0, $heutet, '', '', '', '', 0, 25);
			?>
		</tbody>
		</table>

		<p><span class="cms_button_nein" onclick="cms_postfach_papierkorb_leeren_anzeigen('ausgang')">Papierkorb »Postausgang« leeren</span></p>
	</div>
	</div>
</div>
</div>

<?php
}
else {
	echo cms_meldung_berechtigung();
	echo "</div>";
}
?>


<div class="cms_clear"></div>
