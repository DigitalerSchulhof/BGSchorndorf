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
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
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
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
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
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
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
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
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
		if ($anfrage = $dbs->query($sql)) {	// Safe weil keine Eingabe
			while ($daten = $anfrage->fetch_assoc()) {
				$code .= cms_beschluss_ausgeben($daten, true, $CMS_URLGANZ);
			}
			$anfrage->free();
		}
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

	$code = "";
	$code .= "<div id=\"cms_chat\">";
		$code .= "<div id=\"cms_chat_nachrichten\">";
			/*
				Löschstatus:
				0: Nichts
				1: Gemeldet & Gelöscht
				2: Direkt gelöscht
			*/

			// Nachrichten laden
			$sql = "SELECT chat.id, chat.person, chat.datum, AES_DECRYPT(chat.inhalt, '$CMS_SCHLUESSEL') as inhalt, chat.meldestatus, chat.loeschstatus, AES_DECRYPT(sender.vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(sender.titel, '$CMS_SCHLUESSEL') FROM $gk"."chat as chat JOIN personen as sender ON sender.id = chat.person WHERE chat.gruppe = $gruppenid AND chat.meldestatus = 0 ORDER BY chat.id DESC LIMIT ".($limit+1);
			$sql = $dbs->prepare($sql);
			$sql->bind_result($id, $p, $d, $i, $m, $gl, $v, $n, $t);
			$sql->execute();
			while($sql->fetch())
				array_push($nachrichten, array("id" => $id, "person" => $p, "datum" => $d, "inhalt" => $i, "meldestatus" => $m, "name" => cms_generiere_anzeigename($v, $n, $t), "geloescht" => $gl));

			if(!count($nachrichten))
				$code .= "<div id=\"cms_chat_leer\" class=\"cms_notiz\">Keine Nachrichten vorhanden.</div>";
			else if(count($nachrichten) > $limit)
				$code .= "<div id=\"cms_chat_nachrichten_nachladen\" class=\"cms_notiz\" onclick=\"cms_chat_nachrichten_nachladen('$g', '$gruppenid', $limit);\">Ältere Nachrichten laden</div>";

			$_SESSION["LETZTENACHRICHT_$g"]["$gruppenid"] = -1;	// Fürs Aktualisieren genutzt
			$letztesDatum = "blub";	// Dummy
			$tag = "blub";					// Dummy
			$ccode = $code;	// Rest-Code
			$ncode = "";	// Nachrichten Code

			echo "<script>var chat_rechte = [";
			if($rechte["nachrichtloeschen"])
			echo "\"nachrichtloeschen\",";
			if($rechte["nutzerstummschalten"])
			echo "\"nutzerstummschalten\",";
			echo "];</script>";

			foreach($nachrichten as $i => $n) {		// Nachrichten von unten nach oben (Neuste zu Älteste)
				if($i >= $limit)	//	Limit an zu ladenden Nachrichten überschritten. Es wird Eine zu viel in der SQL geladen, um zu prüfen, ob noch Nachrichten nachzuladen sind (Anzahl an Nachrchten > $limit)
					break;
				$code = "";	// Code der aktuellen Nachricht

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

				$eigen = $n["person"]==$CMS_BENUTZERID;

				$extraklassen = "";
				if($gemeldet)
					$extraklassen .= " cms_chat_nachricht_gemeldet";
				if($eigen)
					$extraklassen .= " cms_chat_nachricht_eigen";
				if($n["geloescht"])
					$extraklassen .= " cms_chat_nachricht_geloescht";

				if($n["geloescht"])
					$n["inhalt"] = "<img src=\"res/icons/klein/geloescht.png\" height=\"10\"> Vom Administrator gelöscht";

				$code .= "<div class=\"cms_chat_nachricht_aussen$extraklassen\">";
					$code .= "<div class=\"cms_chat_nachricht_innen\">";
						$code .= "<div class=\"cms_chat_nachricht_id\">".$n["id"]."</div>";
						$code .= "<div class=\"cms_chat_nachricht_aktion\" data-aktion=\"sendend\"><img src=\"res/laden/standard.gif\"></div>";

						$aktionen = "";

						if(!$eigen) {
							$aktionen .= "<p data-mehr=\"melden\" onclick=\"cms_chat_nachricht_melden_anzeigen(this, '$g', '$gruppenid')\">Nachricht melden</p>";
							if($rechte["nachrichtloeschen"])
								$aktionen .= "<p data-mehr=\"loeschen\" onclick=\"cms_chat_nachricht_loeschen_anzeigen(this, '$g', '$gruppenid')\">Nachricht löschen</p>";
							if($rechte["nutzerstummschalten"])
								$aktionen .= "<p data-mehr=\"bannen\" onclick=\"cms_chat_nutzer_stummschalten_anzeigen(this, '$g', '$gruppenid')\">Sender stummschalten</p>";
						}
						if($n["geloescht"])
							$aktionen = "";

						if(strlen($aktionen) > 0)
							$code .= "<div class=\"cms_chat_nachricht_aktion\" data-aktion=\"mehr\">&vellip;<span class=\"cms_chat_aktion\">$aktionen</span></div>";
						$code .= "<div class=\"cms_chat_nachricht_autor\">".$n["name"]."</div>";
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

		if($rechte["chatten"]) {	// Schreibrecht
			$code .= "<div id=\"cms_chat_nachricht_verfassen\" class=\"".($gebannt?"cms_chat_gebannt":"")."\">";
				$code .= "<label for=\"cms_chat_neue_nachricht\"><p class=\"cms_notiz\">Nachricht verfassen:</p></label>";
				$code .= "<textarea data-gramm=\"false\" type=\"text\" id=\"cms_chat_neue_nachricht\" onkeypress=\"return cms_chat_enter(event, '$g', '$gruppenid');\"></textarea><div onclick=\"cms_chat_nachricht_senden('$g', '$gruppenid')\"><img src=\"res/icons/klein/senden.png\"></div>";
				if($CMS_BENUTZERART == 's')
				$code .= cms_meldung("fehler", "<h4>Du wurdest stummgeschalten</h4><p>Dir wurde vorläufig das Recht des Schreibens genommen!</p>");
				else
				$code .= cms_meldung("fehler", "<h4>Sie wurden stummgeschalten</h4><p>Ihnen wurde vorläufig das Recht des Schreibens genommen!</p>");
			$code .= "</div>";
		}
	$code .= "</div>";
	$code .= "<script>$(window).on(\"load\", function() {cms_chat_aktualisieren('$g', '$gruppenid')});</script>";
	return $code;
}
?>
