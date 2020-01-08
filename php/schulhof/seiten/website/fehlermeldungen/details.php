<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Fehlermeldung (Detailansicht)</h1>
<?php
$code = "";
if (cms_r("technik.fehlermeldungen"))) {
  include_once("php/schulhof/seiten/website/fehlermeldungen/auswerten.php");
  if (!isset($_SESSION["BUGID"])) {
        $code .= cms_meldung_bastler();
  }
  else {
    $id = $_SESSION["BUGID"];
    $code .= cms_fehlermeldung_details($id);
    $code .= "<a class=\"cms_button_nein\" href=\"Schulhof/Website/Fehlermeldungen\">Zurück</a>";
    $code .= "</div>";
  }
}
else {
  $code .= cms_meldung_berechtigung();
}
echo $code;
?>
<div class="cms_clear"></div>
