<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
</div>
<?php
include_once(dirname(__FILE__)."/../../../allgemein/funktionen/yaml.php");
use Async\YAML;

if (r("schulhof.verwaltung.rechte.bedingt || schulhof.verwaltung.rechte.rollen.bedingt")) {
  echo "<div class=\"cms_spalte_i\">".cms_meldung("warnung", "<h4>Technische Eingaben</h4><p>Gynmasialnivea vorrausgesetzt 😈</p>").cms_meldung("info", "<h4>Bedingte Rollen-/Rechtezuordnung</h4><p>Für Rollen und Rechte können Bedingungen gesetzt werden, die es ermöglichen, Benutzern anhand von gewissen Kriterien Rollen und Rechte zu vergeben.</p>".
  "<p><u>Zur Verfügung stehende Daten:</u><br>".
  "<b>zeit:</b> Aktuelle UNIX-Zeit (Zahl)<br>".
  "<b>nutzer.id</b> (Zahl)<br>".
  "<b>nutzer.vorname</b> (Zeichenkette)<br>".
  "<b>nutzer.nachname</b> (Zeichenkette)<br>".
  "<b>nutzer.titel</b> (Zeichenkette)<br>".
  "<b>nutzer.art</b> (Zeichenkette: \"s\" (Schüler), \"l\" (Lehrer), \"e\" (Eltern), \"v\" (Verwaltung), \"x\" (Externe))<br>".
  "<b>nutzer.imln:</b> Ist Benutzer im Lehrernetz (Boolean)<br>".
  "<b>nutzer.hatRolle[rolle]</b> (Boolean)<br>".
  "<u>Zur Verfügung stehende logische Operatoren:</u><br>".
  "<b>!</b> Nicht<br>".
  "<b>==</b> Ist gleich<br>".
  "<b>!=</b> Ist ungleich<br>".
  "<b>&lt;</b> Ist kleiner als<br>".
  "<b>&gt;</b> Ist größer als</p><p>".
  "<u>Beispiele:</u><br>".
  "Alle Lehrer oder Benutzer 97: <b>(nutzer.art == \"l\" || nutzer.id == 97)</b><br>".
  "Schüler namens »Max«: <b>(nutzer.art == \"s\" && nutzer.vorname == \"Max\")</b><br>".
  "Benutzer 97 bis zum Zeitpunkt x und alle Eltern: <b>((nutzer.id == 97 && zeit < 1577369965) || nutzer.art == \"e\")</b><br>".
  "Rolle »Stundenplanung« und im Lehrernetz: <b>(nutzer.imln && nutzer.hatRolle[\"Stundenplanung\"])</b><br>"
  )."</div>";


  if(r("schulhof.verwaltung.rechte.bedingt")) {
    $rechte = YAML::loader(dirname(__FILE__)."/../../../allgemein/funktionen/rechte/rechte.yml");
    $recht_machen = function($pfad, $recht, $kinder = null, $unterstes = false) use (&$recht_machen) {
      $code = "";

      $knoten = $recht;

      // Alternativer Knotenname
      if(!is_null($kinder) && !is_array($kinder))
        $recht = $kinder;
      if(is_array($kinder) && isset($kinder["knotenname"])) {
        $recht = $kinder["knotenname"];
        unset($kinder["knotenname"]);
      }

      $code .= "<div class=\"cms_recht".(is_array($kinder)?" cms_hat_kinder":"").($unterstes?" cms_recht_unterstes":"")."\" data-knoten=\"$knoten\"><i class=\"icon cms_recht_eingeklappt\"></i><span class=\"cms_recht_beschreibung\"><span class=\"cms_recht_beschreibung_i\" onclick=\"cms_bedingt_recht(this)\">".mb_ucfirst($recht)."</span></span>";

      // Kinder ausgeben
      $c = 0;
      if(is_array($kinder)) {
        $code .= "<div class=\"cms_rechtekinder\"".($recht?"style=\"display: none;\"":"").">";
        foreach($kinder as $n => $i)
          $code .= "<div class=\"cms_rechtebox".(!is_null($i) && !is_array($i)?" cms_recht_wert":"").(++$c==count($kinder)?" cms_recht_unterstes":"")."\">".$recht_machen("$pfad.$knoten", $n, $i, $c == count($kinder))."</div>";
        $code .= "</div>";
      }
      $code .= "</div>";
      return $code;
    };

    echo "<div class=\"cms_spalte_i\"><h1>Bedingte Rechtezuordnung</h1></div>";
    echo "<div class=\"cms_spalte_2\">";
      echo "<div class=\"cms_spalte_i\">";
        echo "<div id=\"cms_rechtepapa\" class=\"cms_spalte_i\">".$recht_machen("", "", $rechte, true)."</div>";
      echo "</div>";
    echo "</div>";
    echo "<div class=\"cms_spalte_2\">";
      echo "<div class=\"cms_spalte_i\">";
        echo "<table class=\"cms_liste\" id=\"cms_bedingte_rechte\">";
          echo "<thead>";
            echo "<tr><th>Recht</th><th>Bedingung</th><th></th></tr>";
          echo "</thead>";
          echo "<tbody>";
            $sql = "SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL'), AES_DECRYPT(bedingung, '$CMS_SCHLUESSEL') FROM bedingterechte";
            $sql = $dbs->prepare($sql);
            $sql->execute();
            $sql->bind_result($recht, $bedingung);
            while($sql->fetch()) {
              $recht          = htmlentities($recht);
              $bedingung      = htmlentities($bedingung);
              echo "<tr>".
                "<td><input class=\"cms_bedingt_recht_recht\" type=\"hidden\" value=\"$recht\">$recht</td>".
                "<td><input onkeyup=\"cms_bedingte_bedingung_syntax_pruefen(this);cms_bedingt_gui_von_string(this)\" class=\"cms_bedingt_bedingung\" type=\"text\" value=\"$bedingung\"></td>".
                "<td>".
                  "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_bedingt_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ".
                  "<span class=\"cms_syntax_ok cms_aktion_klein cms_button_ja\"><span class=\"cms_hinweis\">Syntax ist in Ordnung</span><img src=\"res/icons/klein/richtig.png\"></span>".
                  "<span style=\"display: none\" class=\"cms_syntax_fehler cms_aktion_klein cms_button_nein\"><span class=\"cms_hinweis\">Syntaxfehler!</span><img src=\"res/icons/klein/falsch.png\"></span>".
                "</td>".
              "</tr>";
            }
          echo "</tbody>";
        echo "</table>";
        echo "<span class=\"cms_button cms_button_ja\" onclick=\"cms_bedingte_rechte_speichern()\">Änderungen speichern</span>";
      echo "</div>";
    echo "</div>";
    echo "<div class=\"cms_clear\"></div>";
  }
  if(r("schulhof.verwaltung.rechte.rollen.bedingt")) {
    echo "<div class=\"cms_spalte_i\"><h1>Bedingte Rollenzuordnung</h1></div>";
    echo "<div class=\"cms_spalte_2\">";
      echo "<div class=\"cms_spalte_i\">";
        $rollencode = "";
        $sql = "SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') as bezeichnung, id FROM rollen WHERE id != 0) AS rollen ORDER BY id ASC";
        $sql = $dbs->prepare($sql);
        $sql->bind_result($bez, $rid);
        $sql->execute();
        while ($sql->fetch()) {
          $rollencode .= "<span class=\"cms_toggle\" onclick=\"cms_bedingt_rolle('$bez', $rid)\">$bez</span> ";
        }
        $sql->close();
        if(!$rollencode)
          $rollencode = "<p class=\"cms_notiz\">keine Rollen verfügbar</p>";
        echo $rollencode;
      echo "</div>";
    echo "</div>";
    echo "<div class=\"cms_spalte_2\">";
      echo "<div class=\"cms_spalte_i\">";
        echo "<table class=\"cms_liste\" id=\"cms_bedingte_rollen\">";
          echo "<thead>";
            echo "<tr><th>Rolle</th><th>Bedingung</th><th></th></tr>";
          echo "</thead>";
          echo "<tbody>";
            $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(bedingung, '$CMS_SCHLUESSEL'), rollen.id FROM bedingterollen JOIN rollen ON rollen.id = bedingterollen.rolle";
            $sql = $dbs->prepare($sql);
            $sql->execute();
            $sql->bind_result($rolle, $bedingung, $rid);
            while($sql->fetch()) {
              $rolle     = htmlentities($rolle);
              $bedingung = htmlentities($bedingung);
              echo "<tr>".
                "<td><input class=\"cms_bedingt_rolle_rolle\" type=\"hidden\" value=\"$rid\">$rolle</td>".
                "<td><input onkeyup=\"cms_bedingte_bedingung_syntax_pruefen(this)\" class=\"cms_bedingt_bedingung\" type=\"text\" value=\"$bedingung\"></td>".
                "<td>".
                  "<span class=\"cms_aktion_klein cms_button_nein\" onclick=\"cms_bedingt_loeschen(this)\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ".
                  "<span class=\"cms_syntax_ok cms_aktion_klein cms_button_ja\"><span class=\"cms_hinweis\">Syntax ist in Ordnung</span><img src=\"res/icons/klein/richtig.png\"></span>".
                  "<span style=\"display: none\" class=\"cms_syntax_fehler cms_aktion_klein cms_button_nein\"><span class=\"cms_hinweis\">Syntaxfehler!</span><img src=\"res/icons/klein/falsch.png\"></span>".
                "</td>".
              "</tr>";
            }
          echo "</tbody>";
        echo "</table>";
        echo "<span class=\"cms_button cms_button_ja\" onclick=\"cms_bedingte_rollen_speichern()\">Änderungen speichern</span>";
      echo "</div>";
    echo "</div>";
    echo "<div class=\"cms_clear\"></div>";
  }

  echo '<script>$(window).ready(function() {$(".cms_bedingt_bedingung").trigger("keyup"))</script>';

} else {
	echo cms_meldung_berechtigung();
  echo "</div>";
}
?>
<div class="cms_clear"></div>
