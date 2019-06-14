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
?>
