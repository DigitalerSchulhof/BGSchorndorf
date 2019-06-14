<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Feedback (Detailansicht)</h1>
<?php
$zugriff = $CMS_RECHTE['Website']['Feedback sehen'];
$code = "";
if ($zugriff) {
  include_once("php/schulhof/seiten/website/feedback/auswerten.php");
  if (!isset($_SESSION["FEEDBACKID"])) {
        $code .= cms_meldung_bastler();
  }
  else {
    $id = $_SESSION["FEEDBACKID"];
    $code .= cms_feedback_details($id);
    $code .= "<a class=\"cms_button_nein\" href=\"Schulhof/Website/Feedback\">Zurück</a></div>";
  }
}
else {
  $code .= cms_meldung_berechtigung();
}
echo $code;
?>
<div class="cms_clear"></div>
