<?php
function cms_gruppentermine_ausgeben($dbs, $gruppe, $gruppenid, $limit, $CMS_URLGANZ, $gruppenrechte) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid,0))) {
		$gk = cms_textzudb($gruppe);
		$TERMINE = array();
		if (!$gruppenrechte['termine']) {$aktivitaetnoetig = " AND aktiv = 1";}
		else {$aktivitaetnoetig = "";}

		$jetzt = time();
		$sqloe = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, 'oe' AS art FROM termine JOIN $gk"."termine ON termine.id = $gk"."termine.termin WHERE gruppe = ? AND ende > ? AND aktiv = 1 ORDER BY beginn ASC, ende ASC LIMIT ?";
		$sqlin = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, 'in' AS art FROM $gk"."termineintern WHERE gruppe = ? AND ende > ?$aktivitaetnoetig ORDER BY beginn ASC, ende ASC LIMIT ?";

		$sql = $dbs->prepare("SELECT * FROM (($sqloe) UNION ($sqlin)) AS x ORDER BY beginn ASC, ende ASC, bezeichnung ASC LIMIT ?");
		$sql->bind_param("iiiiiii", $gruppenid, $jetzt, $limit, $gruppenid, $jetzt, $limit, $limit);
		// Terminausgabe erzeugen
		if ($sql->execute()) {	// Safe weil keine Eingabe
			$sql->bind_result($tid, $tbezeichnung, $tort, $tbeginn, $tende, $tmehrtaegigt, $tuhrzeitbt, $tuhrzeitet, $tortt, $tgenehmigt, $taktiv, $ttext, $tart);
			while ($sql->fetch()) {
				$T = array();
				$T['id'] = $tid;
				$T['bezeichnung'] = $tbezeichnung;
				$T['ort'] = $tort;
				$T['beginn'] = $tbeginn;
				$T['ende'] = $tende;
				$T['mehrtaegigt'] = $tmehrtaegigt;
				$T['uhrzeitbt'] = $tuhrzeitbt;
				$T['uhrzeitet'] = $tuhrzeitet;
				$T['ortt'] = $tortt;
				$T['genehmigt'] = $tgenehmigt;
				$T['aktiv'] = $taktiv;
				$T['text'] = $ttext;
				$T['art'] = $tart;
				array_push($TERMINE, $T);
			}
		}
		$sql->close();

		foreach ($TERMINE AS $E) {
			$code .= cms_termin_link_ausgeben($dbs, $E, $CMS_URLGANZ);
		}
	}

	return $code;
}


function cms_gruppenblogeintraege_ausgeben($dbs, $gruppe, $gruppenid, $limit, $art, $CMS_URLGANZ, $gruppenrechte) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid,0))) {
		$gk = cms_textzudb($gruppe);
		$BLOGEINTRAEGE = array();
		if (!$gruppenrechte['blogeintraege']) {$aktivitaetnoetig = " AND aktiv = 1";}
		else {$aktivitaetnoetig = "";}

		$jetzt = time();
		$sqloe = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, 'oe' AS art FROM blogeintraege JOIN $gk"."blogeintraege ON blogeintraege.id = $gk"."blogeintraege.blogeintrag WHERE gruppe = ? AND aktiv = 1 ORDER BY datum DESC LIMIT ?";
		$sqlin = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, '' AS vorschaubild, 'in' AS art FROM $gk"."blogeintraegeintern WHERE gruppe = ?$aktivitaetnoetig ORDER BY datum DESC LIMIT ?";
		$sql = $dbs->prepare("SELECT * FROM (($sqloe) UNION ($sqlin)) AS x ORDER BY datum DESC, bezeichnung ASC LIMIT ?");
		$sql->bind_param("iiiii", $gruppenid, $limit, $gruppenid, $limit, $limit);
		// Blogausgabe erzeugen
		if ($sql->execute()) {	// Safe weil keine Eingabe
			$sql->bind_result($bid, $bbezeichnung, $bautor, $bdatum, $bgenehmigt, $baktiv, $btext, $bvorschau, $bvorschaubild, $bart);
			while ($sql->fetch()) {
				$B = array();
				$B['id'] = $bid;
				$B['bezeichnung'] = $bbezeichnung;
				$B['autor'] = $bautor;
				$B['datum'] = $bdatum;
				$B['genehmigt'] = $bgenehmigt;
				$B['aktiv'] = $baktiv;
				$B['text'] = $btext;
				$B['vorschau'] = $bvorschau;
				$B['vorschaubild'] = $bvorschaubild;
				$B['art'] = $bart;
				array_push($BLOGEINTRAEGE, $B);
			}
		}
		$sql->close();

		foreach ($BLOGEINTRAEGE AS $E) {
			$code .= cms_blogeintrag_link_ausgeben($dbs, $E, $art, $CMS_URLGANZ);
		}
	}

	return $code;
}


function cms_gruppenbeschluesse_ausgeben($dbs, $gruppe, $gruppenid, $limit, $CMS_URLGANZ) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid,0))) {
		$gk = cms_textzudb($gruppe);
		$BESCHLUESSE = array();
		$jetzt = time();
		$sql = "SELECT $gk"."blogeintraegeintern.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(langfristig, '$CMS_SCHLUESSEL') AS langfristig, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, pro, contra, enthaltung FROM $gk"."blogeintraegeintern JOIN $gk"."blogeintragbeschluesse ON $gk"."blogeintraegeintern.id = $gk"."blogeintragbeschluesse.blogeintrag WHERE gruppe = ?";
		$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY datum DESC, titel ASC LIMIT ?");
		$sql->bind_param("ii", $gruppenid, $limit);

		// Blogausgabe erzeugen
		if ($sql->execute()) {	// Safe weil keine Eingabe
			$sql->bind_result($bid, $bbezeichnung, $bdatum, $btitel, $blangfristig, $bbeschreibung, $bpro, $bcontra, $benthaltung);
			while ($sql->fetch()) {
				$B = array();
				$B['id'] = $bid;
				$B['bezeichnung'] = $bbezeichnung;
				$B['datum'] = $bdatum;
				$B['titel'] = $btitel;
				$B['langfristig'] = $blangfristig;
				$B['beschreibung'] = $bbeschreibung;
				$B['pro'] = $bpro;
				$B['contra'] = $bcontra;
				$B['enthaltung'] = $benthaltung;
				array_push($BESCHLUESSE, $B);
			}
		}
		$sql->close();

		foreach ($BESCHLUESSE AS $E) {
			$code .= cms_beschluss_ausgeben($E, true, $CMS_URLGANZ);
		}
	}

	return $code;
}


function cms_gruppenblogeintraege_monat_ausgeben($dbs, $gruppe, $gruppenid, $art, $CMS_URLGANZ, $monat, $jahr, $gruppenrechte) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && cms_check_ganzzahl($gruppenid,0) && cms_check_ganzzahl($monat,1,12) && cms_check_ganzzahl($jahr,0)) {
    $beginn = mktime (0, 0, 0, $monat, 1, $jahr);
    $ende = mktime(0,0,0,$monat+1,1,$jahr)-1;
		$BLOGEINTRAEGE  = array();
		$gk = cms_textzudb($gruppe);
		if (!$gruppenrechte['blogeintraege']) {$aktivitaetnoetig = " AND aktiv = 1";}
		else {$aktivitaetnoetig = "";}

		$sqloe = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, 'oe' AS art FROM blogeintraege JOIN $gk"."blogeintraege ON blogeintraege.id = $gk"."blogeintraege.blogeintrag WHERE gruppe = ? AND (datum BETWEEN ? AND ?) AND aktiv = 1";
		$sqlin = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, '' AS vorschaubild, 'in' AS art FROM $gk"."blogeintraegeintern WHERE gruppe = ? AND (datum BETWEEN ? AND ?) $aktivitaetnoetig";
		$sql = $dbs->prepare("SELECT * FROM (($sqloe) UNION ($sqlin)) AS x ORDER BY datum DESC, bezeichnung ASC");
		$sql->bind_param("iiiiii", $gruppenid, $beginn, $ende, $gruppenid, $beginn, $ende);
		// Blogausgabe erzeugen
		if ($sql->execute()) {
			$sql->bind_result($bid, $bbezeichnung, $bautor, $bdatum, $bgenehmigt, $baktiv, $btext, $bvorschau, $bvorschaubild, $bart);
			while ($sql->fetch()) {
				$B = array();
				$B['id'] = $bid;
				$B['bezeichnung'] = $bbezeichnung;
				$B['autor'] = $bautor;
				$B['datum'] = $bdatum;
				$B['genehmigt'] = $bgenehmigt;
				$B['aktiv'] = $baktiv;
				$B['text'] = $btext;
				$B['vorschau'] = $bvorschau;
				$B['vorschaubild'] = $bvorschaubild;
				$B['art'] = $bart;
				array_push($BLOGEINTRAEGE, $B);
			}
		}
		$sql->close();

		foreach ($BLOGEINTRAEGE AS $E) {
			$code .= cms_blogeintrag_link_ausgeben($dbs, $E, $art, $CMS_URLGANZ);
		}
	}

	return $code;
}


function cms_gruppenbeschluesse_jahr_ausgeben($dbs, $gruppe, $gruppenid, $CMS_URLGANZ, $jahr) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid,0))) {
		$beginn = mktime (0, 0, 0, 1, 1, $jahr);
    $ende = mktime(0,0,0,1,1,$jahr+1)-1;
		$gk = cms_textzudb($gruppe);
		$jetzt = time();
		$sql = "SELECT $gk"."blogeintraegeintern.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), datum, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(langfristig, '$CMS_SCHLUESSEL'), AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL'), pro, contra, enthaltung FROM $gk"."blogeintraegeintern JOIN $gk"."blogeintragbeschluesse ON $gk"."blogeintraegeintern.id = $gk"."blogeintragbeschluesse.blogeintrag WHERE gruppe = ? AND (datum BETWEEN ? AND ?)";
		$sql = $dbs->prepare("SELECT * FROM ($sql) AS x ORDER BY datum DESC, titel ASC ");
		$sql->bind_param("iii", $gruppenid, $beginn, $ende);
		// Blogausgabe erzeugen
		if ($sql->execute()) {
			$sql->bind_result($bid, $bez, $bdatum, $btitel, $blangfristig, $bbeschreibung, $bpro, $bcontra, $benthaltung);
			while ($sql->fetch()) {
				$B = array();
				$B['id'] = $bid;
				$B['bezeichnung'] = $bez;
				$B['datum'] = $bdatum;
				$B['titel'] = $btitel;
				$B['langfristig'] = $blangfristig;
				$B['beschreibung'] = $bbeschreibung;
				$B['pro'] = $bpro;
				$B['contra'] = $bcontra;
				$B['enthaltung'] = $benthaltung;
				$code .= cms_beschluss_ausgeben($B, true, $CMS_URLGANZ);
			}
		}
		$sql->close();
	}

	return $code;
}

function cms_gruppenchat_ausgeben($dbs, $g, $gruppenid, $rechte) {
	GLOBAL $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_BENUTZERART, $CMS_EINSTELLUNGEN;
	$limit = 20;
	$namecache = array();
	$nachrichten = array();
	$gk = cms_textzudb($g);

	$loeschen = $CMS_EINSTELLUNGEN["Chat Nachrichten löschen nach"];
	if($loeschen) {
		$loeschen = $loeschen*24*60*60;	// Zu sek
		$sql = "DELETE FROM $gk"."chat WHERE datum < ".(time()-$loeschen);
		$sql = $dbs->prepare($sql);
		$sql->execute();
		$sql->close();
	}

	$sql = "UPDATE $gk"."mitglieder SET chatbannbis = 0 WHERE chatbannbis < ".time();
	$sql = $dbs->prepare($sql);
	$sql->execute();
	$sql->close();

	$gebannt = 1;
	// Stummschaltung prüfen
	$sql = "SELECT COUNT(*) FROM $gk"."mitglieder WHERE person = ? AND gruppe = ? AND chatbannbis = 0";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $CMS_BENUTZERID, $gruppenid);
	$sql->bind_result($gebannt);
	$sql->execute();
	$sql->fetch();
	$sql->close();
	$gebannt = !$gebannt;		// Umkehrung, weil bei abgelaufener Banndauer (bannbis == 0) 1 gegeben wird.

	$sql = "SELECT COUNT(*) FROM $gk"."mitglieder WHERE person = ? AND gruppe = ?";
	$sql = $dbs->prepare($sql);
	$sql->bind_param("ii", $CMS_BENUTZERID, $gruppenid);
	$sql->bind_result($istda);
	$sql->execute();
	$sql->fetch();
	$sql->close();
	$gebannt = $gebannt && $istda;
	$code = "";
	$code .= "<div id=\"cms_chat\" class=\"cms_chat_status cms_chat_laden\">";
		$code .= "<div id=\"cms_chat_laden\">";
			$code .= cms_ladeicon();
		$code .= "</div>";
		$code .= "<div id=\"cms_chat_status\">";
			$code .= "<h3>Skript wird geladen...</h3>";
		$code .= "</div>";
		$code .= "<div id=\"cms_chat_leer\" class=\"cms_notiz\">";
			$code .= "Keine Nachrichten vorhanden";
		$code .= "</div>";
		$code .= "<div id=\"cms_chat_mehr\" class=\"cms_notiz\" onclick=\"socketChat.nachladen();\">";
			$code .= "Ältere Nachrichten laden";
		$code .= "</div>";
		$code .= "<div id=\"cms_chat_nachrichten\">";
			$code .= cms_meldung_fehler();	// Sollte bei erfolgreichem Laden nicht sichtbar sein
		$code .= "</div>";
		$code .= "<div id=\"cms_chat_berechtigung\">";
			$code .= cms_meldung_berechtigung(false);
		$code .= "</div>";
		$code .= "<div id=\"cms_chat_stumm\">";
			if($CMS_BENUTZERART == 's')
				$code .= cms_meldung("fehler", "<h4>Du wurdest stummgeschalten</h4><p>Dir wurde vorläufig das Recht des Schreibens genommen!</p>");
			else
				$code .= cms_meldung("fehler", "<h4>Sie wurden stummgeschalten</h4><p>Ihnen wurde vorläufig das Recht des Schreibens genommen!</p>");
		$code .= "</div>";

		if($rechte["chatten"]) {	// Schreibrecht
			$code .= "<div id=\"cms_chat_nachricht_verfassen\">";
				$code .= "<label for=\"cms_chat_neue_nachricht\"><p class=\"cms_notiz\">Nachricht verfassen:</p></label>";
				$code .= "<textarea data-gramm=\"false\" type=\"text\" id=\"cms_chat_neue_nachricht\" onkeypress=\"return cms_chat_enter(event, '$g', '$gruppenid');\"></textarea><div onclick=\"cms_chat_nachricht_senden('$g', '$gruppenid')\"><img src=\"res/icons/klein/senden.png\"></div>";
			$code .= "</div>";
		}

	$code .= "</div>";
	$code .= "<script>socketChat.server.ip='".$CMS_EINSTELLUNGEN['Netze Socket-IP']."';";
	$code .= "socketChat.server.port='".$CMS_EINSTELLUNGEN['Netze Socket-Port']."';";
	$code .= "$(window).on('load', function() {socketChat.init('$g', '$gruppenid');})</script>";
	return $code;
}


function cms_gruppenlinks_ausgeben($dbs, $gruppe, $gruppenid, $gruppenrechte) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid, 0))) {
		$gk = cms_textzudb($gruppe);
		$LINKS = array();

		$jetzt = time();
		$sql = $dbs->prepare("SELECT id, AES_DECRYPT(link, '$CMS_SCHLUESSEL') AS link, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung FROM $gk" . "links WHERE gruppe = ? ORDER BY id ASC");
		$sql->bind_param("i", $gruppenid);
		// Blogausgabe erzeugen
		if ($sql->execute()) {	// Safe weil keine Eingabe
			$sql->bind_result($lid, $llink, $ltitel, $lbeschreibung);
			while ($sql->fetch()) {
				$L = array();
				$L['id'] = $lid;
				$L['link'] = $llink;
				$L['titel'] = $ltitel;
				$L['beschreibung'] = $lbeschreibung;
				array_push($LINKS, $L);
			}
		}
		$sql->close();

		foreach ($LINKS as $L) {
			$code .= "<a href=\"{$L["link"]}\" class=\"cms_artikellink_anzeige\" target=\"_blank\" style=\"background-image: url('res/gruppen/gross/gebaeude_turm.png');\">";
			$code .= "<h4>{$L["titel"]}</h4>";
			if (strlen($L["beschreibung"]) > 0) {
				$code .= "<p>{$L["beschreibung"]}</p>";
			}
			$info = $L["link"];
			if (strlen($info) > 0) {
				$code .= "<p class=\"cms_notiz\">" . $info . "</p>";
			}
			$code .= "</a>";
		}
	}

	return $code;
}

?>
