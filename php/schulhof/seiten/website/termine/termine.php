<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Öffentliche Termine</h1>

<?php
include_once('php/schulhof/seiten/website/termine/terminsuche.php');

$bearbeiten = $CMS_RECHTE['Website']['Termine bearbeiten'];
$loeschen = $CMS_RECHTE['Website']['Termine löschen'];
$anlegen = $CMS_RECHTE['Website']['Termine anlegen'];
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

$sql = "SELECT MIN(beginn) AS anfang, MAX(ende) AS ende FROM termine";
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
    $canzeigen .= "<span id=\"cms_verwaltung_termine_jahr_$i\" class=\"cms_toggle".$zusatzklasse."\" onclick=\"cms_terminverwaltung('$i', '$spalten', '$jahranfang', '$jahrende')\">".$i."</span> ";
  }
  $canzeigen .= "</p>";
}
$canzeigen .= '<table class="cms_liste">';
$canzeigen .= '<thead>';
$canzeigen .= '<tr><th></th><th>Bezeichnung</th><th></th><th>Beginn</th><th>Ende</th><th></th><th>Aktionen</th>';
$canzeigen .= "</thead>";
$canzeigen .= '<tbody id="cms_verwaltung_termine_jahr">';
if (($jahraktuell < $jahranfang) || ($jahraktuell > $jahrende)) {
  $canzeigen .= '<tr><td class="cms_notiz" colspan="'.$spalten.'">Keine Termine verfügbar</td></tr>';
}
else {
  $canzeigen .= cms_terminverwaltung_suche($dbs, $jahraktuell, $anzeigen, $bearbeiten, $loeschen);
}
$canzeigen .= '</tbody>';
$canzeigen .= '</tr>';
$canzeigen .= '</table>';
$canzeigen .= '<p><input type="hidden" name="cms_verwaltung_termine_jahr_angezeigt" id="cms_verwaltung_termine_jahr_angezeigt" value="'.$jahraktuell.'"></p>';

if ($CMS_RECHTE['Website']['Termine löschen']) {$canzeigen .= '<p><span class="cms_button_nein" onclick="cms_termine_jahr_loeschen_vorbereiten()">Alle Termine dieses Jahres löschen</span></p>';}

cms_trennen($dbs);

$code = '<h2>Bestehende Termine nach Jahren</h2>';
$code .= $canzeigen;


if ($anlegen) {
  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_termin('Schulhof/Website/Termine')\">+ Neuer öffentlicher Termin</span></p>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
