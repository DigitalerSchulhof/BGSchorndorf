<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Öffentliche Blogeinträge</h1>

<?php
include_once('php/schulhof/seiten/website/blogeintraege/blogeintragsuche.php');

$bearbeiten = $CMS_RECHTE['Website']['Blogeinträge bearbeiten'];
$loeschen = $CMS_RECHTE['Website']['Blogeinträge löschen'];
$anzeigen = $bearbeiten || $loeschen;

if ($anzeigen) {
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

  $sql = "SELECT MIN(datum) AS anfang, MAX(datum) AS ende FROM blogeintraege";
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
      $canzeigen .= "<span id=\"cms_verwaltung_blogeintraege_jahr_$i\" class=\"cms_toggle".$zusatzklasse."\" onclick=\"cms_blogeintragverwaltung('$i', '$spalten', '$jahranfang', '$jahrende')\">".$i."</span> ";
    }
    $canzeigen .= "</p>";
  }
  $canzeigen .= '<table class="cms_liste">';
  $canzeigen .= '<thead>';
  $canzeigen .= '<tr><th></th><th>Bezeichnung</th><th></th><th>Datum</th><th>Autor</th><th></th><th>Aktionen</th>';
  $canzeigen .= "</thead>";
  $canzeigen .= '<tbody id="cms_verwaltung_blogeintraege_jahr">';
  if (($jahraktuell < $jahranfang) || ($jahraktuell > $jahrende)) {
    $canzeigen .= '<tr><td class="cms_notiz" colspan="'.$spalten.'">Keine Blogeinträge verfügbar</td></tr>';
  }
  else {
    $canzeigen .= cms_blogeintragverwaltung_suche($dbs, $jahraktuell, $bearbeiten, $loeschen);
  }
  $canzeigen .= '</tbody>';
  $canzeigen .= '</tr>';
  $canzeigen .= '</table>';
  $canzeigen .= '<p><input type="hidden" name="cms_verwaltung_blogeintraege_jahr_angezeigt" id="cms_verwaltung_blogeintraege_jahr_angezeigt" value="'.$jahraktuell.'"></p>';

  if ($CMS_RECHTE['Website']['Blogeinträge löschen']) {$canzeigen .= '<p><span class="cms_button_nein" onclick="cms_blogeintraege_jahr_loeschen_vorbereiten()">Alle Blogeinträge dieses Jahres löschen</span></p>';}

  cms_trennen($dbs);

  $code = '<h2>Bestehende Blogeinträge nach Jahren</h2>';
  $code .= $canzeigen;


  if ($CMS_RECHTE['Website']['Blogeinträge anlegen']) {
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_blogeintrag('Schulhof/Website/Blogeinträge')\">+ Neuer öffentlicher Blogeintrag</span></p>";
  }
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
