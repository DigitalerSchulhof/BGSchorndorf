<?php
function cms_raum_ausgeben ($raumid) {
	global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$stundenplan = "";
	$buchbar = 0;
	$verfuegbar = 0;
	$extern = 0;
	$geraete = array();
	$blockierungen = array();

	if ($raumid != "-") {

		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(stundenplan, '$CMS_SCHLUESSEL') AS stundenplan, buchbar, verfuegbar, externverwaltbar FROM raeume WHERE id = ?");
	  $sql->bind_param("i", $raumid);
	  if ($sql->execute()) {
	    $sql->bind_result($bezeichnung, $stundenplan, $buchbar, $verfuegbar, $extern);
	    $sql->fetch();
	  }
	  $sql->close();

		// Geräte laden
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM raeumegeraete WHERE standort = ?) AS x ORDER BY bezeichnung ASC");
	  $sql->bind_param("i", $raumid);
	  if ($sql->execute()) {
			$i = 1;
	    $sql->bind_result($gid, $gbez);
	    while($sql->fetch()) {
				$geraete[$i]['bezeichnung'] = $gbez;
				$geraete[$i]['id'] = $gid;
				$i++;
	    }
	  }
	  $sql->close();

		// Blockierungen laden
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(grund, '$CMS_SCHLUESSEL') AS grund, wochentag, AES_DECRYPT(beginns, '$CMS_SCHLUESSEL') AS beginns, AES_DECRYPT(beginnm, '$CMS_SCHLUESSEL') AS beginnm, AES_DECRYPT(endes, '$CMS_SCHLUESSEL') AS endes, AES_DECRYPT(endem, '$CMS_SCHLUESSEL') AS endem, ferien FROM raeumeblockieren WHERE standort = ?) AS x ORDER BY wochentag ASC, grund ASC");
	  $sql->bind_param("i", $raumid);
	  if ($sql->execute()) {
			$i = 1;
	    $sql->bind_result($bid, $bgrund, $bwochentag, $bbgeinns, $bbeginnm, $bendes, $bendem, $bferien);
	    while($sql->fetch()) {
				$blockierungen[$i]['grund'] = $bgrund;
				$blockierungen[$i]['wochentag'] = $bwochentag;
				$blockierungen[$i]['beginns'] = $bbgeinns;
				$blockierungen[$i]['beginnm'] = $bbeginnm;
				$blockierungen[$i]['endes'] = $bendes;
				$blockierungen[$i]['endem'] = $bendem;
				$blockierungen[$i]['ferien'] = $bferien;
				$blockierungen[$i]['id'] = $bid;
				$i++;
	    }
	  }
	  $sql->close();
	}

	$code .= "</div>";

	$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_raeume_bezeichnung\" id=\"cms_raeume_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th>Verfügbar:</th><td>".cms_schieber_generieren('raeume_verfuegbar', $verfuegbar)."</td></tr>";
		$code .= "<tr><th>Buchbar:</th><td>".cms_schieber_generieren('raeume_buchbar', $buchbar)."</td></tr>";
		$code .= "<tr><th>Extern verwaltbar:</th><td>".cms_schieber_generieren('raeume_extern', $extern)."</td></tr>";
		if ($CMS_EINSTELLUNGEN['Stundenplan Raum extern'] == "1") {
			$code .= "<tr><th>Stundenplan:</th><td>".cms_dateiwahl_knopf ("schulhof/stundenplaene", "cms_raeume_stundenplan", "s", "Stundenplan", "-", "download", $stundenplan)."</td></tr>";
		}
	$code .= "</table>";

	if ($CMS_EINSTELLUNGEN['Stundenplan Raum extern'] != "1") {
		$code .= "<p><input type=\"hidden\" name=\"cms_raeume_stundenplan\" id=\"cms_raeume_stundenplan\" value=\"\"></p>";
	}

	$code .= "</div></div>";



	$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Blockierungen</h3>";

	$code .= "<div id=\"cms_blockierungen\">";

	$anzahl = 0;
	$ids = "";
	for ($i=1; $i<=count($blockierungen); $i++) {
		$code .= "<table class=\"cms_formular\" id=\"cms_blockierungen_".$blockierungen[$i]['id']."\">";
			$code .= "<tr><th>Wochentag:</th><td><select name=\"cms_blockierungen_wochentag_".$blockierungen[$i]['id']."\" id=\"cms_blockierungen_wochentag_".$blockierungen[$i]['id']."\">";
			for ($j=1; $j<=7; $j++) {
				if ($j == $blockierungen[$i]['wochentag']) {$selected = " selected=\"selected\"";} else {$selected = "";}
				$code .= "<option$selected value=\"$j\">".cms_tagnamekomplett($j)."</option>";
			}
			$code .= "</select><input type=\"hidden\" name=\"cms_blockierungen_id_".$blockierungen[$i]['id']."\" id=\"cms_blockierungen_id_".$blockierungen[$i]['id']."\" value=\"".$blockierungen[$i]['id']."\"></td>";
			$code .= "<td><span class=\"cms_button_nein\" onclick=\"cms_blockierung_entfernen('".$blockierungen[$i]['id']."');\">&times;</span></td></tr>";
			$code .= "<tr><th>Beginn:</th><td colspan=\"2\">".cms_uhrzeit_eingabe("cms_blockierungen_beginn_".$blockierungen[$i]['id'], $blockierungen[$i]['beginns'], $blockierungen[$i]['beginnm'])."</td></tr>";
			$code .= "<tr><th>Ende:</th><td colspan=\"2\">".cms_uhrzeit_eingabe("cms_blockierungen_ende_".$blockierungen[$i]['id'], $blockierungen[$i]['endes'], $blockierungen[$i]['endem'])."</td></tr>";
			$code .= "<tr><th>Grund:</th><td colspan=\"2\"><input type=\"text\" name=\"cms_blockierungen_grund_".$blockierungen[$i]['id']."\" id=\"cms_blockierungen_grund_".$blockierungen[$i]['id']."\" value=\"".$blockierungen[$i]['grund']."\"></td></tr>";
			$code .= "<tr><th><span class=\"cms_hinweis_aussen\">Ferien:<span class=\"cms_hinweis\">Auch in den Ferien blockieren?</span></span></th><td colspan=\"2\">".cms_schieber_generieren("blockierungen_ferien_".$blockierungen[$i]['id'], $blockierungen[$i]['ferien'])."</td></tr>";
		$code .= "</table>";
		$anzahl++;
		$ids .= "|".$blockierungen[$i]['id'];
	}
	$code .= "</div>";

	$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_blockierung_neu();\">+ Blockierung hinzufügen</span>";

	$code .= "<input type=\"hidden\" id=\"cms_blockierungen_anzahl\" name=\"cms_blockierungen_anzahl\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_blockierungen_nr\" name=\"cms_blockierungen_nr\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_blockierungen_ids\" name=\"cms_blockierungen_ids\" value=\"$ids\"></p>";

	$code .= "</div></div>";



	$code .= "<div class=\"cms_spalte_3\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Ausstattung</h3>";

	$code .= "<div id=\"cms_ausstattung_geraete\">";

	$anzahl = 0;
	$ids = "";
	for ($i=1; $i<=count($geraete); $i++) {
		$code .= "<table class=\"cms_formular\" id=\"cms_ausstattung_geraete_".$geraete[$i]['id']."\">";
			$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_ausstattung_geraete_bezeichnung_".$geraete[$i]['id']."\" id=\"cms_ausstattung_geraete_bezeichnung_".$geraete[$i]['id']."\" value=\"".$geraete[$i]['bezeichnung']."\">";
			$code .= " <input type=\"hidden\" name=\"cms_ausstattung_geraete_id_".$geraete[$i]['id']."\" id=\"cms_ausstattung_geraete_id_".$geraete[$i]['id']."\" value=\"".$geraete[$i]['id']."\"></td>";
		$code .= "<td><span class=\"cms_button_nein\" onclick=\"cms_schulhof_ausstattung_geraet_entfernen('".$geraete[$i]['id']."');\">&times;</span></td></tr>";
		$code .= "</table>";
		$anzahl++;
		$ids .= "|".$geraete[$i]['id'];
	}
	$code .= "</div>";

	$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_schulhof_ausstattung_neues_geraet();\">+ Gerät hinzufügen</span>";

	$code .= "<input type=\"hidden\" id=\"cms_ausstattung_geraete_anzahl\" name=\"cms_ausstattung_geraete_anzahl\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_ausstattung_geraete_nr\" name=\"cms_ausstattung_geraete_nr\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_ausstattung_geraete_ids\" name=\"cms_ausstattung_geraete_ids\" value=\"$ids\"></p>";

	$code .= "</div></div>";




	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";

	cms_trennen($dbs);


	return $code;
}
?>
