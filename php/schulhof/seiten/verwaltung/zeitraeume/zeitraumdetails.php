<?php
function cms_zeitraum_ausgeben ($zeitraumid) {
	global $CMS_SCHLUESSEL, $CMS_EINSTELLUNGEN;
	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	$jetzt = time();
	$beginn = mktime(0,0,0,date('m', $jetzt), date('d', $jetzt), date('Y', $jetzt));
	$ende = mktime(0,0,0,date('m', $jetzt)+6, date('d', $jetzt)-1, date('Y', $jetzt));
	$mo = 1;
	$di = 1;
	$mi = 1;
	$do = 1;
	$fr = 1;
	$sa = 0;
	$so = 0;
	$schulstunden = array();

	if ($zeitraumid != "-") {

		$sql = $dbs->prepare("SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginn, ende, mo, di, mi, do, fr, sa, so FROM zeitraeume WHERE id = ?");
	  $sql->bind_param("i", $zeitraumid);
	  if ($sql->execute()) {
	    $sql->bind_result($bezeichnung, $beginn, $ende, $mo, $di, $mi, $do, $fr, $sa, $so);
	    $sql->fetch();
	  }
	  $sql->close();

		// Schulstunden laden
		$sql = $dbs->prepare("SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, beginns, beginnm, endes, endem FROM schulstunden WHERE zeitraum = ?) AS x ORDER BY beginns ASC, beginnm ASC");
	  $sql->bind_param("i", $zeitraumid);
	  if ($sql->execute()) {
			$i = 1;
	    $sql->bind_result($sid, $sbez, $sbeginns, $sbeginnm, $sendes, $sendem);
	    while($sql->fetch()) {
				$schulstunden[$i]['id'] = $sid;
				$schulstunden[$i]['bezeichnung'] = $sbez;
				$schulstunden[$i]['beginns'] = $sbeginns;
				$schulstunden[$i]['beginnm'] = $sbeginnm;
				$schulstunden[$i]['endes'] = $sendes;
				$schulstunden[$i]['endem'] = $sendem;
				$i++;
	    }
	  }
	  $sql->close();
	}

	$code .= "</div>";

	$code .= "<div class=\"cms_spalte_60\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Details</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td colspan=\"7\"><input type=\"text\" name=\"cms_zeitraeume_bezeichnung\" id=\"cms_zeitraeume_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
		$code .= "<tr><th>Beginn:</th><td colspan=\"7\">".cms_datum_eingabe('cms_zeitraeume_beginn', date('d', $beginn), date('m', $beginn), date('Y', $beginn))."</td></tr>";
		$code .= "<tr><th>Ende:</th><td colspan=\"7\">".cms_datum_eingabe('cms_zeitraeume_ende', date('d', $ende), date('m', $ende), date('Y', $ende))."</td></tr>";
		$code .= "<tr><th></th><td>MO</td><td>DI</td><td>MI</td><td>DO</td><td>FR</td><td>SA</td><td>SO</td></tr>";
		$code .= "<tr><th>Schultage:</th><td>".cms_schieber_generieren('cms_zeitraeume_mo', $mo)."</td>";
		$code .= "<td>".cms_schieber_generieren('cms_zeitraeume_di', $di)."</td>";
		$code .= "<td>".cms_schieber_generieren('cms_zeitraeume_mi', $mi)."</td>";
		$code .= "<td>".cms_schieber_generieren('cms_zeitraeume_do', $do)."</td>";
		$code .= "<td>".cms_schieber_generieren('cms_zeitraeume_fr', $fr)."</td>";
		$code .= "<td>".cms_schieber_generieren('cms_zeitraeume_sa', $sa)."</td>";
		$code .= "<td>".cms_schieber_generieren('cms_zeitraeume_so', $so)."</td>";
		$code .= "</tr>";
	$code .= "</table>";

	$code .= "</div></div>";



	$code .= "<div class=\"cms_spalte_40\"><div class=\"cms_spalte_i\">";
	$code .= "<h3>Schulstunden</h3>";

	$code .= "<div id=\"cms_zeitraeume_schulstunden\">";

	$anzahl = 0;
	$ids = "";
	for ($i=1; $i<=count($schulstunden); $i++) {
		$code .= "<table class=\"cms_formular\" id=\"cms_zeitraeume_schulstunden_".$schulstunden[$i]['id']."\">";
			$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_zeitraeume_schulstunden_bezeichnung_".$schulstunden[$i]['id']."\" id=\"cms_zeitraeume_schulstunden_bezeichnung_".$schulstunden[$i]['id']."\" value=\"".$schulstunden[$i]['bezeichnung']."\"><input type=\"hidden\" name=\"cms_zeitraeume_schulstunden_id_".$schulstunden[$i]['id']."\" id=\"cms_zeitraeume_schulstunden_id_".$schulstunden[$i]['id']."\" value=\"".$schulstunden[$i]['id']."\"></td></tr>";
			$code .= "<tr><th>Beginn:</th><td>".cms_uhrzeit_eingabe('cms_zeitraeume_schulstunden_beginn_'.$schulstunden[$i]['id'], cms_fuehrendenull($schulstunden[$i]['beginns']), cms_fuehrendenull($schulstunden[$i]['beginnm']))."</td></tr>";
			$code .= "<tr><th>Ende:</th><td>".cms_uhrzeit_eingabe('cms_zeitraeume_schulstunden_ende_'.$schulstunden[$i]['id'], cms_fuehrendenull($schulstunden[$i]['endes']), cms_fuehrendenull($schulstunden[$i]['endem']))."</td></tr>";
		$code .= "<tr><th></th><td><span class=\"cms_button_nein\" onclick=\"cms_zeitraeume_schulstunden_entfernen('".$schulstunden[$i]['id']."');\">Schulstunde löschen</span></td></tr>";
		$code .= "</table>";
		$anzahl++;
		$ids .= "|".$schulstunden[$i]['id'];
	}
	$code .= "</div>";

	$code .= "<p><span class=\"cms_button_ja\" onclick=\"cms_zeitraeume_neue_schulstunde();\">+ Schulstunde hinzufügen</span>";

	$code .= "<input type=\"hidden\" id=\"cms_zeitraeume_schulstunden_anzahl\" name=\"cms_zeitraeume_schulstunden_anzahl\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_zeitraeume_schulstunden_nr\" name=\"cms_zeitraeume_schulstunden_nr\" value=\"$anzahl\">";
	$code .= "<input type=\"hidden\" id=\"cms_zeitraeume_schulstunden_ids\" name=\"cms_zeitraeume_schulstunden_ids\" value=\"$ids\"></p>";

	$code .= "</div></div>";




	$code .= "<div class=\"cms_clear\"></div>";
	$code .= "<div class=\"cms_spalte_i\">";

	cms_trennen($dbs);


	return $code;
}
?>
