<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Masterlemente bearbeiten</h1>

<?php
if (cms_r("website.masterelemente")) {
  $master = array();
  $sql = $dbs->prepare("SELECT inhalt, wert FROM master");
  if ($sql->execute()) {
    $sql->bind_result($inhalt, $wert);
    while ($sql->fetch()) {
      $master[$inhalt] = $wert;
    }
  }

	include_once('php/schulhof/seiten/website/editor/editor.php');
  $code = "<h2>Fußzeile</h2>";
	$code .= cms_webeditor('cms_master_fusszeile', $master['Fußzeile']);


  $code .= "<h2>Informationen neben der Anmeldung</h2>";
	$code .= cms_webeditor('cms_master_anmelden', $master['Anmelden']);

  echo $code;

  echo "<p><span class=\"cms_button\" onclick=\"cms_website_master_bearbeiten();\">Speichern</span> <a class=\"cms_button_nein\" href=\"Schulhof/Website/Master_bearbeiten\">Abbrechen</a></p>";
}

?>

</div>
<div class="cms_clear"></div>
