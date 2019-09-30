<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>Schulhof aktualisieren</h1>

<?php

  if(!$CMS_RECHTE["Administration"]["Schulhof aktualisieren"])
    echo cms_meldung_berechtigung();
  else {
    $GitHub_base = "https://api.github.com/repos/oxydon/BGSchorndorf";
    $basis_verzeichnis = dirname(__FILE__)."/../../../../..";

    include_once("$basis_verzeichnis/php/schulhof/funktionen/neuerungen.php");

    $releases = array();


    if(!file_exists("$basis_verzeichnis/version")) {
      if($_SESSION["BENUTZERART"] == "s")
        echo cms_meldung("fehler", "<h4>Ungültige Version</h4><p>Bitte wende dich an die Entwickler.</p>");
      else
        echo cms_meldung("fehler", "<h4>Ungültige Version</h4><p>Bitte wenden Sie sich an die Entwickler.</p>");
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

      if(!($antwort = json_decode($antwort, true)))
        echo cms_meldung_fehler();
      else {
        foreach($antwort as $release) {
          if($release["draft"] || $release["prerelease"])
            continue;
          $releases[] = array(
            "version" => $release["name"],
            "inhalt" => $release["body"],
            "zeitpunkt" => $release["created_at"],
            "id" => $release["id"],
            "node" => $release["node_id"]
          );
        }

        usort($releases, function($a, $b) {
          return version_compare($a["version"], $b["version"], "lt");
        });

        if(version_compare($releases[0]["version"], $version, "gt")) {
          echo cms_meldung("erfolg", "<h4>Neue Version</h4><p>Es ist eine neue Version verfügbar: <b>".$releases[0]["version"]."</b></p>");
        }
        // Versionen ausgeben
        echo "<div class=\"cms_spalte_2\">";
          echo "<div class=\"cms_spalte_i\">";
            echo "<h2>Verfügbare Versionen</h2>";
            echo "<table class=\"cms_formular\">";
              foreach($releases as $release) {
                echo "<tr class=\"cms_release cms_release_".$release["id"]."\">";
                  echo "<th class=\"cms_release_v\">".$release["version"]."</th>";
                  echo "<td class=\"cms_release_i\">".$release["inhalt"]."</td>";
                  echo "<td><span class=\"cms_aktion_klein\" onclick=\"cms_release_waehlen('".$release["id"]."', '".$release["version"]."')\"><span class=\"cms_hinweis\">Version auswählen</span><img src=\"res/icons/klein/version_hoch.png\"></span></td>";
                echo "</tr>";
              }
            echo "</table>";
            echo "<h2>Neuerungen</h2>";
            echo cms_neuerungen();
          echo "</div>";
        echo "</div>";
        echo "<div class=\"cms_spalte_2\">";
          echo "<div class=\"cms_spalte_i\">";
            echo "<h2>Gewählte Version</h2>";
            echo "<table class=\"cms_formular\">";
              $code = "";
              foreach($releases as $release) {
                if($release["version"] != $version)
                  continue;
                $code .= "<tr id=\"cms_aktuelles_release\">";
                  $code .= "<th id=\"cms_aktuelles_release_v\">".$release["version"]."</th>";
                  $code .= "<td id=\"cms_aktuelles_release_i\">".$release["inhalt"]."</td>";
                  $code .= "<td><input type=\"hidden\" id=\"cms_gewaehltes_release\" value=\"".$release["id"]."\"></td>";
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
