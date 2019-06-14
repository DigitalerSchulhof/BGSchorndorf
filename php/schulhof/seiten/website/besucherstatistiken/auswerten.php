<?php
$kd = false;
function cms_besucherstatistik_schulhof($anzeigetyp, $start = 0, $ende = 0, $gesamt = false) {
  global $kd, $CMS_SCHLUESSEL;
  date_default_timezone_set("CET");

  // Start - Ende vorbereiten
  if($ende["jahr"] == 0 && $ende["monat"] == 0)
    $ende = array("jahr" => date("Y"), "monat" => date("m"));
  if($start["jahr"] == 0 && $start["monat"] == 0)
    $start = array("jahr" => $ende["jahr"]-($ende["monat"]-11<1?1:0), "monat" => $ende["monat"]-11<1?$ende["monat"]+1:$ende["monat"]-11);
  $dbs = cms_verbinden('s');
  if($gesamt) {
    $sql = "SELECT MIN(jahr) AS jahr, MIN(monat) AS monat FROM besucherstatistik_schulhof WHERE jahr = (SELECT MIN(jahr) FROM besucherstatistik_schulhof)";
    $anfrage = $dbs->query($sql);
    if($r = $anfrage->fetch_assoc()) {
      $start = array("jahr" => $r["jahr"], "monat" => $r["monat"]);
    }
    $sql = "SELECT MAX(jahr) AS jahr, MAX(monat) AS monat FROM besucherstatistik_schulhof WHERE jahr = (SELECT MAX(jahr) FROM besucherstatistik_schulhof)";
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
  if($anzeigetyp == "rollen_pie")
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

  if($anzeigetyp == "gesamtaufrufe_linie") {
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
    $datenS = $datenL = $datenE = $datenV = $datenLinie;
  }
  if($typ == "pie") {
    $datenPie = array();
  }
  if($typ == "horizontalBar") {
    $datenHBar = array();
  }

  // SQL Bauen
  $sql = "SELECT ";
  if($anzeigetyp == "gesamtaufrufe_linie" || $anzeigetyp == "rollen_pie" || $anzeigetyp == "bereiche_balken")
    $sql .= "jahr, monat, rolle, SUM(aufrufe) AS sum";
  else
    $sql .= "*";
  if($anzeigetyp == "bereiche_balken")
    $sql .= ", url";
  $sql .= " FROM besucherstatistik_schulhof";
  if(!$gesamt)//      MAX                                         MIN
    $sql .= " WHERE ((jahr = ? AND monat <= ?) OR (jahr < ?)) AND ((jahr = ? AND monat >= ?) OR (jahr > ?))";
  if($anzeigetyp == "gesamtaufrufe_linie")
    $sql .= " GROUP BY jahr, monat, rolle";
  if($anzeigetyp == "rollen_pie")
    $sql .= " GROUP BY rolle";
  if($anzeigetyp == "bereiche_balken")
    $sql .= " GROUP BY url";
  $sql = $dbs->prepare($sql);
  if(!$gesamt)
    $sql->bind_param("iiiiii", $ende["jahr"], $ende["monat"], $ende["jahr"], $start["jahr"], $start["monat"], $start["jahr"]);

  // Daten auswerten
  if ($sql->execute()) {
    $r = $sql->get_result();
    while($sqld = $r->fetch_assoc()) {
      if($anzeigetyp == "gesamtaufrufe_linie") {
        if($sqld["rolle"] == "s")
          $datenS[cms_fuehrendenull($sqld["jahr"])."-".cms_fuehrendenull($sqld["monat"])."-01"]=$sqld["sum"];
        if($sqld["rolle"] == "l")
          $datenL[cms_fuehrendenull($sqld["jahr"])."-".cms_fuehrendenull($sqld["monat"])."-01"]=$sqld["sum"];
        if($sqld["rolle"] == "e")
          $datenE[cms_fuehrendenull($sqld["jahr"])."-".cms_fuehrendenull($sqld["monat"])."-01"]=$sqld["sum"];
        if($sqld["rolle"] == "v")
          $datenV[cms_fuehrendenull($sqld["jahr"])."-".cms_fuehrendenull($sqld["monat"])."-01"]=$sqld["sum"];
      }
      if($anzeigetyp == "rollen_pie") {
        $rolle = $sqld["rolle"];
        $datenPie[$rolle] = (isset($datenPie[$rolle])?$datenPie[$rolle]:0)+$sqld["sum"];
      }
      if($anzeigetyp == "bereiche_balken") {
        $url = $sqld["url"];
        $abk = array("Schulhof/Nutzerkonto" => "Nutzerkonto",
                     "Schulhof/Anmeldung" => "Anmeldung",
                     "Schulhof/Passwort_vergessen" => "Anmeldung",
                     "Schulhof/Listen" => "Listen",
                     "Schulhof/Verwaltung" => "Verwaltung",
                     "Schulhof/Profile" => "Nutzerprofile",
                     "Schulhof/Website/Besucherstatistiken" => "Besucherstatistiken",
                     "Schulhof/Website/Termine" => "Termine",
                     "Schulhof/Website/Seiten" => "Seiten",
                     "Schulhof/Website/Hauptnavigationen" => "Hauptnavigationen",
                     "Schulhof/Website/Titelbilder" => "Titelbilder",
                     "Schulhof/Website/Blogeinträge" => "Blogeinträge",
                     "Schulhof/Website/Galerien" => "Galerien",
                     "Schulhof/Gruppen" => "Gruppen",
                     "Schulhof/Termine" => "Termine",
                     "Schulhof/Blog" => "Blog",
                     "Schulhof/Website" => "Website - Übersicht",
                     "Schulhof" => "Sonstiges"
                    );
        foreach($abk as $urlch => $name)
          if(substr($url, 0, strlen($urlch)) === $urlch) {
            $bereich = $name;
            break;
          }

        if($bereich == "")
          $bereich = $url;

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

  // Daten in Datensatz einspeisen
  if($anzeigetyp == "gesamtaufrufe_linie") {
    // Arrays in x => y umwandeln
    foreach($datenS as $t => $v)
      array_push($datenS, array("x" => $t, "y" => $v));
    $datenS = array_slice($datenS, count($datenS)/2);
    foreach($datenL as $t => $v)
      array_push($datenL, array("x" => $t, "y" => $v));
    $datenL = array_slice($datenL, count($datenL)/2);
    foreach($datenE as $t => $v)
      array_push($datenE, array("x" => $t, "y" => $v));
    $datenE = array_slice($datenE, count($datenE)/2);
    foreach($datenV as $t => $v)
      array_push($datenV, array("x" => $t, "y" => $v));
    $datenV = array_slice($datenV, count($datenV)/2);

    $datensatz[0]["label"] = "Schüler";
    $datensatz[0]["borderColor"] = "rgba(255, 255, 255, 1)";
    $datensatz[0]["backgroundColor"] = "rgba(169, 198, 127, 1)";
    $datensatz[0]["data"] = $datenS;

    $datensatz[1]["label"] = "Lehrer";
    $datensatz[1]["borderColor"] = "rgba(255, 255, 255, 1)";
    $datensatz[1]["backgroundColor"] = "rgba(255, 171, 145, 1)";
    $datensatz[1]["data"] = $datenL;

    $datensatz[2]["label"] = "Eltern";
    $datensatz[2]["borderColor"] = "rgba(255, 255, 255, 1)";
    $datensatz[2]["backgroundColor"] = "rgba(255, 218, 109, 1)";
    $datensatz[2]["data"] = $datenE;

    $datensatz[3]["label"] = "Verwaltung";
    $datensatz[3]["borderColor"] = "rgba(255, 255, 255, 1)";
    $datensatz[3]["backgroundColor"] = "rgba(194, 212, 221, 1)";
    $datensatz[3]["data"] = $datenV;
  }
  if($anzeigetyp == "rollen_pie") {
    $datenAufrufe = array(0,0,0,0);
    $datenLabels = array("Schüler", "Lehrer", "Eltern", "Verwaltung");
    $datenBG = array("rgba(169, 198, 127, 1.0)", "rgba(255, 171, 145, 1.0)", "rgba(255, 218, 109, 1.0)", "rgba(194, 212, 221, 1.0)");
    $datenB =  array("rgba(255, 255, 255, 1.0)", "rgba(255, 255, 255, 1.0)", "rgba(255, 255, 255, 1.0)", "rgba(255, 255, 255, 1.0)");
    $datenB = array("#fff", "#fff", "#fff", "#fff");
    foreach($datenPie as $r => $a) {
      if($r == "s") {
          $datenAufrufe[0] = $a;
      }
      if($r == "l") {
        $datenAufrufe[1] = $a;
      }
      if($r == "e") {
        $datenAufrufe[2] = $a;
      }
      if($r == "v") {
        $datenAufrufe[3] = $a;
      }
    }

    $datensatz[0]["backgroundColor"] = $datenBG;
    $datensatz[0]["hoverBackgroundColor"] = $datenBG;
    $datensatz[0]["borderColor"] = $datenB;
    $datensatz[0]["data"] = $datenAufrufe;

    $daten["labels"] = $datenLabels;
  }
  if($anzeigetyp == "bereiche_balken") {
    arsort($datenHBar); // Weil SQL messy ist
    $datenAufrufe = $datenLabels = $datenB = $datenBG = array();
    $max = -1;
    foreach($datenHBar as $r => $a) {
      $max == -1 && $max = $a;
      // Weniger als .5 %
      $p = $a/$max;

      // if($p < 0.005)
      //   break;
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

    $datensatz[0]["label"] = "Aufrufe";
  }

  $daten["datasets"] = $datensatz;

  // Generelles
  $optionen["responsive"] = true;
  $optionen["tooltips"]["mode"] = "index";
  $optionen["hover"]["mode"] = "index";
  $optionen["elements"]["point"]["radius"] = 3;

  // Überschrift
  $optionen["title"]["display"] = true;
  $optionen["title"]["text"] = "Seitenaufrufe ".cms_fuehrendenull($start["monat"]).".".cms_fuehrendenull($start["jahr"])." - ".$ende["monat"].".".$ende["jahr"]."";

  if($typ == "linie"){
    $skalen["xAxes"][0]["type"] = "time";
    $skalen["xAxes"][0]["time"]["unit"] = "month";
    $skalen["xAxes"][0]["time"]["tooltipFormat"] = "MMMM YYYY";

    $skalen["yAxes"][0]["stacked"] = true;
    $skalen["yAxes"][0]["ticks"]["beginAtZero"] = true;
    $skalen["yAxes"][0]["ticks"]["suggestedMin"] = 0;
    $skalen["yAxes"][0]["ticks"]["suggestedMax"] = 100;
  }

  if($typ == "balken") {
    $optionen["maintainAspectRatio"] = false;
    $skalen["xAxes"][0]["ticks"]["beginAtZero"] = true;
    $skalen["xAxes"][0]["ticks"]["suggestedMin"] = 0;
    $skalen["xAxes"][0]["ticks"]["suggestedMax"] = 100;
    $optionen["legend"]["display"] = false;
  }


  // Generiertes einspeisen
  $optionen["scales"] = $skalen;
  $config["options"] = $optionen;
  $config["data"] = $daten;
  $js .= "var c = new Chart(ctx, ".json_encode($config).");";
  if($anzeigetyp == "bereiche_balken")
    $js .= "c.canvas.parentNode.style.height = '".(34+count($datenHBar)*50)."px';";
  $js .= "Chart.defaults.global.defaultFontFamily = 'rob, sans-serif';";

  $code .= "<script>".$js."</script>";

  return $code;
}

function cms_besucherstatistik_schulhof_jahresplaettchen() {
  global $kd, $CMS_RECHTE;
  if(!$CMS_RECHTE['Website']['Besucherstatistiken - Schulhof sehen'])
    return; // Erroa kommt später
  $code = "";
  $code .= "<span id='cms_besucherstatistik_zeitraum_toggle_letzte' class='cms_toggle cms_toggle_aktiv cms_besucherstatistik_toggle' onclick='cms_besucherstatistik_schulhof_zeitraum(\"letzte\", 0, 0, 0, 0)'>Letzte zwölf Monate</span>";
  $code .= " <span id='cms_besucherstatistik_zeitraum_toggle_gesamt' class='cms_toggle cms_besucherstatistik_toggle' onclick='cms_besucherstatistik_schulhof_zeitraum(\"gesamt\", 0, 0, 0, 0, \"gesamt\")'>Gesamter Zeitraum</span>";
  $minJahr;
  $jahr = date("Y");
  $dbs = cms_verbinden('s');
  $sql = "SELECT MIN(jahr) AS jahr FROM besucherstatistik_schulhof";
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
    $code .= " <span id='cms_besucherstatistik_zeitraum_toggle_$jahr' class='cms_toggle cms_besucherstatistik_toggle' onclick='cms_besucherstatistik_schulhof_zeitraum($jahr, $jahr, 1, ".$jahr.", 12)'>".($jahr)."</span>";
    --$jahr;
  }
  return $code;
}

function cms_erfasse_click() {
  global $CMS_URL, $CMS_ANGEMELDET, $CMS_BENUTZERART, $CMS_URLGANZ, $CMS_SEITENDETAILS, $CMS_TERMINID, $CMS_BLOGID, $CMS_GALERIEID;
  $nichtf5 = true;
  if(!$nichtf5)
    return;

  $dbs = cms_verbinden('s');
  $startDesTages = mktime(0,0,0,date('m'),date('d'), date('Y'));
  $url = $CMS_URLGANZ;
  $jahr = date("Y");
  $monat = date("m");
  if($CMS_URL[0] == "Schulhof") {
    if($CMS_ANGEMELDET) {
      $rolle = $CMS_BENUTZERART;
      $sql = "SELECT aufrufe FROM besucherstatistik_schulhof WHERE jahr = '$jahr' AND monat = '$monat' AND url = '$url' AND rolle = '$rolle'";
      $anfrage = $dbs->query($sql);
      if ($anfrage->fetch_assoc()) {
          $sql = "UPDATE besucherstatistik_schulhof SET aufrufe = aufrufe + 1 WHERE jahr = $jahr AND monat = $monat AND url = '$url' AND rolle = '$rolle'";
      } else {
        $sql = "INSERT into besucherstatistik_schulhof (jahr, monat, rolle, url, aufrufe) VALUES ($jahr, $monat, '$rolle', '$url', 1)";
      }
      $dbs->query($sql);
    }
  }
  $tabelle = "";
  if(!is_null($CMS_SEITENDETAILS) && $CMS_URL[0] != "Schulhof" && (strlen($CMS_SEITENDETAILS['id']) > 0)) {
    $dbs->query("INSERT INTO urls (url) VALUES ('$CMS_URLGANZ')");
    $id = $CMS_SEITENDETAILS["id"];
    $tabelle = "besucherstatistik_website";
    if($CMS_SEITENDETAILS["art"] == "t") {
      $tabelle = "besucherstatistik_termine";
      $id = $CMS_TERMINID;
    }
    if($CMS_SEITENDETAILS["art"] == "b") {
      $tabelle = "besucherstatistik_blog";
      $id = $CMS_BLOGID;
    }
    if($CMS_SEITENDETAILS["art"] == "g") {
      $tabelle = "besucherstatistik_galerien";
      $id = $CMS_GALERIEID;
    }
    $sql = "SELECT aufrufe FROM $tabelle WHERE jahr = '$jahr' AND monat = '$monat' AND id = '$id'";
    $anfrage = $dbs->query($sql);
    if ($anfrage->fetch_assoc()) {
        $sql = "UPDATE $tabelle SET aufrufe = aufrufe + 1 WHERE jahr = $jahr AND monat = $monat AND id = $id";
    } else {
      $sql = "INSERT into $tabelle (jahr, monat, id, aufrufe) VALUES ($jahr, $monat, $id, 1)";
    }
    $anfrage = $dbs->query($sql);
  }
  cms_trennen($dbs);
}
?>
