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
		echo "<p class=\"cms_notiz\">$vorname $nachname</p>";

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
				$alle = false;

				$cms_desnutzersrechte = array();
				$cms_derrollerechte = array();
				cms_rechte_laden_n($id, $cms_desnutzersrechte);
				cms_rechte_laden_r($id, $cms_derrollerechte);

				$recht_machen = function($pfad, $recht, $kinder = null, $unterstes = false) use (&$recht_machen, $cms_desnutzersrechte, $cms_derrollerechte) {
					$code = "";

					$knoten = $recht;

					// Alternativer Knotenname
					if(!is_null($kinder) && !is_array($kinder))
						$recht = $kinder;
					if(is_array($kinder) && isset($kinder["knotenname"])) {
						$recht = $kinder["knotenname"];
						unset($kinder["knotenname"]);
					}

					// Hat die Person das Recht?
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

					$personhatrecht = false;
					$rollehatrecht = false;

					if(substr("$pfad.$knoten", 2) !== false && ($pf = explode(".", substr("$pfad.$knoten", 2))) !== null) {
						$personhatrecht = $rechtecheck($cms_desnutzersrechte, substr("$pfad.$knoten", 2));
						$rollehatrecht = $rechtecheck($cms_derrollerechte, substr("$pfad.$knoten", 2));
					}
					$code .= "<div class=\"cms_recht".(is_array($kinder)?" cms_hat_kinder":"").($unterstes?" cms_recht_u":"").($personhatrecht&&!$rollehatrecht?" cms_recht_aktiv":"").($rollehatrecht?" cms_recht_rolle":"")."\" data-knoten=\"$knoten\"><i class=\"icon cms_recht_eingeklappt\"></i><span class=\"cms_recht_beschreibung\"><span class=\"cms_recht_beschreibung_i\">".mb_ucfirst($recht)."</span></span>";

					// Kinder ausgeben
					$c = 0;
					if(is_array($kinder)) {
						$code .= "<div class=\"cms_rechtekinder\"".($recht?"style=\"display: none;\"":"").">";
						foreach($kinder as $n => $i)
							$code .= "<div class=\"cms_rechtebox".(!is_null($i) && !is_array($i)?" cms_recht_wert":"").(++$c==count($kinder)?" cms_recht_u":"")."\">".$recht_machen("$pfad.$knoten", $n, $i, $c == count($kinder))."</div>";
						$code .= "</div>";
					}
					$code .= "</div>";
					return $code;
				};

				echo "<div id=\"cms_rechtepapa\" class=\"cms_spalte_i\">".$recht_machen("", "", $rechte, true)."</div>";

				echo "<div class=\"cms_spalte_2\">";
					echo "<span class=\"cms_button_ja\" onclick=\"cms_rechte_speichern()\">Speichern</span> <span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Personen')\">Abbrechen</span>";
					echo "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
				echo "</div>";
				echo "<div class=\"cms_spalte_2\">";
					echo "<h3>Legende</h3>";
					echo "<span class=\"cms_demorecht\">Unvergebenes Recht</span> ";
					echo "<span class=\"cms_demorecht cms_demorecht_aktiv\">Vergebenes Recht</span> ";
					echo "<span class=\"cms_demorecht cms_demorecht_rolle\">Recht einer zugeordneten Rolle</span> ";
				echo "</div>";
			echo "</div>";
		}
	}
	else {
		echo cms_meldung_berechtigung();
	}
}
?>
<div class="cms_clear"></div>
