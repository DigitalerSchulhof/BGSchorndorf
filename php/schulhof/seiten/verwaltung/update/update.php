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

    include_once("$basis_verzeichnis/php/schulhof/funktionen/neuerungen.php");

    $versionen = array(); // Tatsächliche Versionen (Aus origin/versionen.yml)
    $waehlbar = array();  // Wählbare Versionen     (Aus Releases)


    if(!file_exists("$basis_verzeichnis/version")) {
      echo cms_meldung("fehler", "<h4>Ungültige Version</h4><p>Bitte den Administrator benachrichtigen!</p>");
    } else {
      $version = trim(file_get_contents("$basis_verzeichnis/version"));

      // Versionen von GitHub holen
      $curl = curl_init();
      $curlConfig = array(
        CURLOPT_URL             => "$GitHub_base/releases",
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HTTPHEADER      => array(
          "Content-Type: application/json",
          "Authorization: token $GITHUB_OAUTH",
          "User-Agent: ".$_SERVER["HTTP_USER_AGENT"],
          "Accept: application/vnd.github.v3+json",
        )
      );
      curl_setopt_array($curl, $curlConfig);
      $antwort = curl_exec($curl);
      curl_close($curl);

      // Neuerungsverlauf von GitHub holen
      $curl = curl_init();
      $curlConfig = array(
        CURLOPT_URL             => "$GitHub_base/contents/versionen.yml?ref=updater", // TODO: REF RAUS
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

      if(($antwort = json_decode($antwort, true)) === null || ($versionen = @Yaml::loadString($versionenYaml)["version"]) === null)
        echo cms_meldung_fehler();
      else {
        foreach($antwort as $release) {
          if($release["draft"] || $release["prerelease"])
            continue;
          $waehlbar[] = array(
            "version" => $release["name"],
            "id" => $release["id"]
          );
        }

        usort($waehlbar, function($a, $b) {
          return version_compare($a["version"], $b["version"], "lt");
        });

        if(count($waehlbar))
          if(version_compare($waehlbar[0]["version"], $version, "gt")) {
            echo cms_meldung("erfolg", "<h4>Neue Version</h4><p>Es ist eine neue Version verfügbar: <b>".$waehlbar[0]["version"]."</b></p>");
          }


        // Versionen ausgeben
        echo "<div class=\"cms_spalte_2\">";
          echo "<div class=\"cms_spalte_i\">";
            echo "<h2>Verfügbare Versionen</h2>";
            echo "<table class=\"cms_formular\">";
              foreach($waehlbar as $w) {
                $v = $versionen[str_replace(".", "_", $w["version"])];
                $t = $v["tag"];
                $v = $v["version"];

                echo "<tr class=\"cms_release cms_release_".$w["id"]."\">";
                  echo "<th class=\"cms_release_v\">$v</th>";
                  echo "<td class=\"cms_release_t\">$t</td>";
                  echo "<td><span class=\"cms_aktion_klein\" onclick=\"cms_release_waehlen('".$release["id"]."', '$v')\"><span class=\"cms_hinweis\">Version auswählen</span><img src=\"res/icons/klein/version_hoch.png\"></span></td>";
                echo "</tr>";
              }
            echo "</table>";
            echo "<div class=\"cms_notiz\">Ältere Versionen werden nicht unterstützt.</div>";
          echo "</div>";
          echo "<div class=\"cms_spalte_i\">";
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
        echo "</div>";
        echo "<div class=\"cms_spalte_2\">";
          echo "<div class=\"cms_spalte_i\">";
            echo "<h2>Gewählte Version</h2>";
            echo "<table class=\"cms_formular\">";
              $code = "";
              foreach($waehlbar as $w) {
                if($w["version"] != $version)
                  continue;

                $v = $versionen[str_replace(".", "_", $version)];
                $t = $v["tag"];
                $v = $v["version"];

                $code .= "<tr id=\"cms_aktuelles_release\">";
                  $code .= "<th id=\"cms_aktuelles_release_v\">$v</th>";
                  $code .= "<td id=\"cms_aktuelles_release_t\">$t</td>";
                  $code .= "<td><input type=\"hidden\" id=\"cms_gewaehltes_release\" value=\"".$w["id"]."\"></td>";
                $code .= "</tr>";
              }
              if(!strlen($code))
                $code = cms_meldung_fehler();
              echo $code;
            echo "</table>";
            echo "<span class=\"cms_button_ja\" onclick=\"cms_release_hochladen_vorbereiten()\">Hochladen</span> ";
            echo "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung')\">Abbrechen</span>";
          echo "</div>";
        echo "</div>";
      }
    }
  }
?>
</div>
<div class="cms_clear"></div>
