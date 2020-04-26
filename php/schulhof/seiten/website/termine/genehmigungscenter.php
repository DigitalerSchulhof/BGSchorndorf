<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Bisher nicht genehmigte Termine</h1>

<?php
if (cms_r("artikel.genehmigen.termine || schulhof.gruppen.%GRUPPEN%.artikel.termine.genehmigen")) {
	include_once("php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
	$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
	$ausgabe = "";

	$dbs = cms_verbinden('s');

	if (cms_r("artikel.genehmigen.termine")) {
		$ausgabe .= "<h2>Öffentliche Termine</h2>";
		$ausgabe .= "<table class=\"cms_liste\">";
			$ausgabe .= "<thead>";
				$ausgabe .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Beginn</th><th>Ende</th><th>Ersteller</th><th></th><th>Aktionen</th></tr>";
			$ausgabe .= "</thead>";
			$ausgabe .= "<tbody>";
			$gfaelle = "";
			$TERMINE = array();
			$sql = $dbs->prepare("SELECT termine.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, aktiv, oeffentlichkeit, beginn, ende, erstellt, termine.idzeit AS idzeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, personen.id AS person FROM termine LEFT JOIN personen ON termine.idvon = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE genehmigt = 0 ORDER BY beginn DESC, ende DESC");
			if ($sql->execute()) {
				$sql->bind_result($tid, $tbez, $taktiv, $toeff, $tbeginn, $tende, $terstellt, $tidzeit, $tvor, $tnach, $ttit, $tpid);
				while ($sql->fetch()) {
					$T = array();
					$T['id'] = $tid;
					$T['bezeichnung'] = $tbez;
					$T['aktiv'] = $taktiv;
					$T['oeffentlichkeit'] = $toeff;
					$T['beginn'] = $tbeginn;
					$T['ende'] = $tende;
					$T['erstellt'] = $terstellt;
					$T['idzeit'] = $tidzeit;
					$T['vorname'] = $tvor;
					$T['nachname'] = $tnach;
					$T['titel'] = $ttit;
					$T['person'] = $tpid;
					array_push($TERMINE, $T);
				}
			}
			$sql->close();

			foreach ($TERMINE as $daten) {
				$gfaelle .= '<tr><td><img src="res/icons/klein/termine.png"></td><td>'.$daten['bezeichnung'].'</td>';
				$zuordnungen = "";
				foreach ($CMS_GRUPPEN as $g) {
					$gk = cms_textzudb($g);
					$sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."termine JOIN $gk ON gruppe = id WHERE termin = ?) AS x ORDER BY bezeichnung ASC");
					$sql->bind_param("i", $daten['id']);
					if ($sql->execute()) {
						$sql->bind_result($zbez, $zicon);
						while ($sql->fetch()) {
							$zuordnungen .= "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$g » $zbez</span><img src=\"res/gruppen/klein/$zicon\"></span> ";
						}
					}
					$sql->close();
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
					if (cms_r("artikel.{$daten['oeffentlichkeit']}.termine.anlegen")) {
						$gfaelle .= "<span class=\"cms_aktion_klein\" onclick=\"cms_termine_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Aufgaben/Termine_genehmigen');\"><span class=\"cms_hinweis\">Termin bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_termin_ablehnen('Termine', '".$daten['id']."');\"><span class=\"cms_hinweis\">Ablehnen und löschen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
				$gfaelle .= '</td>';
				$gfaelle .= '</tr>';
			}

			if (strlen($gfaelle) == 0) {
				$gfaelle .= "<tr><td colspan=\"8\" class=\"cms_notiz\">-- keine Termine vorhanden --</td></tr>";
			}
			$ausgabe .= $gfaelle;
			$ausgabe .= "</tbody>";
		$ausgabe .= "</table>";
	}


	if (cms_r("schulhof.gruppen.%GRUPPEN%.artikel.termine.genehmigen")) {
		$ausgabe .= "<h2>Interne Termine</h2>";
		$ausgabe .= "<table class=\"cms_liste\">";
			$ausgabe .= "<thead>";
				$ausgabe .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Beginn</th><th>Ende</th><th>Ersteller</th><th></th><th>Aktionen</th></tr>";
			$ausgabe .= "</thead>";
			$ausgabe .= "<tbody>";
			$gfaelle = "";

			foreach ($CMS_GRUPPEN AS $g) {
				$gk = cms_textzudb($g);

				$sql = $dbs->prepare("SELECT $gk".".id AS gid, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahr, $gk"."termineintern.id AS id, AES_DECRYPT($gk"."termineintern.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT($gk".".bezeichnung, '$CMS_SCHLUESSEL') AS gbezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, aktiv, $gk"."termineintern.beginn AS beginn, $gk"."termineintern.ende AS ende, erstellt, $gk"."termineintern.idzeit AS idzeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, personen.id AS person FROM $gk"."termineintern LEFT JOIN personen ON $gk"."termineintern.idvon = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id JOIN $gk"." ON $gk"."termineintern.gruppe = $gk".".id LEFT JOIN schuljahre ON $gk".".schuljahr = schuljahre.id WHERE genehmigt = 0 ORDER BY beginn DESC, ende DESC");

				if ($sql->execute()) {
					$sql->bind_result($gid, $sjbez, $tiid, $tibez, $gbez, $ticon, $tiaktiv, $tibeginn, $tiende, $tierstellt, $tiidzeit, $tivor, $tinach, $tititel, $tiperson);
			    while ($sql->fetch()) {
			      $gfaelle .= '<tr><td><img src="res/icons/klein/termine.png"></td><td>'.$tibez.'</td>';
			      $zuordnungen = "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$g » $gbez</span><img src=\"res/gruppen/klein/$ticon\"></span> ";
			      $gfaelle .= "<td>$zuordnungen</td>";
			      $gfaelle .= "<td>".date('d.m.Y H:i', $tibeginn)."</td>";
			      $gfaelle .= "<td>".date('d.m.Y H:i', $tiende)."</td>";
						if ($tierstellt < $tiidzeit) {
							$ersteller = cms_generiere_anzeigename($tivor, $tinach, $tititel);
							if (in_array($tiperson, $POSTEMPFAENGERPOOL)) {
								$ersteller = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('vorgabe', '', '', '$tiperson')\">$ersteller kontaktieren</span>";
							}
							else {$ersteller = "<span class=\"cms_button_passiv\">$ersteller kontaktieren</span>";}
						}
						else {$ersteller = "<i>Nutzerkonto existiert nicht mehr</i>";}
						$gfaelle .= "<td>$ersteller</td>";
			      if ($tiaktiv == 0) {$icon = "rot"; $aktiv = 'Inaktiv';}
			      if ($tiaktiv == 1) {$icon = "gruen"; $aktiv = 'Aktiv';}
			      $gfaelle .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$aktiv</span><img src=\"res/icons/klein/".$icon.".png\"></span></td>";
			      $gfaelle .= '<td>';
							$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_termin_genehmigen('$g', '$tiid');\"><span class=\"cms_hinweis\">Genehmigen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
							if (cms_r("schulhof.gruppen.$g.artikel.termine.bearbeiten")) {
								if (is_null($sjbez)) {$sjbez = 'Schuljahrübergreifend';}
								$gfaelle .= "<span class=\"cms_aktion_klein\" onclick=\"cms_termineintern_bearbeiten_vorbereiten('$tiid', 'Schulhof/Aufgaben/Termine_genehmigen', '$g', '$gid', '$sjbez', '$gbez');\"><span class=\"cms_hinweis\">Termin bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
							}
							$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_termin_ablehnen('$g', '$tiid');\"><span class=\"cms_hinweis\">Ablehnen und löschen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
			      $gfaelle .= '</td>';
			      $gfaelle .= '</tr>';
			    }
			  }
				$sql->close();
			}
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
