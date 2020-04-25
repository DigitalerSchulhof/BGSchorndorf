<?php
function cms_raumplan_aus_datei ($datei) {
  if (is_file($datei)) {
    $plan = file_get_contents_utf8($datei);

    $plan = str_replace("\n", '', $plan);
    $plan = str_replace("\N", '', $plan);
    $plan = str_replace("\r", '', $plan);
    $plan = str_replace("\R", '', $plan);

    $ausgabe = "";

    $plan = explode('<TABLE border="3" rules="all" cellpadding="1" cellspacing="1"><TR>', $plan);
    $plan = explode('</TR><TR></TR></TABLE><TABLE cellspacing="1" cellpadding="1">', $plan[1]);

    $plan = str_replace(' colspan=12', '', $plan[0]);
    $plan = str_replace(' rowspan=2', '', $plan);
    $plan = str_replace(' rowspan=4', ' rowspan=2', $plan);
    $plan = str_replace(' align="center"', '', $plan);
    $plan = str_replace(' nowrap="1"', '', $plan);
    $plan = str_replace(' nowrap=1', '', $plan);
    $plan = str_replace('<font size="3" face="Arial"  color="#000000">', '', $plan);
    $plan = str_replace('<font size="3" face="Arial" color="#000000">', '', $plan);
    $plan = str_replace('<font size="3" face="Arial">', '', $plan);
    $plan = str_replace('</font> ', '', $plan);
    $plan = str_replace(' width="50%"', '', $plan);
    $plan = str_replace(' bgcolor="#FFFFFF"', '', $plan);
    $plan = str_replace('  ', ' ', $plan);
    $plan = str_replace(' >', '>', $plan);

    $zeilen = explode("</TR><TR>", $plan);

    $ausgabe = "<table class=\"cms_liste cms_klassen_stundenplan\">";
    $zelle = explode("</TD></TR></TABLE></TD><TD><TABLE><TR><TD>", substr($zeilen[0], 19, -23));
    $ausgabe .= "<tr><th></th>";
    for ($i = 1; $i < count($zelle); $i++) {
      $ausgabe .= "<th>".$zelle[$i]."</th>";
    }
    $ausgabe .= "</tr>";

    $stunden = 0;
    for ($z = 1; $z < count($zeilen); $z++) {
      if (strlen($zeilen[$z]) > 0) {
        $zeilen[$z] = str_replace('<TD rowspan=2>', '<TD>2|', $zeilen[$z]);
        $zellen = explode('</TD></TR></TABLE></TD><TD>', $zeilen[$z]);
        $ausgabe .= "<tr>";
        $stunden++;
        $ausgabe .= "<th>$stunden</th>";
        for ($s = 1; $s < count($zellen); $s++) {
          $zelle = $zellen[$s];
          $zelle = str_replace('<TABLE>', '', $zelle);
          $zelle = str_replace('</TABLE>', '', $zelle);
          $zelle = str_replace('.</TD><TD>', '|', $zelle);
          $zelle = str_replace('</TD><TD>', '|', $zelle);
          $zelle = str_replace('<TR>', '', $zelle);
          $zelle = str_replace('</TR>', '', $zelle);
          $zelle = str_replace('<TD>', '', $zelle);
          $zelle = str_replace('</TD>', '', $zelle);
          if (substr($zelle, 0, 2) == "2|") {
            $zelle = substr($zelle, 2);
            $rowspan = 2;
          }
          else {$rowspan = 1;}
          $inhalt = explode('|', $zelle);

          if (isset($inhalt[0]) && (strlen($inhalt[0]) > 0)) {$style = cms_stunde_farbe($inhalt[0]);}
          else {$style = "";}
          $ausgabe .= "<td rowspan=\"$rowspan\" style=\"$style\">";
            if (isset($inhalt[0])) {$ausgabe .= "<span>".$inhalt[0]."</span>";}
            if (isset($inhalt[1])) {$ausgabe .= "<span>".$inhalt[1]."</span>";}
          $ausgabe .= "</td>";
        }
        $ausgabe .= "</tr>";
      }
    }

    $ausgabe .= "</table>";
  }
  else {
    $ausgabe = cms_meldung("info", "<h4>Kein Raumplan verfügbar</h4><p>Für diesen Raum wurde kein Raumplan hinterlegt.</p>");
  }
  return $ausgabe;
}



function cms_klassenplan_aus_datei ($datei) {
  if (is_file($datei)) {
    $plan = file_get_contents_utf8($datei);

    $plan = str_replace("\n", '', $plan);
    $plan = str_replace("\N", '', $plan);
    $plan = str_replace("\r", '', $plan);
    $plan = str_replace("\R", '', $plan);

    $ausgabe = "";

    $plan = explode('<TABLE border="3" rules="all" cellpadding="1" cellspacing="1"><TR>', $plan);
    $plan = explode('</TR><TR></TR></TABLE><TABLE cellspacing="1" cellpadding="1">', $plan[1]);

    $plan = str_replace(' colspan=12', '', $plan[0]);
    $plan = str_replace(' rowspan=2', '', $plan);
    $plan = str_replace(' rowspan=4', ' rowspan=2', $plan);
    $plan = str_replace(' width="33%"', '', $plan);
    $plan = str_replace(' align="center"', '', $plan);
    $plan = str_replace(' nowrap="1"', '', $plan);
    $plan = str_replace(' nowrap=1', '', $plan);
    $plan = str_replace('<font size="3" face="Arial"  color="#000000">', '', $plan);
    $plan = str_replace('<font size="5" face="Arial"  color="#000000">', '', $plan);
    $plan = str_replace('<font size="3" face="Arial" color="#000000">', '', $plan);
    $plan = str_replace('<font size="5" face="Arial" color="#000000">', '', $plan);
    $plan = str_replace('<font size="2" face="Arial">', '', $plan);
    $plan = str_replace('<font size="3" face="Arial">', '', $plan);
    $plan = str_replace('<font size="5" face="Arial">', '', $plan);
    $plan = str_replace('<font size="7" face="Arial">', '', $plan);
    $plan = str_replace('Halbe Klasse im.', 'Halbe Klasse im Wechsel', $plan);
    $plan = str_replace('</font> ', '', $plan);
    $plan = str_replace(' width="50%"', '', $plan);
    $plan = str_replace(' bgcolor="#FFFFFF"', '', $plan);
    $plan = str_replace('  ', ' ', $plan);
    $plan = str_replace(' >', '>', $plan);

    $zeilen = explode("</TR><TR><TD><TABLE><TR><TD><B>", $plan);

    $ausgabe = "<table class=\"cms_liste cms_klassen_stundenplan\">";
    $zelle = explode("</TD></TR></TABLE></TD><TD><TABLE><TR><TD>", substr($zeilen[0], 19, -23));
    $ausgabe .= "<tr><th></th>";
    for ($i = 1; $i < count($zelle); $i++) {
      $ausgabe .= "<th>".$zelle[$i]."</th>";
    }
    $ausgabe .= "</tr>";


    $stunden = 0;
    for ($z = 1; $z < count($zeilen); $z++) {
      if (strlen($zeilen[$z]) > 0) {
        $zeilen[$z] = str_replace('<TD rowspan=2>', '<TD>2|', $zeilen[$z]);
        $zellen = explode('</TD></TR></TABLE></TD><TD>', $zeilen[$z]);
        $ausgabe .= "<tr>";

        $stunden = str_replace('</B>', '', $zellen[0]);
        $stunden = str_replace('<TR>', '', $stunden);
        $stunden = str_replace('</TR>', '', $stunden);
        $stunden = str_replace('</TABLE>', '', $stunden);
        $stunden = str_replace('</TD></TD>', '', $stunden);
        $stunden = str_replace('<BR>', '', $stunden);
        $stunden = str_replace('<BR>', '', $stunden);
        $stunden = str_replace('<TD>-</TD>', '', $stunden);
        $stunden = explode('</TD><TD>', $stunden);

        $ausgabe .= "<th>".cms_zeitzelle_extern($stunden)."</th>";


        for ($s = 1; $s < count($zellen); $s++) {
          $zelle = $zellen[$s];
          $zelle = str_replace('<TABLE>', '', $zelle);
          $zelle = str_replace('</TABLE>', '', $zelle);
          $zelle = str_replace('.</TD><TD>', '|', $zelle);
          $zelle = str_replace('</TD><TD>', '|', $zelle);
          $zelle = str_replace('</TD></TR><TR><TD>', '*', $zelle);
          $zelle = str_replace('<TD colspan="3">', '#', $zelle);
          $zelle = str_replace('<TR>', '', $zelle);
          $zelle = str_replace('</TR>', '', $zelle);
          $zelle = str_replace('<TD>', '', $zelle);
          $zelle = str_replace('</TD>', '', $zelle);

          if (substr($zelle, 0, 2) == "2|") {
            $zelle = substr($zelle, 2);
            $rowspan = 2;
          }
          else {$rowspan = 1;}
          $hinweis = explode('#', $zelle);
          $unterricht = explode('*', $hinweis[0]);

          if (isset($hinweis[0]) && (strlen($hinweis[0]) > 0)) {$style = cms_stunde_farbe($hinweis[0]);}
          else {$style = "";}
          $ausgabe .= "<td rowspan=\"$rowspan\" style=\"$style\">";
            foreach ($unterricht AS $u) {
              $inhalt = explode('|', $u);
              foreach ($inhalt AS $in) {$ausgabe .= "<span>".$in."</span>";}
            }
            if (isset($hinweis[1])) {$ausgabe .= "<span class=\"cms_stundenplan_hinweis\">".$hinweis[1]."</span>";}
          $ausgabe .= "</td>";
        }
        $ausgabe .= "</tr>";
      }
    }
    $ausgabe .= "</table>";
  }
  else {
    $ausgabe = cms_meldung("info", "<h4>Kein Stundenplan verfügbar</h4><p>Für diese Klasse wurde kein Stundenplan hinterlegt.</p>");
  }
  return $ausgabe;
}



function cms_lehrerplan_aus_datei ($datei) {
  if (is_file($datei)) {
    $plan = file_get_contents_utf8($datei);

    $plan = str_replace("\n", '', $plan);
    $plan = str_replace("\N", '', $plan);
    $plan = str_replace("\r", '', $plan);
    $plan = str_replace("\R", '', $plan);

    $ausgabe = "";

    $plan = explode('<TABLE border="3" rules="all" cellpadding="1" cellspacing="1"><TR>', $plan);
    $plan = explode('</TR><TR></TR></TABLE><TABLE cellspacing="1" cellpadding="1">', $plan[1]);

    $plan = str_replace(' colspan=12', '', $plan[0]);
    $plan = str_replace(' rowspan=2', '', $plan);
    $plan = str_replace(' rowspan=4', ' rowspan=2', $plan);
    $plan = str_replace(' width="33%"', '', $plan);
    $plan = str_replace(' align="center"', '', $plan);
    $plan = str_replace(' nowrap="1"', '', $plan);
    $plan = str_replace(' nowrap=1', '', $plan);
    $plan = str_replace('<font size="3" face="Arial"  color="#000000">', '', $plan);
    $plan = str_replace('<font size="5" face="Arial"  color="#000000">', '', $plan);
    $plan = str_replace('<font size="3" face="Arial" color="#000000">', '', $plan);
    $plan = str_replace('<font size="5" face="Arial" color="#000000">', '', $plan);
    $plan = str_replace('<font size="2" face="Arial">', '', $plan);
    $plan = str_replace('<font size="3" face="Arial">', '', $plan);
    $plan = str_replace('<font size="5" face="Arial">', '', $plan);
    $plan = str_replace('<font size="6" face="Arial">', '', $plan);
    $plan = str_replace('<font size="7" face="Arial">', '', $plan);
    $plan = str_replace('Halbe Klasse im.', 'Halbe Klasse im Wechsel', $plan);
    $plan = str_replace('</font> ', '', $plan);
    $plan = str_replace(' width="50%"', '', $plan);
    $plan = str_replace(' bgcolor="#FFFFFF"', '', $plan);
    $plan = str_replace('  ', ' ', $plan);
    $plan = str_replace(' >', '>', $plan);

    $zeilen = explode("</TR><TR><TD><TABLE><TR><TD><B>", $plan);

    $ausgabe = "<table class=\"cms_liste cms_klassen_stundenplan\">";
    $zelle = explode("</TD></TR></TABLE></TD><TD><TABLE><TR><TD>", substr($zeilen[0], 19, -23));
    $ausgabe .= "<tr><th></th>";
    for ($i = 1; $i < count($zelle); $i++) {
      $ausgabe .= "<th>".$zelle[$i]."</th>";
    }
    $ausgabe .= "</tr>";


    for ($z = 1; $z < count($zeilen); $z++) {
      if (strlen($zeilen[$z]) > 0) {
        $zeilen[$z] = str_replace('<TD rowspan=2>', '<TD>2|', $zeilen[$z]);

        $zellen = explode('</TD></TR></TABLE></TD><TD>', $zeilen[$z]);
        $ausgabe .= "<tr>";

        $stunden = str_replace('</B>', '', $zellen[0]);
        $stunden = str_replace('<TR>', '', $stunden);
        $stunden = str_replace('</TR>', '', $stunden);
        $stunden = str_replace('</TABLE>', '', $stunden);
        $stunden = str_replace('</TD></TD>', '', $stunden);
        $stunden = str_replace('<BR>', '', $stunden);
        $stunden = str_replace('<BR>', '', $stunden);
        $stunden = str_replace('<TD>-</TD>', '', $stunden);
        $stunden = explode('</TD><TD>', $stunden);

        $ausgabe .= "<th>".cms_zeitzelle_extern($stunden)."</th>";


        for ($s = 1; $s < count($zellen); $s++) {
          $zelle = $zellen[$s];
          $zelle = str_replace('<TABLE>', '', $zelle);
          $zelle = str_replace('</TABLE>', '', $zelle);
          $zelle = str_replace('.</TD><TD>', '|', $zelle);
          $zelle = str_replace('</TD><TD colspan="2">', '|', $zelle);
          $zelle = str_replace('</TD><TD>', '|', $zelle);
          $zelle = str_replace('</TD></TR><TR><TD>', '*', $zelle);
          $zelle = str_replace('<TD colspan="3">', '#', $zelle);
          $zelle = str_replace('<TR>', '', $zelle);
          $zelle = str_replace('</TR>', '', $zelle);
          $zelle = str_replace('<TD>', '', $zelle);
          $zelle = str_replace('</TD>', '', $zelle);

          if (substr($zelle, 0, 2) == "2|") {
            $zelle = substr($zelle, 2);
            $rowspan = 2;
          }
          else {$rowspan = 1;}
          $hinweis = explode('#', $zelle);
          $unterricht = explode('*', $hinweis[0]);

          if (isset($hinweis[0]) && (strlen($hinweis[0]) > 0)) {$style = cms_stunde_farbe($hinweis[0]);}
          else {$style = "";}
          $ausgabe .= "<td rowspan=\"$rowspan\" style=\"$style\">";
            $klasse = "";
            $kurs = "";
            $raum = "";
            if (isset($unterricht[0])) {
              $inhalt = explode('|', $unterricht[0]);
              if (isset($inhalt[0])) {$klasse .= $inhalt[0];}
              if (isset($inhalt[1])) {$kurs .= $inhalt[1];}
              if (isset($inhalt[2])) {$raum .= $inhalt[2];}
            }
            if (isset($unterricht[1])) {
              $inhalt = explode('|', $unterricht[1]);
              foreach ($inhalt AS $in) {$klasse .= substr($in,-1);}
            }

            $ausgabe .= "<span>$klasse</span><span>$kurs</span><span>$raum</span>";
            if (isset($hinweis[1])) {$ausgabe .= "<span class=\"cms_stundenplan_hinweis\">".$hinweis[1]."</span>";}
          $ausgabe .= "</td>";
        }
        $ausgabe .= "</tr>";
      }
    }
    $ausgabe .= "</table>";
  }
  else {
    $ausgabe = cms_meldung("info", "<h4>Kein Stundenplan verfügbar</h4><p>Für diese Klasse wurde kein Stundenplan hinterlegt.</p>");
  }
  return $ausgabe;
}


function cms_zeitzelle_extern ($stunde) {
  $bez = $stunde[0];
  $beginn = $stunde[1];
  $ende = $stunde[2];
  $code = "<span class=\"cms_stundenplan_stunde_zeit\">";
    $code .= "<span class=\"cms_stundenplan_stunde_bez\">".$bez."</span>";
    $code .= "<span class=\"cms_stundenplan_stunde_beginn\">".$beginn."</span>";
    $code .= "<span class=\"cms_stundenplan_stunde_ende\">".$ende."</span>";
  $code .= "</span>";
  return $code;
}
?>
