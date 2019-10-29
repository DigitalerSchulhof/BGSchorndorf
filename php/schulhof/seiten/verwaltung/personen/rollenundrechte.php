<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
if (!function_exists('mb_ucfirst')) {
	function mb_ucfirst($str, $encoding = "UTF-8", $lower_str_end = false) {
		$first_letter = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding);
		$str_end = "";
		if ($lower_str_end) {
			$str_end = mb_strtolower(mb_substr($str, 1, mb_strlen($str, $encoding), $encoding), $encoding);
		}
		else {
			$str_end = mb_substr($str, 1, mb_strlen($str, $encoding), $encoding);
		}
		$str = $first_letter . $str_end;
		return $str;
	}
}


include_once(dirname(__FILE__)."/../../../../allgemein/funktionen/yaml.php");
use Async\YAML;

// PROFILDATEN LADEN
if (!isset($_SESSION['PERSONENDETAILS'])) {
	echo cms_meldung_bastler();
}
else {
	$id = $_SESSION['PERSONENDETAILS'];
	if (r("schulhof.verwaltung.rechte.zuordnen || schulhof.verwaltung.rechte.rollen.zuordnen")) {
		echo "<h1>Rollen und Rechte vergeben</h1>";

		// Person laden, für die die Rechte geändert werden sollen
		$dbs = cms_verbinden('s');
		$sql = "SELECT AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(art, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("i", $id);
		$sql->bind_result($vorname, $nachname, $personart);
		if(!$sql->execute() || !$sql->fetch())
			echo cms_meldung_unbekannt();

		if(r("schulhof.verwaltung.rechte.rollen.zuordnen")) {
			echo "<div class=\"cms_spalte_2\">";
				echo "<h3>Verfügbare Rollen</h3>";

				$rollencode = "";
				$sql = "SELECT * FROM (SELECT person, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') as bezeichnung, rollen.id FROM rollen LEFT JOIN (SELECT person, rolle FROM rollenzuordnung WHERE person = ?) AS rollenzuordnung ON rollen.id = rollenzuordnung.rolle WHERE personenart = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')) AS rollen ORDER BY bezeichnung ASC";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("is", $id, $personart);
				$sql->bind_result($person, $bez, $rid);
				$sql->execute();
				while ($sql->fetch()) {
					if ($person == $id)
						$rollencode .= "<span class=\"cms_toggle cms_toggle_aktiv\" onclick=\"cms_schulhof_verwaltung_personen_rolle_vergeben(0, $rid)\">$bez</span> ";
					else
						$rollencode .= "<span class=\"cms_toggle\" onclick=\"cms_schulhof_verwaltung_personen_rolle_vergeben(1, $rid)\">$bez</span> ";
				}
				$sql->close();
				if ($rollencode == "")
					$rollencode = "<p class=\"cms_notiz\">Keine Rollen verfügbar</p>";
				echo $rollencode;
			echo "</div>";
		}
		if(r("schulhof.verwaltung.rechte.zuordnen")) {
			echo "<div class=\"cms_spalte_2\">";
				echo "<h3>Verfügbare Rechte</h3>";
				$rechte = YAML::loader(dirname(__FILE__)."/../../../../allgemein/funktionen/rechte/rechte.yml");
				if($cms_nutzerrechte === true)
					$alle = true;
				else
					$rechte = array_replace_recursive($rechte, $cms_nutzerrechte);


				$recht_machen = function($recht, $kinder = null, $unterstes = false) use (&$recht_machen) {
					$code = "";

					$knoten = $recht;

					// Alternativer Knotenname
					if(!is_null($kinder) && !is_array($kinder))
						$recht = $kinder;
					if(is_array($kinder) && isset($kinder["knotenname"])) {
						$recht = $kinder["knotenname"];
						unset($kinder["knotenname"]);
					}

					$code .= "<div class=\"cms_recht".(is_array($kinder)?" cms_hat_kinder":"").($unterstes?" cms_recht_u":"")."\" data-knoten=\"$knoten\"><i class=\"icon cms_recht_eingeklappt\"></i><span class=\"cms_recht_beschreibung\">".mb_ucfirst($recht)."</span>";

					// Kinder ausgeben
					$c = 0;
					if(is_array($kinder)) {
						$code .= "<div class=\"cms_rechtekinder\"".($recht?"style=\"display: none;\"":"").">";
						foreach($kinder as $n => $i)
							$code .= "<div class=\"cms_rechtebox".(!is_null($i) && !is_array($i)?" cms_recht_wert":"").(++$c==count($kinder)?" cms_recht_u":"")."\">".$recht_machen($n, $i, $c == count($kinder))."</div>";
						$code .= "</div>";
					}
					$code .= "</div>";
					return $code;
				};

				echo "<div id=\"cms_rechtepapa\">".$recht_machen("", $rechte, true)."</div>";
				echo "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
			echo "</div>";
		}
	}
	else {
		echo cms_meldung_berechtigung();
	}
}
?>
<div class="cms_clear"></div>
