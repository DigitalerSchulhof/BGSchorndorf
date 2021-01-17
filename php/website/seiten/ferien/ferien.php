<?php
$code = "<div class=\"cms_spalte_i\">";
  $code .= "<p class=\"cms_brotkrumen\">".cms_brotkrumen($CMS_URL)."</p>";

  $code .= "<h1>Ferienkalender</h1>";

  if (isset($CMS_URL[2])) {$jahr = $CMS_URL[2];} else {$jahr = date('Y');}
  $jahraktuella = mktime(0,0,0,1,1,$jahr);
  $jahraktuelle = mktime(0,0,0,1,1,$jahr+1)-1;

  $code .= "<div class=\"cms_termine_jahrueberischt_knoepfe\"><span class=\"cms_termine_jahrueberischt_knoepfe_vorher\"><a class=\"cms_button\" href=\"".$CMS_URL[0]."/Ferien/".($jahr-1)."\">«</a></span><span class=\"cms_termine_jahrueberischt_knoepfe_jahr\">$jahr</span><span class=\"cms_termine_jahrueberischt_knoepfe_nachher\"><a class=\"cms_button\" href=\"".$CMS_URL[0]."/Ferien/".($jahr+1)."\">»</a></span></div>";

  for ($m = 1; $m <= 12; $m++) {
    $tage[$m] = array();
    for ($t = 1; $t <= 31; $t++) {
      $datum = mktime(0,0,0,$m,$t,$jahr);
      if (date('m', $datum) == $m) {
        $tage[$m][$t]['datum'] = $datum;
        $tage[$m][$t]['bez'] = cms_tagname(date('w', $datum));
      }
    }
  }

  $freietage = array();

  $sql = $dbs->prepare("SELECT beginn, ende FROM ferien WHERE (beginn BETWEEN ? AND ?) OR (ende BETWEEN ? AND ?)");
  $sql->bind_param("iiii", $jahraktuella, $jahraktuelle, $jahraktuella, $jahraktuelle);
  if ($sql->execute()) {
    $sql->bind_result($ferbeginn, $ferende);
    while ($sql->fetch()) {
      // 86400 Sekunden hat ein Tag
      for ($i = $ferbeginn; $i <= $ferende; $i = $i + 86400) {
        array_push($freietage, mktime(0,0,0,date('m', $i), date('d', $i), date('Y', $i)));
      }
    }
  }
  $sql->close();

  $code .= "<table class=\"cms_ferienkalender\">";
  $code .= "<tr>";
  for ($m = 1; $m<=12; $m++) {$code .= "<th>".cms_monatsnamekomplett($m)."</th>";}
  $code .= "</tr>";
  for ($t = 1; $t<=31; $t++) {
    $code .= "<tr>";
    for ($m = 1; $m<=12; $m++) {
      if (isset($tage[$m][$t]['datum'])) {
        if (($tage[$m][$t]['bez'] == 'SA') || ($tage[$m][$t]['bez'] == 'SO')) {$zusatzklasse = " class=\"cms_ferienkalender_we\"";}
        else if (in_array($tage[$m][$t]['datum'], $freietage)) {$zusatzklasse = " class=\"cms_ferienkalender_frei\"";}
        else {$zusatzklasse = "";}
        $code .= "<td$zusatzklasse><span class=\"cms_ferienkalender_inhalt\">$t</span><span class=\"cms_ferienkalender_inhalt\">".$tage[$m][$t]['bez']."</span></td>";
      }
      else {
        $code .= "<td></td>";
      }
    }
    $code .= "</tr>";
  }
  $code .= "</table>";

$code .= "</div>";

echo $code;
?>
