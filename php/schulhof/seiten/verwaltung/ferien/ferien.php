<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Ferien</h1>

<?php
include_once('php/schulhof/seiten/verwaltung/ferien/feriensuche.php');

$bearbeiten = $CMS_RECHTE['Organisation']['Ferien bearbeiten'];
$loeschen = $CMS_RECHTE['Organisation']['Ferien löschen'];
$anlegen = $CMS_RECHTE['Organisation']['Ferien anlegen'];
$anzeigen = $bearbeiten || $loeschen || $anlegen;


$canlegen = '';
$canzeigen = '';

$dbs = cms_verbinden('s');
// SQL für die gesuchten Termine zusammenbauen
$sqlwhere = "";
$jahraktuell = date('Y');
$jahrgewaehlt = $jahraktuell;
$jahranfang = $jahrgewaehlt;
$jahrende = $jahrgewaehlt;
$jahre = false;

$sql = "SELECT MIN(beginn) AS anfang, MAX(ende) AS ende FROM ferien";
if ($anfrage = $dbs->query($sql)) {
  if ($daten = $anfrage->fetch_assoc()) {
    if (!is_null($daten['anfang'])) {
      $jahranfang = min(date('Y', $daten['anfang']), $jahranfang);
      $jahrende = max(date('Y', $daten['ende']), $jahrende);
      $jahre = true;
    }
  }
  $anfrage->free();
}

$spalten = 7;
$aktionen = false;

if ($jahre) {
  $canzeigen .= "<p>";
  for ($i = $jahrende; $i >= $jahranfang; $i--) {
    if ($i == $jahrgewaehlt) {$zusatzklasse = "_aktiv";} else {$zusatzklasse = '';}
    $canzeigen .= "<span id=\"cms_verwaltung_ferien_jahr_$i\" class=\"cms_toggle".$zusatzklasse."\" onclick=\"cms_ferienverwaltung('$i', '$spalten', '$jahranfang', '$jahrende')\">".$i."</span> ";
  }
  $canzeigen .= "</p>";
}
$canzeigen .= '<table class="cms_liste">';
$canzeigen .= '<thead>';
$canzeigen .= '<tr><th></th><th>Bezeichnung</th><th>Art</th><th>Beginn</th><th>Ende</th><th>Aktionen</th>';
$canzeigen .= "</thead>";
$canzeigen .= '<tbody id="cms_verwaltung_ferien_jahr">';
if (($jahraktuell < $jahranfang) || ($jahraktuell > $jahrende)) {
  $canzeigen .= '<tr><td class="cms_notiz" colspan="'.$spalten.'">Keine Ferien verfügbar</td></tr>';
}
else {
  $canzeigen .= cms_ferienverwaltung_suche($dbs, $jahraktuell, $anzeigen, $bearbeiten, $loeschen);
}
$canzeigen .= '</tbody>';
$canzeigen .= '</tr>';
$canzeigen .= '</table>';
$canzeigen .= '<p><input type="hidden" name="cms_verwaltung_ferien_jahr_angezeigt" id="cms_verwaltung_ferien_jahr_angezeigt" value="'.$jahraktuell.'"></p>';

if ($CMS_RECHTE['Organisation']['Ferien löschen']) {$canzeigen .= '<p><span class="cms_button_nein" onclick="cms_ferien_jahr_loeschen_vorbereiten()">Alle Ferien dieses Jahres löschen</span></p>';}

cms_trennen($dbs);

$code = '<h2>Bestehende Ferien nach Jahren</h2>';
$code .= $canzeigen;


if ($anlegen) {
  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neue_ferien()\">+ Neue Ferien</span></p>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
