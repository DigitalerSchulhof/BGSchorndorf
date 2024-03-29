<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Bisher nicht genehmigte Blogeinträge</h1>

<?php
if (cms_r("artikel.genehmigen.blogeinträge || schulhof.gruppen.%GRUPPEN%.artikel.blogeinträge.genehmigen")) {
	include_once("php/schulhof/anfragen/nutzerkonto/postfach/vorbereiten.php");
	$POSTEMPFAENGERPOOL = cms_postfach_empfaengerpool_generieren($dbs);
	$ausgabe = "";
	$dbs = cms_verbinden('s');

	if (cms_r("artikel.genehmigen.blogeinträge")) {
		$ausgabe .= "<h2>Öffentliche Blogeinträge</h2>";
		$ausgabe .= "<table class=\"cms_liste\">";
			$ausgabe .= "<thead>";
				$ausgabe .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Datum</th><th>Autor</th><th>Ersteller</th><th></th><th>Aktionen</th></tr>";
			$ausgabe .= "</thead>";
			$ausgabe .= "<tbody>";
			$gfaelle = "";
			$sql = $dbs->prepare("SELECT blogeintraege.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, aktiv, oeffentlichkeit, datum, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, erstellt, blogeintraege.idzeit AS idzeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, personen.id AS person FROM blogeintraege LEFT JOIN personen ON blogeintraege.idvon = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE genehmigt = 0 ORDER BY datum DESC");

			$EINTRAEGE = array();
			if ($sql->execute()) {
				$sql->bind_result($bid, $bbez, $baktiv, $boeffentlichkeit, $bdatum, $bautor, $berstellt, $bidzeit, $bvorname, $bnachname, $btitel, $bpid);
				while ($sql->fetch()) {
					$E = array();
					$E['id'] = $bid;
					$E['bezeichnung'] = $bbez;
					$E['aktiv'] = $baktiv;
					$E['oeffentlichkeit'] = $boeffentlichkeit;
					$E['datum'] = $bdatum;
					$E['autor'] = $bautor;
					$E['erstellt'] = $berstellt;
					$E['idzeit'] = $bidzeit;
					$E['vorname'] = $bvorname;
					$E['nachname'] = $bnachname;
					$E['titel'] = $btitel;
					$E['person'] = $bpid;
					array_push($EINTRAEGE, $E);
				}
			}
			$sql->close();

			foreach ($EINTRAEGE AS $daten) {
				$gfaelle .= '<tr><td><img src="res/icons/klein/blog.png"></td><td>'.$daten['bezeichnung'].'</td>';
				$zuordnungen = "";
				foreach ($CMS_GRUPPEN as $g) {
					$gk = cms_textzudb($g);
					$sql = $dbs->prepare("SELECT * FROM (SELECT DISTINCT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon FROM $gk"."blogeintraege JOIN $gk ON gruppe = id WHERE blogeintrag = ?) AS x ORDER BY bezeichnung ASC");
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
				$gfaelle .= "<td>".date('d.m.Y', $daten['datum'])."</td>";
				$gfaelle .= "<td>".$daten['autor']."</td>";
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
					$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_blog_genehmigen('Blogeinträge', '".$daten['id']."');\"><span class=\"cms_hinweis\">Genehmigen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
					if (cms_r("artikel.{$daten['oeffentlichkeit']}.blogeinträge.bearbeiten")) {
						$gfaelle .= "<span class=\"cms_aktion_klein\" onclick=\"cms_blogeintraege_bearbeiten_vorbereiten('".$daten['id']."', 'Schulhof/Aufgaben/Blogeinträge_genehmigen');\"><span class=\"cms_hinweis\">Blogeintrag bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
					}
					$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_blog_ablehnen('Blogeinträge', '".$daten['id']."');\"><span class=\"cms_hinweis\">Ablehnen und löschen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
				$gfaelle .= '</td>';
				$gfaelle .= '</tr>';
			}
	    if (strlen($gfaelle) == 0) {
	      $gfaelle .= "<tr><td colspan=\"8\" class=\"cms_notiz\">-- keine Blogeinträge vorhanden --</td></tr>";
	    }
			$ausgabe .= $gfaelle;
			$ausgabe .= "</tbody>";
		$ausgabe .= "</table>";
	}


	if (cms_r("schulhof.gruppen.%GRUPPEN%.artikel.blogeinträge.genehmigen")) {
		$ausgabe .= "<h2>Interne Blogeinträge</h2>";
		$ausgabe .= "<table class=\"cms_liste\">";
			$ausgabe .= "<thead>";
				$ausgabe .= "<tr><th></th><th>Bezeichnung</th><th></th><th>Datum</th><th>Autor</th><th>Ersteller</th><th></th><th>Aktionen</th></tr>";
			$ausgabe .= "</thead>";
			$ausgabe .= "<tbody>";
			$gfaelle = "";

			foreach ($CMS_GRUPPEN as $g) {
				$gk = cms_textzudb($g);
				$sql = $dbs->prepare("SELECT $gk".".id AS gid, AES_DECRYPT(schuljahre.bezeichnung, '$CMS_SCHLUESSEL') AS schuljahr, $gk"."blogeintraegeintern.id AS id, AES_DECRYPT($gk"."blogeintraegeintern.bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, AES_DECRYPT($gk".".bezeichnung, '$CMS_SCHLUESSEL') AS gbezeichnung, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, aktiv, datum, erstellt, $gk"."blogeintraegeintern.idzeit AS idzeit, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, personen.id AS person FROM $gk"."blogeintraegeintern LEFT JOIN personen ON $gk"."blogeintraegeintern.idvon = personen.id LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id JOIN $gk"." ON $gk"."blogeintraegeintern.gruppe = $gk".".id LEFT JOIN schuljahre ON $gk".".schuljahr = schuljahre.id WHERE genehmigt = 0 ORDER BY beginn DESC, ende DESC");
			  if ($sql->execute()) {
					$sql->bind_result($gid, $gsj, $gbiid, $gbibezeichnng, $gbiautor, $gbez, $gicon, $gkativ, $gdatum, $gerstellt, $gbizeit, $gbivorname, $gbinachname, $gbititel, $gbpersid);
			    while ($sql->fetch()) {
			      $gfaelle .= '<tr><td><img src="res/icons/klein/blog.png"></td><td>'.$gbibezeichnng.'</td>';
			      $zuordnungen = "<span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">".$g." » ".$gbez."</span><img src=\"res/gruppen/klein/".$gicon."\"></span> ";
			      $gfaelle .= "<td>$zuordnungen</td>";
			      $gfaelle .= "<td>".date('d.m.Y', $gdatum)."</td>";
			      $gfaelle .= "<td>".$gbiautor."</td>";
						if ($gerstellt < $gbizeit) {
							$ersteller = cms_generiere_anzeigename($gbivorname, $gbinachname, $gbititel);
							if (in_array($gbpersid, $POSTEMPFAENGERPOOL)) {
								$ersteller = "<span class=\"cms_button\" onclick=\"cms_schulhof_postfach_nachricht_vorbereiten('vorgabe', '', '', '$gbpersid')\">$ersteller kontaktieren</span>";
							}
							else {$ersteller = "<span class=\"cms_button_passiv\">$ersteller kontaktieren</span>";}
						}
						else {$ersteller = "<i>Nutzerkonto existiert nicht mehr</i>";}
						$gfaelle .= "<td>$ersteller</td>";
			      if ($gkativ == 0) {$icon = "rot"; $aktiv = 'Inaktiv';}
			      if ($gkativ == 1) {$icon = "gruen"; $aktiv = 'Aktiv';}
			      $gfaelle .= "<td><span class=\"cms_icon_klein_o\"><span class=\"cms_hinweis\">$aktiv</span><img src=\"res/icons/klein/".$icon.".png\"></span></td>";
			      $gfaelle .= '<td>';
							$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_ja\" onclick=\"cms_blog_genehmigen('$g', '$gbiid');\"><span class=\"cms_hinweis\">Genehmigen</span><img src=\"res/icons/klein/akzeptieren.png\"></span> ";
							if (cms_r("schulhof.gruppen.$g.artikel.blogeinträge.bearbeiten")) {
								if (is_null($gsj)) {$gsj = 'Schuljahrübergreifend';}
								$gfaelle .= "<span class=\"cms_aktion_klein\" onclick=\"cms_blogeintraegeintern_bearbeiten_vorbereiten('$gbiid', 'Schulhof/Aufgaben/Blogeinträge_genehmigen', '$g', '$gid', '$gsj', '$gbez');\"><span class=\"cms_hinweis\">Blogeintrag bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
							}
							$gfaelle .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_blog_ablehnen('$g', '$gbiid');\"><span class=\"cms_hinweis\">Ablehnen und löschen</span><img src=\"res/icons/klein/ablehnen.png\"></span> ";
			      $gfaelle .= '</td>';
			      $gfaelle .= '</tr>';
			    }
			  }
				$sql->close();
			}

			if (strlen($gfaelle) == 0) {
				$gfaelle .= "<tr><td colspan=\"8\" class=\"cms_notiz\">-- keine Blogeinträge vorhanden --</td></tr>";
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
