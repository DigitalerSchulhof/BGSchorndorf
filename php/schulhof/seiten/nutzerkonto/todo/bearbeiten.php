<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>ToDo Bearbeiten</h1>
<?php
	// ToDo laden
	$bez = $CMS_URL[3];
	$bez = cms_linkzutext($bez);
	$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') FROM todo WHERE person = ? AND bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("is", $CMS_BENUTZERID, $bez);
	$sql->bind_result($id, $bezeichnung, $beschreibung);
	if(!$sql->execute() || !$sql->fetch()) {
		echo cms_meldung_bastler();
	} else {
?>
<table class="cms_formular">
	<tr><td>Bezeichnung</td><td><input type="text" id="cms_todo_bezeichnung" value="<?php echo $bezeichnung; ?>"></td></tr>
	<tr><td>Beschreibung</td><td><textarea id="cms_todo_beschreibung"><?php echo $beschreibung; ?></textarea></td></tr>
	<tr><td colspan="2"><span class="cms_button_ja" onclick="cms_eigenes_todo_speichern()">Änderungen speichern</td></tr>
</table>
<input type="hidden" id="cms_todo_id" value="<?php echo $id;?>">
<div class="cms_clear"></div>

<?php
}
$sql->close();
?>
