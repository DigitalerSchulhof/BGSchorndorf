<?php
function cms_seitenwahl_generieren($dbs, $id) {
	$code = "";

	$code .= "<span class=\"cms_button\" onclick=\"cms_einblenden('$id"."_wahl_seitenwahlF')\">Ändern</span>";
	$code .= "<div class=\"cms_seitenwahl\" id=\"$id"."_wahl_seitenwahlF\">";
	$code .= "<span class=\"cms_fenster_schliessen cms_button_nein\" onclick=\"cms_ausblenden('$id"."_wahl_seitenwahlF')\"><span class=\"cms_hinweis\">Fenster schließen</span>×</span>";
	$code .= "<div class=\"cms_spalte_i\">";
		$code .= cms_seitenwahl_seiten($dbs, $id, '-', true);
	$code .= "</div>";
	$code .= "</div>";
	return $code;
}

function cms_seitenwahl_seiten ($dbs, $id, $oberseite, $sichtbar = false) {
	$code = "";
	if ($oberseite === '-') {
		$sql = $dbs->prepare("SELECT id, bezeichnung FROM seiten WHERE zuordnung IS NULL ORDER BY position");
	}
	else {
		$sql = $dbs->prepare("SELECT id, bezeichnung FROM seiten WHERE zuordnung = ? ORDER BY position");
		$sql->bind_param('s', $oberseite);
	}
	$SEITEN = array();
	if ($sql->execute()) {
		$sql->bind_result($sid, $sbez);
		while ($sql->fetch()) {
			$S = array();
			$S['id'] = $sid;
			$S['bezeichnung'] = $sbez;
			array_push($SEITEN, $S);
		}
	}
	$sql->close();

	if (!$sichtbar) {$style = " style=\"display: none;\"";} else {$style = "";}
	$code .= "<ul$style class=\"$id"."_wahl_gruppe_".$oberseite."\">";
	foreach ($SEITEN as $daten) {
		$code .= "<li><span id=\"$id"."_wahl_knopf_".$daten['id']."\"><span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_ausklappen ('$id"."_wahl_knopf_".$daten['id']."', '$id"."_wahl_gruppe_".$daten['id']."')\"><img src=\"res/icons/klein/ausklappen.png\"><span class=\"cms_hinweis\">ausklappen</span></span></span> <span class=\"cms_button\" onclick=\"cms_seitenwahl_auswahl('$id', '".$daten['bezeichnung']."', '".$daten['id']."')\">".$daten['bezeichnung']."</span>";
		$code .= cms_seitenwahl_seiten ($dbs, $id, $daten['id']);
		$code .= "</li>";
	}
	if (count($SEITEN) == 0) {$code .= "<li class=\"cms_notiz\">Keine Seiten</li>";}
	$code .= "</ul>";
	return $code;
}
?>
