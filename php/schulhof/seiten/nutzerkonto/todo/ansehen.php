<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
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
<h1><?php echo $bezeichnung;?></h1>
<?php
	echo $beschreibung;
	if(strlen($beschreibung)) {
		echo "<p>$beschreibung</p>";
	} else {
		echo "<p class=\"cms_notiz\">Keine Beschreibung angegeben</p>";
	}
?>

<div class="cms_clear"></div>

<?php
}
$sql->close();
?>
