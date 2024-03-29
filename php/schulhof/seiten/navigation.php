<?php
function cms_schulhofnavigation () {
	$dbs = cms_verbinden('s');

	global $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERART;
	$nutzerkonto = cms_schulhofnavigation_nutzerkonto($dbs);
	if (($CMS_BENUTZERART == 'v') || ($CMS_BENUTZERART == 'l')) {$informationen = cms_schulhofnavigation_informationen($dbs);}
	$gruppen = cms_schulhofnavigation_gruppen($dbs);
	if (($CMS_BENUTZERART == 'v') || ($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'x')) {$plaene = cms_schulhofnavigation_plaene($dbs);}
	$verwaltung = cms_schulhofnavigation_verwaltung($dbs);

	$mobil = "<span id=\"cms_mobilnavigation\" onclick=\"cms_einblenden('cms_mobilmenue_a')\"><span class=\"cms_menuicon\"></span><span class=\"cms_menuicon\"></span><span class=\"cms_menuicon\"></span></span>";
	$mobil .= "<div id=\"cms_mobilmenue_a\" style=\"display: none;\">";
	$mobil .= "<div id=\"cms_mobilmenue_i\">";
	$mobil .= "<p class=\"cms_mobilmenue_knoepfe\"><a class=\"cms_button\" href=\"\">Website</a> <a class=\"cms_button\" href=\"Schulhof/Nutzerkonto\">Schulhof</a></p>";
	$mobil .= "<p class=\"cms_mobilmenue_knoepfe\"><a class=\"cms_button_ja\" href=\"Schulhof/Nutzerkonto\">Nutzerkonto</a> <span class=\"cms_button_nein\" onclick=\"cms_ausblenden('cms_mobilmenue_a')\">Schließen</span></p>";
	$mobil .= "<h3>Aktivität</h3><p>Willkommen $CMS_BENUTZERVORNAME $CMS_BENUTZERNACHNAME!</p>";
	$mobil .= "<div id=\"cms_maktivitaet_out\"><div id=\"cms_maktivitaet_in\"></div></div>";
	$mobil .= "<p class=\"cms_notiz\" id=\"cms_maktivitaet_text\">Berechnung läuft ...</p>";
	$mobil .= "<p><span class=\"cms_button_ja\" onclick=\"cms_timeout_verlaengern()\">Verlängern</span> <span class=\"cms_button_nein\" onclick=\"cms_abmelden_frage();\">Abmelden</span></p>";
	$mobil .= "<div id=\"cms_mobilmenue_seiten\">";
	$mobil .= $nutzerkonto['mobil'];
	if (($CMS_BENUTZERART == 'v') || ($CMS_BENUTZERART == 'l')) {$mobil .= $informationen['mobil'];}
	$mobil .= $gruppen['mobil'];
	if (($CMS_BENUTZERART == 'v') || ($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'x')) {$mobil .= $plaene['mobil'];}
	$mobil .= $verwaltung['mobil'];
	$mobil .= "</div>";
	$mobil .= "</div>";
	$mobil .= "</div>";

	$pc = "<ul class=\"cms_hauptnavigation\" id=\"cms_hauptnavigation\">";
	$pc .= $nutzerkonto['pc'];
	if (($CMS_BENUTZERART == 'v') || ($CMS_BENUTZERART == 'l')) {$pc .= $informationen['pc'];}
	$pc .= $gruppen['pc'];
	if (($CMS_BENUTZERART == 'v') || ($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'x')) {$pc .= $plaene['pc'];}
	$pc .= $verwaltung['pc'];
	$pc .= "</ul>";

	cms_trennen($dbs);
	return $mobil.$pc;
}

function cms_schulhofnavigation_nutzerkonto($dbs) {
	global $CMS_BENUTZERVORNAME, $CMS_BENUTZERNACHNAME, $CMS_BENUTZERID, $CMS_BENUTZERART, $CMS_SCHLUESSEL, $CMS_GRUPPEN, $CMS_BENUTZERSCHULJAHR;
	$sql = "SELECT AES_DECRYPT(gelesen, '$CMS_SCHLUESSEL') AS gelesen, COUNT(gelesen) AS anzahl FROM posteingang_$CMS_BENUTZERID WHERE AES_DECRYPT(papierkorb, '$CMS_SCHLUESSEL') = '-' GROUP BY gelesen;";
	$anzahl['-'] = 0;
	$anzahl[1] = 0;
	$dbp = cms_verbinden('p');
	$sql = $dbp->prepare($sql);
	if ($sql->execute()) {
		$sql->bind_result($gelesen, $anzgelesen);
		while ($sql->fetch()) {$anzahl[$gelesen] = $anzgelesen;}
	}
	$sql->close();
	cms_trennen($dbp);
	$gesamt = $anzahl['-'] + $anzahl[1];
	$meldezahl = "";
	if ($anzahl['-'] > 0) {
		$meldezahl = "<span class=\"cms_meldezahl cms_meldezahl_wichtig\"><b>".$anzahl['-']."</b> / $gesamt</span>";
	}
	else if ($gesamt > 0) {
		$meldezahl = "<span class=\"cms_meldezahl\">$gesamt</span>";
	}

	// Gruppen anzeigen
	$meinegruppenpc = "";
	include_once('php/schulhof/seiten/gruppen/mitgliedschaftenausgeben.php');
	foreach ($CMS_GRUPPEN as $g) {
		$gruppencode = cms_gruppen_mitgliedschaften_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR);
		if (strlen($gruppencode) > 0) {
			$gruppencode = str_replace('<ul>', '', $gruppencode);
			$gruppencode = str_replace('</ul>', '', $gruppencode);
			$meinegruppenpc .= $gruppencode;
		}
	}
	$meinegruppenmobil = str_replace('class="cms_button"', '', $meinegruppenpc);

	$aufgabenpc = "";
	$aufgabenmobil = "";
	$sonderrollen = cms_sonderrollen_generieren();
	if (strlen($sonderrollen) != 0) {
		$aufgabenpc .= "<h3>Aufgaben</h3><ul>".$sonderrollen."</ul>";
		$aufgabenmobil .= "<li><a href=\"Schulhof/Aufgaben\">Aufgaben</a><span id=\"cms_mobilmenue_knopf_n_aufgaben\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('n_aufgaben')\">&#8628;</span>";
			$aufgabenmobil .= "<div id=\"cms_mobilmenue_seite_n_aufgaben\" style=\"display:none;\"><ul>";
			$aufgabenmobil .= str_replace('class="cms_button"', '', $sonderrollen);
			$aufgabenmobil .= "</ul></div>";
		$aufgabenmobil .= "</li>";
	}

	$code['mobil'] = "<h3>Nutzerkonto</h3>";
	$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_n\">";
		$code['mobil'] .= "<ul>";
			$code['mobil'] .= "<li><a href=\"Schulhof/Nutzerkonto\">Übersicht</a><span id=\"cms_mobilmenue_knopf_n_uebersicht\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('n_uebersicht')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_n_uebersicht\" style=\"display:none;\">";
					$code['mobil'] .= "<ul>";
						$code['mobil'] .= "<li><a href=\"Schulhof/Nutzerkonto\">Nutzerkonto</a></li> ";
						$code['mobil'] .= "<li><a href=\"Schulhof/Nutzerkonto/Mein_Profil\">Profildaten</a></li> ";
						if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 's')) {$code['mobil'] .= "<li><a href=\"javascript:cms_stundenplan_vorbereiten('m', '$CMS_BENUTZERID', '-')\">Stundenplan</a></li> ";}
						$code['mobil'] .= "<li><a href=\"Schulhof/Termine\">Kalender</a></li> ";
						$code['mobil'] .= "<li><a href=\"Schulhof/Blog\">Blog</a></li> ";
						$code['mobil'] .= "<li><a href=\"Schulhof/Nutzerkonto/Postfach\">Postfach $meldezahl</a></li> ";
						$code['mobil'] .= "<li><a href=\"Schulhof/Nutzerkonto/Favoriten\">Favoriten</a></li> ";
						$code['mobil'] .= "<li><a href=\"Schulhof/Nutzerkonto/Einstellungen\">Einstellungen</a></li>";
					$code['mobil'] .= "</ul>";
				$code['mobil'] .= "</div>";
			$code['mobil'] .= "</li>";
			if ((($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 'v')) && (strlen($meinegruppenmobil) > 0)) {
				$code['mobil'] .= "<li><a href=\"Schulhof/Gruppen\">Gruppen</a><span id=\"cms_mobilmenue_knopf_n_gruppen\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('n_gruppen')\">&#8628;</span>";
					$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_n_gruppen\" style=\"display:none;\">";

						$code['mobil'] .= "<ul>";
							$code['mobil'] .= $meinegruppenmobil;
						$code['mobil'] .= "</ul>";
					$code['mobil'] .= "</div>";
				$code['mobil'] .= "</li>";
			}
			$code['mobil'] .= $aufgabenmobil;
		$code['mobil'] .= "</ul>";
	$code['mobil'] .= "</div>";


	$code['pc'] = "<li><span class=\"cms_kategorie1\" onclick=\"cms_hauptnavigation_einblenden('nutzer')\">Nutzerkonto</span>";
		$code['pc'] .= "<div class=\"cms_unternavigation_o\" id=\"cms_hauptnavigation_nutzer_o\">";
		$code['pc'] .= "<div class=\"cms_unternavigation_m\">";
			$code['pc'] .= "<div class=\"cms_unternavigation_i\">";
			$code['pc'] .= "<span class=\"cms_unternavigation_schliessen cms_button_nein\" id=\"cms_hauptnavigation_nutzer_l\" onclick=\"cms_hauptnavigation_ausblenden('nutzer')\">&times;</span>";
			$code['pc'] .= "<div class=\"cms_spalte_4\">";
				$code['pc'] .= "<div class=\"cms_spalte_i\">";
					$code['pc'] .= "<h3>Mein Konto</h3>";
					$code['pc'] .= "<p>Willkommen $CMS_BENUTZERVORNAME $CMS_BENUTZERNACHNAME!</p>";
					$code['pc'] .= "<div id=\"cms_aktivitaet_out\"><div id=\"cms_aktivitaet_in\"></div></div>";
					$code['pc'] .= "<p class=\"cms_notiz\" id=\"cms_aktivitaet_text\">Berechnung läuft ...</p>";
					$code['pc'] .= "<ul>";
						$code['pc'] .= "<li><span class=\"cms_button_ja\" onclick=\"cms_timeout_verlaengern()\">Verlängern</span></li> ";
						$code['pc'] .= "<li><span class=\"cms_button_nein\" onclick=\"cms_abmelden_frage();\">Abmelden</span></li>";
					$code['pc'] .= "</ul>";
					$code['pc'] .= "<ul>";
						$code['pc'] .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto\">Nutzerkonto</a></li> ";
						$code['pc'] .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Mein_Profil\">Profildaten</a></li> ";
						$code['pc'] .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Postfach\">Postfach $meldezahl</a></li> ";
						$code['pc'] .= "<li><a class=\"cms_button\" href=\"Schulhof/Termine\">Kalender</a></li> ";
						$code['pc'] .= "<li><a class=\"cms_button\" href=\"Schulhof/Blog\">Blog</a></li> ";
						if (($CMS_BENUTZERART == 'l') || ($CMS_BENUTZERART == 's')) {$code['pc'] .= "<li><a class=\"cms_button\" href=\"javascript:cms_stundenplan_vorbereiten('m', '$CMS_BENUTZERID', '-')\">Stundenplan</a></li> ";}
						$code['pc'] .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Favoriten\">Favoriten</a></li> ";
						$code['pc'] .= "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto/Einstellungen\">Einstellungen</a></li> ";
					$code['pc'] .= "</ul>";
				$code['pc'] .= "</div>";
			$code['pc'] .= "</div>";
			$code['pc'] .= "<div class=\"cms_spalte_34\">";
				$code['pc'] .= "<div class=\"cms_spalte_i\">";
					$code['pc'] .= "<h3>Gruppen</h3>";
					$code['pc'] .= "<ul>";
						$code['pc'] .= $meinegruppenpc;
					$code['pc'] .= "</ul>";
					$code['pc'] .= $aufgabenpc;
				$code['pc'] .= "</div>";
			$code['pc'] .= "</div>";
			$code['pc'] .= "<div class=\"cms_clear\"></div>";
			$code['pc'] .= "</div>";
		$code['pc'] .= "</div>";
		$code['pc'] .= "</div>";
	$code['pc'] .= "</li>";
	return $code;
}

function cms_schulhofnavigation_informationen($dbs) {
	global $CMS_BENUTZERART;

	include_once('php/schulhof/seiten/verwaltung/dauerbrenner/linksausgeben.php');
	$dauerbrenner = cms_dauerbrenner_links_anzeigen();
	include_once('php/schulhof/seiten/verwaltung/pinnwaende/linksausgeben.php');
	$pinnwaende = cms_pinnwaende_links_anzeigen();
	include_once('php/schulhof/seiten/listen/linksausgeben.php');
	$listen = cms_listen_links_anzeigen();

	$code['mobil'] = "<h3>Informationen</h3>";
	$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_i\">";
		$code['mobil'] .= "<ul>";

				$code['mobil'] .= "<li><a href=\"Schulhof/Listen\">Listen</a><span id=\"cms_mobilmenue_knopf_i_listen\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('i_listen')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_i_listen\" style=\"display:none;\">";
					$code['mobil'] .= str_replace('class="cms_button"', '', $listen);
				$code['mobil'] .= "</div>";
				$code['mobil'] .= "</li>";

				$code['mobil'] .= "<li><a href=\"Schulhof/Dauerbrenner\">Dauerbrenner</a><span id=\"cms_mobilmenue_knopf_i_dauerbrenner\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('i_dauerbrenner')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_i_dauerbrenner\" style=\"display:none;\">";
					$code['mobil'] .= str_replace('class="cms_button"', '', $dauerbrenner);
				$code['mobil'] .= "</div>";
				$code['mobil'] .= "</li>";

				$code['mobil'] .= "<li><a href=\"Schulhof/Pinnwände\">Pinnwände</a><span id=\"cms_mobilmenue_knopf_i_pinnwaende\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('i_pinnwaende')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_i_pinnwaende\" style=\"display:none;\">";
					$code['mobil'] .= str_replace('class="cms_button"', '', $pinnwaende);
				$code['mobil'] .= "</div>";
				$code['mobil'] .= "</li>";

		$code['mobil'] .= "</ul>";
	$code['mobil'] .= "</div>";




	$code['pc'] = "<li><span class=\"cms_kategorie1\" onclick=\"cms_hauptnavigation_einblenden('info')\">Informationen</span>";
		$code['pc'] .= "<div class=\"cms_unternavigation_o\" id=\"cms_hauptnavigation_info_o\">";
		$code['pc'] .= "<div class=\"cms_unternavigation_m\">";
			$code['pc'] .= "<div class=\"cms_unternavigation_i\">";
				$code['pc'] .= "<span class=\"cms_unternavigation_schliessen cms_button_nein\" id=\"cms_hauptnavigation_info_l\" onclick=\"cms_hauptnavigation_ausblenden('info')\">&times;</span>";
				$code['pc'] .= "<div class=\"cms_spalte_i\">";
					$code['pc'] .= "<ul class=\"cms_reitermenue\">";
						$code['pc'] .= "<li><span id=\"cms_reiter_informationen_0\" class=\"cms_reiter_aktiv\" onclick=\"cms_reiter('informationen', 0,2)\">Listen</a></li> ";
						$code['pc'] .= "<li><span id=\"cms_reiter_informationen_1\" class=\"cms_reiter\" onclick=\"cms_reiter('informationen', 1,2)\">Dauerbrenner</a></li> ";
						$code['pc'] .= "<li><span id=\"cms_reiter_informationen_2\" class=\"cms_reiter\" onclick=\"cms_reiter('informationen', 2,2)\">Pinnwände</a></li> ";
					$code['pc'] .= "</ul>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_informationen_0\" style=\"display: block;\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
							$code['pc'] .= $listen;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_informationen_1\" style=\"display: none;\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= $dauerbrenner;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_informationen_2\" style=\"display: none;\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= $pinnwaende;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

				$code['pc'] .= "</div>";
			$code['pc'] .= "</div>";
		$code['pc'] .= "</div>";
	$code['pc'] .= "</li>";
	return $code;
}


function cms_schulhofnavigation_gruppen($dbs) {
	global $CMS_GRUPPEN, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR;
	include_once('php/schulhof/seiten/gruppen/linksausgeben.php');

	$anzeigen = false;
	foreach ($CMS_GRUPPEN as $g) {
		$gruppen[$g] = cms_gruppen_links_anzeigen($dbs, $g, $CMS_BENUTZERART, $CMS_BENUTZERID, $CMS_BENUTZERSCHULJAHR);
		if (strlen($gruppen[$g]) != 0) {$anzeigen = true;}
	}

	$code['mobil'] = "";
	$code['pc'] = "";

	if ($anzeigen) {
		$code['mobil'] = "<h3>Gruppen</h3>";
		$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_i\">";
			$code['mobil'] .= "<ul>";
				foreach ($CMS_GRUPPEN as $g) {
					if (strlen($gruppen[$g]) > 0) {
						$name = cms_textzudb($g);
						$code['mobil'] .= "<li><span class=\"cms_mobilnavi_passiv\">$g</span><span id=\"cms_mobilmenue_knopf_g_$name\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('g_$name')\">&#8628;</span>";
							$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_g_$name\" style=\"display:none;\">";
								$code['mobil'] .= "<ul>";
									$code['mobil'] .= str_replace('class="cms_button"', '', $gruppen[$g]);
								$code['mobil'] .= "</ul>";
							$code['mobil'] .= "</div>";
						$code['mobil'] .= "</li>";
					}
				}
			$code['mobil'] .= "</ul>";
		$code['mobil'] .= "</div>";


		$code['pc'] = "<li><span class=\"cms_kategorie1\" onclick=\"cms_hauptnavigation_einblenden('gruppen')\">Gruppen</span>";
			$code['pc'] .= "<div class=\"cms_unternavigation_o\" id=\"cms_hauptnavigation_gruppen_o\">";
			$code['pc'] .= "<div class=\"cms_unternavigation_m\">";
				$code['pc'] .= "<div class=\"cms_unternavigation_i\">";
					$code['pc'] .= "<span class=\"cms_unternavigation_schliessen cms_button_nein\" id=\"cms_hauptnavigation_gruppen_l\" onclick=\"cms_hauptnavigation_ausblenden('gruppen')\">&times;</span>";
					$code['pc'] .= "<div class=\"cms_spalte_i\">";
						$code['pc'] .= "<ul class=\"cms_reitermenue\">";
							$anzahlgruppen = count($CMS_GRUPPEN)-1;
							$gruppennr = 0;
							$aktiv = 0;
							foreach ($CMS_GRUPPEN as $g) {
								$zusatz = "";
								if (strlen($gruppen[$g]) == 0) {$style = "display: none;";}
								else if ($aktiv == 0) {$zusatz = "_aktiv"; $aktiv++; $style = "";}
								else {$style = ""; $zusatz = "";}
								$name = cms_textzudb($g);
								$code['pc'] .= "<li style=\"$style\"><span id=\"cms_reiter_gruppen_$gruppennr\" class=\"cms_reiter$zusatz\" onclick=\"cms_reiter('gruppen',$gruppennr,$anzahlgruppen)\">$g</a></li> ";
								$gruppennr++;
							}
						$code['pc'] .= "</ul>";

						$aktiv = 0;
						$gruppennr = 0;
						foreach ($CMS_GRUPPEN as $g) {
							if (strlen($gruppen[$g]) == 0) {$style = "none";}
							else if ($aktiv == 0) {$aktiv++; $style = "block";}
							else {$style = "none";}
							$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_gruppen_$gruppennr\" style=\"display: $style;\">";
								$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
									$code['pc'] .= $gruppen[$g];
								$code['pc'] .= "</div>";
							$code['pc'] .= "</div>";
							$gruppennr++;
						}

					$code['pc'] .= "</div>";
				$code['pc'] .= "</div>";
			$code['pc'] .= "</div>";
		$code['pc'] .= "</li>";
	}
	return $code;
}

function cms_schulhofnavigation_plaene($dbs) {
	global $CMS_EINSTELLUNGEN;
	include_once('php/schulhof/seiten/plaene/vertretungen/linksausgeben.php');
	include_once('php/schulhof/seiten/plaene/raeume/linksausgeben.php');
	include_once('php/schulhof/seiten/plaene/leihgeraete/linksausgeben.php');
	include_once('php/schulhof/seiten/plaene/lehrer/linksausgeben.php');
	include_once('php/schulhof/seiten/plaene/klassen/linksausgeben.php');
	if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '0') {include_once('php/schulhof/seiten/plaene/stufen/linksausgeben.php');}
	$vertretungen = cms_schulhof_vertretungen_links_anzeigen();
	$raeume = cms_schulhof_raeume_links_anzeigen();
	$leihgeraete = cms_schulhof_leihgeraete_links_anzeigen();
	$lehrer = cms_schulhof_lehrer_links_anzeigen();
	$klassen = cms_schulhof_klassen_links_anzeigen();
	if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '0') {$stufen = cms_schulhof_stufen_links_anzeigen();}
	else $stufen = "";

	if (strlen($vertretungen.$raeume.$leihgeraete.$lehrer.$klassen.$stufen) == 0) {
		return "";
	}

	$code['mobil'] = "<h3>Pläne</h3>";
	$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_p\">";
		$code['mobil'] .= "<ul>";
			$code['mobil'] .= "<li><a href=\"Schulhof/Pläne/Vertretungen\">Vertretungspläne</a><span id=\"cms_mobilmenue_knopf_p_vertretungsplaene\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('p_vertretungsplaene')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_p_vertretungsplaene\" style=\"display:none;\">";
					$code['mobil'] .= "<ul>";
						$code['mobil'] .= str_replace('class="cms_button"', '', $vertretungen);
					$code['mobil'] .= "</ul>";
				$code['mobil'] .= "</div>";
			$code['mobil'] .= "</li>";
				$code['mobil'] .= "<li><a href=\"Schulhof/Pläne/Lehrer\">Lehrerstundenpläne</a><span id=\"cms_mobilmenue_knopf_p_lehrerstundenplaene\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('p_lehrerstundenplaene')\">&#8628;</span>";
					$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_p_lehrerstundenplaene\" style=\"display:none;\">";
						$code['mobil'] .= "<ul>";
							$code['mobil'] .= str_replace('class="cms_button"', '', $lehrer);
						$code['mobil'] .= "</ul>";
					$code['mobil'] .= "</div>";
				$code['mobil'] .= "</li>";
			$code['mobil'] .= "<li><a href=\"Schulhof/Pläne/Klassen\">Klassenstundenpläne</a><span id=\"cms_mobilmenue_knopf_p_klassenstundenplaene\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('p_klassenstundenplaene')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_p_klassenstundenplaene\" style=\"display:none;\">";
					$code['mobil'] .= "<ul>";
						$code['mobil'] .= str_replace('class="cms_button"', '', $klassen);
					$code['mobil'] .= "</ul>";
				$code['mobil'] .= "</div>";
			$code['mobil'] .= "</li>";
			if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '0') {
				$code['mobil'] .= "<li><a href=\"Schulhof/Pläne/Stufen\">Stufenstundenpläne</a><span id=\"cms_mobilmenue_knopf_p_stufenstundenplaene\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('p_stufenstundenplaene')\">&#8628;</span>";
					$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_p_stufenstundenplaene\" style=\"display:none;\">";
						$code['mobil'] .= "<ul>";
							$code['mobil'] .= str_replace('class="cms_button"', '', $stufen);
						$code['mobil'] .= "</ul>";
					$code['mobil'] .= "</div>";
				$code['mobil'] .= "</li>";
			}
			$code['mobil'] .= "<li><a href=\"Schulhof/Pläne/Räume\">Raumpläne</a><span id=\"cms_mobilmenue_knopf_p_raeume\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('p_raeume')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_p_raeume\" style=\"display:none;\">";
					$code['mobil'] .= "<ul>";
						$code['mobil'] .= str_replace('class="cms_button"', '', $raeume);
					$code['mobil'] .= "</ul>";
				$code['mobil'] .= "</div>";
			$code['mobil'] .= "</li> ";
			$code['mobil'] .= "<li><a href=\"Schulhof/Pläne/Leihgeräte\">Leihgeräte</a><span id=\"cms_mobilmenue_knopf_p_leihgeraete\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('p_leihgeraete')\">&#8628;</span>";
				$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_p_leihgeraete\" style=\"display:none;\">";
					$code['mobil'] .= "<ul>";
						$code['mobil'] .= str_replace('class="cms_button"', '', $leihgeraete);
					$code['mobil'] .= "</ul>";
				$code['mobil'] .= "</div>";
			$code['mobil'] .= "</li>";
		$code['mobil'] .= "</ul>";
	$code['mobil'] .= "</div>";

	$code['pc'] = "<li><span class=\"cms_kategorie1\" onclick=\"cms_hauptnavigation_einblenden('plaene')\">Pläne</span>";
		$code['pc'] .= "<div class=\"cms_unternavigation_o\" id=\"cms_hauptnavigation_plaene_o\">";
		$code['pc'] .= "<div class=\"cms_unternavigation_m\">";
			$code['pc'] .= "<div class=\"cms_unternavigation_i\">";
				$code['pc'] .= "<span class=\"cms_unternavigation_schliessen cms_button_nein\" id=\"cms_hauptnavigation_plaene_l\" onclick=\"cms_hauptnavigation_ausblenden('plaene')\">&times;</span>";
				$code['pc'] .= "<div class=\"cms_spalte_i\">";
					$code['pc'] .= "<ul class=\"cms_reitermenue\">";
						$code['pc'] .= "<li><span id=\"cms_reiter_stundenplan_0\" class=\"cms_reiter_aktiv\" onclick=\"cms_reiter('stundenplan', 0,5)\">Vertretungspläne</a></li> ";
						$code['pc'] .= "<li><span id=\"cms_reiter_stundenplan_1\" class=\"cms_reiter\" onclick=\"cms_reiter('stundenplan', 1,5)\">Lehrerstundenpläne</a></li> ";
						$code['pc'] .= "<li><span id=\"cms_reiter_stundenplan_2\" class=\"cms_reiter\" onclick=\"cms_reiter('stundenplan', 2,5)\">Klassenstundenpläne</a></li> ";
						$code['pc'] .= "<li><span id=\"cms_reiter_stundenplan_3\" class=\"cms_reiter\" onclick=\"cms_reiter('stundenplan', 3,5)\">Stufenstundenpläne</a></li> ";
						$code['pc'] .= "<li><span id=\"cms_reiter_stundenplan_4\" class=\"cms_reiter\" onclick=\"cms_reiter('stundenplan', 4,5)\">Raumpläne</a></li> ";
						$code['pc'] .= "<li><span id=\"cms_reiter_stundenplan_5\" class=\"cms_reiter\" onclick=\"cms_reiter('stundenplan', 5,5)\">Leihgeräte</a></li> ";
					$code['pc'] .= "</ul>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_stundenplan_0\" style=\"display: block;\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
							$code['pc'] .= $vertretungen;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_stundenplan_1\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
							$code['pc'] .= $lehrer;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_stundenplan_2\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
							$code['pc'] .= $klassen;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_stundenplan_3\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
							if ($CMS_EINSTELLUNGEN['Stundenplan Klassen extern'] == '0') {$code['pc'] .= $stufen;}
							else {$code['pc'] .= cms_meldung('info', '<h4>Nicht verfügbar</h4><p>Bei externer Stundenplanverwaltung stehen im Moment noch keine Stufenpläne zur Verfügung.</p>');}
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_stundenplan_4\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
							$code['pc'] .= $raeume;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

					$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_stundenplan_5\">";
						$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
							$code['pc'] .= $leihgeraete;
						$code['pc'] .= "</div>";
					$code['pc'] .= "</div>";

				$code['pc'] .= "</div>";
			$code['pc'] .= "</div>";
		$code['pc'] .= "</div>";
		$code['pc'] .= "</div>";
	$code['pc'] .= "</li>";
	return $code;
}

function cms_schulhofnavigation_verwaltung($dbs) {
	global $CMS_GRUPPEN, $CMS_BENUTZERSCHULJAHR;

	// Ermitteln, welche Verwaltungsrechte der Benutzer hat
	$VERWALTUNG = false;
	// PERSONEN
	$VERpersonenundgruppen = "";
	if (cms_r("schulhof.verwaltung.personen.sehen")) {
		$VERpersonenundgruppen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Personen\">Personen</a></li> ";
	}
	if (cms_r("schulhof.verwaltung.rechte.rollen.[|anlegen,bearbeiten,löschen]")) {
		$VERpersonenundgruppen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Rollen\">Rollen</a></li> ";
	}
	if (cms_r("schulhof.verwaltung.rechte.bedingt || schulhof.verwaltung.rechte.rollen.bedingt")) {
		$VERpersonenundgruppen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Bedingte_Rechte\">Rollen und Rechte bedingt zuordnen</a></li> ";
	}
	foreach ($CMS_GRUPPEN as $g) {
		if (cms_r("schulhof.gruppen.$g.[|anlegen,bearbeiten,löschen]")) {
			$VERpersonenundgruppen .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Gruppen/".cms_textzulink($g)."\">$g</a></li> ";
		}
	}
	if (cms_r("schulhof.verwaltung.nutzerkonten.verstöße.chatmeldungen")) {
		$VERpersonenundgruppen .= "<li><a class=\"cms_button\" href=\"Schulhof/Aufgaben/Chatmeldungen\">Chatmeldungen</a></li> ";
	}
	// PLANUNG
	$VERplanung = "";
	if (cms_r("schulhof.planung.schuljahre.fabrik")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"javascript:cms_schuljahrfabrik_vorbereiten($CMS_BENUTZERSCHULJAHR)\">Schuljahrfabrik</a></li> ";
	}
	if (cms_r("schulhof.planung.schuljahre.planungszeiträume.[|anlegen,bearbeiten,löschen,duplizieren,rythmisieren]")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"javascript:cms_stundenplanzeitraeume_vorbereiten($CMS_BENUTZERSCHULJAHR)\">Stundenplanzeiträume</a></li> ";
	}
	if (cms_r("schulhof.planung.schuljahre.fächer.*")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"javascript:cms_faecher_vorbereiten($CMS_BENUTZERSCHULJAHR)\">Fächer</a></li> ";
	}
	if (cms_r("schulhof.planung.schuljahre.profile.*")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"javascript:cms_profile_vorbereiten($CMS_BENUTZERSCHULJAHR)\">Profile</a></li> ";
	}
	if (cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.schienen.*")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"javascript:cms_schienen_vorbereiten($CMS_BENUTZERSCHULJAHR, '-')\">Schienen</a></li> ";
	}
	if (cms_r("schulhof.planung.schuljahre.planungszeiträume.stundenplanung.durchführen")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"javascript:cms_stundenplanung_vorbereiten($CMS_BENUTZERSCHULJAHR, '-')\">Stundenplanung</a></li> ";
	}
	if (cms_r("schulhof.planung.schuljahre.stundentagebücher.erzeugen")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"javascript:cms_stundenerzeugen_vorbereiten($CMS_BENUTZERSCHULJAHR, '-')\">Stunden und Tagebücher erzeugen</a></li> ";
	}
	if (cms_r("lehrerzimmer.vertretungsplan.vertretungsplanung")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Planung/Vertretungsplanung\">Vertretungsplan</a></li> ";
	}
	if (cms_r("lehrerzimmer.vertretungsplan.ausplanungen")) {
		$VERplanung .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Planung/Ausplanungen\">Ausplanungen</a></li> ";
	}
	// ORGANISATION
	$VERorganisation = "";
	if (cms_r("schulhof.planung.schuljahre.[|anlegen,bearbeiten,löschen]")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Schuljahre\">Schuljahre</a></li> ";
	}
	if (cms_r("schulhof.planung.räume.*")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Räume\">Räume</a></li> ";
	}
	if (cms_r("schulhof.organisation.leihgeräte.*")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Leihgeräte\">Leihgeräte</a></li> ";
	}
	if (cms_r("schulhof.organisation.ferien.*")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Ferien\">Ferien</a></li> ";
	}
	if (cms_r("schulhof.organisation.schulanmeldung.*")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Schulanmeldung\">Schulanmeldung</a></li> ";
	}
	if (cms_r("artikel.genehmigen.termine || schulhof.gruppen.%GRUPPEN%.artikel.termine.genehmigen")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Aufgaben/Termine_genehmigen\">Termine genehmigen</a></li> ";
	}
	if (cms_r("artikel.genehmigen.blogeinträge || schulhof.gruppen.%GRUPPEN%.artikel.blogeinträge.genehmigen")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Aufgaben/Blogeinträge_genehmigen\">Blogeinträge genehmigen</a></li> ";
	}
	if (cms_r("artikel.genehmigen.galerien")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Aufgaben/Gelerien_genehmigen\">Galerien genehmigen</a></li> ";
	}
	if (cms_r("schulhof.information.dauerbrenner.*")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Dauerbrenner\">Dauerbrenner</a></li> ";
	}
	if (cms_r("schulhof.information.pinnwände.[|anlegen,bearbeiten,löschen]")) {
		$VERorganisation .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Pinnwände\">Pinnwände</a></li> ";
	}
	// WEBSITE
	$VERwebsite = "";
	if (cms_r("website.seiten.*")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Seiten\">Seiten</a></li> ";
	}
	if (cms_r("website.navigation")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Hauptnavigationen\">Hauptnavigationen</a></li> ";
	}
	if (cms_r("website.dateien.*")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Dateien\">Dateien</a></li> ";
	}
	if (cms_r("artikel.%ARTIKELSTUFEN%.termine.* || artikel.genehmigen.termine")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Termine\">Termine</a></li> ";
	}
	if (cms_r("artikel.%ARTIKELSTUFEN%.blogeinträge.* || artikel.genehmigen.blogeinträge")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Blogeinträge\">Blogeinträge</a></li> ";
	}
	if (cms_r("artikel.galerien.* || artikel.genehmigen.galerien")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Galerien\">Galerien</a></li> ";
	}
	if (cms_r("website.titelbilder.*")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Titelbilder\">Titelbilder</a></li> ";
	}
	if (cms_r("website.auszeichnungen.*")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Auszeichnungen\">Auszeichnungen</a></li> ";
	}
	if (cms_r("statistik.besucher.*")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Besucherstatistiken\">Besucherstatistiken</a></li> ";
	}
	if (cms_r("schulhof.information.newsletter.*")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Newsletter\">Newsletter</a></li> ";
	}
	if (cms_r("website.weiterleiten")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Weiterleiten\">Weiterleitungen</a></li> ";
	}
	if (cms_r("website.masterelemente")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Masterelemente_bearbeiten\">Masterelemente</a></li> ";
	}
	if (cms_r("website.styleändern")) {
		$VERwebsite .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Style_ändern\">Style ändern</a></li> ";
	}

	// TECHNIK
	$VERtechnik = "";
	if (cms_r("schulhof.technik.geräte.verwalten")) {
		$VERtechnik .= "<li><a class=\"cms_button\" href=\"Schulhof/Aufgaben/Geräte_verwalten\">Geräte verwalten</a></li> ";
	}
	if (cms_r("schulhof.technik.hausmeisteraufträge.*")) {
		$VERtechnik .= "<li><a class=\"cms_button\" href=\"Schulhof/Hausmeister/Aufträge\">Hausmeisteraufträge</a></li> ";
	}

	// ADMINISTRATION
	$VERadministration = "";
	if (cms_r("schulhof.verwaltung.einstellungen")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Allgemeine_Einstellungen\">Allgemeine Einstellungen</a></li> ";
	}
	if (cms_r("technik.server.netze")) {
		$VERadministration .= "<li><a class=\"cms_button_wichtig\" href=\"Schulhof/Verwaltung/Schulnetze\">Schulnetze</a></li> ";
	}
	if (cms_r("technik.server.vpn")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/VPN\">VPN</a></li> ";
	}
	if (cms_r("technik.server.dateienerlaubnis")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Zulässige_Dateien\">Zulässige Dateien</a></li> ";
	}
	if (cms_r("schulhof.verwaltung.schule.[|adressen,mail]")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Schuldetails\">Schuldetails</a></li> ";
	}
	if (cms_r("schulhof.verwaltung.schule.mail")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Schulhofmailer\">Schulhofmailer</a></li> ";
	}
	if (cms_r("technik.server.update")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Update\">Schulhof aktualisieren</a></li> ";
	}
	if (cms_r("statistik.speicherplatz")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Verwaltung/Speicherplatz/Statistik\">Speicherplatzstatistik</a></li> ";
	}
	if (cms_r("technik.feedback")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Feedback\">Feedback</a></li> ";
	}
	if (cms_r("technik.fehlermeldungen")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Website/Fehlermeldungen\">Fehlermeldungen</a></li> ";
	}
	if (cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges")) {
		$VERadministration .= "<li><a class=\"cms_button\" href=\"Schulhof/Aufgaben/Auffälliges\">Auffälliges Verhalten</a></li> ";
	}


	$VERsupport = "";
	$VERhilfe = "";

	if ((strlen($VERpersonenundgruppen) > 0) || (strlen($VERplanung) > 0) || (strlen($VERorganisation) > 0) ||
	    (strlen($VERwebsite) > 0) || (strlen($VERtechnik) > 0) || (strlen($VERadministration) > 0) ||
			(strlen($VERsupport) > 0) || (strlen($VERhilfe) > 0)) {
		$VERWALTUNG = true;
	}

	$code['mobil'] = '';
	$code['pc'] = '';

	if ($VERWALTUNG) {
		$code['mobil'] .= "<h3>Verwaltung</h3>";
		$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v\">";
			$code['mobil'] .= "<ul>";
				if (strlen($VERpersonenundgruppen) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Personen und Gruppen</a><span id=\"cms_mobilmenue_knopf_v_personen\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_personen')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_personen\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERpersonenundgruppen);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
				if (strlen($VERplanung) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Planung</a><span id=\"cms_mobilmenue_knopf_v_planung\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_planung')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_planung\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERplanung);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
				if (strlen($VERorganisation) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Organisation</a><span id=\"cms_mobilmenue_knopf_v_organisation\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_organisation')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_organisation\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERorganisation);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
				if (strlen($VERwebsite) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Website</a><span id=\"cms_mobilmenue_knopf_v_website\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_website')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_website\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERwebsite);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
				if (strlen($VERtechnik) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Technik</a><span id=\"cms_mobilmenue_knopf_v_website\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_technik')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_technik\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERtechnik);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
				if (strlen($VERadministration) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Administration</a><span id=\"cms_mobilmenue_knopf_v_administration\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_administration')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_administration\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERadministration);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
				if (strlen($VERsupport) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Support</a><span id=\"cms_mobilmenue_knopf_v_support\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_support')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_support\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERsupport);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
				if (strlen($VERhilfe) > 0) {
					$code['mobil'] .= "<li><a href=\"Schulhof/Verwaltung\">Hilfe</a><span id=\"cms_mobilmenue_knopf_v_hilfe\" class=\"cms_mobilmenue_aufklappen\" onclick=\"cms_mobinavi_zeigen('v_hilfe')\">&#8628;</span>";
						$code['mobil'] .= "<div id=\"cms_mobilmenue_seite_v_hilfe\" style=\"display:none;\">";
							$code['mobil'] .= "<ul>";
								$code['mobil'] .= str_replace('class="cms_button"', '', $VERhilfe);
							$code['mobil'] .= "</ul>";
						$code['mobil'] .= "</div>";
					$code['mobil'] .= "</li>";
				}
			$code['mobil'] .= "</ul>";
		$code['mobil'] .= "</div>";


		$code['pc'] .= "<li><span class=\"cms_kategorie1\" onclick=\"cms_hauptnavigation_einblenden('verwaltung')\">Verwaltung</span>";
			$code['pc'] .= "<div class=\"cms_unternavigation_o\" id=\"cms_hauptnavigation_verwaltung_o\">";
			$code['pc'] .= "<div class=\"cms_unternavigation_m\">";
				$code['pc'] .= "<div class=\"cms_unternavigation_i\">";
					$code['pc'] .= "<span class=\"cms_unternavigation_schliessen cms_button_nein\" id=\"cms_hauptnavigation_verwaltung_l\" onclick=\"cms_hauptnavigation_ausblenden('verwaltung')\">&times;</span>";
					$code['pc'] .= "<div class=\"cms_spalte_i\">";
						$code['pc'] .= "<ul class=\"cms_reitermenue\">";
							$style = "";
							if (strlen($VERpersonenundgruppen) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_0\" class=\"cms_reiter_aktiv\" onclick=\"cms_reiter('verwaltung', 0,7)\">Personen und Gruppen</a></li> ";
							if (strlen($VERplanung) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_1\" class=\"cms_reiter\" onclick=\"cms_reiter('verwaltung', 1,7)\">Planung</a></li> ";
							if (strlen($VERorganisation) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_2\" class=\"cms_reiter\" onclick=\"cms_reiter('verwaltung', 2,7)\">Organisation</a></li> ";
							if (strlen($VERwebsite) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_3\" class=\"cms_reiter\" onclick=\"cms_reiter('verwaltung', 3,7)\">Website</a></li> ";
							if (strlen($VERtechnik) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_4\" class=\"cms_reiter\" onclick=\"cms_reiter('verwaltung', 4,7)\">Technik</a></li> ";
							if (strlen($VERadministration) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_5\" class=\"cms_reiter\" onclick=\"cms_reiter('verwaltung', 5,7)\">Administration</a></li> ";
							if (strlen($VERsupport) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_6\" class=\"cms_reiter\" onclick=\"cms_reiter('verwaltung', 6,7)\">Support</a></li> ";
							if (strlen($VERhilfe) == 0) {$style = " style=\"display: none\"";} else {$style = "";}
							$code['pc'] .= "<li".$style."><span id=\"cms_reiter_verwaltung_7\" class=\"cms_reiter\" onclick=\"cms_reiter('verwaltung', 7,7)\">Hilfe</a></li> ";
						$code['pc'] .= "</ul>";

						$angezeigt = false;
						if (!$angezeigt && (strlen($VERpersonenundgruppen) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_0\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERpersonenundgruppen</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						if (!$angezeigt && (strlen($VERplanung) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_1\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERplanung</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						if (!$angezeigt && (strlen($VERorganisation) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_2\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERorganisation</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						if (!$angezeigt && (strlen($VERwebsite) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_3\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERwebsite</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						if (!$angezeigt && (strlen($VERtechnik) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_4\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERtechnik</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						if (!$angezeigt && (strlen($VERadministration) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_5\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERadministration</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						if (!$angezeigt && (strlen($VERsupport) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_6\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERsupport</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						if (!$angezeigt && (strlen($VERhilfe) > 0)) {$style = " style=\"display: block;\""; $angezeigt = true;} else {$style = "";}
						$code['pc'] .= "<div class=\"cms_reitermenue_o\" id=\"cms_reiterfenster_verwaltung_7\"$style>";
							$code['pc'] .= "<div class=\"cms_reitermenue_i\">";
								$code['pc'] .= "<ul>$VERhilfe</ul>";
							$code['pc'] .= "</div>";
						$code['pc'] .= "</div>";

						$code['pc'] .= "<p><a href=\"Schulhof/Verwaltung\" class=\"cms_button\">Verwaltungsübersicht</a></p>";
					$code['pc'] .= "</div>";
				$code['pc'] .= "</div>";
			$code['pc'] .= "</div>";
			$code['pc'] .= "</div>";
		$code['pc'] .= "</li>";
	}
	return $code;
}

echo cms_schulhofnavigation();
?>
