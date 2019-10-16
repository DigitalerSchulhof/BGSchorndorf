<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Schulhof aktualisieren</h1>

<?php
  include_once(dirname(__FILE__)."/../../../../allgemein/funktionen/yaml.php");
  use Async\Yaml;


  if(!$CMS_RECHTE["Administration"]["Schulhof aktualisieren"])
    echo cms_meldung_berechtigung();
  else {
    $GitHub_base = "https://api.github.com/repos/oxydon/BGSchorndorf";
    $basis_verzeichnis = dirname(__FILE__)."/../../../../..";

    if(!file_exists("$basis_verzeichnis/version")) {
      echo cms_meldung("fehler", "<h4>Ungültige Version</h4><p>Bitte den Administrator benachrichtigen!</p>");
    } else {
      $version = trim(file_get_contents("$basis_verzeichnis/version"));

      // Versionsverlauf von GitHub holen
      $curl = curl_init();
      $curlConfig = array(
        CURLOPT_URL             => "$GitHub_base/releases/latest",
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HTTPHEADER      => array(
          "Content-Type: application/json",
          "Authorization: token $GITHUB_OAUTH",
          "User-Agent: ".$_SERVER["HTTP_USER_AGENT"],
          "Accept: application/vnd.github.v3+json",
        )
      );
      curl_setopt_array($curl, $curlConfig);
      $neuste = curl_exec($curl);
      curl_close($curl);

      // Neuerungsverlauf von GitHub holen
      $curl = curl_init();
      $curlConfig = array(
        CURLOPT_URL             => "$GitHub_base/contents/versionen.yml?ref=updater",
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HTTPHEADER      => array(
          "Content-Type: application/json",
          "Authorization: token $GITHUB_OAUTH",
          "User-Agent: ".$_SERVER["HTTP_USER_AGENT"],
          "Accept: application/vnd.github.VERSION.raw"
        )
      );
      curl_setopt_array($curl, $curlConfig);
      $versionenYaml = curl_exec($curl);
      curl_close($curl);

      if(($neuste = json_decode($neuste, true)) === null || ($versionen = @Yaml::loadString($versionenYaml)["version"]) === null)
        echo cms_meldung_fehler();
      else {
        $neusteversion = $neuste["name"];
        $neusteversiondatum = $neuste["created_at"];
        $neusteversionid = $neuste["id"];
        $assets = $neuste["assets"];

        if(version_compare($neusteversion, $version, "gt")) {
          echo cms_meldung("warnung", "<h4>Aktualisieren</h4><p>Der Programmcode sowie die Datenbanken des Digitalen Schulhofs werden automatisch aktualisiert. Sobald der Vorgang gestartet wird, ist die gesamte Website für einige Minuten nicht erreichbar.</p><p>Sollte die Website nach dem Update fehlerhaft funktionieren, ist der Administrator <b>umgehend</b> zu benachrichtigen.");
          echo cms_meldung("erfolg", "<h4>Neue Version</h4><p>Es ist eine neue Version verfügbar: <b>".$neusteversion."</b></p>");

          echo "<div class=\"cms_spalte_2\">";
            echo "<span class=\"cms_button_ja\" onclick=\"cms_schulhof_aktualisieren_vorbereiten();\">Schulhof aktualisieren</span> ";
            echo "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung')\">Zurück zur Übersicht</span>";
          echo "</div>";

          echo "<div class=\"cms_spalte_2\">";
            echo "<h2>Neuerungsverlauf</h2>";

            $aeltere = "";
            $versionCode = function ($v, $version, $sichtbar = 0) {
              $code = "<h4>".$version["version"]." - ".$version["tag"]."</h4>";
              $code .= "<ul>";
                foreach($version["neuerungen"] as $n)
                  $code .= "<li>$n</li>";
              $code .= "</ul>";
              return cms_toggleeinblenden_generieren ("cms_neuerungenverlaufknopf_$v", "Neuerungen in Version <b>".$version["version"]."</b> einblenden", "Neuerungen in Version <b>".$version["version"]."</b> ausblenden", $code, $sichtbar);
            };

            $vcopy = $versionen;
            echo $versionCode(array_keys($vcopy)[0], array_shift($vcopy), 1);

            foreach($vcopy as $k => $v) {
              $aeltere .= $versionCode($k, $v);
            }

            echo cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_aeltere', "Neuerungen älterer Versionen einblenden", "Neuerungen älterer Versionen ausblenden", $aeltere, 0);
          echo "</div>";
        } else {
          echo cms_meldung("erfolg", "<h4>Aktuelle Version</h4><p>Der Digitale Schulhof ist auf der neusten Version!</p>");
          echo "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung')\">Zurück zur Übersicht</span>";
        }
      }
    }
  }
?>
</div>
<div class="cms_clear"></div>
