<?php
$kd = false;
function cms_besucherstatistik_website($seitenTyp, $anzeigetyp, $start = 0, $ende = 0, $gesamt = false) {
  global $kd, $CMS_SCHLUESSEL;
  date_default_timezone_set("CET");
  // Typ auswerten
  $tabelle = "";
  switch($seitenTyp) {
    case "t":
      $tabelle = "termine";
      break;
    case "g":
      $tabelle = "galerien";
      break;
    case "b":
      $tabelle = "blog";
      break;
    case "w":
      $tabelle = "website";
      break;
    default:
      return cms_meldung_fehler();
  }
  // Start - Ende vorbereiten
  if($ende["jahr"] == 0 && $ende["monat"] == 0)
    $ende = array("jahr" => date("Y"), "monat" => date("m"));
  if($start["jahr"] == 0 && $start["monat"] == 0)
    $start = array("jahr" => $ende["jahr"]-($ende["monat"]-11<1?1:0), "monat" => $ende["monat"]-11<1?$ende["monat"]+1:$ende["monat"]-11);
  $dbs = cms_verbinden('s');
  if($gesamt) {
    $sql = "SELECT MIN(jahr) AS jahr, MIN(monat) AS monat FROM besucherstatistik_$tabelle WHERE jahr = (SELECT MIN(jahr) FROM besucherstatistik_$tabelle)";
    $anfrage = $dbs->query($sql);
    if($r = $anfrage->fetch_assoc()) {
      $start = array("jahr" => $r["jahr"], "monat" => $r["monat"]);
    }
    $sql = "SELECT MAX(jahr) AS jahr, MAX(monat) AS monat FROM besucherstatistik_$tabelle WHERE jahr = (SELECT MAX(jahr) FROM besucherstatistik_$tabelle)";
    $anfrage = $dbs->query($sql);
    if($r = $anfrage->fetch_assoc()) {
      $ende = array("jahr" => $r["jahr"], "monat" => $r["monat"]);
    }
  }

  // Start - Ende auswerten und ggf. meckern
  if($start["jahr"] > $ende["jahr"] || ($start["jahr"] == $ende["jahr"] && $start["monat"] > $ende["monat"]))
    return cms_meldung_fehler();
  if($start["jahr"] == $ende["jahr"] && $start["monat"] == $ende["monat"]) {
    if(!$kd)
      echo '<div class="cms_meldung cms_meldung_warnung">Für eine ordentliche Darstellung sind nicht genügend Daten vorhanden.</div>';
    $kd = true;
    return;
  }

  if($anzeigetyp == "gesamtaufrufe_linie")
    $typ = "linie";
  if($anzeigetyp == "bereiche_pie")
    $typ = "pie";
  if($anzeigetyp == "bereiche_balken")
    $typ = "balken";

  // Variablen vorbereiten
  $code = "";
  $js = "";
  $config = array();
  $daten = array();
  $datensatz = array();
  $skalen = array();
  $optionen = array();

  //Nur ein Mal probieren
  if($anzeigetyp == "bereiche_balken")
    $js .= "var ctx = $('<canvas></canvas>', {style: 'margin-bottom: 100px'}).appendTo($('<div></div>', {style: 'position: relative'}).appendTo('#besucherstatistik'));";
  else
    $js .= "var ctx = $('<canvas></canvas>', {style: 'margin-bottom: 100px'}).appendTo('#besucherstatistik');";
  $js .= "ctx = ctx[0].getContext('2d');";

  // Config: Typ
  if($typ == "linie")
    $config["type"] = "line";

  if($typ == "pie")
    $config["type"] = "pie";

  if($typ == "balken")
    $config["type"] = "horizontalBar";

  // Arrays vorfüllen
  if($typ == "linie") {
    $monatI = $start["monat"];
    $jahrI = $start["jahr"];
    $datenLinie = array();
    while($jahrI < $ende["jahr"] || ($jahrI == $ende["jahr"] && $monatI <= $ende["monat"])) {
      $datenLinie[cms_fuehrendenull($jahrI)."-".cms_fuehrendenull($monatI)."-01"] = 0;
      if(++$monatI == 13) {
        $monatI = 1;
        $jahrI++;
      }
    }
  }
  if($typ == "pie") {
    $datenPie = array();
  }
  if($typ == "horizontalBar") {
    $datenHBar = array();
  }

  // SQL Bauen
  $sql = "SELECT ";
  if($anzeigetyp == "gesamtaufrufe_linie" || $anzeigetyyp = "bereiche_balken")
    $sql .= "jahr, monat, SUM(aufrufe) AS sum";
  else
    $sql .= "*";
  if($anzeigetyp == "bereiche_balken")
    $sql .= ", id";
  $sql .= " FROM besucherstatistik_$tabelle";
  if(!$gesamt)//      MAX                                         MIN
    $sql .= " WHERE ((jahr = ? AND monat <= ?) OR (jahr < ?)) AND ((jahr = ? AND monat >= ?) OR (jahr > ?))";
  if($anzeigetyp == "gesamtaufrufe_linie")
    $sql .= " GROUP BY jahr, monat";
  if($anzeigetyp == "bereiche_balken")
    $sql .= " GROUP BY id";
  if($anzeigetyp == "bereiche_balken")
    $sql .= " ORDER BY SUM(aufrufe) DESC";
  $sql = $dbs->prepare($sql);
  if(!$gesamt)
    $sql->bind_param("iiiiii", $ende["jahr"], $ende["monat"], $ende["jahr"], $start["jahr"], $start["monat"], $start["jahr"]);

  // Daten auswerten
  if ($sql->execute()) {
    $r = $sql->get_result();
    while($sqld = $r->fetch_assoc()) {
      if($anzeigetyp == "gesamtaufrufe_linie") {
        $datenLinie[cms_fuehrendenull($sqld["jahr"])."-".cms_fuehrendenull($sqld["monat"])."-01"] = $sqld["sum"];
      }
      if($anzeigetyp == "bereiche_pie") {
        $id = $sqld["id"];
        $bereich = "Nicht gefunden";
        $sql = "kommt noch";
        if($seitenTyp == "t") {
          $bereich = "Gelöschter Termin";
          $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS titel FROM termine WHERE id = $id";
        }
        if($seitenTyp == "g") {
          $bereich = "Noch nicht implementiert.";
          $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS titel FROM galerien WHERE id = $id";
        }
        if($seitenTyp == "b") {
          $bereich = "Gelöschter Blog";
          $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS titel FROM blogeintraege WHERE id = $id";
        }
        if($seitenTyp == "w") {
          $bereich = "Gelöschte Seite";
          $sql = "SELECT bezeichnung AS titel FROM seiten WHERE id = $id";
        }

        // Titel getten
        if ($anfrage = $dbs->query($sql)) {
      		if ($daten = $anfrage->fetch_assoc()) {
            $bereich = $daten["titel"];
      			$dbs->query($sql);
      		}
      		$anfrage->free();
      	}
        $datenPie[$bereich] = (isset($datenPie[$bereich])?$datenPie[$bereich]:0)+$sqld["aufrufe"];
      }
      if($anzeigetyp == "bereiche_balken") {
        $id = $sqld["id"];
        $bereich = "Nicht gefunden";
        $sql = "kommt noch";
        if($seitenTyp == "t") {
          $bereich = "Gelöschter Termin";
          $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS titel FROM termine WHERE id = $id";
        }
        if($seitenTyp == "g") {
          $bereich = "Noch nicht implementiert.";
          $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS titel FROM galerien WHERE id = $id";
        }
        if($seitenTyp == "b") {
          $bereich = "Gelöschter Blog";
          $sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS titel FROM blogeintraege WHERE id = $id";
        }
        if($seitenTyp == "w") {
          $bereich = "Gelöschte Seite";
          $sql = "SELECT bezeichnung AS titel FROM seiten WHERE id = $id";
        }

        // Titel getten
        if ($anfrage = $dbs->query($sql)) {
          if ($daten = $anfrage->fetch_assoc()) {
            $bereich = $daten["titel"];
            $dbs->query($sql);
          }
          $anfrage->free();
        }
        $datenHBar[$bereich] = (isset($datenHBar[$bereich])?$datenHBar[$bereich]:0)+$sqld["sum"];
      }
    }
    if($r->num_rows == 0) {
      if(!$kd)
        echo '<div class="cms_meldung cms_meldung_warnung">Für eine ordentliche Darstellung sind nicht genügend Daten vorhanden.</div>';
      $kd = true;
      return;
    }
  } else {
    if(!$kd)
      echo '<div class="cms_meldung cms_meldung_warnung">Für eine ordentliche Darstellung sind nicht genügend Daten vorhanden.</div>';
    $kd = true;
    return;
  }

  $startR = 144; $startG = 164; $startB = 174; $startA = 1.0;  // Start
  $endeR =  144; $endeG =  164; $endeB =  174; $endeA =  1.0;  // Ende

  $datensatz[0]["label"] = "Aufrufe";
  $datensatz[0]["borderColor"] = "#ffffff";
  $datensatz[0]["backgroundColor"] = "#C2D4DD";
  $datensatz[0]["hoverBackgroundColor"] = "#C2D4DD";

  // Daten in Datensatz einspeisen
  if($anzeigetyp == "gesamtaufrufe_linie") {
    foreach($datenLinie as $t => $v)
      array_push($datenLinie, array("x" => $t, "y" => $v));

    $datenLinie = array_slice($datenLinie, count($datenLinie)/2);

    $datensatz[0]["data"] = $datenLinie;
  }

  if($anzeigetyp == "bereiche_balken") {
    $datenAufrufe = $datenLabels = $datenB = $datenBG = array();
    $max = -1;
    foreach($datenHBar as $r => $a) {
      $max == -1 && $max = $a;
      // Weniger als .5 %
      $p = $a/$max;

      if($p < 0.005)
        break;
      array_push($datenAufrufe, $a);
      array_push($datenLabels, $r);

      $p += 0.20; // Kleiner Boost
      $p > 1 && $p = 1;

      $Cr = $startR; $Cg = $startG; $Cb = $startB; $Ca = $startA;
      $Er =  $endeR; $Eg =  $endeG; $Eb =  $endeB; $Ea =  $endeA;

      $Dr = $Er - $Cr;
      $Cr = floor($Cr + $Dr * $p);

      $Dg = $Eg - $Cg;
      $Cg = floor($Cg + $Dg * $p);

      $Db = $Eb - $Cb;
      $Cb = floor($Cb + $Db * $p);

      $Da = $Ea - $Ca;
      $Ca = floor(($Ca + $Da * $p)*100)/100;

      array_push($datenBG, "rgba($Cr, $Cg, $Cb, $Ca)");
      array_push($datenB, "rgba($Cr, $Cg, $Cb, $Ca)");
    }

    $datensatz[0]["borderColor"] = $datenB;
    $datensatz[0]["backgroundColor"] = $datenBG;
    $datensatz[0]["hoverBackgroundColor"] = $datenBG;

    $datensatz[0]["data"] = $datenAufrufe;

    $daten["labels"] = $datenLabels;
  }

  $daten["datasets"] = $datensatz;

  // Generelles
  $optionen["responsive"] = true;
  $optionen["tooltips"]["mode"] = "index";
  $optionen["hover"]["mode"] = "index";
  $optionen["elements"]["point"]["radius"] = 3;
  $optionen["legend"]["display"] = false;   // Immer "Aufrufe"

  // Überschrift
  $optionen["title"]["display"] = true;
  $optionen["title"]["text"] = "Seitenaufrufe ".cms_fuehrendenull($start["monat"]).".".cms_fuehrendenull($start["jahr"])." - ".$ende["monat"].".".$ende["jahr"]."";

  if($typ == "linie"){
    $skalen["xAxes"][0]["type"] = "time";
    $skalen["xAxes"][0]["time"]["unit"] = "month";
    $skalen["xAxes"][0]["time"]["tooltipFormat"] = "MMMM YYYY";

    $skalen["yAxes"][0]["ticks"]["beginAtZero"] = true;
    $skalen["yAxes"][0]["ticks"]["suggestedMin"] = 0;
    $skalen["yAxes"][0]["ticks"]["suggestedMax"] = 100;
  }

  if($typ == "balken") {
    $optionen["maintainAspectRatio"] = false;
    $skalen["xAxes"][0]["ticks"]["beginAtZero"] = true;
    $skalen["xAxes"][0]["ticks"]["suggestedMin"] = 0;
    $skalen["xAxes"][0]["ticks"]["suggestedMax"] = 100;
  }


  // Generiertes einspeisen
  $optionen["scales"] = $skalen;
  $config["options"] = $optionen;
  $config["data"] = $daten;

  $js .= "var c = new Chart(ctx, ".json_encode($config).");";
  if($anzeigetyp == "bereiche_balken")
    $js .= "c.canvas.parentNode.style.height = '".(34+count($datenAufrufe)*50)."px';";
  // if($anzeigetyp == "gesamtaufrufe_linie")
    // $js .= "var gradient = ctx.createLinearGradient(0, 0, 0, 400); gradient.addColorStop(1, 'rgba($startR,$startG,$startB, $startA)'); gradient.addColorStop(0.5, 'rgba($endeR,$endeG,$endeB, $endeA)'); c.data.datasets[0].backgroundColor = gradient; c.update();";
  $js .= "Chart.defaults.global.defaultFontFamily = 'rob, sans-serif';";
  $code .= "<script>".$js."</script>";

  return $code;
}

function cms_besucherstatistik_website_jahresplaettchen($typ) {
  global $kd, $CMS_RECHTE;
  if(!$CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'])
    return; // Erroa kommt später
  $code = "";

  $tabelle = "";
  switch($typ) {
    case "t":
      $tabelle = "termine";
      break;
    case "g":
      $tabelle = "galerien";
      break;
    case "b":
      $tabelle = "blog";
      break;
    case "w":
      $tabelle = "website";
      break;
    default:
      return cms_meldung_fehler();
  }

  $code .= "<span id='cms_besucherstatistik_zeitraum_toggle_letzte' class='cms_toggle cms_toggle_aktiv cms_besucherstatistik_toggle' onclick='cms_besucherstatistik_website_zeitraum(\"$typ\", \"letzte\", 0, 0, 0, 0)'>Letzte zwölf Monate</span>";
  $code .= " <span id='cms_besucherstatistik_zeitraum_toggle_gesamt' class='cms_toggle cms_besucherstatistik_toggle' onclick='cms_besucherstatistik_website_zeitraum(\"$typ\", \"gesamt\", 0, 0, 0, 0, \"gesamt\")'>Gesamter Zeitraum</span>";
  $minJahr;
  $jahr = date("Y");
  $dbs = cms_verbinden('s');
  $sql = "SELECT MIN(jahr) AS jahr FROM besucherstatistik_$tabelle";
  $anfrage = $dbs->query($sql);
  if(!$anfrage) {
    echo cms_meldung_fehler();
    return;
  }
  $sqld = $anfrage->fetch_assoc();
  $minJahr = intval($sqld["jahr"]);
  if($minJahr == 0) {
    echo '<div class="cms_meldung cms_meldung_warnung">Für eine ordentliche Darstellung sind nicht genügend Daten vorhanden.</div>';
    $kd = true;
    return;
  }
  while($jahr >= $minJahr) {
    $code .= " <span id='cms_besucherstatistik_zeitraum_toggle_$jahr' class='cms_toggle cms_besucherstatistik_toggle' onclick='cms_besucherstatistik_website_zeitraum(\"$typ\", $jahr, $jahr, 1, ".$jahr.", 12)'>".($jahr)."</span>";
    --$jahr;
  }
  return $code;
}
?>