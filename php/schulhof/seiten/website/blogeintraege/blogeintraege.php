<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Öffentliche Blogeinträge</h1>

<?php
include_once('php/schulhof/seiten/website/blogeintraege/blogeintragsuche.php');

$bearbeiten = cms_r("artikel.%ARTIKELSTUFEN%.blogeinträge.bearbeiten");
$loeschen   = cms_r("artikel.%ARTIKELSTUFEN%.blogeinträge.löschen");
$anzeigen   = $bearbeiten || $loeschen;

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

  $sql = $dbs->prepare("SELECT MIN(datum) AS anfang, MAX(datum) AS ende FROM blogeintraege");
  if ($sql->execute()) {
    $sql->bind_result($eanfang, $eende);
    if ($sql->fetch()) {
      if (!is_null($eanfang)) {
        $jahranfang = min(date('Y', $eanfang), $jahranfang);
        $jahrende = max(date('Y', $eende), $jahrende);
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
    $canzeigen .= cms_blogeintragverwaltung_suche($dbs, $jahraktuell);
  }
  $canzeigen .= '</tbody>';
  $canzeigen .= '</tr>';
  $canzeigen .= '</table>';
  $canzeigen .= '<p><input type="hidden" name="cms_verwaltung_blogeintraege_jahr_angezeigt" id="cms_verwaltung_blogeintraege_jahr_angezeigt" value="'.$jahraktuell.'"></p>';

  if (cms_r("artikel.%ARTIKELSTUFEN%.blogeinträge.löschen")) {$canzeigen .= '<p><span class="cms_button_nein" onclick="cms_blogeintraege_jahr_loeschen_vorbereiten()">Alle Blogeinträge dieses Jahres löschen</span></p>';}

  cms_trennen($dbs);

  $code = '<h2>Bestehende Blogeinträge nach Jahren</h2>';
  $code .= $canzeigen;


  if (cms_r("artikel.%ARTIKELSTUFEN.blogeinträge.anlegen")) {
    $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_blogeintrag('Schulhof/Website/Blogeinträge')\">+ Neuer öffentlicher Blogeintrag</span></p>";
  }
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
