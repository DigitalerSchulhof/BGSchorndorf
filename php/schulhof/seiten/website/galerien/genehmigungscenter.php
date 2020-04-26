<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Bisher nicht genehmigte Galerien</h1>

<?php
if (cms_r("artikel.genehmigen.galerien")) {
	$ausgabe = "<table class=\"cms_liste\">";
		$ausgabe .= "<thead>";
			$ausgabe .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Datum</th><th>Autor</th><th></th><th>Aktionen</th></tr>";
		$ausgabe .= "</thead>";
		$ausgabe .= "<tbody>";
		$dbs = cms_verbinden('s');
		$gfaelle = "";
		$GALERIEN = array();

		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, aktiv, oeffentlichkeit, datum, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor FROM galerien WHERE genehmigt = 0 ORDER BY datum DESC");
	  if ($sql->execute()) {
			$sql->bind_result($gid, $gbez, $gaktiv, $goeff, $gdat, $gaut);
			while ($sql->fetch()) {
				$G = array();
				$G['id'] = $gid;
				$G['bezeichnung'] = $gbez;
				$G['aktiv'] = $gaktiv;
				$G['oeffentlichkeit'] = $goeff;
				$G['datum'] = $gdat;
				$G['autor'] = $gaut;
				array_push($GALERIEN, $G);
			}
		}
		$sql->close();

    foreach ($GALERIEN AS $daten) {
      $gfaelle .= '<tr><td><img src="res/icons/klein/galerie.png"></td><td>'.$daten['bezeichnung'].'</td>';
      $zuordnungen = "";
      foreach ($CMS_GRUPPEN as $g) {
        $gk = cms_textzudb($g);
        $sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."galerien JOIN $gk ON gruppe = id WHERE galerie = ?) AS x ORDER BY bezeichnung ASC");
				$sql->bind_param("i", $daten['id']);
        if ($sql->execute()) {
					$sql->bind_result($zbez, $zicon);
          while ($sql->fetch()) {
            $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$g » $zbez</span><img src=\"res/gruppen/klein/$zicon\"></span> "; ;
          }
        }
				$sql->close();
      }
      $gfaelle .= "<td>$zuordnungen</td>";
      $gfaelle .= "<td>".date('d.m.Y', $daten['datum'])."</td>";
      $gfaelle .= "<td>".$daten['autor']."</td>";
      if ($daten['oeffentlichkeit'] == 0) {$icon = "rot"; $oeffentlichkeit = 'Mitglieder der zugeordneten Gruppen';}
      if ($daten['oeffentlichkeit'] == 1) {$icon = "orange"; $oeffentlichkeit = 'Lehrer';}
      if ($daten['oeffentlichkeit'] == 2) {$icon = "gelb"; $oeffentlichkeit = 'Lehrer und Verwaltung';}
      if ($daten['oeffentlichkeit'] == 3) {$icon = "blau"; $oeffentlichkeit = 'Gesamter Schulhof';}
      if ($daten['oeffentlichkeit'] == 4) {$icon = "gruen"; $oeffentlichkeit = 'Auf der Website und im Schulhof';}
      $gfaelle .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$oeffentlichkeit</span><img src=\"res/icons/klein/".$icon.".png\"></span> ";
      if ($daten['aktiv'] == 0) {$icon = "rot"; $aktiv = 'Inaktiv';}
      if ($daten['aktiv'] == 1) {$icon = "gruen"; $aktiv = 'Aktiv';}
      $gfaelle .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$aktiv</span><img src=\"res/icons/klein/".$icon.".png\"></span></td>";
      $gfaelle .= '<td>';
				$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_galerie_genehmigen('".$daten['id']."');\"><span class=\"cms_hinweis\">Genehmigen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
				if (cms_r("artikel.galerien.bearbeiten")) {
					$gfaelle .= "<span class=\"cms_aktion_klein\" onclick=\"cms_galerie_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Aufgaben/Galerien_genehmigen');\"><span class=\"cms_hinweis\">Galerie bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
				}
				$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_galerie_ablehnen('".$daten['id']."');\"><span class=\"cms_hinweis\">Ablehnen und löschen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
      $gfaelle .= '</td>';
      $gfaelle .= '</tr>';
    }

    if (strlen($gfaelle) == 0) {
      $gfaelle .= "<tr><td colspan=\"7\" class=\"cms_notiz\">-- keine Galerien vorhanden --</td></tr>";
    }
		$ausgabe .= $gfaelle;

		cms_trennen($dbs);
		$ausgabe .= "</tbody>";
	$ausgabe .= "</table>";
	echo $ausgabe;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
<div class="cms_clear"></div>
