<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Newsletter</h1>

<?php

$anlegen    = cms_r("schulhof.information.newsletter.anlegen");
$bearbeiten = cms_r("schulhof.information.newsletter.bearbeiten");
$loeschen   = cms_r("schulhof.information.newsletter.löschen");
$sehen      = cms_r("schulhof.information.newsletter.empfänger.sehen");
$anzeigen = $bearbeiten || $loeschen || $anlegen || $sehen;

$canzeigen = "";

$dbs = cms_verbinden('s');

$canzeigen .= '<table class="cms_liste">';
$canzeigen .= '<thead>';
$canzeigen .= '<tr><th></th><th>Bezeichnung</th><th></th><th>Aktionen</th>';
$canzeigen .= "</thead>";
$canzeigen .= '<tbody id="cms_verwaltung_newsletter">';
$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM newslettertypen";
$newsletter = "";

$anfrage = $dbs->query($sql);
while ($daten = $anfrage->fetch_assoc()) {
  $newsletter .= '<tr><td><img src="res/icons/klein/newsletter.png"></td><td>'.$daten['bezeichnung'].'</td>';
  $zuordnungen = "";
  foreach ($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    $sql = "SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."newsletter JOIN $gk ON gruppe = id WHERE newsletter = ".$daten['id'].") AS x ORDER BY bezeichnung ASC";
    if ($anfrage2 = $dbs->query($sql)) {
      while ($z = $anfrage2->fetch_assoc()) {
        $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$g." » ".$z['bezeichnung']."</span><img src=\"res/gruppen/klein/".$z['icon']."\"></span> "; ;
      }
      $anfrage2->free();
    }
  }
  $newsletter .= "<td>$zuordnungen</td>";
  $newsletter .= "<td>";
  if ($sehen) {
    $newsletter .= "<span class=\"cms_aktion_klein\" onclick=\"cms_newsletter_details_vorbereiten('".$daten['id']."', 'Schulhof/Website/Newsletter')\"><span class=\"cms_hinweis\">Empfängerliste sehen</span><img src=\"res/icons/klein/empfaengerliste.png\"></span> ";
  }
  if ($bearbeiten) {
    $newsletter .= "<span class=\"cms_aktion_klein\" onclick=\"cms_newsletter_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Website/Newsletter')\"><span class=\"cms_hinweis\">Newsletter bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
  }
  if ($loeschen) {
    $newsletter .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_newsletter_loeschen_vorbereiten('".$daten['id']."', '".$daten['bezeichnung']."', 'Schulhof/Website/Newsletter')\"><span class=\"cms_hinweis\">Newsletter löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
  }
  $newsletter .= '</td>';
  $newsletter .= '</tr>';
}
$anfrage->free();
if (strlen($newsletter) == 0)
  $canzeigen .= "<tr><td colspan=\"4\" class=\"cms_notiz\">-- keine Newsletter vorhanden --</td></tr>";
else
  $canzeigen .= $newsletter;


$canzeigen .= '</tbody>';
$canzeigen .= '</tr>';
$canzeigen .= '</table>';

if (cms_r("schulhof.information.newsletter.löschen")) {$canzeigen .= '<p><span class="cms_button_nein" onclick="cms_newsletter_alle_loeschen_vorbereiten()">Alle Newsletter löschen</span></p>';}

cms_trennen($dbs);

$code = '<h2>Newsletter</h2>';
$code .= $canzeigen;


if ($anlegen) {
  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_newsletter('Schulhof/Website/Newsletter')\">+ Neuer Newsletter</span></p>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
