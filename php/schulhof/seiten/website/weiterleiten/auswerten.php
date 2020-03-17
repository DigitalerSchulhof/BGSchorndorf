<?php

function cms_weiterleitung_details($id = '-') {
  global $CMS_SCHLUESSEL;
  $dbs = cms_verbinden("s");
  $von = "";
  $zu = "";
  if($id == '-') {
    if(isset($_SESSION["WEITERLEITUNGZIEL"])) {
      $zu = $_SESSION["WEITERLEITUNGZIEL"];
    }
  } else {
    $sql = $dbs->prepare("SELECT AES_DECRYPT(von, '$CMS_SCHLUESSEL'), AES_DECRYPT(zu, '$CMS_SCHLUESSEL') FROM weiterleiten WHERE id = ?");
    $sql->bind_param("i", $id);
    $sql->bind_result($von, $zu);
    $sql->execute();
    if(!$sql->fetch()) {
      return cms_meldung_bastler();
    }
  }

  $code = "<h3>Neue URL:</h3>";
  $code .= "<input type=\"text\" name=\"cms_weiterleitung_von\" id=\"cms_weiterleitung_von\" value=\"$von\" placeholder=\"/FAQ/Anfahrt\">";
  $code .= "<h3>Zielseite:</h3>";
  $code .= "<input type=\"text\" name=\"cms_weiterleitung_zu\" id=\"cms_weiterleitung_zu\" value=\"$zu\" placeholder=\"/Website/Seiten/Information/Lage/Anfahrt\">";

  $code .= "<p>";
  if($id == '-') {
    $code .= "<span class=\"cms_button_ja\" onclick=\"cms_weiterleitung_neu_speichern()\">Weiterleitung einrichten</span>";
  } else {
    $code .= "<span class=\"cms_button_ja\" onclick=\"cms_weiterleitung_bearbeiten_speichern($id)\">Weiterleitung bearbeiten</span>";
  }
  $code .= " <span class=\"cms_button_nein\" onclick=\"cms_link('/Schulhof/Website/Weiterleiten')\">Abbrechen</span></p>";

  $dbs->close();
  return $code;
}

?>
