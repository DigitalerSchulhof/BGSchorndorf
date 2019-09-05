<?php
$code = "";
$code .= "<div class=\"cms_spalte_i\">";
$code .= "<p class=\"cms_brotkrumen\">";
$MONATE = array('Januar', 'Februar', 'März', 'April', 'Mai', 'Juni', 'Juli', 'August', 'September', 'Oktober', 'November', 'Dezember');

if (!isset($CMS_URL[2]) || (!cms_check_ganzzahl($CMS_URL[2]))) {$CMS_URL[2] = date('Y');}
if (!isset($CMS_URL[3]) || (!in_array($CMS_URL[3], $MONATE))) {$CMS_URL[3] = cms_monatsnamekomplett(date('m'));}
if (!isset($CMS_URL[4]) || (!cms_check_ganzzahl($CMS_URL[4],0,31))) {$CMS_URL[4] = date('d');}
$code .= cms_brotkrumen($CMS_URL);
$code .= "</p>";
$code .= "<h1>Kalender</h1>";
  $fehler = false;
  if (isset($_SESSION["KALENDERANSICHTTAG"]) && isset($_SESSION["KALENDERANSICHTWOCHE"]) && isset($_SESSION["KALENDERANSICHTMONAT"]) &&
      isset($_SESSION["KALENDERANSICHTJAHR"]) && isset($_SESSION["KALENDERANSICHT"]) && isset($_SESSION["KALENDERTERMINEPERSOENLICH"]) &&
      isset($_SESSION["KALENDERTERMINEOEFFENTLICH"]) && isset($_SESSION["KALENDERTERMINEFERIEN"]) && isset($_SESSION["KALENDERTERMINESICHTBAR"])) {
    $ansicht = $_SESSION["KALENDERANSICHT"];
    $ansichtT = $_SESSION["KALENDERANSICHTTAG"];
    $ansichtW = $_SESSION["KALENDERANSICHTWOCHE"];
    $ansichtM = $_SESSION["KALENDERANSICHTMONAT"];
    $ansichtJ = $_SESSION["KALENDERANSICHTJAHR"];
    $ansichtP = $_SESSION["KALENDERTERMINEPERSOENLICH"];
    $ansichtO = $_SESSION["KALENDERTERMINEOEFFENTLICH"];
    $ansichtF = $_SESSION["KALENDERTERMINEFERIEN"];
    $ansichtS = $_SESSION["KALENDERTERMINESICHTBAR"];
  }
  else {$fehler = true;}
	foreach ($CMS_GRUPPEN as $g) {
		if (isset($_SESSION["KALENDERGRUPPEN: ".$g])) {
      $ansichtG[$g] = $_SESSION["KALENDERGRUPPEN: ".$g];
    }
    else {$fehler = true;}
	}

  if ($fehler) {$code .= cms_meldung_bastler();}

$code .= "</div>";

if (!$fehler) {
  include_once('php/schulhof/seiten/termine/termineausgeben.php');
  $url = implode('/', $CMS_URL);
  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Filter</h2>";
  $code .= "<table class=\"cms_liste\">";
    $code .= "<tr><th>Ansicht:</th><td>";
      $code .= cms_togglebutton_generieren ('cms_kalender_tagansicht', 'Tag', $ansichtT, "cms_kalender_ansicht_erneuern('tag');cms_kalender_neu('$url');")." ";
      $code .= cms_togglebutton_generieren ('cms_kalender_wocheansicht', 'Woche', $ansichtW, "cms_kalender_ansicht_erneuern('woche');cms_kalender_neu('$url');")." ";
      $code .= cms_togglebutton_generieren ('cms_kalender_monatansicht', 'Monat', $ansichtM, "cms_kalender_ansicht_erneuern('monat');cms_kalender_neu('$url');")." ";
      $code .= cms_togglebutton_generieren ('cms_kalender_jahransicht', 'Jahr', $ansichtJ, "cms_kalender_ansicht_erneuern('jahr');cms_kalender_neu('$url');")." ";
      $code .= "<input type=\"hidden\" id=\"cms_kalender_ansicht\" name=\"cms_kalender_ansicht\" value=\"$ansicht\"> ";
    $code .= "</td></tr>";
    $code .= "<tr><th>Termine:</th><td>";
      $code .= cms_togglebutton_generieren ('cms_kalender_persoenlich', 'Persönlich', $ansichtP, "cms_kalender_neu('$url');")." ";
      $code .= cms_togglebutton_generieren ('cms_kalender_oeffentlich', 'Öffentlich', $ansichtO, "cms_kalender_neu('$url');")." ";
      $code .= cms_togglebutton_generieren ('cms_kalender_ferien', 'Ferien', $ansichtF, "cms_kalender_neu('$url');")." ";
      //$code .= cms_togglebutton_generieren ('cms_kalender_sichtbar', 'Sichtbar', $ansichtS, "cms_kalender_sichtbaretermine();cms_kalender_neu('$url');")." ";
    $code .= "</td></tr>";
    if ($ansichtS == '1') {$style = "display: table-row;";} else {$style = "display: none;";}
    $code .= "<tr style=\"$style\" id=\"cms_kalender_gruppenansicht\"><th>Gruppen:</th><td>";
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $code .= cms_togglebutton_generieren ('cms_kalender_gruppen_'.$gk, $g, $ansichtG[$g], "cms_kalender_neu('$url');")." ";
    }
    $code .= "</td></tr></table><table class=\"cms_liste\">";
  $code .= "</table>";
  $code .= "</div></div>";

  $code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Übersicht</h2>";
  $jahr = $CMS_URL[2];
  $monat = cms_monatnamezuzahl($CMS_URL[3]);
  $tag = $CMS_URL[4];

  $monatsbeginn = mktime(0, 0, 0, $monat, 1, $jahr);
  $monatsende = mktime(0, 0, 0, $monat+1, 1, $jahr)-1;
  $suchbeginn = $monatsbeginn;
  $suchende = $monatsende;
  $naechstermonat = mktime(0, 0, 0, $monat+1, 1, $jahr);
  $letztermonat = mktime(0, 0, 0, $monat-1, 1, $jahr);
  $nm = cms_monatsnamekomplett(date('m', $naechstermonat));
  $nj = date('Y', $naechstermonat);
  $jm = cms_monatsnamekomplett(date('m', $monatsbeginn));
  $jj = date('Y', $monatsbeginn);
  $vm = cms_monatsnamekomplett(date('m', $letztermonat));
  $vj = date('Y', $letztermonat);

  $letztertag = date('d', $naechstermonat-1);

  $heute = time();
  $tagH = date('d', $heute);
  $monatH = date('m', $heute);
  $jahrH = date('Y', $heute);

  // Sichtbarkeitszeitraum
  if ($ansichtT == 1) {
    $sichtbarbeginn = mktime(0,0,0,$monat,$tag,$jahr);
    $sichtbarende = mktime(0,0,0,$monat,$tag+1,$jahr)-1;
  }
  else if ($ansichtW == 1) {
    $sichtbarbeginn = mktime(0,0,0,$monat,$tag,$jahr);
    $sichtbarende = mktime(0,0,0,$monat,$tag+7,$jahr)-1;
  }
  else if ($ansichtM == 1) {
    $sichtbarbeginn = mktime(0,0,0,$monat,1,$jahr);
    $sichtbarende = mktime(0,0,0,$monat+1,$tag,$jahr)-1;
  }
  else if ($ansichtJ == 1) {
    $sichtbarbeginn = mktime(0,0,0,1,1,$jahr);
    $sichtbarende = mktime(0,0,0,1,1,$jahr+1)-1;
  }

  $suchbeginn = min($monatsbeginn, $sichtbarbeginn);
  $suchende = max($monatsende, $sichtbarende);


  for ($i = 1; $i <= $letztertag; $i++) {
    $uebersichtsbelegung[$i]['P'] = false;
    $uebersichtsbelegung[$i]['O'] = false;
    $uebersichtsbelegung[$i]['F'] = false;
    $uebersichtsbelegung[$i]['S'] = false;
  }

  $termineansicht['P'] = array();
  $termineansicht['O'] = array();
  $termineansicht['F'] = array();
  $termineansicht['S'] = array();



  if ($ansichtF == 1) {
    // FERIEN LADEN
    $sql = "SELECT bezeichnung, art, beginn, ende, mehrtaegigt, '' AS text, '0' AS ortt, '0' AS uhrzeitbt, '0' AS uhrzeitet, '1' AS genehmigt FROM ferien WHERE (beginn BETWEEN $suchbeginn AND $suchende) OR (ende BETWEEN $suchbeginn AND $suchende) OR";
    $sql .= " (beginn < $suchbeginn AND ende > $suchende) ORDER BY beginn ASC, ende DESC";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        // Tag des Beginns erhalten
        $beginntag = ceil(($daten['beginn'] + 1 - $monatsbeginn)/60/60/24); if ($beginntag < 0) {$beginntag = 1;}
        $endetag = ceil(($daten['ende'] - $monatsbeginn)/60/60/24); if ($endetag > $letztertag) {$endetag = $letztertag;}
        for ($t=$beginntag; $t<=$endetag; $t++) {$uebersichtsbelegung[$t]['F'] = true;}

        if (($daten['beginn'] <= $sichtbarbeginn) && ($daten['ende'] >= $sichtbarbeginn) ||
            ($daten['ende'] >= $sichtbarende) && ($daten['beginn'] <= $sichtbarende) ||
            ($daten['beginn'] >= $sichtbarbeginn) && ($daten['ende'] <= $sichtbarende)) {
          array_push($termineansicht['F'], $daten);
        }
      }
      $anfrage->free();
    }
  }

  $oeffentlichausschluss = "";

  if ($ansichtP == 1) {
    // Persönliche Termine LADEN
    $gruppensuche = "";
    $sqlintern = "";
    foreach ($CMS_GRUPPEN as $g) {
      $gk = cms_textzudb($g);
      $gruppensuche .= " UNION (SELECT DISTINCT termin FROM $gk"."termine JOIN $gk"."mitglieder ON $gk"."mitglieder.gruppe = $gk"."termine.gruppe WHERE person = $CMS_BENUTZERID)";
      $gruppensuche .= " UNION (SELECT DISTINCT termin FROM $gk"."termine JOIN $gk"."aufsicht ON $gk"."aufsicht.gruppe = $gk"."termine.gruppe WHERE person = $CMS_BENUTZERID)";

      $sqlintern .= " UNION (SELECT id, '$g' AS gruppenart, $gk"."termineintern.gruppe AS gruppe, 'in' AS art, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM $gk"."termineintern WHERE gruppe IN (SELECT gruppe FROM $gk"."mitglieder WHERE person = $CMS_BENUTZERID UNION SELECT gruppe FROM $gk"."aufsicht WHERE person = $CMS_BENUTZERID))";
    }
    $gruppensuche = "SELECT DISTINCT termin FROM (".substr($gruppensuche, 7).") AS x";
    $sql = "SELECT * FROM ((SELECT id, '' AS gruppenart, '' AS gruppe, 'oe' AS art, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM termine WHERE aktiv = 1 AND id IN ($gruppensuche)) $sqlintern) AS x WHERE (beginn BETWEEN $suchbeginn AND $suchende) OR (ende BETWEEN $suchbeginn AND $suchende) OR";
    $sql .= " (beginn < $suchbeginn AND ende > $suchende) ORDER BY beginn ASC, ende DESC";

    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        // Tag des Beginns erhalten
        $beginntag = ceil(($daten['beginn'] + 1 - $monatsbeginn)/60/60/24); if ($beginntag < 0) {$beginntag = 1;}
        $endetag = ceil(($daten['ende'] - $monatsbeginn)/60/60/24); if ($endetag > $letztertag) {$endetag = $letztertag;}
        for ($t=$beginntag; $t<=$endetag; $t++) {$uebersichtsbelegung[$t]['P'] = true;}

        $oeffentlichausschluss .= ",".$daten['id'];

        if (($daten['beginn'] <= $sichtbarbeginn) && ($daten['ende'] >= $sichtbarbeginn) ||
            ($daten['ende'] >= $sichtbarende) && ($daten['beginn'] <= $sichtbarende) ||
            ($daten['beginn'] >= $sichtbarbeginn) && ($daten['ende'] <= $sichtbarende)) {
          array_push($termineansicht['P'], $daten);
        }
      }
      $anfrage->free();
    }
  }

  $sichtbarkeitsausschluss = "";

  if ($ansichtO == 1) {
    // Öffentliche Termine LADEN
    if ($CMS_BENUTZERART == 'l') {$oeffentlichkeitslimit = 1;}
    else if ($CMS_BENUTZERART == 'v') {$oeffentlichkeitslimit = 2;}
    else {$oeffentlichkeitslimit = 3;}

    if (strlen($oeffentlichausschluss) > 0) {$oeffentlichausschluss = "AND id NOT IN (".substr($oeffentlichausschluss, 1).")";}
    else {$oeffentlichausschluss = "";}

    $sql = "SELECT id, 'oe' AS art, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text FROM termine WHERE aktiv = 1 AND oeffentlichkeit >= $oeffentlichkeitslimit $oeffentlichausschluss ORDER BY beginn ASC, ende DESC";
    if ($anfrage = $dbs->query($sql)) {
      while ($daten = $anfrage->fetch_assoc()) {
        // Tag des Beginns erhalten
        $beginntag = ceil(($daten['beginn'] + 1 - $monatsbeginn)/60/60/24); if ($beginntag < 0) {$beginntag = 1;}
        $endetag = ceil(($daten['ende'] - $monatsbeginn)/60/60/24); if ($endetag > $letztertag) {$endetag = $letztertag;}
        for ($t=$beginntag; $t<=$endetag; $t++) {$uebersichtsbelegung[$t]['O'] = true;}

        $sichtbarkeitsausschluss .= ",".$daten['id'];

        if (($daten['beginn'] <= $sichtbarbeginn) && ($daten['ende'] >= $sichtbarbeginn) ||
            ($daten['ende'] >= $sichtbarende) && ($daten['beginn'] <= $sichtbarende) ||
            ($daten['beginn'] >= $sichtbarbeginn) && ($daten['ende'] <= $sichtbarende)) {
          array_push($termineansicht['O'], $daten);
        }
      }
      $anfrage->free();
    }
  }


  $rohlink = implode('/', array_slice($CMS_URL,0,2));

  $code .= "<div class=\"cms_termine_jahrueberischt_knoepfe\"><span class=\"cms_termine_jahrueberischt_knoepfe_vorher\"><a class=\"cms_button\" href=\"$rohlink/$vj/$vm\">«</a></span><span class=\"cms_termine_jahrueberischt_knoepfe_jahr\">$jm $jj</span><span class=\"cms_termine_jahrueberischt_knoepfe_nachher\"><a class=\"cms_button\" href=\"$rohlink/$nj/$nm\">»</a></span></div>";

  $code .= "<table class=\"cms_kalenderuebersicht\">";
  $code .= "<tr>";
    $code .= "<th class=\"cms_kalender_kw\">KW</th>";
    for ($i=1; $i<=7; $i++) {
      $code .= "<th class=\"cms_kalender_tag\">".cms_tagname($i)."</th>";
    }
  $code .= "</tr>";
    // erste Zeile:
    $wochentag = date('N', $monatsbeginn);
    $kw = date('W', $monatsbeginn);
    $tagzahl = 1;
    $code .= "<tr>";
      $code .= "<th class=\"cms_kalender_kwzahl\">$kw</th>"; $kw++;
      for ($i=1; $i<$wochentag; $i++) {$code .= "<td></td>";}
      for ($i=$wochentag; $i<=7; $i++) {
        $zusatzklassen = "";
        if ($uebersichtsbelegung[$tagzahl]['F']) {$zusatzklassen = " cms_kalenderzahl_ferien";}
        if ($uebersichtsbelegung[$tagzahl]['S']) {$zusatzklassen = " cms_kalenderzahl_sichtbar";}
        if ($uebersichtsbelegung[$tagzahl]['O']) {$zusatzklassen = " cms_kalenderzahl_oeffentlich";}
        if ($uebersichtsbelegung[$tagzahl]['P']) {$zusatzklassen = " cms_kalenderzahl_persoenlich";}
        if ($tag == $tagzahl) {$zusatzklassen .= " cms_kalenderzahl_gewaehlt";}
        if ($ansichtW == 1) {if (($tagzahl > $tag) && ($tagzahl < $tag+7)) {$zusatzklassen .= " cms_kalenderzahl_gewaehlt";}}
        if (($tagH == $tagzahl) && ($monatH == $monat) && ($jahrH == $jahr)) {$zusatzklassen .= " cms_kalenderzahl_heute";}
        $code .= "<td class=\"cms_kalender_tagzahl\"><a class=\"cms_kalenderzahl$zusatzklassen\" href=\"$rohlink/$jj/$jm/".cms_fuehrendenull($tagzahl)."\">$tagzahl</a></td>"; $tagzahl++;
      }
    $code .= "</tr>";
    while ($tagzahl <= $letztertag) {
      $code .= "<tr>";
        $code .= "<th class=\"cms_kalender_kwzahl\">$kw</th>"; $kw++;
        for ($i=1; $i<=7; $i++) {
          if ($tagzahl <= $letztertag) {
            $zusatzklassen = "";
            if ($uebersichtsbelegung[$tagzahl]['F']) {$zusatzklassen = " cms_kalenderzahl_ferien";}
            if ($uebersichtsbelegung[$tagzahl]['S']) {$zusatzklassen = " cms_kalenderzahl_sichtbar";}
            if ($uebersichtsbelegung[$tagzahl]['O']) {$zusatzklassen = " cms_kalenderzahl_oeffentlich";}
            if ($uebersichtsbelegung[$tagzahl]['P']) {$zusatzklassen = " cms_kalenderzahl_persoenlich";}
            if ($tag == $tagzahl) {$zusatzklassen .= " cms_kalenderzahl_gewaehlt";}
            if ($ansichtW == 1) {if (($tagzahl > $tag) && ($tagzahl < $tag+7)) {$zusatzklassen .= " cms_kalenderzahl_gewaehlt";}}
            if (($tagH == $tagzahl) && ($monatH == $monat) && ($jahrH == $jahr)) {$zusatzklassen .= " cms_kalenderzahl_heute";}
            $code .= "<td class=\"cms_kalender_tagzahl\"><a class=\"cms_kalenderzahl$zusatzklassen\" href=\"$rohlink/$jj/$jm/".cms_fuehrendenull($tagzahl)."\">$tagzahl</a></td>"; $tagzahl++;
          }
          else {$code .= "<td></td>";}
        }
      $code .= "</tr>";
    }
  $code .= "</table>";

  $code .= "</div></div>";

  $code .= "<div class=\"cms_clear\"></div>";

  $code .= "<div class=\"cms_spalte_i\">";
  $code .= "<h2>Termine</h2>";
  $code .= "</div>";

  $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
  $code .= "<h3>Persönlich</h3>";
  $termincode = "";
  foreach ($termineansicht['P'] AS $t) {
    $internvorlink = "Schulhof";
    if ($t['art'] == 'in') {
      $g = $t['gruppenart'];
      $gk = cms_textzudb($g);
      $gid = $t['id'];
      $sql = "SELECT AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS sbez, AES_DECRYPT($gk.bezeichnung, '$CMS_SCHLUESSEL') AS gbez FROM $gk LEFT JOIN schuljahre ON $gk.schuljahr = schuljahre.id WHERE $gk.id = $gid";
      if ($anfrage2 = $dbs->query($sql)) {
        if ($daten2 = $anfrage2->fetch_assoc()) {
          $schuljahrbez = $daten2['sbez'];
          $gbez = $daten2['gbez'];
          if (is_null($schuljahrbez)) {$schuljahrbez = "Schuljahrübergreifend";}
          $internvorlink = "Schulhof/Gruppen/".cms_textzulink($schuljahrbez)."/".cms_textzulink($g)."/".cms_textzulink($gbez);
        }
        $anfrage2->free();
      }
    }
    $termincode .= cms_termin_link_ausgeben($dbs, $t, $internvorlink);
  }
  if (strlen($termincode) > 0) {$code .= "<ul class=\"cms_terminuebersicht\">".$termincode."</ul>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Termine.</p>";}
  $code .= "</div></div>";

  $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
  $code .= "<h3>Öffentlich</h3>";
  $termincode = "";
  foreach ($termineansicht['O'] AS $t) {$termincode .= cms_termin_link_ausgeben($dbs, $t, $CMS_URLGANZ);}
  if (strlen($termincode) > 0) {$code .= "<ul class=\"cms_terminuebersicht\">".$termincode."</ul>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Termine.</p>";}
  $code .= "</div></div>";

  $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
  $code .= "<h3>Ferien</h3>";
  $termincode = "";
  foreach ($termineansicht['F'] AS $t) {$termincode .= cms_termin_link_ausgeben($dbs, $t, $CMS_URLGANZ);}
  if (strlen($termincode) > 0) {$code .= "<ul class=\"cms_terminuebersicht\">".$termincode."</ul>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Termine.</p>";}
  $code .= "</div></div>";

  $code .= "<div class=\"cms_spalte_4\"><div class=\"cms_spalte_i\">";
  //$code .= "<h3>Sichtbar</h3>";
  $code .= "</div></div>";

  $code .= "<div class=\"cms_clear\"></div>";
}

echo $code;
?>
