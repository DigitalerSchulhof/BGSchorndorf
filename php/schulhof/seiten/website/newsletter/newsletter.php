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
$newsletter = "";

$NEWSLETTER = array();
$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM newslettertypen");
if ($sql->execute()) {
  $sql->bind_result($nid, $nbez);
  while ($sql->fetch()) {
    $N = array();
    $N['id'] = $nid;
    $N['bezeichnung'] = $nbez;
    array_push($NEWSLETTER, $N);
  }
}
$sql->close();


foreach ($NEWSLETTER AS $daten) {
  $hmeta = "<input type=\"hidden\" class=\"cms_multiselect_id\" value=\"{$daten['id']}\">";

  $newsletter .= '<tr><td class="cms_multiselect">'.$hmeta.'<img src="res/icons/klein/newsletter.png"></td><td>'.$daten['bezeichnung'].'</td>';
  $zuordnungen = "";
  foreach ($CMS_GRUPPEN as $g) {
    $gk = cms_textzudb($g);
    $sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."newsletter JOIN $gk ON gruppe = id WHERE newsletter = ?) AS x ORDER BY bezeichnung ASC");
    $sql->bind_param("i", $daten['id']);
    if ($sql->execute()) {
      $sql->bind_result($zbez, $zicon);
      while ($sql->fetch()) {
        $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$g » $zbez</span><img src=\"res/gruppen/klein/$zicon\"></span> ";
      }
    }
    $sql->close();
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

if (strlen($newsletter) == 0) {
  $canzeigen .= "<tr><td colspan=\"4\" class=\"cms_notiz\">-- keine Newsletter vorhanden --</td></tr>";
} else {
  $canzeigen .= $newsletter;
  $canzeigen .= "<tr class=\"cms_multiselect_menue\"><td colspan=\"4\">";
  if ($loeschen) {
    $canzeigen .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_multiselect_newsletter_loeschen_anzeigen('Schulhof/Website/Newsletter')\"><span class=\"cms_hinweis\">Alle löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
  }
  $canzeigen .= "</tr>";
}

$canzeigen .= '</tbody>';
$canzeigen .= '</tr>';
$canzeigen .= '</table>';

if ($loeschen) {$canzeigen .= '<p><span class="cms_button_nein" onclick="cms_newsletter_alle_loeschen_vorbereiten()">Alle Newsletter löschen</span></p>';}

cms_trennen($dbs);

$code = '<h2>Newsletter</h2>';
$code .= $canzeigen;


if ($anlegen) {
  $code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_neuer_newsletter('Schulhof/Website/Newsletter')\">+ Neuer Newsletter</span></p>";
}

echo $code.'</div>';
?>


<div class="cms_clear"></div>
