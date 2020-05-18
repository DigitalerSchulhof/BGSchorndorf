<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Weiterleitungen</h1>

<?php
if (cms_r("website.weiterleiten")) {
  $dbs = cms_verbinden('s');

  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(von, '$CMS_SCHLUESSEL'), AES_DECRYPT(zu, '$CMS_SCHLUESSEL') FROM weiterleiten");
  $sql->bind_result($id, $von, $zu);
  $sql->execute();
  echo "<table class=\"cms_liste\">";
  $c = 0;
  while($sql->fetch()) {
    $hmeta = "<input type=\"hidden\" class=\"cms_multiselect_id\" value=\"$id\">";

    $c++; // Ne PHP :)
    echo "<tr><td class=\"cms_multiselect\">$hmeta<img src=\"res/icons/klein/weiterleiten.png\"></td><td>$von</td><td><img src='res/icons/klein/springen.png'></td><td>$zu</td><td><span class=\"cms_aktion_klein\" onclick=\"cms_weiterleitung_bearbeiten_vorbereiten($id);\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> <span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_weiterleitung_loeschen($id);\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span></td></tr>";
  }
  if(!$c) {
    echo "<tr><td colspan='4' class=\"cms_notiz\">-- Keine Weiterleitungen eingerichtet --</td></tr>";
  }
  echo "<tr class=\"cms_multiselect_menue\"><td colspan=\"4\">";
  echo "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_weiterleitungen_loeschen();\"><span class=\"cms_hinweis\">Alle löschen</span><img src=\"res/icons/klein/loeschen.png\"></span>";
  echo "</tr>";
  echo "</table>";

  echo "<span class=\"cms_button_ja\" onclick=\"cms_neue_weiterleitung();\">+ Neue Weiterleitung einrichten</span>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
