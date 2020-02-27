<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Allgemeine Einstellungen</h1>

<?php
include_once(dirname(__FILE__)."/../../../../allgemein/funktionen/yaml.php");
use Async\YAML;

if (cms_r("schulhof.verwaltung.einstellungen")) {
	$code = "";

	$personen = array("Lehrer", "Schüler", "Verwaltungsangestellte", "Eltern", "Externe");
	$gruppen = $CMS_GRUPPEN;
	$raenge = array("Vorsitzende", "Aufsicht", "Mitglieder");

	$einstellungen = cms_einstellungen_laden();

	$code .= "<ul class=\"cms_reitermenue\">";
		$code .= "<li><span id=\"cms_reiter_einstellungen_0\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 0, 5, true)\">Rechte</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_1\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 1, 5, true)\">Gruppen</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_2\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 2, 5, true)\">Stundenpläne</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_3\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 3, 5, true)\">Tagebücher</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_4\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 4, 5, true)\">Website</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_5\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 5, 5, true)\">Geräteverwaltung</span></li> ";
	$code .= "</ul>";

	$rechte = YAML::loader(dirname(__FILE__)."/../../../../allgemein/funktionen/rechte/rechte.yml");


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_0\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Rechte</h2>";

		$lehrerrechte = array();
		$schülerrechte = array();
		$verwaltungrechte = array();
		$elternrechte = array();
		$externerechte = array();
		cms_rechte_laden_sql("SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte WHERE rolle = 1", $lehrerrechte, '');
		cms_rechte_laden_sql("SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte WHERE rolle = 2", $schülerrechte, '');
		cms_rechte_laden_sql("SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte WHERE rolle = 3", $verwaltungrechte, '');
		cms_rechte_laden_sql("SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte WHERE rolle = 4", $elternrechte, '');
		cms_rechte_laden_sql("SELECT AES_DECRYPT(recht, '$CMS_SCHLUESSEL') FROM rollenrechte WHERE rolle = 5", $externerechte, '');

		$recht_machen = function($pfad, $recht, $rechte, $kinder = null, $unterstes = false) use (&$recht_machen) {
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
					else {
						if(isset($r[$p])) {
							if(($r = $r[$p]) === true)
								return true;
						} else {
							return false;
						}
					}
				}
			};
			$rollehatrecht = false;
			if(substr("$pfad.$knoten", 2) !== false && ($pf = explode(".", substr("$pfad.$knoten", 2))) !== null) {
				$rollehatrecht = $rechtecheck($rechte, substr("$pfad.$knoten", 2));
			}
			$code .= "<div class=\"cms_recht".(is_array($kinder)?" cms_hat_kinder":"").($unterstes?" cms_recht_unterstes":"").($rollehatrecht?" cms_recht_rolle":"")."\" data-knoten=\"$knoten\"><i class=\"".($pfad?"icon ":"")."cms_recht_eingeklappt\"></i><span class=\"cms_recht_beschreibung\"><span class=\"cms_recht_beschreibung_i\" onclick=\"cms_recht_vergeben_rolle(this)\">".mb_ucfirst($recht)."</span></span>";
			// Kinder ausgeben
			$c = 0;
			if(is_array($kinder)) {
				$code .= "<div class=\"cms_rechtekinder\"".($recht?"style=\"display: none;\"":"").">";
				foreach($kinder as $n => $i)
					$code .= "<div class=\"cms_rechtebox".(!is_null($i) && !is_array($i)?" cms_recht_wert":"").(++$c==count($kinder)?" cms_recht_unterstes":"")."\">".$recht_machen("$pfad.$knoten", $n, $rechte, $i, $c == count($kinder))."</div>";
				$code .= "</div>";
			}
			$code .= "</div>";
			return $code;
		};

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h3><img src=\"res/icons/gross/lehrer.png\"></h3>";
			$code .= "<div class=\"cms_rechtepapa\" id=\"cms_rechtepapa_lehrer\">".$recht_machen("", "", $lehrerrechte, $rechte, true)."</div>";
			$code .= "<div class=\"cms_spalte_2\">";
				$code .= "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
				$code .= "<span class=\"cms_button\" onclick=\"cms_alle_rechte_ausklappen(this, '#cms_rechtepapa_lehrer')\">Alle ausklappen</span>";
			$code .= "</div>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h3><img src=\"res/icons/gross/schueler.png\"></h3>";
			$code .= "<div class=\"cms_rechtepapa\" id=\"cms_rechtepapa_schueler\" class=\"cms_spalte_i\">".$recht_machen("", "", $schülerrechte, $rechte, true)."</div>";
			$code .= "<div class=\"cms_spalte_2\">";
				$code .= "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
				$code .= "<span class=\"cms_button\" onclick=\"cms_alle_rechte_ausklappen(this, '#cms_rechtepapa_schueler')\">Alle ausklappen</span>";
			$code .= "</div>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h3><img src=\"res/icons/gross/verwaltung.png\"></h3>";
			$code .= "<div class=\"cms_rechtepapa\" id=\"cms_rechtepapa_verwaltung\">".$recht_machen("", "", $verwaltungrechte, $rechte, true)."</div>";
			$code .= "<div class=\"cms_spalte_2\">";
				$code .= "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
				$code .= "<span class=\"cms_button\" onclick=\"cms_alle_rechte_ausklappen(this, '#cms_rechtepapa_verwaltung')\">Alle ausklappen</span>";
			$code .= "</div>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h3><img src=\"res/icons/gross/eltern.png\"></h3>";
			$code .= "<div class=\"cms_rechtepapa\" id=\"cms_rechtepapa_eltern\">".$recht_machen("", "", $elternrechte, $rechte, true)."</div>";
			$code .= "<div class=\"cms_spalte_2\">";
				$code .= "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
				$code .= "<span class=\"cms_button\" onclick=\"cms_alle_rechte_ausklappen(this, '#cms_rechtepapa_eltern')\">Alle ausklappen</span>";
			$code .= "</div>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h3><img src=\"res/icons/gross/externe.png\"></h3>";
			$code .= "<div class=\"cms_rechtepapa\" id=\"cms_rechtepapa_externe\">".$recht_machen("", "", $externerechte, $rechte, true)."</div>";
			$code .= "<div class=\"cms_spalte_2\">";
				$code .= "<p class=\"cms_notiz\">Das Vergeben eines Rechts vergibt alle untergeordneten Rechte.</p>";
				$code .= "<span class=\"cms_button\" onclick=\"cms_alle_rechte_ausklappen(this, '#cms_rechtepapa_externe')\">Alle ausklappen</span>";
			$code .= "</div>";
		$code .= "</div></div>";


		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
			$code .= "<h3>Legende</h3>";
			$code .= "<span class=\"cms_demorecht\">Nicht vergebenes Recht</span><br>";
			$code .= "<span class=\"cms_demorecht cms_demorecht_rolle\">Vergebenes Recht</span> ";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_clear\"></div>";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_rechte_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";
		$code .= "</div>";
	$code .= "</div>";


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_1\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Gruppen</h2></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Personen-Gruppen-Zuordnung für Mitglieder</h3>";
		$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th></th><th><img src=\"res/icons/klein/lehrer.png\"></th><th><img src=\"res/icons/klein/schueler.png\"></th><th><img src=\"res/icons/klein/verwaltung.png\"></th><th><img src=\"res/icons/klein/elter.png\"></th><th><img src=\"res/icons/klein/extern.png\"></th></tr>";
			foreach ($gruppen as $g) {
				$code .= "<tr>";
					$code .= "<th>$g</th>";
					$namek = cms_textzudb($g);
					foreach ($personen as $p) {
						$code .= "<td>".cms_schieber_generieren('gruppen_mitglieder_'.$namek.'_'.cms_textzudb($p), $einstellungen['Mitglieder '.$g.' '.$p])."</td>";
					}
				$code .= "</tr>";
			}
		$code .= "</table>";

		$code .= "<h3>Genehmigungsnotwendigkeit für Termine und Blogeinträge</h3>";
		$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th></th><th>Termine</th><th>Blogeinträge</th></tr>";
			foreach ($CMS_GRUPPEN as $g) {
				$code .= "<tr>";
					$code .= "<th>$g</th>";
					$namek = cms_textzudb($g);
					$code .= "<td>".cms_schieber_generieren('gruppen_genehmigung_'.$namek.'_termine', $einstellungen['Genehmigungen '.$g.' Termine'])."</td>";
					$code .= "<td>".cms_schieber_generieren('gruppen_genehmigung_'.$namek.'_blogeintraege', $einstellungen['Genehmigungen '.$g.' Blogeinträge'])."</td>";
				$code .= "</tr>";
			}
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Personen-Gruppen-Zuordnung für Aufsichten</h3>";
		$code .= "<table class=\"cms_formular\">";
			$code .= "<tr><th></th><th><img src=\"res/icons/klein/lehrer.png\"></th><th><img src=\"res/icons/klein/schueler.png\"></th><th><img src=\"res/icons/klein/verwaltung.png\"></th><th><img src=\"res/icons/klein/elter.png\"></th><th><img src=\"res/icons/klein/extern.png\"></th></tr>";
			foreach ($gruppen as $g) {
				$code .= "<tr>";
					$code .= "<th>$g</th>";
					$namek = cms_textzudb($g);
					foreach ($personen as $p) {
						$code .= "<td>".cms_schieber_generieren('gruppen_aufsicht_'.$namek.'_'.cms_textzudb($p), $einstellungen['Aufsicht '.$g.' '.$p])."</td>";
					}
				$code .= "</tr>";
			}
		$code .= "</table>";

		$code .= "<h3>Sichtbare Gruppen</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Download von Dateien gestatten:</th>";
		$code .= "<td>".cms_schieber_generieren('sichtbardownload',$einstellungen['Download aus sichtbaren Gruppen'])."</td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</table>";

		$code .= "<h3>Gruppenchat</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Nachrichten löschen nach (0 für nie)</th>";
		$code .= "<td><input type=\"number\" class=\"cms_klein\" min=\"0\" max=\"1000\" step=\"1\" name=\"cms_nachrichtloeschen\" id=\"cms_nachrichtloeschen\" value=\"".$einstellungen['Chat Nachrichten löschen nach']."\" onchange=\"cms_nur_ganzzahl('cms_nachrichtloeschen', 365)\"> Tagen</td>";
		$code .= "</tr>";
		$code .= "</table>";

		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_i cms_clear\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_gruppen_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";
		$code .= "</div>";
	$code .= "</div>";

	$kennungS = "";
	$kennungL = "";
	$sql = $dbs->prepare("SELECT AES_DECRYPT(inhalt, '$CMS_SCHLUESSEL') AS inhalt, AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('VPlanS', '$CMS_SCHLUESSEL') OR  inhalt = AES_ENCRYPT('VPlanL', '$CMS_SCHLUESSEL')");
	if ($sql->execute()) {
		$sql->bind_result($kinhalt, $kwert);
	  while ($sql->fetch()) {
	    if ($kinhalt == "VPlanS") {$kennungS = $kwert;}
		  if ($kinhalt == "VPlanL") {$kennungL = $kwert;}
	  }
	}
	$sql->close();

	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_2\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Stundenpläne</h2></div>";
		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Vertretungsplan</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Untis-Vertretungsplan verwenden:</th>";
		$code .= "<td>".cms_schieber_generieren('vertretungsplan_extern',$einstellungen['Vertretungsplan extern'], 'cms_vertretungsplan_einstellungen_anzeigen()')."</td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Persönlicher Vertretungsplan nach ...</th><td><select id=\"cms_vertretungsplan_persoenlich\" name=\"cms_vertretungsplan_persoenlich\">";
		if ($einstellungen['Persönlicher Vertretungsplan nach ...'] == 'Klassen') {$selected = "selected=\"selected\"";} else {$selected = "";}
		$code .= "<option value=\"Klassen\" $selected>Klassen</option>";
		if ($einstellungen['Persönlicher Vertretungsplan nach ...'] == 'Kursen') {$selected = "selected=\"selected\"";} else {$selected = "";}
		$code .= "<option value=\"Kursen\" $selected>Kursen</option>";
		$code .= "</select></td></tr>";
		if ($einstellungen['Vertretungsplan extern'] == 1) {$style = "display: table-row";}
		else {$style = "display: none";}
		$code .= "<tr id=\"cms_vertretungsplan_schueler_aktuell_F\" style=\"$style\">";
		$code .= "<th>Schüler aktuell:</th>";
		$code .= "<td>".cms_dateiwahl_knopf ('schulhof/stundenplaene', 'cms_vertretungsplan_schueler_aktuell', 's', 'Vertretungsplan', '-', 'download', $einstellungen['Vertretungsplan Schüler aktuell'])."</td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_vertretungsplan_schueler_folgetag_F\" style=\"$style\">";
		$code .= "<th>Schüler Folgetag:</th>";
		$code .= "<td>".cms_dateiwahl_knopf ('schulhof/stundenplaene', 'cms_vertretungsplan_schueler_folgetag', 's', 'Vertretungsplan', '-', 'download', $einstellungen['Vertretungsplan Schüler Folgetag'])."</td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_vertretungsplan_lehrer_aktuell_F\" style=\"$style\">";
		$code .= "<th>Lehrer Aktuell:</th>";
		$code .= "<td>".cms_dateiwahl_knopf ('schulhof/stundenplaene', 'cms_vertretungsplan_lehrer_aktuell', 's', 'Vertretungsplan', '-', 'download', $einstellungen['Vertretungsplan Lehrer aktuell'])."</td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_vertretungsplan_lehrer_folgetag_F\" style=\"$style\">";
		$code .= "<th>Lehrer Folgetag</th>";
		$code .= "<td>".cms_dateiwahl_knopf ('schulhof/stundenplaene', 'cms_vertretungsplan_lehrer_folgetag', 's', 'Vertretungsplan', '-', 'download', $einstellungen['Vertretungsplan Lehrer Folgetag'])."</td>";
		$code .= "</tr>";
		$code .= "</table>";

		$code .= "<h3>Kennungen für die internen Dienste</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Kennung Schüler:</th>";
		$code .= "<td><input name=\"cms_schulhof_intern_svplankennung\" id=\"cms_schulhof_intern_svplankennung\" value=\"$kennungS\"></td>";
		$code .= "<td><span class=\"cms_button\" onclick=\"cms_kennung_generieren('cms_schulhof_intern_svplankennung')\">Generieren</span></td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Kennung Lehrer:</th>";
		$code .= "<td><input name=\"cms_schulhof_intern_lvplankennung\" id=\"cms_schulhof_intern_lvplankennung\" value=\"$kennungL\"></td>";
		$code .= "<td><span class=\"cms_button\" onclick=\"cms_kennung_generieren('cms_schulhof_intern_lvplankennung')\">Generieren</span></td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Stundenpläne</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Untis-Lehrerstundenpläne:</th>";
		$code .= "<td>".cms_schieber_generieren('lehrerstundenplaene',$einstellungen['Stundenplan Lehrer extern'])."</td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Untis-Klassenstundenpläne:</th>";
		$code .= "<td>".cms_schieber_generieren('klassenstundenplaene',$einstellungen['Stundenplan Klassen extern'])."</td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Untis-Raumpläne:</th>";
		$code .= "<td>".cms_schieber_generieren('raumstundenplaene',$einstellungen['Stundenplan Raum extern'])."</td>";
		$code .= "</tr>";
		$code .= "</table>";

		$code .= "<h3>Buchungen</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Buchungsbeginn:</th>";
		$code .= "<td>".cms_uhrzeit_eingabe('cms_buchungsbeginn', $einstellungen['Stundenplan Buchungsbeginn Stunde'], $einstellungen['Stundenplan Buchungsbeginn Minute'])."</td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Buchungsende:</th>";
		$code .= "<td>".cms_uhrzeit_eingabe('cms_buchungsende', $einstellungen['Stundenplan Buchungsende Stunde'], $einstellungen['Stundenplan Buchungsende Minute'])."</td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</div></div>";
		$code .= "<div class=\"cms_spalte_i cms_clear\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_stundenplaene_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";
		$code .= "</div>";
	$code .= "</div>";


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_3\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Tagebücher</h2></div>";
		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";

		$fristen = "<option value=\"s\">In der Stunde</option>";
		$fristen .= "<option value=\"t\">Am selben Tag</option>";
		$fristen .= "<option value=\"1\">Am nächsten Tag</option>";
		$fristen .= "<option value=\"2\">2 Tage danach</option>";
		$fristen .= "<option value=\"3\">3 Tage danach</option>";
		$fristen .= "<option value=\"4\">4 Tage danach</option>";
		$fristen .= "<option value=\"5\">5 Tage danach</option>";
		$fristen .= "<option value=\"6\">6 Tage danach</option>";
		$fristen .= "<option value=\"7\">eine Woche danach</option>";
		$fristen .= "<option value=\"14\">zwei Wochen danach</option>";
		$fristen .= "<option value=\"-\">keine</option>";

		$code .= "<th>Frist für Abwesenheiten:</th>";
		$code .= "<td><select name=\"cms_schulhof_tagebuch_abwesend_frist\" id=\"cms_schulhof_tagebuch_abwesend_frist\">";
			$code .= str_replace("value=\"".$einstellungen["Tagebuch Frist Abwesenheit"]."\"", "value=\"".$einstellungen["Tagebuch Frist Abwesenheit"]."\" selected=\"selected\"", $fristen);
		$code .= "</select></td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Frist für inhaltliche Einträge:</th>";
		$code .= "<td><select name=\"cms_schulhof_tagebuch_inhalt_frist\" id=\"cms_schulhof_tagebuch_inhalt_frist\">";
			$code .= str_replace("value=\"".$einstellungen["Tagebuch Frist Inhalt"]."\"", "value=\"".$einstellungen["Tagebuch Frist Inhalt"]."\" selected=\"selected\"", $fristen);
		$code .= "</select></td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Frist für Lob und Tadel:</th>";
		$code .= "<td><select name=\"cms_schulhof_tagebuch_lobtadel_frist\" id=\"cms_schulhof_tagebuch_lobtadel_frist\">";
			$code .= str_replace("value=\"".$einstellungen["Tagebuch Frist Lob und Tadel"]."\"", "value=\"".$einstellungen["Tagebuch Frist Lob und Tadel"]."\" selected=\"selected\"", $fristen);
		$code .= "</select></td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Frist für Hausaufgaben:</th>";
		$code .= "<td><select name=\"cms_schulhof_tagebuch_hausaufgaben_frist\" id=\"cms_schulhof_tagebuch_hausaufgaben_frist\">";
			$code .= str_replace("value=\"".$einstellungen["Tagebuch Frist Hausaufgaben"]."\"", "value=\"".$einstellungen["Tagebuch Frist Hausaufgaben"]."\" selected=\"selected\"", $fristen);
		$code .= "</select></td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Frist für Entschuldigungen:</th>";
		$code .= "<td><select name=\"cms_schulhof_tagebuch_entschuldigungen_frist\" id=\"cms_schulhof_tagebuch_entschuldigungen_frist\">";
			$code .= str_replace("value=\"".$einstellungen["Tagebuch Frist Entschuldigungen"]."\"", "value=\"".$einstellungen["Tagebuch Frist Entschuldigungen"]."\" selected=\"selected\"", $fristen);
		$code .= "</select></td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Mindestabwesenheit für Entschuldigungspflicht:</th>";
		$code .= "<td><input class=\"cms_klein\" name=\"cms_schulhof_tagebuch_abwesenheitsminimum\" id=\"cms_schulhof_tagebuch_abwesenheitsminimum\" type=\"number\" value=\"".$einstellungen['Tagebuch Mindestabwesenheit']."\"> min</td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_i cms_clear\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_tagebuch_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";
		$code .= "</div>";
	$code .= "</div>";


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_4\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Website</h2></div>";
		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";

		$code .= "<h3>Verhalten bei Menüseiten</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th><span class=\"cms_hinweis_aussen\">Menüseiten weiterleiten:<span class=\"cms_hinweis\">Menüseiten werden auf die am niedrigsten positionierte Seite weitergeleitet.</span></span></th>";
		$code .= "<td>".cms_schieber_generieren('menueseitenweiterleiten',$einstellungen['Menüseiten weiterleiten'])."</td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";

		$code .= "<h3>Nutzerfeedback</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Fehlermeldungen sind aktiv:</th>";
		$code .= "<td>".cms_schieber_generieren('fehlermeldungenaktiv',$einstellungen['Fehlermeldung aktiv'])."</td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Fehlermeldungen benötigen Anmeldung:</th>";
		$code .= "<td>".cms_schieber_generieren('fehlermeldungenangemeldet',$einstellungen['Fehlermeldung Anmeldung notwendig'])."</td>";
		$code .= "</tr>";
		$code .= "<th>Fehlermeldungen ans Entwicklerteam weiterleiten:<br><p class=\"cms_notiz\" style=\"font-weight: normal;\">Diese Option dient ausschließlich zur Verbesserung des Digitalen Schulhofs und kann nicht deaktiviert werden.</p></th>";
		$code .= "<td>".cms_schieber_generieren('fehlermeldungengithub',$einstellungen['Fehlermeldung an GitHub'],'cms_schieber(\'fehlermeldungengithub\')')."</td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Feedback ist aktiv:</th>";
		$code .= "<td>".cms_schieber_generieren('feedbackaktiv',$einstellungen['Feedback aktiv'])."</td>";
		$code .= "</tr>";
		$code .= "<tr>";
		$code .= "<th>Feedback benötigt Anmeldung:</th>";
		$code .= "<td>".cms_schieber_generieren('feedbackangemeldet',$einstellungen['Feedback Anmeldung notwendig'])."</td>";
		$code .= "</tr>";
		$code .= "</table>";

		$code .= "</div></div>";
		$code .= "<div class=\"cms_spalte_i cms_clear\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_website_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";

		$code .= "</div>";
	$code .= "</div>";

	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_5\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Geräteverwaltung</h2></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Erster Ansprechpartner</h3>";
		if ($einstellungen['Externe Geräteverwaltung1 existiert'] == 1) {$style = "display: table-row;";}
		else {$style = "display: none;";}
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Existiert:</th>";
		$code .= "<td>".cms_schieber_generieren('externegeraete1_existiert',$einstellungen['Externe Geräteverwaltung1 existiert'], "cms_allgemeineeinstellungen_externegeraeteverwaltung_anzeigen('1')")."</td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung1_geschlechtF\" style=\"$style\">";
		$code .= "<th>Geschlecht:</th>";
		$code .= "<td><select name=\"cms_externegeraete1_geschlecht\" id=\"cms_externegeraete1_geschlecht\" value=\"".$einstellungen['Externe Geräteverwaltung1 Geschlecht']."\">";
		if ($einstellungen['Externe Geräteverwaltung1 Geschlecht'] == '-') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"-\" $wahl>-</option>";
		if ($einstellungen['Externe Geräteverwaltung1 Geschlecht'] == 'm') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"m\" $wahl>&#x2642;</option>";
		if ($einstellungen['Externe Geräteverwaltung1 Geschlecht'] == 'w') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"w\" $wahl>&#x2640;</option>";
		if ($einstellungen['Externe Geräteverwaltung1 Geschlecht'] == 'u') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"u\" $wahl>&#x26a5;</option>";
		$code .= "</td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung1_titelF\" style=\"$style\">";
		$code .= "<th>Titel:</th>";
		$code .= "<td colspan=\"2\"><input type=\"text\" name=\"cms_externegeraete1_titel\" id=\"cms_externegeraete1_titel\" value=\"".$einstellungen['Externe Geräteverwaltung1 Titel']."\"></td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung1_vornameF\" style=\"$style\">";
		$code .= "<th>Vorname:</th>";
		$code .= "<td colspan=\"2\"><input type=\"text\" name=\"cms_externegeraete1_vorname\" id=\"cms_externegeraete1_vorname\" value=\"".$einstellungen['Externe Geräteverwaltung1 Vorname']."\"></td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung1_nachnameF\" style=\"$style\">";
		$code .= "<th>Nachname:</th>";
		$code .= "<td colspan=\"2\"><input type=\"text\" name=\"cms_externegeraete1_nachname\" id=\"cms_externegeraete1_nachname\" value=\"".$einstellungen['Externe Geräteverwaltung1 Nachname']."\"></td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung1_mailF\" style=\"$style\">";
		$code .= "<th>eMailadresse:</th>";
		$code .= "<td><input type=\"text\" name=\"cms_schulhof_externegeraete1_mail\" id=\"cms_schulhof_externegeraete1_mail\" value=\"".$einstellungen['Externe Geräteverwaltung1 Mail']."\" onkeyup=\"cms_check_mail_wechsel('externegeraete1_mail');\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_externegeraete1_mail_icon\"><img src=\"res/icons/klein/richtig.png\"></span></td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Zweiter Ansprechpartner</h3>";
		if ($einstellungen['Externe Geräteverwaltung2 existiert'] == 1) {$style = "display: table-row;";}
		else {$style = "display: none;";}
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Existiert:</th>";
		$code .= "<td>".cms_schieber_generieren('externegeraete2_existiert',$einstellungen['Externe Geräteverwaltung2 existiert'], "cms_allgemeineeinstellungen_externegeraeteverwaltung_anzeigen('2')")."</td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung2_geschlechtF\" style=\"$style\">";
		$code .= "<th>Geschlecht:</th>";
		$code .= "<td><select name=\"cms_externegeraete2_geschlecht\" id=\"cms_externegeraete2_geschlecht\" value=\"".$einstellungen['Externe Geräteverwaltung2 Geschlecht']."\">";
		if ($einstellungen['Externe Geräteverwaltung2 Geschlecht'] == '-') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"-\" $wahl>-</option>";
		if ($einstellungen['Externe Geräteverwaltung2 Geschlecht'] == 'm') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"m\" $wahl>&#x2642;</option>";
		if ($einstellungen['Externe Geräteverwaltung2 Geschlecht'] == 'w') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"w\" $wahl>&#x2640;</option>";
		if ($einstellungen['Externe Geräteverwaltung2 Geschlecht'] == 'u') {$wahl = "selected=\"selected\"";} else {$wahl = "";}
		$code .= "<option value=\"u\" $wahl>&#x26a5;</option>";
		$code .= "</td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung2_titelF\" style=\"$style\">";
		$code .= "<th>Titel:</th>";
		$code .= "<td colspan=\"2\"><input type=\"text\" name=\"cms_externegeraete2_titel\" id=\"cms_externegeraete2_titel\" value=\"".$einstellungen['Externe Geräteverwaltung2 Titel']."\"></td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung2_vornameF\" style=\"$style\">";
		$code .= "<th>Vorname:</th>";
		$code .= "<td colspan=\"2\"><input type=\"text\" name=\"cms_externegeraete2_vorname\" id=\"cms_externegeraete2_vorname\" value=\"".$einstellungen['Externe Geräteverwaltung2 Vorname']."\"></td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung2_nachnameF\" style=\"$style\">";
		$code .= "<th>Nachname:</th>";
		$code .= "<td colspan=\"2\"><input type=\"text\" name=\"cms_externegeraete2_nachname\" id=\"cms_externegeraete2_nachname\" value=\"".$einstellungen['Externe Geräteverwaltung2 Nachname']."\"></td>";
		$code .= "</tr>";
		$code .= "<tr id=\"cms_allgemeineeinstellungen_externegeraeteverwaltung2_mailF\" style=\"$style\">";
		$code .= "<th>eMailadresse:</th>";
		$code .= "<td><input type=\"text\" name=\"cms_schulhof_externegeraete2_mail\" id=\"cms_schulhof_externegeraete2_mail\" value=\"".$einstellungen['Externe Geräteverwaltung2 Mail']."\" onkeyup=\"cms_check_mail_wechsel('externegeraete2_mail');\"></td><td><span class=\"cms_eingabe_icon\" id=\"cms_schulhof_externegeraete2_mail_icon\"><img src=\"res/icons/klein/richtig.png\"></span></td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</div></div>";
		$code .= "<div class=\"cms_spalte_i cms_clear\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_geraeteverwaltung_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";
		$code .= "</div>";
		$code .= "</div>";
	$code .= "</div>";

	$code .= "</div>";

	$code .= "<script>cms_reiter_laden(\"einstellungen\");</script>";

	echo $code;
}
else {
	cms_meldung_berechtigung();
	echo "</div>";
}
?>
