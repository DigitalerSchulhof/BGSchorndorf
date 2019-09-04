<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Emoticons</h1>

<?php
include_once("php/schulhof/funktionen/emoticons.php");
$zugriff = $CMS_RECHTE['Website']['Emoticons verwalten'];
$code = "";

$sql = cms_sql_mit_aes(array("id", "aktiv"), "emoticons");
$sql = $dbs->query($sql);
if ($zugriff) {
  $code .= "</div><div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
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
  $code .= "</div></div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
    $code .= "<h2>Aktive Emoticons</h2>";
    $code .= "<table class=\"cms_formular\">";
      $code .= "<tr><th>Icon</th><th>Name</th><th>Id</th><th>Aktiv</th></tr>";
        foreach($CMS_EMOTICONS as $e)
          $code .= "<tr data-emoticon=\"".$e["id"]."\" id=\"cms_emoticons_liste_".$e["id"]."\"><td class=\"min\"><img src=\"res/emoticons/gross/".$e["datei"]."\"></td><td>".$e["name"]."</td><td class=\"min\">".$e["id"]."</td><td class=\"min\">".cms_schieber_generieren("emoticon_".$e["id"], $e["aktiv"])."</tr>";
    $code .= "</table>";
    $code .= "<div class=\"cms_button\" onclick=\"cms_emoticons_speichern();\">Speichern</div>";
  $code .= "</div></div>";
}
else {
  $code .= cms_meldung_berechtigung();
}
echo $code;
?>
<div class="cms_clear"></div>
