<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Allgemeine Einstellungen</h1>

<?php
$zugriff = $CMS_RECHTE['Administration']['Allgemeine Einstellungen vornehmen'];
if ($zugriff) {
	$code = "";

	$personen = array("Lehrer", "Schüler", "Verwaltungsangestellte", "Eltern", "Externe");
	$gruppen = $CMS_GRUPPEN;
	$raenge = array("Vorsitzende", "Aufsicht", "Mitglieder");

	$einstellungen = cms_einstellungen_laden();

	$code .= "<ul class=\"cms_reitermenue\">";
		$code .= "<li><span id=\"cms_reiter_einstellungen_0\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 0, 5, true)\">Rechte</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_1\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 1, 5, true)\">Postfach</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_2\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 2, 5, true)\">Gruppen</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_3\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 3, 5, true)\">Stundenpläne</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_4\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 4, 5, true)\">Website</span></li> ";
		$code .= "<li><span id=\"cms_reiter_einstellungen_5\" class=\"cms_reiter\" onclick=\"cms_reiter('einstellungen', 5, 5, true)\">Geräteverwaltung</span></li> ";
	$code .= "</ul>";

	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_0\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Rechte</h2></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Persönliche Termine anlegen</h3>";
		$code .= "<table class=\"cms_formular\">";
		foreach ($personen as $p) {
			$code .= "<tr>";
			$code .= "<th>$p:</th>";
			$code .= "<td>".cms_schieber_generieren('persoenlichetermine_'.cms_textzudb($p),$einstellungen[$p.' dürfen persönliche Termine anlegen'])."</td>";
			$code .= "</tr>";
		}
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Persönliche Notizen anlegen</h3>";
		$code .= "<table class=\"cms_formular\">";
		foreach ($personen as $p) {
			$code .= "<tr>";
			$code .= "<th>$p:</th>";
			$code .= "<td>".cms_schieber_generieren('persoenlichenotiz_'.cms_textzudb($p),$einstellungen[$p.' dürfen persönliche Notizen anlegen'])."</td>";
			$code .= "</tr>";
		}
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_i cms_clear\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_rechte_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";
		$code .= "</div>";
	$code .= "</div>";


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_1\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Postfach</h2></div>";
		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$personenspalte1 = array("Lehrer", "Verwaltungsangestellte", "Externe");
		$personenspalte2 = array("Schüler", "Eltern");
		foreach ($personenspalte1 as $ps) {
			$code .= "<h3>$ps schreiben an ...</h3>";
			$code .= "<table class=\"cms_formular\">";
			foreach ($personen as $p) {
				$code .= "<tr>";
				$code .= "<th>$p:</th>";
				$code .= "<td>".cms_schieber_generieren('postfach_'.cms_textzudb($ps).'an'.cms_textzudb($p),$einstellungen['Postfach - '.$ps.' dürfen '.$p.' schreiben'])."</td>";
				$code .= "</tr>";
				$code .= "<tr>";
			}
			$code .= "</table>";
			$code .= "<h4>Außerdem gilt für $ps für Gruppen zusätzlich:</h4>";
			$code .= "<table class=\"cms_formular\">";
			$code .= "<tr>";
			$code .= "<th></th>";
			foreach ($raenge as $r) {
				$code .= "<th>$r</td>";
			}
			$code .= "</tr>";
			foreach ($gruppen as $g) {
				$gk = cms_textzudb($g);
				$code .= "<tr>";
				$code .= "<th>$g:</th>";
				foreach ($raenge as $r) {
					$code .= "<td>".cms_schieber_generieren('postfach_'.cms_textzudb($ps).'angruppen'.cms_textzudb($r).$gk,$einstellungen["Postfach - $ps dürfen $g $r schreiben"])."</td>";
				}
				$code .= "</tr>";
			}
			$code .= "</table>";
		}
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		foreach ($personenspalte2 as $ps) {
			$code .= "<h3>$ps schreiben an ...</h3>";
			$code .= "<table class=\"cms_formular\">";
			foreach ($personen as $p) {
				$code .= "<tr>";
				$code .= "<th>$p:</th>";
				$code .= "<td>".cms_schieber_generieren('postfach_'.cms_textzudb($ps).'an'.cms_textzudb($p),$einstellungen['Postfach - '.$ps.' dürfen '.$p.' schreiben'])."</td>";
				$code .= "</tr>";
				$code .= "<tr>";
			}
			$code .= "</table>";
			$code .= "<h4>Außerdem gilt für $ps für Gruppen zusätzlich:</h4>";
			$code .= "<table class=\"cms_formular\">";
			$code .= "<tr>";
			$code .= "<th></th>";
			foreach ($raenge as $r) {
				$code .= "<th>$r</td>";
			}
			$code .= "</tr>";
			foreach ($gruppen as $g) {
				$gk = cms_textzudb($g);
				$code .= "<tr>";
				$code .= "<th>$g:</th>";
				foreach ($raenge as $r) {
					$code .= "<td>".cms_schieber_generieren('postfach_'.cms_textzudb($ps).'angruppen'.cms_textzudb($r).$gk,$einstellungen["Postfach - $ps dürfen $g $r schreiben"])."</td>";
				}
				$code .= "</tr>";
			}
			$code .= "</table>";
		}
		$code .= "</div></div>";
		$code .= "<div class=\"cms_spalte_i cms_clear\">";
		$code .= "<p><span class=\"cms_button\" onclick=\"cms_einstellungen_postfach_aendern()\">Speichern</span> ";
		$code .= "<span class=\"cms_button_nein\" onclick=\"cms_link('Schulhof/Verwaltung/Allgemeine_Einstellungen');\">Abbrechen</span></p>";
		$code .= "</div>";
		$code .= "</div>";
	$code .= "</div>";


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_2\" style=\"display: none;\">";
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

		$code .= "<h3>Interne Termine</h3>";
		$code .= "<table class=\"cms_formular\">";
		foreach ($personen as $p) {
			$code .= "<tr>";
			$code .= "<th>$p dürfen Termine vorschlagen:</th>";
			$code .= "<td>".cms_schieber_generieren(cms_textzudb($p).'termineinternvorschlagen',$einstellungen[$p.' dürfen intern Termine vorschlagen'])."</td>";
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

		$code .= "<h3>Interne Blogeinträge</h3>";
		$code .= "<table class=\"cms_formular\">";
		foreach ($personen as $p) {
			$code .= "<tr>";
			$code .= "<th>$p dürfen Blogeinträge vorschlagen:</th>";
			$code .= "<td>".cms_schieber_generieren(cms_textzudb($p).'bloginternvorschlagen',$einstellungen[$p.' dürfen intern Blogeinträge vorschlagen'])."</td>";
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


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_3\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Stundenpläne</h2></div>";
		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Vertretungsplan</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Untis-Vertretungsplan verwenden:</th>";
		$code .= "<td>".cms_schieber_generieren('vertretungsplan_extern',$einstellungen['Vertretungsplan extern'], 'cms_vertretungsplan_einstellungen_anzeigen()')."</td>";
		$code .= "</tr>";
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


	$code .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_einstellungen_4\" style=\"display: none;\">";
		$code .= "<div class=\"cms_reitermenue_i\">";
		$code .= "<div class=\"cms_spalte_i\"><h2>Website</h2></div>";
		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Öffentliche Termine</h3>";
		$code .= "<table class=\"cms_formular\">";
		foreach ($personen as $p) {
			$code .= "<tr>";
			$code .= "<th>$p dürfen Termine vorschlagen:</th>";
			$code .= "<td>".cms_schieber_generieren(cms_textzudb($p).'terminevorschlagen',$einstellungen[$p.' dürfen Termine vorschlagen'])."</td>";
			$code .= "</tr>";
		}
		$code .= "</table>";

		$code .= "<h3>Öffentliche Galerien</h3>";
		$code .= "<table class=\"cms_formular\">";
		foreach ($personen as $p) {
			$code .= "<tr>";
			$code .= "<th>$p dürfen Galerien vorschlagen:</th>";
			$code .= "<td>".cms_schieber_generieren(cms_textzudb($p).'galerienvorschlagen',$einstellungen[$p.' dürfen Galerien vorschlagen'])."</td>";
			$code .= "</tr>";
		}
		$code .= "</table>";

		$code .= "<h3>Verhalten bei Menüseiten</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th><span class=\"cms_hinweis_aussen\">Menüseiten weiterleiten:<span class=\"cms_hinweis\">Menüseiten werden auf die am niedrigsten positionierte Seite weitergeleitet.</span></span></th>";
		$code .= "<td>".cms_schieber_generieren('menueseitenweiterleiten',$einstellungen['Menüseiten weiterleiten'])."</td>";
		$code .= "</tr>";
		$code .= "</table>";
		$code .= "</div></div>";

		$code .= "<div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
		$code .= "<h3>Öffentliche Blogeinträge</h3>";
		$code .= "<table class=\"cms_formular\">";
		foreach ($personen as $p) {
			$code .= "<tr>";
			$code .= "<th>$p dürfen Blogeinträge vorschlagen:</th>";
			$code .= "<td>".cms_schieber_generieren(cms_textzudb($p).'blogvorschlagen',$einstellungen[$p.' dürfen Blogeinträge vorschlagen'])."</td>";
			$code .= "</tr>";
		}
		$code .= "</table>";

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
		$code .= "<th>Fehlermeldungen ans Entwicklerteam weiterleiten:</th>";
		$code .= "<td>".cms_schieber_generieren('fehlermeldungengithub',$einstellungen['Fehlermeldung an GitHub'])."</td>";
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

	$kennung = "";
	$sql = "SELECT AES_DECRYPT(wert, '$CMS_SCHLUESSEL') AS wert FROM internedienste WHERE inhalt = AES_ENCRYPT('Gerätekennung', '$CMS_SCHLUESSEL')";
	if ($anfrage = $dbs->query($sql)) {
	  if ($daten = $anfrage->fetch_assoc()) {
	    $kennung = $daten['wert'];
	  }
	  $anfrage->free();
	}

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

		$code .= "<h3>Geräteverwaltung</h3>";
		$code .= "<table class=\"cms_formular\">";
		$code .= "<tr>";
		$code .= "<th>Kennung:</th>";
		$code .= "<td><input name=\"cms_schulhof_intern_geraetekennung\" id=\"cms_schulhof_intern_geraetekennung\" value=\"$kennung\"></td>";
		$code .= "<td><span class=\"cms_button\" onclick=\"cms_kennung_generieren('cms_schulhof_intern_geraetekennung')\">Generieren</span></td>";
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
