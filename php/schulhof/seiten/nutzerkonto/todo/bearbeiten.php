<div class="cms_spalte_i">
<?php
	// ToDo laden
	if(count($CMS_URL) == 10) {
		$schuljahr = cms_linkzutext($CMS_URL[2]);
		$g = cms_linkzutext($CMS_URL[3]);
		$gk = cms_textzudb($g);
		$gbez = cms_linkzutext($CMS_URL[4]);
		$gruppenid = "";
		$fehler = false;

		// Prüfen, ob diese Gruppe existiert
		if (in_array($g, $CMS_GRUPPEN)) {
			if ($schuljahr == "Schuljahrübergreifend") {
				$sql = $dbs->prepare("SELECT id, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IS NULL");
				$sql->bind_param("s", $gbez);
			}
			else {
				$sql = $dbs->prepare("SELECT id, COUNT(*) as anzahl FROM $gk WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr IN (SELECT id FROM schuljahre WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL'))");
				$sql->bind_param("ss", $gbez, $schuljahr);
			}
			// Schuljahr finden
			if ($sql->execute()) {
				$sql->bind_result($gruppenid, $anzahl);
				if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
				else {$fehler = true;}
			}
			else {$fehler = true;}
			$sql->close();
		}
		else {$fehler = true;}

		$blogquery = "IS NULL";
		$terminquery = "IS NULL";
		$art = '';
		$artikelid;

		if(!$fehler) {
			if(preg_match("/^Schulhof\/ToDo\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Blog\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)) {
				// Blogeintrag prüfen
				$art = 'b';
				$blogquery = "= ?";

				$jahr = $CMS_URL[6];
				$monat = cms_monatnamezuzahl($CMS_URL[7]);
				$tag = $CMS_URL[8];
				$blogeintragbez = cms_linkzutext($CMS_URL[9]);
				$datum = mktime(0, 0, 0, $monat, $tag, $jahr);
				$aktiv = false;
				$gefunden = false;

				$sql = $dbs->prepare("SELECT id, aktiv FROM {$gk}blogeintraegeintern WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND datum = ?;");
				$sql->bind_param("si", $blogeintragbez, $datum);
				if ($sql->execute()) {
					$sql->bind_result($artikelid, $aktiv);
					if ($sql->fetch()) {
						$gefunden = true;
					}
					else {$fehler = true;}
				}
				else {$fehler = true;}
				$sql->close();

				if($gefunden) {
					$gruppenrecht = cms_gruppenrechte_laden($dbs, $g, $gruppenid);
					$gefunden = $gefunden && $gruppenrecht['sichtbar'] && ($aktiv || $gruppenrecht['blogeintraege']);
				}
				$fehler |= !$gefunden;

			} else if(preg_match("/^Schulhof\/ToDo\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/$CMS_LINKMUSTER\/Termine\/[0-9]{4}\/$CMS_MONATELINK\/[0-9]{2}\/$CMS_LINKMUSTER$/", $CMS_URLGANZ)) {
				// Termin prüfen
				$art = 't';
				$terminquery = "= ?";

				$jahr = $CMS_URL[6];
				$monat = cms_monatnamezuzahl($CMS_URL[7]);
				$tag = $CMS_URL[8];
				$terminbez = cms_linkzutext($CMS_URL[9]);
				$datumb = mktime(0, 0, 0, $monat, $tag, $jahr);
				$datume = mktime(0, 0, 0, $monat, $tag+1, $jahr)-1;
				$gruppe = cms_linkzutext($CMS_URL[3]);
				$aktiv = false;
				$gefunden = false;

				$sql = $dbs->prepare("SELECT id, aktiv FROM {$gk}termineintern WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND (beginn BETWEEN ? AND ?);");
				$sql->bind_param("sii", $terminbez, $datumb, $datume);
				if ($sql->execute()) {
					$sql->bind_result($artikelid, $aktiv);
					if ($sql->fetch()) {$gefunden = true;}
					else {$fehler = true;}
				}
				else {$fehler = true;}
				$sql->close();

				if($gefunden) {
					$gruppenrecht = cms_gruppenrechte_laden($dbs, $g, $gruppenid);
					$gefunden = $gefunden && $gruppenrecht['sichtbar'] && ($aktiv || $gruppenrecht['termine']);
				}
				$fehler |= !$gefunden;
			}

			if($fehler) {
				echo cms_meldung_bastler();
			} else {

				$sql = "SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') FROM {$gk}todoartikel WHERE person = ? AND blogeintrag $blogquery AND termin $terminquery";
				$sql = $dbs->prepare($sql);
				$sql->bind_param("ii", $CMS_BENUTZERID, $artikelid);
				$sql->bind_result($todobez, $beschreibung);
				if(!$sql->execute() || !$sql->fetch()) {
					echo cms_meldung_bastler();
				} else {
					$bezeichnung = $todobez ?? $terminbez ?? $blogeintragbez;
					?>

						<p class="cms_brotkrumen"><?php $bk = cms_brotkrumen($CMS_URL); echo preg_replace("/Schulhof\/ToDo\//", "Schulhof/Gruppen/", $bk); ?></p>
						<h1>ToDo Bearbeiten</h1>
						<table class="cms_formular">
							<tr><td>Bezeichnung</td><td><input type="text" id="cms_todo_bezeichnung" value="<?php echo $bezeichnung; ?>"></td></tr>
							<tr><td>Notizen</td><td><textarea id="cms_todo_beschreibung"><?php echo $beschreibung; ?></textarea></td></tr>
							<tr><td colspan="2"><span class="cms_button_ja" onclick="cms_seite_todo_speichern(<?php echo "'$g', '$gruppenid', '$art', '$artikelid'";?>)">Änderungen speichern</span> <span class="cms_button_nein" onclick="cms_seite_todo_setzen(<?php echo "'$g', '$gruppenid', '$art', '$artikelid'";?>)">Als erledigt markieren</span><input type="hidden" value="1" name="cms_seite_todo" id="cms_seite_todo"></td></tr>
						</table>
						<?php
					$sql->close();
				}
			}
		}
	} else {
		$bez = $CMS_URL[2];
		$bez = cms_linkzutext($bez);
		$sql = "SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(beschreibung, '$CMS_SCHLUESSEL') FROM todo WHERE person = ? AND bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL')";
		$sql = $dbs->prepare($sql);
		$sql->bind_param("is", $CMS_BENUTZERID, $bez);
		$sql->bind_result($id, $bezeichnung, $beschreibung);
		if(!$sql->execute() || !$sql->fetch()) {
			echo cms_meldung_bastler();
		} else {
			?>
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<h1>ToDo Bearbeiten</h1>
<table class="cms_formular">
	<tr><td>Bezeichnung</td><td><input type="text" id="cms_todo_bezeichnung" value="<?php echo $bezeichnung; ?>"></td></tr>
	<tr><td>Notizen</td><td><textarea id="cms_todo_beschreibung"><?php echo $beschreibung; ?></textarea></td></tr>
	<tr><td colspan="2"><span class="cms_button_ja" onclick="cms_eigenes_todo_speichern()">Änderungen speichern</span> <span class="cms_button_nein" onclick="cms_eigenes_todo_loeschen(<?php echo $id;?>)">Als erledigt markieren</span></td></tr>
</table>
<input type="hidden" id="cms_todo_id" value="<?php echo $id;?>">
		<?php
	}
	$sql->close();
}
?>
<div class="cms_clear"></div>
