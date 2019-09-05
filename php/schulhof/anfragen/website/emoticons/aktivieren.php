<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/emoticons.php");

session_start();

foreach($CMS_EMOTICONS as $e)
	postLesen($e["id"]."_aktiv");

// Variablen einlesen, falls übergeben
$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Website']['Emoticons verwalten'];

if (cms_angemeldet() && $zugriff) {
	$fehler = false;
	if (!$fehler) {
			$dbs = cms_verbinden('s');
			$sql = "SELECT * FROM emoticons WHERE id = ?";
			$sql = $dbs->prepare($sql);

			// Prüfen, on INSERT oder UPDATE
			$ins = array();
			foreach($CMS_EMOTICONS as $e) {
  			$sql->bind_param("s", $e["id"]);
				$sql->execute();
				$f = true;
				if($sql)
					if($sql->fetch())
						$f = false;
				$ins[$e["id"]] = $f;
			}
			$sql->close();

			$sql = "INSERT INTO `emoticons` (`id`, `aktiv`) VALUES (?, ?);";
			$sql = $dbs->prepare($sql);
			foreach($CMS_EMOTICONS as $e) {
				if(!$ins[$e["id"]])
					continue;
  			$sql->bind_param("ss", $e["id"], ${$e["id"]."_aktiv"});
				$sql->execute();
			}
			$sql->close();

			$sql = "UPDATE `emoticons` SET aktiv = ? WHERE id = ?;";
			$sql = $dbs->prepare($sql);
			foreach($CMS_EMOTICONS as $e) {
				if($ins[$e["id"]])
					continue;
  			$sql->bind_param("ss", ${$e["id"]."_aktiv"}, $e["id"]);
				$sql->execute();
			}

			echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
