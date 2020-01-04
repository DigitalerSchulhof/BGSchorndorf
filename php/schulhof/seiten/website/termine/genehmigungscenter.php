<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Bisher nicht genehmigte Termine</h1>

<?php
if (r("artikel.genehmigen.termine || schulhof.gruppen.%GRUPPEN%.artikel.termine.genehmigen")) {
	include_once("php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
	$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
	$ausgabe = "";

	$dbs = cms_verbinden('s');

	if (r("artikel.genehmigen.termine")) {
		$ausgabe .= "<h2>Öffentliche Termine</h2>";
		$ausgabe .= "<table class=\"cms_liste\">";
			$ausgabe .= "<thead>";
				$ausgabe .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Beginn</th><th>Ende</th><th>Ersteller</th><th></th><th>Aktionen</th></tr>";
			$ausgabe .= "</thead>";
			$ausgabe .= "<tbody>";
			$gfaelle = "";
			$sql = "SELECT termine.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, aktiv, oeffentlichkeit, beginn, ende, erstellt, termine.idzeit AS idzeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, personen.id AS person FROM termine LEFT JOIN personen ON termine.idvon = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE genehmigt = 0 ORDER BY beginn DESC, ende DESC";
		  if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
		    while ($daten = $anfrage->fetch_assoc()) {
		      $gfaelle .= '<tr><td><img src="res/icons/klein/termine.png"></td><td>'.$daten['bezeichnung'].'</td>';
		      $zuordnungen = "";
		      foreach ($CMS_GRUPPEN as $g) {
		        $gk = cms_textzudb($g);
		        $sql = "SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."termine JOIN $gk ON gruppe = id WHERE termin = ".$daten['id'].") AS x ORDER BY bezeichnung ASC";
		        if ($anfrage2 = $dbs->query($sql)) {	// Safe weil interne ID
		          while ($z = $anfrage2->fetch_assoc()) {
		            $zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$g." » ".$z['bezeichnung']."</span><img src=\"res/gruppen/klein/".$z['icon']."\"></span> ";
		          }
		          $anfrage2->free();
		        }
		      }
		      $gfaelle .= "<td>$zuordnungen</td>";
		      $gfaelle .= "<td>".date('d.m.Y H:i', $daten['beginn'])."</td>";
		      $gfaelle .= "<td>".date('d.m.Y H:i', $daten['ende'])."</td>";
					if ($daten['erstellt'] < $daten['idzeit']) {
						$ersteller = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
						if (in_array($daten['person'], $POSTEMPFAENGERPOOL)) {
							$ersteller = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('vorgabe', '', '', '".$daten['person']."')\">$ersteller kontaktieren</span>";
						}
						else {$ersteller = "<span class=\"cms_button_passiv\">$ersteller kontaktieren</span>";}
					}
					else {$ersteller = "<i>Nutzerkonto existiert nicht mehr</i>";}
					$gfaelle .= "<td>$ersteller</td>";
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
						$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_termin_genehmigen('Termine', '".$daten['id']."');\"><span class=\"cms_hinweis\">Genehmigen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
						if ($CMS_RECHTE['Website']['Termine bearbeiten']) {
							$gfaelle .= "<span class=\"cms_aktion_klein\" onclick=\"cms_termine_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Aufgaben/Termine_genehmigen');\"><span class=\"cms_hinweis\">Termin bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
						}
						$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_termin_ablehnen('Termine', '".$daten['id']."');\"><span class=\"cms_hinweis\">Ablehnen und löschen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
		      $gfaelle .= '</td>';
		      $gfaelle .= '</tr>';
		    }

				$anfrage->free();
		    if (strlen($gfaelle) == 0) {
		      $gfaelle .= "<tr><td colspan=\"8\" class=\"cms_notiz\">-- keine Termine vorhanden --</td></tr>";
		    }
				$ausgabe .= $gfaelle;
		  }
			$ausgabe .= "</tbody>";
		$ausgabe .= "</table>";
	}


	if (r("schulhof.gruppen.%GRUPPEN%.artikel.termine.genehmigen")) {
		$ausgabe .= "<h2>Interne Termine</h2>";
		$ausgabe .= "<table class=\"cms_liste\">";
			$ausgabe .= "<thead>";
				$ausgabe .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Beginn</th><th>Ende</th><th>Ersteller</th><th></th><th>Aktionen</th></tr>";
			$ausgabe .= "</thead>";
			$ausgabe .= "<tbody>";
			$gfaelle = "";

			foreach ($CMS_GRUPPEN AS $g) {
				$gk = cms_textzudb($g);

				$sql = "SELECT $gk".".id AS gid, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahr, $gk"."termineintern.id AS id, AES_DECRYPT($gk"."termineintern.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT($gk".".bezeichnung, '$CMS_SCHLUESSEL') AS gbezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, aktiv, $gk"."termineintern.beginn AS beginn, $gk"."termineintern.ende AS ende, erstellt, $gk"."termineintern.idzeit AS idzeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, personen.id AS person FROM $gk"."termineintern LEFT JOIN personen ON $gk"."termineintern.idvon = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id JOIN $gk"." ON $gk"."termineintern.gruppe = $gk".".id LEFT JOIN schuljahre ON $gk".".schuljahr = schuljahre.id WHERE genehmigt = 0 ORDER BY beginn DESC, ende DESC";

				if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			    while ($daten = $anfrage->fetch_assoc()) {
			      $gfaelle .= '<tr><td><img src="res/icons/klein/termine.png"></td><td>'.$daten['bezeichnung'].'</td>';
			      $zuordnungen = "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$g." » ".$daten['gbezeichnung']."</span><img src=\"res/gruppen/klein/".$daten['icon']."\"></span> ";
			      $gfaelle .= "<td>$zuordnungen</td>";
			      $gfaelle .= "<td>".date('d.m.Y H:i', $daten['beginn'])."</td>";
			      $gfaelle .= "<td>".date('d.m.Y H:i', $daten['ende'])."</td>";
						if ($daten['erstellt'] < $daten['idzeit']) {
							$ersteller = cms_generiere_anzeigename($daten['vorname'], $daten['nachname'], $daten['titel']);
							if (in_array($daten['person'], $POSTEMPFAENGERPOOL)) {
								$ersteller = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('vorgabe', '', '', '".$daten['person']."')\">$ersteller kontaktieren</span>";
							}
							else {$ersteller = "<span class=\"cms_button_passiv\">$ersteller kontaktieren</span>";}
						}
						else {$ersteller = "<i>Nutzerkonto existiert nicht mehr</i>";}
						$gfaelle .= "<td>$ersteller</td>";
			      if ($daten['aktiv'] == 0) {$icon = "rot"; $aktiv = 'Inaktiv';}
			      if ($daten['aktiv'] == 1) {$icon = "gruen"; $aktiv = 'Aktiv';}
			      $gfaelle .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$aktiv</span><img src=\"res/icons/klein/".$icon.".png\"></span></td>";
			      $gfaelle .= '<td>';
							$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_termin_genehmigen('$g', '".$daten['id']."');\"><span class=\"cms_hinweis\">Genehmigen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
							if ($CMS_RECHTE['Organisation']['Gruppentermine bearbeiten']) {
								if (is_null($daten['schuljahr'])) {$daten['schuljahr'] = 'Schuljahrübergreifend';}
								$gfaelle .= "<span class=\"cms_aktion_klein\" onclick=\"cms_termineintern_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Aufgaben/Termine_genehmigen', '$g', '".$daten['gid']."', '".$daten['schuljahr']."', '".$daten['gbezeichnung']."');\"><span class=\"cms_hinweis\">Termin bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
							}
							$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_termin_ablehnen('$g', '".$daten['id']."');\"><span class=\"cms_hinweis\">Ablehnen und löschen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
			      $gfaelle .= '</td>';
			      $gfaelle .= '</tr>';
			    }
			  }
			}
			$anfrage->free();
			if (strlen($gfaelle) == 0) {
				$gfaelle .= "<tr><td colspan=\"8\" class=\"cms_notiz\">-- keine Termine vorhanden --</td></tr>";
			}
			$ausgabe .= $gfaelle;
			$ausgabe .= "</tbody>";
		$ausgabe .= "</table>";
	}






	cms_trennen($dbs);
	echo $ausgabe;
}
else {
	echo cms_meldung_berechtigung();
}
?>

</div>
<div class="cms_clear"></div>
