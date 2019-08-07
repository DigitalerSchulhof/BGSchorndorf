<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Emoticons</h1>
<?php
$zugriff = $CMS_RECHTE['Website']['Emoticons verwalten'];
$code = "";
if ($zugriff) {
  include_once("php/schulhof/seiten/website/feedback/auswerten.php");
  $code .= "<div class=\"cms_spalte_2\">";
    $code .= "<h2>Reaktionen auf Artikel</h2>";
    $code .= "<table class=\"cms_formular\">";
      $code .= "<tr>";
        $code .= "<th>Reaktionen auf Blogeinträge:</th>";
        $code .= "<td>".cms_schieber_generieren('reaktionen_b',$CMS_EINSTELLUNGEN['Reaktionen auf Blogeinträge'])."</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Reaktionen auf Termine:</th>";
        $code .= "<td>".cms_schieber_generieren('reaktionen_t',$CMS_EINSTELLUNGEN['Reaktionen auf Termine'])."</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<th>Reaktionen auf Galerien:</th>";
        $code .= "<td>".cms_schieber_generieren('reaktionen_g',$CMS_EINSTELLUNGEN['Reaktionen auf Galerien'])."</td>";
      $code .= "</tr>";
    $code .= "</table>";
    $code .= "<div class=\"cms_button\" onclick=\"cms_reaktionen_speichern();\">Speichern</div>";
  $code .= "</div>";
}
else {
  $code .= cms_meldung_berechtigung();
}
echo $code;
?>
<div class="cms_clear"></div>
