<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<?php
$code = "";
if (r("schulhof.planung.schuljahre.fabrik")) {

  $code .= "<h1>Schuljahre aus bestehenden Schuljahren erzeugen</h1>";


  $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bereits bestehenden Profile im Zielschuljahr, werden mit dem Abschluss dieses Schrittes gelöscht.</b></p>');

  $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM schuljahre ORDER BY beginn DESC");
  $buttonsa = "";
  $buttonsn = "";
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR'])) {$SCHULJAHR = $_SESSION['SCHULJAHRFABRIKSCHULJAHR'];}
  else {$SCHULJAHR = null;}
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {$SCHULJAHRNEU = $_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'];}
  else {$SCHULJAHRNEU = null;}
  if ($sql->execute()) {
    $sql->bind_result($id, $sjbez);
    while ($sql->fetch()) {
      if (($SCHULJAHR == $id) && ($SCHULJAHR != 'null')) {$buttonsa .= "<span class=\"cms_button_ja\"";}
      else {$buttonsa .= "<span class=\"cms_button\"";}
      $buttonsa .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$id', 'Profile', '$SCHULJAHRNEU');\">".$sjbez."</span> ";
      if (($SCHULJAHRNEU == $id) && ($SCHULJAHRNEU != 'null')) {$buttonsn .= "<span class=\"cms_button_ja\"";}
      else {$buttonsn .= "<span class=\"cms_button\"";}
      $buttonsn .= " onclick=\"cms_schuljahrfabrik_vorbereiten('$SCHULJAHR', 'Profile', '$id');\">".$sjbez."</span> ";
    }
  }
  $sql->close();
  $code .= "</div><div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Stammschuljahr auswählen</h2>";
  if (strlen($buttonsa) > 0) {$code .= "<p>$buttonsa</p>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";}
  $code .= "</div></div><div class=\"cms_spalte_2\"><div class=\"cms_spalte_i\">";
  $code .= "<h2>Zielschuljahr auswählen</h2>";
  if (strlen($buttonsa) > 0) {$code .= "<p>$buttonsn</p>";}
  else {$code .= "<p class=\"cms_notiz\">Keine Schuljahre angelegt</p>";}
  $code .= "</div></div><div class=\"cms_clear\"></div><div class=\"cms_spalte_i\">";

  // Prüfen, ob Stammschuljahr vorhanden
  $sjfehler = true;
  if (isset($_SESSION['SCHULJAHRFABRIKSCHULJAHR']) || isset($_SESSION['SCHULJAHRFABRIKSCHULJAHRNEU'])) {
    $sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM schuljahre WHERE id = ?");
    if (!is_null($SCHULJAHR)) {
      $sql->bind_param('i', $SCHULJAHR);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
      }
    }
    if (!is_null($SCHULJAHRNEU)) {
      $sql->bind_param('i', $SCHULJAHRNEU);
      if ($sql->execute()) {
        $sql->bind_result($anzahl);
        if ($sql->fetch()) {if ($anzahl == 1) {$sjfehler = false;}}
      }
      $sql->close();
    }
  }

  $code .= "<h2>Teilschritte</h2>";
  $code .= "<p>";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_grundlagen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Grundlagen\">Grundlagen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_profile cms_button_ja\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Profile\">Profile</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_gruppenschueler\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Schüler_in_Gruppen\">Schüler in Gruppen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_klassenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Klassenkurse\">Klassenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_stufenkurse\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Stufenkurse\">Stufenkurse</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_kurspersonen\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Personen_in_Kursen\">Personen in Kursen</a> ";
  $code .= "<a class=\"cms_iconbutton cms_schuljahrfabrik_lehrauftraege\" href=\"Schulhof/Verwaltung/Planung/Schuljahrfabrik/Lehraufträge\">Lehraufträge</a> ";
  $code .= "</p>";
  echo $code;

  if (is_null($SCHULJAHR) || is_null($SCHULJAHRNEU) ||$SCHULJAHR == 'null' || $SCHULJAHRNEU == 'null') {
    echo cms_meldung('info', '<h4>Informationen fehlen</h4><p>Bitte wählen Sie ein Stammschuljahr und ein Zielschuljahr aus.</p>');
    $sjfehler = true;
  }

  if (!$sjfehler) {
    if ($SCHULJAHR == $SCHULJAHRNEU) {
      echo cms_meldung('info', '<h4>Schuljahrauswahl ungültig</h4><p>Das Stammschuljahr und das Zielschuljahr sind identisch.</p>');
      $sjfehler = true;
    }
  }
  $code = "";

  if (!$sjfehler) {

    $code = "<h2>Profile anlegen</h2>";

    // Alte Profile laden
    $sql = $dbs->prepare("SELECT * FROM (SELECT profile.id, art, AES_DECRYPT(profile.bezeichnung, '$CMS_SCHLUESSEL') AS pbezeichnung, AES_DECRYPT(stufen.bezeichnung, '$CMS_SCHLUESSEL') AS sbezeichnung, reihenfolge, stufen.id AS sid FROM profile JOIN stufen ON profile.stufe = stufen.id WHERE profile.schuljahr = ?) AS x WHERE sbezeichnung IN (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') FROM stufen WHERE schuljahr = ?) ORDER BY reihenfolge ASC, pbezeichnung ASC");

    $ALTEPROFILE = array();
    $sql->bind_param("ii", $SCHULJAHR, $SCHULJAHRNEU);
    if ($sql->execute()) {
      $sql->bind_result($pid, $part, $ppbez, $psbez, $preihenfolge, $pstufe);
      while ($sql->fetch()) {
        $PROFIL = array();
        $PROFIL['altid'] = $pid;
        $PROFIL['art'] = $part;
        $PROFIL['profilbez'] = $ppbez;
  			$PROFIL['altstufe'] = $pstufe;
  			$PROFIL['neustufe'] = null;
        $PROFIL['stufenbez'] = $psbez;
        $PROFIL['stufenrei'] = $preihenfolge;
        $PROFIL['faecher'] = array();
        array_push($ALTEPROFILE, $PROFIL);
      }
    }
    $sql->close();

    // Fächer zu den alten Profilen laden
    $sql = $dbs->prepare("SELECT id, AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL'), AES_DECRYPT(kuerzel, '$CMS_SCHLUESSEL') FROM faecher JOIN profilfaecher ON faecher.id = profilfaecher.fach WHERE profilfaecher.profil = ?");
    for ($i=0; $i < count($ALTEPROFILE); $i++) {
      $sql->bind_param("i", $ALTEPROFILE[$i]['altid']);
      if ($sql->execute()) {
        $sql->bind_result($fid, $fbez, $fkur);
        while ($sql->fetch()) {
          $FACH = array();
          $FACH['altid'] = $fid;
          $FACH['neuid'] = null;
          $FACH['fachbez'] = $fbez;
          $FACH['fachkur'] = $fkur;
          array_push($ALTEPROFILE[$i]['faecher'], $FACH);
        }
      }
    }
    $sql->close();

    // Prüfen, welche der Fächer es im neuen Schuljahr gibt
    $sql = $dbs->prepare("SELECT id FROM faecher WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND kuerzel = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
    for ($i=0; $i < count($ALTEPROFILE); $i++) {
      for ($f=0; $f < count($ALTEPROFILE[$i]['faecher']); $f++) {
        $sql->bind_param("ssi", $ALTEPROFILE[$i]['faecher'][$f]['fachbez'], $ALTEPROFILE[$i]['faecher'][$f]['fachkur'], $SCHULJAHRNEU);
        if ($sql->execute()) {
          $sql->bind_result($fid);
          if ($sql->fetch()) {
            $ALTEPROFILE[$i]['faecher'][$f]['neuid'] = $fid;
          }
        }
      }
    }
    $sql->close();

  	// Prüfen, welche der Stufen es im neuen Schuljahr gibt
  	$sql = $dbs->prepare("SELECT id FROM stufen WHERE bezeichnung = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND schuljahr = ?");
    for ($i=0; $i < count($ALTEPROFILE); $i++) {
  		$sql->bind_param("si", $ALTEPROFILE[$i]['stufenbez'], $SCHULJAHRNEU);
  		if ($sql->execute()) {
  			$sql->bind_result($sid);
  			if ($sql->fetch()) {
  				$ALTEPROFILE[$i]['neustufe'] = $sid;
  			}
  		}
  	}
  	$sql->close();

    // Profile ausgeben deren Fächer alle in der Tabelle vorhanden sind
    $profilecode = "";
    $profileids = "";
    foreach ($ALTEPROFILE AS $p) {
      $profilok = true;
      $faecher = "";

      if (is_null($p['neustufe'])) {$profilok = false;}

      foreach ($p['faecher'] AS $f) {
        if (is_null($f['neuid'])) {$profilok = false;}
        $faecher .= ", ".$f['fachbez']." (".$f['fachkur'].")";
      }

      if (strlen($faecher) > 0) {$faecher = substr($faecher, 2);}

      if ($profilok) {
        if ($p['art'] == 'p') {$buttonbez = "Pflichtprofil ".$p['stufenbez'].": ".$p['profilbez']." – ".$faecher;}
        else {$buttonbez = "Wahlprofil ".$p['stufenbez'].": ".$p['profilbez']." – ".$faecher;}
        $profilecode .= cms_togglebutton_generieren ("cms_sjfabrik_profile_".$p['altid'], $buttonbez, 1)." ";
        $profileids .= "|".$p['altid'];
      }
    }

    $code = "<h3>Profile</h3>";
    $code .= "<p>Die folgenden Profile werden im neuen Schuljahr angelegt:</p>";
    $code .= "<p><input type=\"hidden\" name=\"cms_sjfabrik_profile\" id=\"cms_sjfabrik_profile\" value=\"$profileids\">";
    if (strlen($profilecode) > 0) {$code .= $profilecode;}
    else {$code .= "<span class=\"cms_notiz\">keine Profile verfügbar</span>";}
    $code .= "</p>";
    echo $code;


    $code = "<h2>Übernehmen der Profile abschließen</h2>";
    $code .= cms_meldung('warnung', '<h4>Achtung! Viele Änderungen auf einmal</h4><p>Diese Funktion nimmt viele Änderungen vor, die nicht am Stück sondern nur einzeln rückgängig gemacht werden können. Diese Funktion sollte nicht unter Stress genutzt werden.</p><p>Am Einfachsten wäre im Fehlerfall die Löschung des gesamten neuen Schuljahrs und ein Neustart dieses Prozesses.</p><p><b>Alle bereits bestehenden Profile im Zielschuljahr, werden mit dem Abschluss dieses Schrittes gelöscht.</b></p>');
    $code .= "<p><span class=\"cms_button_wichtig\" onclick=\"cms_schuljahrfabrik_profile();\">+ Schuljahrfabrik – Profile</span> <a class=\"cms_button_nein\" href=\"Schulhof/Verwaltung\">Abbrechen</a></p>";
  }
}
else {
  $code .= "<h1>Schuljahrfabrik</h1>".cms_meldung_berechtigung();
}

echo $code;
?>
</div>
<div class="cms_clear"></div>
