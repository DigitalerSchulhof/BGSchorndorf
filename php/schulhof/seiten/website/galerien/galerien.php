<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Galerien</h1>

<?php
include_once('php/schulhof/seiten/website/galerien/galeriesuche.php');

$bearbeiten = $CMS_RECHTE['Website']['Galerien bearbeiten'];
$loeschen = $CMS_RECHTE['Website']['Galerien löschen'];
$anlegen = $CMS_RECHTE['Website']['Galerien anlegen'];
$anzeigen = $bearbeiten || $loeschen || $anlegen;


$canlegen = '';
$canzeigen = '';

$dbs = cms_verbinden('s');
// SQL für die gesuchten Galerien zusammenbauen
$sqlwhere = "";
$jahraktuell = date('Y');
$jahrgewaehlt = $jahraktuell;
$jahranfang = $jahrgewaehlt;
$jahrende = $jahrgewaehlt;
$jahre = false;

$sql = $dbs->prepare("SELECT MIN(datum) AS anfang, MAX(datum) AS ende FROM galerien");
if ($sql->execute()) {
  $sql->bind_result($anfang, $ende);
  if ($sql->fetch()) {
    if (!is_null($anfang)) {
      $jahranfang = min(date('Y', $anfang), $jahranfang);
      $jahrende = max(date('Y', $ende), $jahrende);
      $jahre = true;
    }
  }
}
$sql->close();

$spalten = 7;
$aktionen = false;

if ($jahre) {
  $canzeigen .= "<p>";
  for ($i = $jahrende; $i >= $jahranfang; $i--) {
    if ($i == $jahrgewaehlt) {$zusatzklasse = "_aktiv";} else {$zusatzklasse = '';}
    $canzeigen .= "<span id=\"cms_verwaltung_galerien_jahr_$i\" class=\"cms_toggle".$zusatzklasse."\" onclick=\"cms_galerieverwaltung('$i', '$spalten', '$jahranfang', '$jahrende')\">".$i."</span> ";
  }
  $canzeigen .= "</p>";
}
$canzeigen .= '<table class="cms_liste">';
$canzeigen .= '<thead>';
$canzeigen .= '<tr><th></th><th>Bezeichnung</th><th></th><th>Datum</th><th>Autor</th><th></th><th>Aktionen</th>';
$canzeigen .= "</thead>";
$canzeigen .= '<tbody id="cms_verwaltung_galerien_jahr">';
if (($jahraktuell < $jahranfang) || ($jahraktuell > $jahrende)) {
  $canzeigen .= '<tr><td class="cms_notiz" colspan="'.$spalten.'">Keine Galerien verfügbar</td></tr>';
}
else {
  $canzeigen .= cms_galerieverwaltung_suche($dbs, $jahraktuell, $anzeigen, $bearbeiten, $loeschen);
}
$canzeigen .= '</tbody>';
$canzeigen .= '</tr>';
$canzeigen .= '</table>';
$canzeigen .= '<p><input type="hidden" name="cms_verwaltung_galerien_jahr_angezeigt" id="cms_verwaltung_galerien_jahr_angezeigt" value="'.$jahraktuell.'"></p>';

if ($CMS_RECHTE['Website']['Galerien löschen']) {$canzeigen .= '<p><span class="cms_button_nein" onclick="cms_galerien_jahr_loeschen_vorbereiten()">Alle Galerien dieses Jahres löschen</span></p>';}

cms_trennen($dbs);

$code = '<h2>Bestehende Galerien nach Jahren</h2>';
$code .= $canzeigen;


if ($anlegen) {
  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neue_galerie('Schulhof/Website/Galerien')\">+ Neue Galerie</span></p>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
