<?php
function cms_schulhof_datenschutz() {
  global $CMS_BENUTZERART;
  $code = "";

  $code .= "<h2>Gespeicherte Daten nach Sicherheitskategorien</h2>";

  $code .= "<h3>Legende verwendeter Abkürzungen</h3>";
  $code .= "<ul>";
    $code .= "<li><b>EU-DSGVO:</b> Datenschutz-Grundverordnung der Europäischen Union</li>";
    $code .= "<li><b>SchG:</b> Schulgesetz des Landes Baden-Württemberg</li>";
    $code .= "<li><b>VwV:</b> Verwaltungsvorschrift des Landes Baden-Württemberg</li>";
    $code .= "<li><b>NB:</b> Netzbrief des Landes Baden-Württemberg</li>";
  $code .= "</ul>";

  $code .= "<ul class=\"cms_reitermenue\">";
    $code .= "<li><span id=\"cms_reiter_gespeicherte_daten_0\" class=\"cms_reiter_aktiv\" onclick=\"javascript:cms_reiter('gespeicherte_daten', 0,2)\">Sicherheitskategorie 1</a></li> ";
    $code .= "<li><span id=\"cms_reiter_gespeicherte_daten_1\" class=\"cms_reiter\" onclick=\"javascript:cms_reiter('gespeicherte_daten', 1,2)\">Sicherheitskategorie 2</a></li> ";
    $code .= "<li><span id=\"cms_reiter_gespeicherte_daten_2\" class=\"cms_reiter\" onclick=\"javascript:cms_reiter('gespeicherte_daten', 2,2)\">Sicherheitskategorie 3</a></li> ";
  $code .= "</ul>";

  $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_gespeicherte_daten_0\" style=\"display: block;\">";
    $code .= "<div class=\"cms_reitermenue_i\">";
    $code .= "<p>Die Daten in Sicherheitskategorie 1 liegen in verschlüsselter Form auf einem aus dem Internet zugänglichen Server.</p>";

    $code .= "<h4>Alle Benutzergruppen</h4>";
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr>";
        $code .= "<th>Daten</th>";
        $code .= "<th>Grundlage</th>";
        $code .= "<th>Zweck</th>";
        $code .= "<th>Sichtbar</th>";
        $code .= "<th>Löschung</th>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>Art des Nutzerkontos (Schüler, Eltern, Lehrer, Verwaltungspersonal)</td>";
        $code .= "<td>SchG</td>";
        $code .= "<td>Befugniskontrolle - Nötig, um die Daten nur denen zugänglich zu machen, die sie auch sehen dürfen.</td>";
        $code .= "<td>ganzer Schulhof</td>";
        $code .= "<td>Verlassen der Schule</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>Benutzername</td>";
        $code .= "<td>Einwilligung</td>";
        $code .= "<td>Zugriffskontrolle - Selbst festlegbar, Identifizierung des Benutzers.</td>";
        $code .= "<td>Verwalter von Personen</td>";
        $code .= "<td>Verlassen der Schule / Löschung des Nutzerkontos</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>Passwort</td>";
        $code .= "<td>Einwilligung</td>";
        $code .= "<td>Zugriffskontrolle - Selbst festlegbar, Identifizierung des Benutzers.</td>";
        $code .= "<td>niemand</td>";
        $code .= "<td>Verlassen der Schule / Löschung des Nutzerkontos</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>Vorname, Nachname, Titel, Geschlecht</td>";
        $code .= "<td>SchG</td>";
        $code .= "<td>Identifizierung der einzelnen Personen</td>";
        $code .= "<td>ganzer Schulhof</td>";
        $code .= "<td>Verlassen der Schule</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>eMailadresse</td>";
        $code .= "<td>Einwilligung</td>";
        $code .= "<td>Zur Nutzung des Schulhofs nötig. Benachrichtigungen werden auf Wunsch versendet, vergessene Passwörter können per eMail geändert werden.</td>";
        $code .= "<td>Verwalter von Personen</td>";
        $code .= "<td>Verlassen der Schule / Löschung des Nutzerkontos</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>Datum und Uhrzeit der letzten und vorletzten Anmeldung</td>";
        $code .= "<td>EU-DSGVO</td>";
        $code .= "<td>Sicherheitsprüfung - Identifizierung von Zugriffen durch fremde Personen</td>";
        $code .= "<td>Administrator</td>";
        $code .= "<td>übernächste und nächste Anmeldung</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>Onlinestatus</td>";
        $code .= "<td>EU-DSGVO</td>";
        $code .= "<td>Verfolgung von Missbrauch durch Identitätsdiebstahl</td>";
        $code .= "<td>Administrator</td>";
        $code .= "<td>Verlassen der Schule / Löschung des Nutzerkontos</td>";
      $code .= "</tr>";
      $code .= "<tr>";
        $code .= "<td>Nachrichten aus dem Postfach</td>";
        $code .= "<td>Einwilligung</td>";
        $code .= "<td>Sichere und verschlüsselte Kommunikation zwischen den Personen des Schullebens</td>";
        $code .= "<td>niemand</td>";
        $code .= "<td>je nach Einstellung / Verlassen der Schule / Löschung des Nutzerkontos</td>";
      $code .= "</tr>";
    $code .= "</table>";

    if (isset($CMS_BENUTZERART)) {
    if ($CMS_BENUTZERART == 'l') {
      $code .= "<h4>Daten von Lehrern</h4>";
      $code .= "<table class=\"cms_liste\">";
        $code .= "<tr>";
          $code .= "<th>Daten</th>";
          $code .= "<th>Grundlage</th>";
          $code .= "<th>Zweck</th>";
          $code .= "<th>Sichtbar</th>";
          $code .= "<th>Löschung</th>";
        $code .= "</tr>";
        $code .= "<tr>";
          $code .= "<td>Lehrerkürzel</td>";
          $code .= "<td>SchG</td>";
          $code .= "<td>Identifizierung der Lehrkraft</td>";
          $code .= "<td>ganzer Schulhof</td>";
          $code .= "<td>Verlassen der Schule</td>";
        $code .= "</tr>";
        $code .= "<tr>";
          $code .= "<td>Mitgliedschaft in Gremien</td>";
          $code .= "<td>SchG</td>";
          $code .= "<td>Zugriffskontrolle auf Daten der Gremien</td>";
          $code .= "<td>Mitglieder der jeweiligen Gremien / Verwalter von Gremien</td>";
          $code .= "<td>Verlassen der Schule</td>";
        $code .= "</tr>";
        $code .= "<tr>";
          $code .= "<td>Mitgliedschaft in Fachschaften</td>";
          $code .= "<td>SchG</td>";
          $code .= "<td>Zugriffskontrolle auf Daten der Fachschaften</td>";
          $code .= "<td>Mitglieder der jeweiligen Fachschaft / Verwalter von Fachschaften</td>";
          $code .= "<td>Verlassen der Schule</td>";
        $code .= "</tr>";
        $code .= "<tr>";
          $code .= "<td>Unterrichtete Fächer und Kurse</td>";
          $code .= "<td>SchG / EU-DSGVO</td>";
          $code .= "<td>Personalisierte Ausgabe der Vertretungen und Stundenplanung sowie eine Zugriffskontrolle auf entsprechende Daten</td>";
          $code .= "<td>Verwalter der Klassen und Kurse<br>Schüler der jeweiligen Kurse</td>";
          $code .= "<td>Verlassen der Schule</td>";
        $code .= "</tr>";
      $code .= "</table>";
    }

    if ($CMS_BENUTZERART == 's') {
      $code .= "<h4>Daten von Schülern</h4>";
      $code .= "<table class=\"cms_liste\">";
        $code .= "<tr>";
          $code .= "<th>Daten</th>";
          $code .= "<th>Grundlage</th>";
          $code .= "<th>Zweck</th>";
          $code .= "<th>Sichtbar</th>";
          $code .= "<th>Löschung</th>";
        $code .= "</tr>";
        $code .= "<tr>";
          $code .= "<td>Besuchte Fächer und Kurse</td>";
          $code .= "<td>SchG / EU-DSGVO</td>";
          $code .= "<td>Personalisierte Ausgabe der Vertretungen und Stundenplanung sowie eine Zugriffskontrolle auf entsprechende Daten</td>";
          $code .= "<td>Verwalter der Klassen und Kurse<br>Fach- und Kurslehrern</td>";
          $code .= "<td>Verlassen der Schule</td>";
        $code .= "</tr>";
      $code .= "</table>";
    }

    if ($CMS_BENUTZERART == 'v') {
      $code .= "<h4>Daten von Verwaltungspersonal</h4>";
      $code .= "<table class=\"cms_liste\">";
        $code .= "<tr>";
          $code .= "<th>Daten</th>";
          $code .= "<th>Grundlage</th>";
          $code .= "<th>Zweck</th>";
          $code .= "<th>Sichtbar</th>";
          $code .= "<th>Löschung</th>";
        $code .= "</tr>";
        $code .= "<tr>";
          $code .= "<td>Mitgliedschaft in Gremien</td>";
          $code .= "<td>SchG</td>";
          $code .= "<td>Zugriffskontrolle auf Daten der Gremien</td>";
          $code .= "<td>Mitglieder der jeweiligen Gremien / Verwalter von Gremien</td>";
          $code .= "<td>Verlassen der Schule / Löschung des Nutzerkontos</td>";
        $code .= "</tr>";
        $code .= "<tr>";
          $code .= "<td>Mitgliedschaft in Fachschaften</td>";
          $code .= "<td>SchG</td>";
          $code .= "<td>Zugriffskontrolle auf Daten der Fachschaften</td>";
          $code .= "<td>Mitglieder der jeweiligen Fachschaft / Verwalter von Fachschaften</td>";
          $code .= "<td>Verlassen der Schule / Löschung des Nutzerkontos</td>";
        $code .= "</tr>";
      $code .= "</table>";
    }
    }
    $code .= "</div>";
  $code .= "</div>";

  $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_gespeicherte_daten_1\">";
    $code .= "<div class=\"cms_reitermenue_i\">";
    $code .= "<p>Die Daten in Sicherheitskategorie 2 liegen in verschlüsselter Form auf einem aus dem Internet nur per VPN zugänglichen Server im lokalen Schulnetz.</p>";

    if (isset($CMS_BENUTZERART)) {
      if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) {
        $code .= "<table class=\"cms_liste\">";
          $code .= "<tr>";
            $code .= "<th>Daten</th>";
            $code .= "<th>Grundlage</th>";
            $code .= "<th>Zweck</th>";
            $code .= "<th>Dauer</th>";
            $code .= "<th>Sichtbar</th>";
          $code .= "</tr>";
          $code .= "<tr>";
            $code .= "<td>Protokolle</td>";
            $code .= "<td>SchG</td>";
            $code .= "<td>Information der gesamten Lehrerschaft über die Abläufe an der Schule</td>";
            $code .= "<td>Mitglieder der jeweiligen Gremien</td>";
            $code .= "<td>Kein Automatismus</td>";
          $code .= "</tr>";
        $code .= "</table>";
      }
      else {
        $code .= "<p class=\"cms_notiz\">Derzeit werden in dieser Kategorie keine Daten gespeichert.</p>";
      }
    }

    $code .= "</div>";
  $code .= "</div>";

  $code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_gespeicherte_daten_2\">";
    $code .= "<div class=\"cms_reitermenue_i\">";
    $code .= "<p>Die Daten in Sicherheitskategorie 3 liegen in verschlüsselter Form auf einem aus dem Internet per zweifacher Authentifizierung zugänglichen Server.</p>";

    $code .= "<p class=\"cms_notiz\">In Panung - Dieser Server wird noch nicht verwendet. Es wurden auch bislang keine Daten gespeichert.</p>";
    $code .= "</div>";
  $code .= "</div>";

  return $code;
}


function cms_schulhof_rechte() {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_ANGEMELDET, $CMS_RECHTE, $CMS_HOSTINGPARTNERIN;

  $link = "";
  $linkanzeige = "";
  if (!isset($CMS_BENUTZERID)) {
    $link = "Website/Datenschutz";
  }

  $code = "";
  $code .= "<p>Die folgenden Rechte ergeben sich aus der Datenschutzgrundverordnung der Europäischen Union (EU-DSGVO), sowie geltendem Recht bezüglich der Datenverarbeitung an Schulen in Form von Verwaltungsvorschriften (VwV) oder dem Schulgesetz (SchG), sowie der Netzbriefe (NB) des Landes Baden-Württemberg der Bundesrepublik Deutschland.</p>";

  $code .= "</div>";
  $code .= "<div class=\"cms_clear\"></div>";

  $code .= "<div class=\"cms_spalte_2\">";
  $code .= "<div class=\"cms_spalte_i\">";
  $code .= "<h2>Grundsätze</h2>";
  $code .= "<p>Die folgenden Grundsätze basieren auf Artikel 5 EU-DSGVO.</p>";
  $code .= "<ol>";
    if (strlen($link) == 0) {$linkanzeige = "Schulhof/Nutzerkonto/Mein_Profil/Gespeicherte_Daten";}
    else {$linkanzeige = $link;}
    $code .= "<li>Im Schulhof gespeicherte Daten basieren auf zwei Säulen: Entweder besteht eine Einwilligung in die Speicherung der Daten oder die Schule ist aufgrund von Gesetzen und Vorschriften berechtigt, die Daten zu verarbeiten. Welche Daten gespeichert werden und auf welcher Grundlage das geschieht ist unter <a href=\"$linkanzeige\">Gespeicherte Daten</a> einzusehen.</li>";
    $code .= "<li>Die gespeicherten Daten dienen alle einem jeweiligen Zweck. Auch dieser Zweck ist unter <a href=\"$linkanzeige\">Gespeicherte Daten</a> ersichtlich. Eine Verarbeitung zu anderen Zwecken wird nicht durchgeführt. Es besteht eine Informationspflicht, wenn sich die Gründe der Datenerhebung verändern.</li>";
    $code .= "<li>Es werden nicht mehr Daten gespeichert als für die Verarbeitung notwendig. Die gespeicherten Daten sind nur für die Personengruppen sichtbar, die zum Zugriff auf diese Daten berechtigt sind.</li>";
    $code .= "<li>Die hinterlegten Daten müssen sachlich korrekt und aktuell sein.</li>";
    $code .= "<li>Keine der hinterlegten Daten werden für immer gespeichert sondern sind an Fristen gebunden. Wie lange die jeweiligen Daten gespeichert werden, bzw. wann sie gelöscht werden ist unter <a href=\"$linkanzeige\">Gespeicherte Daten</a> einsehbar.</li>";
    $code .= "<li>Es wurden Maßnahmen ergriffen, um die Sicherheit der Daten zu gewährleisten. So liegen zum Beispiel alle gespeicherten Daten auf Servern der $CMS_HOSTINGPARTNERIN. Die Verbindung zum Server ist SSL-verschlüsselt, sodass sie auch während der Übertragung nicht lesbar sind. Ferner sind die Daten in verschlüsselter Form auf den Servern der $CMS_HOSTINGPARTNERIN gespeichert. Darüberhinaus sind die persönlichen Daten in Sicherheitskategorien eingeteilt. Je nach Sicherheitskategorie sind die Daten aus dem Internet verfügbar, oder in einem abgeschlossenen Bereich gespeichert, der nicht aus Internet, sondern nur aus einem lokalen Schulnetzwerk erreichbar ist.</li>";
  $code .= "</ol>";

  $code .= "<p>Aus den genannten Grundsätzen ergeben sich die nebenstehenden Rechte.</p>";

  $code .= "</div>";
  $code .= "</div>";

  $code .= "<div class=\"cms_spalte_2\">";
  $code .= "<div class=\"cms_spalte_i\">";
  $code .= "<h2>Rechte</h2>";
  $code .= "<h3>Rechenschaftspflicht</h3>";
  $code .= "<p>Die Schule muss nachweisen können, dass für die gespeicherten Daten entweder eine rechtliche Grundlage besteht, oder dass in die Speicherung eingewilligt wurde. Ferner ist die Schule in der Verantwortung die Daten so sicher wie möglich zu speichern.</p>";

  $code .= "<h3>Auskunftspflicht</h3>";
  $code .= "<p>Unter <a href=\"$linkanzeige\">Gespeicherte Daten</a> kann jederzeit eingesehen werden, welche Daten gespeichert werden, wer sie sieht, auf welcher Grundlage (Einwilligung oder Gesetz) die Daten gespeichert werden und zu welchem Zweck sie gespeichert sind. Entstehen darüber hinaus Fragen, ist die Schule verpflichtet, über die Datenhaltung und die gespeicherten personenbezogenen Daten Auskunft zu geben. Der Ansprechpartner dafür ist der Datenschutzbeauftragte der Schule.</p>";
  $code .= "<p>Für technische Fragen kann ein Administrator des Schulhofs kontaktiert werden.</p>";

  $code .= "<h3>Berichtigung</h3>";
  $code .= "<p>Sollten falsche Daten hinterlegt sein, die nicht direkt geändert werden können, so ist die Schule verpflichtet, die Daten zu berichtigen.</p>";

  $code .= "<h3>Löschen / Widerruf der Einwilligung in die Datenspeicherung</h3>";
  if (strlen($link) == 0) {$linkanzeige = "<a href=\"Schulhof/Nutzerkonto/Mein_Profil\">Profildaten</a>";}
  else {$linkanzeige = "Profildaten";}
  $code .= "<p>In den $linkanzeige können alle Benutzerdaten gelöscht werden, die auf der Einwilligung in die Speicherung beruhen. Damit ist der Widerruf der Einwilligung in die Speicherung von personenbezogenen Daten automatisch gewährleistet. Sie kann bei erneuter Kontoanlegung wieder erteilt werden.</p>";

  $code .= "<h3>Frist</h3>";
  $code .= "<p>Die Schule ist verpflichtet, binnen einem Monat Anfragen, Berichtigungen oder Löschungen nachzukommen.</p>";
  $code .= "</div>";
  $code .= "</div>";

  $code .= "<div class=\"cms_clear\"></div>";

  return $code;
}
?>
