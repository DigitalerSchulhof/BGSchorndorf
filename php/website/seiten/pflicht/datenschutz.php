<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Datenschutz</h1>
</div>

<div class="cms_spalte_i">
  <ul class="cms_reitermenue">
    <li><span id="cms_reiter_datenschutz_0" class="cms_reiter_aktiv" onclick="javascript:cms_reiter('datenschutz', 0,1)">... auf der Website</a></li>
    <li><span id="cms_reiter_datenschutz_1" class="cms_reiter" onclick="javascript:cms_reiter('datenschutz', 1,1)">... im Schulhof</a></li>
  </ul>

  <div class="cms_reitermenue_o" id="cms_reiterfenster_datenschutz_0" style="display: block;">
    <div class="cms_reitermenue_i">
      <h2>Nutzung des öffentlichen Bereichs ohne Nutzerkonto</h2>
      <h3>Zweck der Datenerhebung</h3>
      <p>Alle erhobenen Daten bei der Nutzung der öffentlich zur Verfügung stehenden Seiten werden ausschließlich anonymisiert und zu statistischen Zwecken genutzt.</p>


      <h3>Welche Daten werden gespeichert?</h3>
      <p>Es wird gespeichert in welchem Monat welche Seite wie oft aufgerufen wurde. Aufgrund dieser anonymen Daten und der relativ großen Zeitspanne, der die Zugriffszahlen zugeordnet werden, können keine Rückschlüsse auf die Personen geschlossen werden, die diese Website besuchen. ein Beispieldatensatz könnte also sein:<br>Startseite: 53.278 Besucher im Mai 2318</p>
      <p>Damit der Datenschutzhinweis nicht auf jeder Seite erneut angezeigt werden muss, wird in einem Cookie gespeichert, ob er bereits gelesen und akzeptiert wurde. Der Cookie für die öffentliche Website enthält keine weiteren persönlichen Daten. Der Cookie wird je nach Browsereinstellung gelöscht. Meist geschieht das automatisch mit dem Schließen (nicht Minimieren!) des Browsers.</p>


      <h3>Wer darf welche Daten sehen?</h3>
      <p>Die Zugriffsdaten sehen Personen, die durch den inhaltlich Verantwortlichen dieser Seite dazu beauftragt wurden.</p>


      <h3>Wie und wo werden die Daten gespeichert bzw. übertragen?</h3>
      <ol>
        <li>Sämtliche Daten der Website liegen auf Servern der Stadt Schorndorf.</li>
        <li>An die Datenübertragung werden höchste Sicherheitsstandards gestellt. Die Datenübertragung findet über eine gesicherte SSL-Verbindung statt.</li>
      </ol>


      <h3>Risiken durch unbefugten Zugriff</h3>
      <p>Es bestehen durch die vollständige Anonymisierung keine oder bestenfalls geringe Risiken für die Personen, die den öffentlichen Bereich der Website nutzen. Es ist nicht möglich aufgrund von reinen Zugriffszahlen Rückschlüsse auf einzelne Personen zu ziehen, die den öffentlichen Bereich der Website nutzen. Es werden keinerlei Daten gespeichert, die Verbindungen zwischen Zugriffszahlen und Personen zulassen.</p>
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
