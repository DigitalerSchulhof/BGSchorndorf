<?php
function cms_gruppentermine_ausgeben($dbs, $gruppe, $gruppenid, $limit, $CMS_URLGANZ) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid,0))) {
		$gk = cms_textzudb($gruppe);

		$jetzt = time();
		$sqloe = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, 'oe' AS art FROM termine JOIN $gk"."termine ON termine.id = $gk"."termine.termin WHERE gruppe = $gruppenid AND ende > $jetzt";
		$sqlin = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(ort, '$CMS_SCHLUESSEL') AS ort, beginn, ende, mehrtaegigt, uhrzeitbt, uhrzeitet, ortt, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, 'in' AS art FROM $gk"."termineintern WHERE gruppe = $gruppenid AND ende > $jetzt";
		$sql = "SELECT * FROM (($sqloe) UNION ($sqlin)) AS x ORDER BY beginn ASC, ende ASC, bezeichnung ASC LIMIT $limit";

		// Terminausgabe erzeugen
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_termin_link_ausgeben($dbs, $daten, $CMS_URLGANZ);
			}
			$anfrage->free();
		}
	}

	return $code;
}


function cms_gruppenblogeintraege_ausgeben($dbs, $gruppe, $gruppenid, $limit, $art, $CMS_URLGANZ) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid,0))) {
		$gk = cms_textzudb($gruppe);
		$jetzt = time();
		$sqloe = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, 'oe' AS art FROM blogeintraege JOIN $gk"."blogeintraege ON blogeintraege.id = $gk"."blogeintraege.blogeintrag WHERE gruppe = $gruppenid";
		$sqlin = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, '' AS vorschaubild, 'in' AS art FROM $gk"."blogeintraegeintern WHERE gruppe = $gruppenid";
		$sql = "SELECT * FROM (($sqloe) UNION ($sqlin)) AS x ORDER BY datum DESC, bezeichnung ASC LIMIT $limit";

		// Blogausgabe erzeugen
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_blogeintrag_link_ausgeben($dbs, $daten, $art, $CMS_URLGANZ);
			}
			$anfrage->free();
		}
	}

	return $code;
}


function cms_gruppenbeschluesse_ausgeben($dbs, $gruppe, $gruppenid, $limit, $CMS_URLGANZ) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && (cms_check_ganzzahl($gruppenid,0))) {
		$gk = cms_textzudb($gruppe);
		$jetzt = time();
		$sql = "SELECT $gk"."blogeintraegeintern.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(langfristig, '$CMS_SCHLUESSEL') AS langfristig, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, pro, contra, enthaltung FROM $gk"."blogeintraegeintern JOIN $gk"."blogeintragbeschluesse ON $gk"."blogeintraegeintern.id = $gk"."blogeintragbeschluesse.blogeintrag WHERE gruppe = $gruppenid";
		$sql = "SELECT * FROM ($sql) AS x ORDER BY datum DESC, titel ASC LIMIT $limit";

		// Blogausgabe erzeugen
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_beschluss_ausgeben($daten, true, $CMS_URLGANZ);
			}
			$anfrage->free();
		}
	}

	return $code;
}


function cms_gruppenblogeintraege_monat_ausgeben($dbs, $gruppe, $gruppenid, $art, $CMS_URLGANZ, $monat, $jahr) {
	global $CMS_SCHLUESSEL;
	$code = "";

	if (cms_valide_gruppe($gruppe) && cms_check_ganzzahl($gruppenid,0) && cms_check_ganzzahl($monat,1,12) && cms_check_ganzzahl($jahr,0)) {
    $beginn = mktime (0, 0, 0, $monat, 1, $jahr);
    $ende = mktime(0,0,0,$monat+1,1,$jahr)-1;

		$gk = cms_textzudb($gruppe);
		$sqloe = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, AES_DECRYPT(vorschaubild, '$CMS_SCHLUESSEL') AS vorschaubild, 'oe' AS art FROM blogeintraege JOIN $gk"."blogeintraege ON blogeintraege.id = $gk"."blogeintraege.blogeintrag WHERE gruppe = $gruppenid AND (datum BETWEEN $beginn AND $ende)";
		$sqlin = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(autor, '$CMS_SCHLUESSEL') AS autor, datum, genehmigt, aktiv, AES_DECRYPT(text, '$CMS_SCHLUESSEL') AS text, AES_DECRYPT(vorschau, '$CMS_SCHLUESSEL') AS vorschau, '' AS vorschaubild, 'in' AS art FROM $gk"."blogeintraegeintern WHERE gruppe = $gruppenid AND (datum BETWEEN $beginn AND $ende)";
		$sql = "SELECT * FROM (($sqloe) UNION ($sqlin)) AS x ORDER BY datum DESC, bezeichnung ASC";

		// Blogausgabe erzeugen
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_blogeintrag_link_ausgeben($dbs, $daten, $art, $CMS_URLGANZ);
			}
			$anfrage->free();
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
		$sql = "SELECT $gk"."blogeintraegeintern.id AS id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, datum, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(langfristig, '$CMS_SCHLUESSEL') AS langfristig, AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') AS beschreibung, pro, contra, enthaltung FROM $gk"."blogeintraegeintern JOIN $gk"."blogeintragbeschluesse ON $gk"."blogeintraegeintern.id = $gk"."blogeintragbeschluesse.blogeintrag WHERE gruppe = $gruppenid AND (datum BETWEEN $beginn AND $ende)";
		$sql = "SELECT * FROM ($sql) AS x ORDER BY datum DESC, titel ASC ";

		// Blogausgabe erzeugen
		if ($anfrage = $dbs->query($sql)) {
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_beschluss_ausgeben($daten, true, $CMS_URLGANZ);
			}
			$anfrage->free();
		}
	}

	return $code;
}

function cms_gruppenchat_ausgeben($dbs, $g, $gruppenid, $rechte) {
	GLOBAL $CMS_SCHLUESSEL, $CMS_BENUTZERID, $CMS_EINSTELLUNGEN;
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
	}

	$code = "";
	$code .= "<div id=\"cms_chat\">";
		$code .= "<div id=\"cms_chat_nachrichten\">";
			// Nachrichten laden
			$sql = "SELECT id, person, datum, AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') as inhalt, meldestatus FROM $gk"."chat WHERE gruppe = $gruppenid ORDER BY id DESC LIMIT ".($limit+1);
			$sql = $dbs->prepare($sql);
			$sql->bind_result($id, $p, $d, $i, $m);
			$sql->execute();
			while($sql->fetch())
				array_push($nachrichten, array("id" => $id, "person" => $p, "datum" => $d, "inhalt" => $i, "meldestatus" => $m));

			if(!count($nachrichten))
				$code .= "<div id=\"cms_chat_leer\" class=\"cms_notiz\">Keine Nachrichten vorhanden.</div>";
			else if(count($nachrichten) > $limit)
				$code .= "<div id=\"cms_chat_nachrichten_nachladen\" class=\"cms_notiz\" onclick=\"cms_chat_nachrichten_nachladen('$g', '$gruppenid', $limit);\">Ältere Nachrichten laden</div>";

			$_SESSION["LETZTENACHRICHT_$g"]["$gruppenid"] = -1;
			$letztesDatum = "blub";	// Dummy
			$tag = "blub";					// Dummy
			$ccode = $code;	// Rest-Code
			$ncode = "";	// Nachrichten Code

			foreach($nachrichten as $i => $n) {		// Nachrichten von unten nach oben (Neuste zu Älteste)
				if($i >= $limit)	//	Limit an zu ladenden Nachrichten überschritten. Es wird Eine zu viel in der SQL geladen, um zu prüfen, ob noch Nachrichten nachzuladen sind (Anzahl an Nachrchten > $limit)
					break;
				$code = "";	// Code der aktuellen Nachricht

				if(array_key_exists($n["person"], $namecache)) {	// Name des Senders finden, ggf. im Cache (array)
					$name = $namecache[$n["person"]];
				} else {
					$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') as vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') as nachname, AES_DECRYPT(titel, '$CMS_SCHLUESSEL') as titel FROM personen WHERE id = ?";
					$sql = $dbs->prepare($sql);
					$sql->bind_param("i", $n["person"]);
					$sql->bind_result($vorname, $nachname, $titel);
					$sql->execute();
					$sql->fetch();
					$name = cms_generiere_anzeigename($vorname, $nachname, $titel);
					$namecache[$n["person"]] = $name;
				}

				$tag = cms_tagnamekomplett(date("w", $n["datum"])) . ", den " . date("d", $n["datum"]) . " " . cms_monatsnamekomplett(date("n", $n["datum"]));

				if($letztesDatum == "blub") {	// Für die unterste Nachricht ausgeführt
					$letztesDatum = $tag;
					$_SESSION["LETZTENACHRICHT_$g"]["$gruppenid"] = $n["id"];			// Unterste Nachricht
				}

				// Nachricht gemeldet
				$gemeldet = false;
				if($n["meldestatus"]) {
					$sql = "SELECT 1 as r FROM $gk"."chatmeldungen WHERE nachricht = ? AND melder = ?";
					$sql = $dbs->prepare($sql);
					$sql->bind_param("ii", $n["id"], $CMS_BENUTZERID);
					$sql->bind_result($gemeldet);
					$sql->execute();
					$sql->fetch();
				}
				$code .= "<div class=\"cms_chat_nachricht_aussen".($n["person"]==$CMS_BENUTZERID?" cms_chat_nachricht_eigen":"").($gemeldet?" cms_chat_nachricht_gemeldet":"")."\">";
					$code .= "<div class=\"cms_chat_nachricht_innen\">";
						$code .= "<div class=\"cms_chat_nachricht_id\">".$n["id"]."</div>";
						$code .= "<div class=\"cms_chat_nachricht_aktion\" data-aktion=\"sendend\"><img src=\"res/laden/standard.gif\"></div>";
						$code .= "<div class=\"cms_chat_nachricht_aktion\" data-aktion=\"mehr\">&vellip;<span class=\"cms_hinweis\"><p data-mehr=\"melden\" onclick=\"cms_chat_nachricht_melden_anzeigen(this, '$g', '$gruppenid')\">Nachricht melden</p></span></div>";
						$code .= "<div class=\"cms_chat_nachricht_autor\">".$name."</div>";
						$code .= "<div class=\"cms_chat_nachricht_nachricht\">".$n["inhalt"]."</div>";
						$code .= "<div class=\"cms_chat_nachricht_zeit\">".date("H:i", $n["datum"])."</div>";
					$code .= "</div>";
				$code .= "</div>";

				if($letztesDatum != $tag) {	// Aktueles Datum > Datum vorheriger Nachricht -> neuer Tag
					$code .= "<div class=\"cms_chat_datum cms_notiz\">$letztesDatum</div>";
					$letztesDatum = $tag;
				}

				$ncode = $code . $ncode;
				$_SESSION["ERSTENACHRICHT_$g"]["$gruppenid"] = $n["id"];	// Oberste Nachricht
			}

		$code = $ccode;

		if(count($nachrichten))		// Oberstes Datum ausgeben
			$code .= "<div class=\"cms_chat_datum cms_notiz\">$tag</div>";

		$code .= $ncode;
		$code .= "</div>";

		if($rechte["chatten"] && $rechte["chattenab"] <= time()) {	// Schreibrecht
			$code .= "<div id=\"cms_chat_nachricht_verfassen\">";
				$code .= "<label for=\"cms_chat_neue_nachricht\"><p class=\"cms_notiz\">Nachricht verfassen:</p></label>";
				$code .= "<textarea data-gramm=\"false\" type=\"text\" id=\"cms_chat_neue_nachricht\" onkeypress=\"return cms_chat_enter(event, '$g', '$gruppenid');\"></textarea><div onclick=\"cms_chat_nachricht_senden('$g', '$gruppenid')\"><img src=\"res/icons/klein/senden.png\"></div>";
			$code .= "</div>";
		}
	$code .= "</div>";
	$code .= "<script>$(window).on(\"load\", function() {setInterval(function() {cms_chat_aktualisieren('$g', '$gruppenid')}, 333);});</script>";
	return $code;
}
?>
