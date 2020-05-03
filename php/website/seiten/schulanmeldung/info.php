<?php
include_once('php/website/seiten/schulanmeldung/navigation.php');
$CMS_VORANMELDUNG = cms_schulanmeldung_einstellungen_laden();
$code = "<div class=\"cms_spalte_i\">";

$code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";
$code .= "<h1>Voranmeldung</h1>";

$rueckgabe = cms_voranmeldung_zeit();
if ($rueckgabe['zulaessig']) {
  $code .= "<p>Herzlich willkommen bei der Online-Voranmeldung des ".$CMS_WICHTIG['Schulname Genitiv']." in ".$CMS_WICHTIG['Schule Ort'].". Bitte lesen sie die folgenden Informationen gewissenhaft.</p>";

  if (isset($_SESSION['VORANMELDUNG_FORTSCHRITT'])) {
    $code .= cms_voranmeldung_navigation(0);
  }

  $code .= "<h2>Informationen</h2>";
  $code .= $CMS_VORANMELDUNG['Anmeldung Einleitung'];

  $code .= "<h3>Datenschutzhinweise</h3>";
  $code .= "<ul>";
    $code .= "<li>Verantwortlich für die Erhebung der Daten ist die Schulleitung des ".$CMS_WICHTIG['Schulname Genitiv'].".</li>";
    $code .= "<li>Für Nachfragen können Sie gerne den Datenschutzbeuaftragten der Schule kontaktieren:</p><p><a class=\"cms_button\" href=\"mailto:".$CMS_WICHTIG['Datenschutz Mail']."\">".$CMS_WICHTIG['Datenschutz Name']."</a></li>";
    $code .= "<li>Der Zweck für die Datenerhebung ist die schnellere Abwicklung der Aufnahme an der Schule. Nach der Aufnahme werden die Daten zu unterrichtsorganisatorischen Zwecken gemäß des Schulgesetztes verwendet. Die online erhobenen Daten werden nach Abschluss des Anmeldeprozesses online gelöscht und nur noch lokal in der Schulverwaltungssoftware der Schule auf Grundlage des Schulgesetzes verarbeitet. Vorname und Nachname sowie Geschlecht und Kurszuordnung werden des Weiteren nach der Aufnahme an der Schule online im digitalen Schulhof zu unterrichtlichen Zwecken verarbeitet.</li>";
    $code .= "<li>Die personenbezogenen Daten können von der Schulverwaltung eingesehen werden. Des Weiteren verbleiben Vorname, Nachname, Geschlecht und Kurszuordnung nach einer vollständigen Anmeldung für Lehrer im digitalen Schulhof sichtbar. Mitschüler, die dieselben Kurse besuchen haben ebenfalls Zugriff auf Vorname und Nachname.</li>";
    $code .= "<li>Keine der personenbezogenen Daten werden an Drittländer oder dritte Personen weitergegeben.</li>";
    $code .= "<li><b>Anmeldezeitraum Online:</b> ".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung von'])).", den ".date('d.m.Y H:i', $CMS_VORANMELDUNG['Anmeldung von'])." bis ".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung bis'])).", den ".date('d.m.Y H:i', $CMS_VORANMELDUNG['Anmeldung bis']);
    $code .= "<br><b>Voraussichtliche Online-Löschung</b> bis ".date('d.m.Y H:i', $CMS_VORANMELDUNG['Anmeldung bis'] + $CMS_VORANMELDUNG['Anmeldung Überhang Tage']*24*60*60)." oder früher";
    $code .= "<br><b>Anmeldezeitraum Persönlich:</b> ".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung persönlich von'])).", den ".date('d.m.Y', $CMS_VORANMELDUNG['Anmeldung persönlich von'])." bis ".cms_tagnamekomplett(date('w', $CMS_VORANMELDUNG['Anmeldung persönlich bis'])).", den ".date('d.m.Y', $CMS_VORANMELDUNG['Anmeldung persönlich bis'])."</li>";
    $code .= "<li>Die Daten werden bis zur vollständigen Anmeldung an der Schule gemäß dem aktuellen Stand der Verschlüsselungstechnik und unter höchsten Sicherheitsvorkehrungen bezüglich der Zugriffskontrolle verschlüsselt auf Servern der $CMS_HOSTINGPARTNEREX gespeichert, die aus dem Internet zugänglich sind. Die Daten von Schülerinnen und Schülern, die nicht an der Schule aufgenommen werden, werden nach dem Abschluss der Anmeldung gelöscht. Die Daten von aufgenommenen Schülerinnen und Schülern werden nach dem Abschluss der Anmeldung online gelöscht und auf lokale Schulserver übertragen. Vorname, Nachname und Geschlecht von angemeldeten Schülerinnen und Schülern werden in den digitalen Schulhof übertragen. Sämtliche personenbezogenen Daten werden von allen Speichermedien gelöscht, wenn die Schülerin oder der Schüler die Schule verlässt.</li>";
    $code .= "<li>Mit der Verwendung dieser Online-Voranmeldung willigen Sie ein, dass die Daten wie oben genannt verarbeitet werden.</li>";
    $code .= "<li>Sie verfügen über die folgenden Rechte:";
      $code .= "<ul>";
        $code .= "<li><b>Recht auf Widerruf der Einwilligung in die Speicherung der Daten</b><br>Mit dem Widerruf ist die Löschung aller gespeicherten Daten verbunden, die auf Basis der Einwilligung erhoben wurden. Daten, die aufgrund von gesetzlichen Vorschriften gespeichert werden (z.B. Schulgesetz), bleiben von der Löschung unberührt. Welche Daten das sind, können beim oben genannten Datenschutzbeauftragten erfragt werden.</li>";
        $code .= "<li><b>Recht auf Rechenschaftsauskunft</b><br>Die Schule ist verpflichtet Ihnen auf Antrag die rechtliche Grundlage für die Speicherung und Verarbeitung der personenbezogenen Daten zu nennen.</li>";
        $code .= "<li><b>Recht auf Auskunft über gespeicherte Daten</b><br>Die Schule ist verpflichtet Ihnen auf Antrag eine Auskunft über die gespeicherten Daten Auskunft zu geben.</li>";
        $code .= "<li><b>Recht auf Berichtigung</b><br>Sollten Daten falsch hinterlegt sein, so ist die Schule verpflichtet, diese zu berichtigen.</li>";
        $code .= "<li><b>Recht auf Beschwerde</b><br>Sie haben das Recht sich bei der Aufsichtsbehörde der Schule zu beschweren.</li>";
        $code .= "<li><b>Fristen für die Bearbeitung von Anträgen</b><br>Die Schule ist verpflichtet, die genannten Anträge binnen eines Monats zu bearbeiten.</li>";
      $code .= "</ul>";
    $code .= "</li>";
    $code .= "<li>Für die korrekte Abwicklung der Voranmeldung werden Cookies verwendet. Das sind kleine Dateien auf Ihrem Computer, die je nach Browsereinstellungen - meist beim Schließen des Browsers - wieder gelöscht werden.</li>";
  $code .= "</ul>";

  $verbindlichkeit = 0;
  $gleichbehandlung = 0;
  $datenschutz = 0;
  $cookies = 0;
  if (isset($_SESSION['VORANMELDUNG_COOKIES'])) {
    if ($_SESSION['VORANMELDUNG_COOKIES'] == 1) {
      $cookies = $_SESSION['VORANMELDUNG_COOKIES'];
      if (isset($_SESSION['VORANMELDUNG_VERBINDLICHKEIT'])) {$verbindlichkeit = $_SESSION['VORANMELDUNG_VERBINDLICHKEIT'];}
      if (isset($_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'])) {$gleichbehandlung = $_SESSION['VORANMELDUNG_GLEICHBEHANDLUNG'];}
      if (isset($_SESSION['VORANMELDUNG_DATENSCHUTZ'])) {$datenschutz = $_SESSION['VORANMELDUNG_DATENSCHUTZ'];}
    }
  }

  $code .= "<h3>Voraussetzungen</h3>";
  $code .= "<table class=\"cms_formular\">";
    $code .= "<tr><th>Verbindlichkeit:</th><td>".cms_schieber_generieren('voranmeldung_verbindlichkeit', $verbindlichkeit)."</td><td>Mir ist bewusst, dass die Schulvoranmeldung <b>unverbindlich</b> erfolgt und dass wir zusätzlich an der Schule <b>persönlich vorstellig</b> werden müssen.</td></tr>";
    $code .= "<tr><th>Gleichbehandlung:</th><td>".cms_schieber_generieren('voranmeldung_gleichbehandlung', $gleichbehandlung)."</td><td>Mir ist bewusst, dass vorangemeldete Kinder nicht bevorzugt behandelt werden.</td></tr>";
    $code .= "<tr><th>Datenschutz:</th><td>".cms_schieber_generieren('voranmeldung_datenschutz', $datenschutz)."</td><td>Ich habe die Datenschutzhinweise gelesen und bin damit einverstanden.</td></tr>";
    $code .= "<tr><th>Cookies:</th><td>".cms_schieber_generieren('voranmeldung_cookies', $cookies)."</td><td>Ich bin mit der Verwendung von Cookies einverstanden.</td></tr>";
    $code .= "<tr><th></th><td></td><td><span class=\"cms_button_ja\" onclick=\"cms_voranmeldung_beginnen()\">Anmeldung beginnen</span></td></tr>";
  $code .= "</table>";
}
else {$code .= $rueckgabe['code'];}


$code .= "</div>";
echo $code;
?>
