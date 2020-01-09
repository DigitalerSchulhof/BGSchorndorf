<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Auffälliges Verhalten (Detailansicht)</h1>
<?php
$code = "";
if (cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges")) {
  include_once("php/schulhof/seiten/auffaelliges/auswerten.php");
  if (!isset($_SESSION["AUFFÄLLIGESID"])) {
        $code .= cms_meldung_bastler();
  }
  else {
    $id = $_SESSION["AUFFÄLLIGESID"];
    $code .= cms_auffaelliges_details($id);
    $code .= "<a class=\"cms_button_nein\" href=\"Schulhof/Aufgaben/Auffälliges\">Zurück</a>";
    $code .= "</div></div>";
  }
}
else {
  $code .= cms_meldung_berechtigung();
}
echo $code;
?>
<div class="cms_clear"></div>
