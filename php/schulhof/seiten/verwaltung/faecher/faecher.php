<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$zugriff = $CMS_RECHTE['Planung']['Fächer anlegen'] || $CMS_RECHTE['Planung']['Fächer bearbeiten'] || $CMS_RECHTE['Planung']['Fächer löschen'];

$code = "";
if ($zugriff) {
  // Prüfen, ob Schuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['FÄCHERSCHULJAHR'])) {
    $SCHULJAHR = $_SESSION['FÄCHERSCHULJAHR'];
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    $sql->bind_param('i', $SCHULJAHR);
    if ($sql->execute()) {
      $sql->bind_result($anzahl);
      if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
    }
    $sql->close();
  }


  if (!$sjfehler) {
    $code .= "<h1>Fächer</h1>";

    $schuljahrwahlcode = "";
    // Alle Schuljahre laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM schuljahre ORDER BY beginn DESC");
    if ($sql->execute()) {
      $sql->bind_result($id, $sjbez);
      while ($sql->fetch()) {
        $klasse = "cms_button";
        if ($id == $SCHULJAHR) {$klasse .= "_ja";}
        $schuljahrwahlcode .= "<span class=\"$klasse\" onclick=\"cms_faecher_vorbereiten($id)\">$sjbez</span> ";
      }
    }
    $sql->close();
    $code .= "<p>".$schuljahrwahlcode."</p>";

    $zeilen = "";
    $code .= "<table class=\"cms_liste\">";
      $code .= "<tr><th></th><th></th><th>Bezeichnung</th><th>Kürzel</th><th>Kollegen</th><th>Aktionen</th></tr>";
			$dbs = cms_verbinden('s');
			$sql = "SELECT * FROM (SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung, AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') AS kuerzel, AES_DECRYPT(icon, '$CMS_SCHLUESSEL') AS icon, farbe FROM faecher WHERE schuljahr = $SCHULJAHR) AS f ORDER BY bezeichnung ASC";

			$sqlkollegen = $dbs->prepare("SELECT AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM lehrer JOIN fachkollegen ON lehrer.id = fachkollegen.kollege WHERE fachkollegen.fach = ?");

			$ausgabe = "";
			if ($anfrage = $dbs->query($sql)) {  // Safe weil interne ID
				while ($daten = $anfrage->fetch_assoc()) {
					$ausgabe .= "<tr>";
						$ausgabe .= "<td><img src=\"res/gruppen/klein/".$daten['icon']."\"></td>";
            $ausgabe .= "<td><span class=\"cms_tag_gross cms_farbbeispiel_".$daten['farbe']."\"></span></td>";
						$ausgabe .= "<td>".$daten['bezeichnung']."</td>";
						$ausgabe .= "<td>".$daten['kuerzel']."</td>";

						$kollegen = "";
						$sqlkollegen->bind_param('i', $daten['id']);
						if ($sqlkollegen->execute()) {
							$sqlkollegen->bind_result($lkurz);
							while ($sqlkollegen->fetch()) {
								$kollegen .= ", ".$lkurz;
							}
						}
						if (strlen($kollegen) > 0) {$kollegen = substr($kollegen,2);}
						$ausgabe .= "<td>$kollegen</td>";
						$ausgabe .= "<td>";
						$bezeichnung = cms_texttrafo_e_event($daten['bezeichnung']);
						if ($CMS_RECHTE['Planung']['Fächer bearbeiten']) {
							$ausgabe .= "<span class=\"cms_aktion_klein\" onclick=\"cms_schulhof_faecher_bearbeiten_vorbereiten(".$daten['id'].");\"><span class=\"cms_hinweis\">Bearbeiten</span><img src=\"res/icons/klein/bearbeiten.png\"></span> ";
						}
						if ($CMS_RECHTE['Planung']['Fächer löschen']) {
							$ausgabe .= "<span class=\"cms_aktion_klein cms_aktion_nein\" onclick=\"cms_schulhof_faecher_loeschen_anzeigen('$bezeichnung', ".$daten['id'].");\"><span class=\"cms_hinweis\">Löschen</span><img src=\"res/icons/klein/loeschen.png\"></span> ";
						}
						$ausgabe .= "</td>";

					$ausgabe .= "</tr>";
				}
				$anfrage->free();
			}
			$sqlkollegen->close();

			if ($ausgabe == "") {
				$ausgabe = "<tr><td class=\"cms_notiz\" colspan=\"6\">- keine Datensätze gefunden -</td></tr>";
			}

			$code .= $ausgabe;

    $code .= "</table>";

    if ($CMS_RECHTE['Planung']['Fächer anlegen']) {
      $code .= "<p><a class=\"cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Fächer/Neues_Fach_anlegen\">+ Neues Fach anlegen</a></p>";
    }


  }
  else {$code .= "<h1>Fächer</h1>".cms_meldung_bastler();}
}
else {
  $code .= "<h1>Fächer</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
