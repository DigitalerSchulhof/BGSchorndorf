<?php

include_once(dirname(__FILE__)."/../../../../allgemein/funktionen/yaml.php");
use Async\YAML;

function cms_rolle_ausgeben ($rolle) {
	global $CMS_SCHLUESSEL;

	$dbs = cms_verbinden('s');
	$code = "";

	$bezeichnung = "";
	if ($rolle != "") {
		$sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM rollen WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("s", $rolle);
		if ($sql->execute()) {
			$sql->bind_result($bezeichnung);
			$sql->fetch();
			$sql->close();
		}
	}

	$code .= "<div class=\"cms_spalte_2\">";

	$code .= "<h3>Rollendetails</h3>";
	$code .= "<table class=\"cms_formular\">";
		$code .= "<tr><th>Bezeichnung:</th><td><input type=\"text\" name=\"cms_schulhof_rolle_bezeichnung\" id=\"cms_schulhof_rolle_bezeichnung\" value=\"".$bezeichnung."\"></td></tr>";
	$code .= "</table></div><div class=\"cms_spalte_2\">";

	$rechte = YAML::loader(dirname(__FILE__)."/../../../../allgemein/funktionen/rechte/rechte.yml");
	$alle = false;

	$cms_derrollerechte = array();
	if($rolle != "")
		cms_rechte_laden_sql("SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte WHERE rolle = ?", $cms_derrollerechte, "i", $rolle);
	
	// u: Unterstes
	// k: Hat Kinder	(Pfad nach rechts)
	// 	c: Eingeklappt (Grünes +)

	$recht_machen = function($pfad, $recht, $kinder = null, $unterstes = false) use (&$recht_machen, $cms_derrollerechte) {
		$code = "";
		$knoten = $recht;
		// Alternativer Knotenname
		if(!is_null($kinder) && !is_array($kinder))
			$recht = $kinder;
		if(is_array($kinder) && isset($kinder["knotenname"])) {
			$recht = $kinder["knotenname"];
			unset($kinder["knotenname"]);
		}

		// Hat die Rolle das Recht?
		$rechtecheck = function($r, $pf) {
			foreach(explode(".", $pf) as $p) {
				if($r === true)
					return true;
				else
					if(isset($r[$p])) {
						if(($r = $r[$p]) === true)
							return true;
					} else
						return false;
			}
		};
		$rollehatrecht = false;
		if(substr("$pfad.$knoten", 2) !== false && ($pf = explode(".", substr("$pfad.$knoten", 2))) !== null) {
			$rollehatrecht = $rechtecheck($cms_derrollerechte, substr("$pfad.$knoten", 2));
		}
		$code .= "<div class=\"cms_recht".(is_array($kinder)?" cms_hat_kinder":"").($unterstes?" cms_recht_unterstes":"").($rollehatrecht?" cms_recht_rolle":"")."\" data-knoten=\"$knoten\"><i class=\"icon cms_recht_eingeklappt\"></i><span class=\"cms_recht_beschreibung\"><span class=\"cms_recht_beschreibung_i\" onclick=\"cms_recht_vergeben_rolle(this)\">".mb_ucfirst($recht)."</span></span>";
		// Kinder ausgeben
		$c = 0;
		if(is_array($kinder)) {
			$code .= "<div class=\"cms_rechtekinder\"".($recht?"style=\"display: none;\"":"").">";
			foreach($kinder as $n => $i)
				$code .= "<div class=\"cms_rechtebox".(!is_null($i) && !is_array($i)?" cms_recht_wert":"").(++$c==count($kinder)?" cms_recht_unterstes":"")."\">".$recht_machen("$pfad.$knoten", $n, $i, $c == count($kinder))."</div>";
			$code .= "</div>";
		}
		$code .= "</div>";
		return $code;
	};

	$code .= "<div id=\"cms_rechtepapa\" class=\"cms_spalte_i\">".$recht_machen("", "", $rechte, true)."</div>";
		$code .= "<div class=\"cms_spalte_2\">";
			$code .= "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
			$code .= "<span class=\"cms_button\" onclick=\"cms_alle_rechte_ausklappen(this)\">Alle ausklappen</span>";
		$code .= "</div>";
		$code .= "<div class=\"cms_spalte_2\">";
			$code .= "<h3>Legende</h3>";
			$code .= "<span class=\"cms_demorecht\">Unvergebenes Recht</span> ";
			$code .= "<span class=\"cms_demorecht cms_demorecht_rolle\">Vergebenes Recht</span> ";
		$code .= "</div>";
	$code .= "</div>";
	return $code;
}
?>
