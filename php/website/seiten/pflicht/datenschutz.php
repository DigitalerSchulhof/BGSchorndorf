<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Datenschutz</h1>
</div>

<div class="cms_spalte_2"><div class="cms_spalte_i">
  <h3>Verwantwortliche Personen</h3>
  <h4>Herausgeber</h4>
  <p>Verwantwortlich für die Verarbeitung von Daten auf dieser Website ist das Land Baden-Württemberg vertreten durch</p>
  <?php
    echo "<p>$CMS_NAMESCHULLEITER<br>$CMS_SCHULE<br>";
    echo "$CMS_STRASSE<br>";
    echo "$CMS_PLZORT";
    if (strlen($CMS_TELEFON) > 0) {echo "<br>Telefon: $CMS_TELEFON";}
    if (strlen($CMS_TELEFAX) > 0) {echo "<br>Fax: $CMS_TELEFAX";}
    if (strlen($CMS_MAILSCHULE) > 0) {echo "<br><a href=\"mailto:$CMS_MAILSCHULE\">$CMS_MAILSCHULE</a>";}
    echo "</p>";
  ?>
  <p>Vertretungsberechtigte entscheiden, ggf. mit Rücksprache anderer, über den Zweck der Verarbeitung von personenbezogenen Daten und welche Mittel dazu eingesetzt werden.</p>

  <h4>Verantwortlich im Sinne des Presserechts (§55 RStV)</h4>
  <?php
    echo "<p>$CMS_NAMEPRESSERECHT";
    if (strlen($CMS_MAILPRESSERECHT) > 0) {echo "<br><a href=\"mailto:$CMS_MAILPRESSERECHT\">$CMS_MAILPRESSERECHT</a>";}
    echo "</p>";
  ?>
  <h4>Datenschutzbeauftragter</h4>
  <?php
    echo "<p>$CMS_NAMEDATENSCHUTZ";
    if (strlen($CMS_MAILDATENSCHUTZ) > 0) {echo "<br><a href=\"mailto:$CMS_MAILDATENSCHUTZ\">$CMS_MAILDATENSCHUTZ</a>";}
    echo "</p>";
  ?>
  <h4>Technische Administration</h4>
  <?php
    echo "<p>$CMS_NAMEADMINISTRATION";
    if (strlen($CMS_MAILADMINISTRATION) > 0) {echo "<br><a href=\"mailto:$CMS_MAILADMINISTRATION\">$CMS_MAILADMINISTRATION</a>";}
    echo "</p>";
  ?>
</div></div>

<?php
$CMS_DSGVO_EINWILLIGUNG_A = false;
if (isset($_SESSION['DSGVO_EINWILLIGUNG_A'])) {$CMS_DSGVO_EINWILLIGUNG_A = $_SESSION['DSGVO_EINWILLIGUNG_A'];}
$CMS_DSGVO_EINWILLIGUNG_B = false;
if (isset($_SESSION['DSGVO_EINWILLIGUNG_B'])) {$CMS_DSGVO_EINWILLIGUNG_B = $_SESSION['DSGVO_EINWILLIGUNG_B'];}
if ($CMS_DSGVO_EINWILLIGUNG_A) {$einwilligungA = "<span id=\"cms_datenschutz_einwilligungA\" class=\"cms_datenschutz_einwilligungerteilt\" onclick=\"cms_dsgvo_datenschutz('-', 'n', '-')\">Einwilligung A erteilt</span>";}
else {$einwilligungA = "<span id=\"cms_datenschutz_einwilligungA\" class=\"cms_datenschutz_einwilligungverweigert\" onclick=\"cms_dsgvo_datenschutz('-', 'j', '-')\">Einwilligung A verweigert</span>";}
if ($CMS_DSGVO_EINWILLIGUNG_B) {$einwilligungB = "<span id=\"cms_datenschutz_einwilligungB\" class=\"cms_datenschutz_einwilligungerteilt\" onclick=\"cms_dsgvo_datenschutz('-', '-', 'n')\">Einwilligung B erteilt</span>";}
else {$einwilligungB = "<span id=\"cms_datenschutz_einwilligungB\" class=\"cms_datenschutz_einwilligungverweigert\" onclick=\"cms_dsgvo_datenschutz('-', '-', 'j')\">Einwilligung B verweigert</span>";}
?>

<div class="cms_spalte_2"><div class="cms_spalte_i">
  <h3>Ihre Datenschutzeinstellungen</h3>
  <h4>Einwilligung A</h4>
  <p>Ich gestatte dieser Website meine personenbezogenen Daten durch die Nutzung von Kontaktformularen an den gewählten Empfänger zu übermitteln. Ferner gestatte ich der Website die Art meines Gerätes in einem Cookie zu speichern, um die Ladezeit zu verbessern. Bei Smartphones wird darüberhinaus auch die geladene Navigation gespeichert.</p>
  <p><?php echo $einwilligungA; ?></p>
  <h4>Einwilligung B</h4>
  <p>Ich gestatte dieser Website Inhalte Dritter anzuzeigen und erkläre mich mit den Datenschutzvereinbarungen dieser Dritten Seiten einverstanden.</p>
  <p><?php echo $einwilligungB; ?></p>
</div></div>
<div class="cms_clear"></div>

<div class="cms_spalte_i">
  <ul class="cms_reitermenue">
    <li><span id="cms_reiter_datenschutz_0" class="cms_reiter_aktiv" onclick="javascript:cms_reiter('datenschutz', 0,1)">... auf der Website</a></li>
    <li><span id="cms_reiter_datenschutz_1" class="cms_reiter" onclick="javascript:cms_reiter('datenschutz', 1,1)">... im Schulhof</a></li>
  </ul>

  <div class="cms_reitermenue_o" id="cms_reiterfenster_datenschutz_0" style="display: block;">
    <div class="cms_reitermenue_i">
      <h2>Nutzung des öffentlichen Bereichs ohne Nutzerkonto</h2>

      <h3>Gespeicherte personenbezogene Daten</h3>
      <p>Personenbezogene Daten sind alle Daten, die auf Sie persönlich bezeihbar sind, z.B. Name, Adresse, eMail-Adressen oder ihr Nutzerverhalten.</p>

      <h4>Dienstleister</h4>
      <p>Diese Website kann nur durch Server eines Dienstleisters (<?php echo $CMS_HOSTINGPARTNEREX;?>) zur Verfügung gestellt werden. Alle eingeebenen Daten werde auf diesen Servern gespeichert oder verarbeitet.</p>

      <h4>Kontaktformular</h4>
      <p>Alle im Kontaktformular angegeben Daten werden nicht über die Nachricht selbst hinaus zwischengespeichert, sondern per eMail an den gewünschten Empfänger und auf Wunsch an Sie selbst geschickt. Es verbleibt keine Kopie auf den Servern der Website. Die eingegebenen Informationen werden nicht an Dritte weitergeben. Um diesen Dienst nutzen zu können ist <b>Einwilligung A</b> notwendig. Hier werden Cookies erzeugt, die sicherstellen, dass keine Spamnachrichten generiert werden. Diese Cookies werden nach dem Schließen des Browsers gelöscht und enthalten keine personenbezogenen Daten.</p>

      <h4>Dienste durch Dritte</h4>
      <p>Eingebundene Dienste Dritter können Ihre Daten außerhalb dieser Datenschutvereinbarung nutzen. Dazu gehören zum Beispiel Kartendienste oder Soziale Netzwerke. Um diese Dienste nutzen zu können ist <b>Einwilligung B</b> notwendig.</p>

      <h4>Newsletter</h4>
      <p>Die Anmeldung zu unserem Newsletter erfolgt absolut freiwillig und ausschließlich auf Ihre Initiative hin. Erhoben wird zu diesem Zweck Ihr voller Name sowie Ihre eMailadresse. Grundlage für die Erhebung dieser Daten ist daher Ihre Einwilligung (Art. 6 Abs. 1 lit. a DSGVO). Die angegeben Daten werden ausschließlich zum Versand des Newsletters verwendet und nicht an Dritte weitergegeben.</p>
      <p>Ein Widerruf Ihrer bereits erteilten Einwilligung ist jederzeit möglich. Nutzen Sie dazu bitte den Link zur Abmeldung vom Newsletter, der in jedem Newsletter ganz unten enthalten ist. Mit der Abmeldung vom Newsletter ist die automatische Löschung der Daten verbunden, die zum Zweck des Newsletterversands erhoben wurden.</p>

      <h4>Besucherstatistik</h4>
      <p>Zu statistischen Zwecken werden die Zugriffszahlen auf die jeweiligen Seiten gespeichert. Dabei werden keine personenbezogenen Daten erhoben, weshalb keine Einwilligung erforderlich ist. (Beispiel: 35.267 Zugriffe auf die Starseite im Monat Januar im Jahr 1857.)</p>

      <h4>Registrierung</h4>
      <p>Manche Teile dieses Online-Angebots lassen sich nur mit einer Registrierung einsehen. Alle übermittelten Daten dienen ausschließlich zum Zweck der Nutzung des Online-Angebots und zur Erfüllung unserer Aufgaben als Schule. Welche Daten von dieser Erhebung genau betroffen sind, kann dem Reiter »... im Schulhof« entnommen werden. Sie sind verpflichtet, zur Nutzung des Online-Angebots korrekte Angaben zu machen. Sollte ein Betrugsversuch entdeckt werden, behalten wir uns vor, Ihnen das Recht zur Nutzung des Online-Angebots entziehen.</p>

      <h3>Technische Informationen</h3>
      <h4>SSL-Verschlüsselung</h4>
      <p>Jede Übertragung von Daten zwischen dieser Seite und Ihnen erfolgt grundsätzlich verschlüsselt, damit diese Daten während der Übertragung nicht von Dritten eingesehen werden können. Verschlüsselte Verbindungen erkennen Sie an dem Präfix »https://« in der Adresszeile Ihres Browsers. Oft wird dazu ein Schlosssymbol angezeigt.</p>

      <h4>Server-Log-Dateien</h4>
      <p>Der Dienstleister dieser Seite (<?php echo $CMS_HOSTINGPARTNEREX; ?>) speichert beim Aufruf einer Seite automatisch den Zugriff, um Ihnen diese Website anzeigen zu können und ihre Sicherheit und Stabilität zu gewährlseiten. Ihr Browser übermittelt automatisch folgende Daten:</p>
      <ul>
        <li>besuchte Seiten dieser Domain</li>
        <li>Datum und Uhrzeit des Zugriffs auf die jeweilige Seite</li>
        <li>die Version und der Typ des verwendeten Browsers</li>
        <li>das von Ihnen verwendete Betriebssystem</li>
        <li>die Seite, über die eine Seite dieser Domain aufgerufen wurde (Referrer-URL)</li>
        <li>der Hostname des zugreifenden Rechners</li>
        <li>die IP-Adresse des Zugriffs</li>
      </ul>
      <p>Durch diese Daten können keine eindeutigen Rückschlüsse auf Sie als Person gezogen werden. Grundlage der Datenverarbeitung bildet Art. 6 Abs. 1 lit. b DSGVO, der die Verarbeitung von Daten zur Erfüllung eines Vertrags oder vorvertraglicher Maßnahmen gestattet, weshalb keine gesonderte Einwilligung notwendig ist.</p>

      <h4>Verkürzung der Ladezeit</h4>
      <p>Um die Anzeige dieser Website für Ihr Gerät zu optimieren, muss ermittelt werden, ob es sich um einen Desktop-Computer, ein Tablet oder ein Smartphone handelt. Um die Ladezeit dieser Seite zu verkürzen kann diese Information in einem Cookie zwischengespeichert werden. Diese Informationen werden nach dem Schließen des Browsers gelöscht. Um diesen Dienst zur Verfügung zu stellen, ist <b>Einwilligung A</b> nötig. Bei der Nutzung von Smartphones ist es von Vorteil bereits geladene Teile der Navigation zwischenzuspeichern, um Ladezeit zu sparen. Diese Informationen werden nach dem Schließen des Browsers gelöscht. Um diesen Dienst zur Verfügung zu stellen, ist <b>Einwilligung A</b> nötig.</p>

      <h3>Ihre Rechte</h3>
      <h4>Widerruf Ihrer Einwilligung zur Datenverarbeitung</h4>
      <p>Einige Angebote dieser Website sind ohne Ihrer ausdrücklichen Einwilligung zur Datenverarbeitung nicht möglich. Diese mit der Nutzung der Seite erteilte Einwilligung kann jederzeit widerrufen werden. Es genügt eine formlose Mitteilung per eMail. Die Rechtmäßigkeit der Verarbeitung Ihrer Daten bis zum Widerruf bleibt unberührt.</p>

      <h4>Beschwerde</h4>
      <p>Bei Verstößen gegen den Datenschutz haben Sie das Recht, Beschwerde bei der zuständigen Aufsichtsbehörde einzureichen. Zuständig ist der Datenschutzbeauftragte des Landes Baden-Württemberg.</p>

      <h4>Datenauskunft</h4>
      <p>Sie haben das Recht, selbst Einsicht in die über Sie automatisiert erhobenen Daten zu nehmen. Ferner können Sie Dritte benennen, denen das Datenmaterial übergeben werden muss. Die Bereitstellung der Daten erfolgt elektronisch. Sie erhalten eine *.pdf-Datei. Grenze der Bereitstellung ist die technische Machbarkeit.</p>

      <h4>Auskunft, Berichtigung, Sperrung, Löschung</h4>
      <p>Sie haben das Recht auf eine unentgeltliche Auskunft über alle personenbezogenen Daten, die über Sie gespeichert wurden: Wie sie erhoben wurden, wer sie erhalten hat, wo sie gespeichert sind und warum sie erhoben wurden. Sie sind berechtigt, eine Berichtigung, Sperrung oder Löschung dieser Daten zu beantragen.</p>
      <p>Für weitere Fragen nutzen Sie die bereitgestellten Kontaktmöglichkeiten.</p>


      <h3>Risiken durch unbefugten Zugriff</h3>
      <p>Es bestehen durch die vollständige Anonymisierung keine oder bestenfalls geringe Risiken für die Personen, die den öffentlichen Bereich der Website nutzen. Es ist nicht möglich aufgrund von reinen Zugriffsdaten Rückschlüsse auf einzelne Personen zu ziehen, die den öffentlichen Bereich der Website nutzen. Es werden keinerlei Daten gespeichert, die Verbindungen zwischen Zugriffszahlen und Personen zulassen.</p>
    </div>
  </div>

  <div class="cms_reitermenue_o" id="cms_reiterfenster_datenschutz_1">
    <div class="cms_reitermenue_i">
      <h2>... im Schulhof</h2>

      <?php
      echo cms_schulhof_rechte();
      echo cms_schulhof_datenschutz();
      ?>
    </div>
  </div>

</div></div>

<div class="cms_clear"></div>
