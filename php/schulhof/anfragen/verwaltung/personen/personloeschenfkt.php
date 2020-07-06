<?php
function cms_verwaltung_personloeschen ($dbs, $dbp, $id) {
	global $CMS_SCHLUESSEL;
	if (!cms_check_ganzzahl($id, 0)) {return "FEHLER";}

	$fehler = false;
	$nutzerkonto = false;

	// Zu löschende Person laden
	$sql = $dbs->prepare("SELECT AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht, AES_DECRYPT(email, '$CMS_SCHLUESSEL') AS email, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art FROM personen LEFT JOIN nutzerkonten ON personen.id = nutzerkonten.id WHERE personen.id = ?");
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($titel, $vorname, $nachname, $geschlecht, $email, $art);
    if ($sql->fetch()) {if (!is_null($email)) {$nutzerkonto = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	$adminfehler = false;
	// Prüfen, ob es sich bei dem Löschvorgang um den letzten Administrator handelt
	$sql = $dbs->prepare("SELECT person FROM rollenzuordnung JOIN nutzerkonten ON person = nutzerkonten.id WHERE rolle = 0");
	if ($sql->execute()) {
		$anzahl = 0;
		$admindabei = false;
		// Anzahl der Administratoren ermitteln
		// Prüfen, ob der zu löschende Nutzer ein Administrator ist
		$sql->bind_result($admin);
		while ($sql->fetch()) {
			$anzahl++;
			if ($admin == $id) {
				$admindabei = true;
			}
		}
		if (($anzahl == 1) && ($admindabei)) {
			$adminfehler = true;
		}
	}
	else {$adminfehler = true;}
	$sql->close();

	if ($adminfehler) {
		return "ADMINFEHLER";
	}

	if ($fehler) {return "FEHLER";}

	// Anhang-Ordner des Postfachs löschen
	if (strlen($id) > 0) {
		if (is_dir('../../../dateien/schulhof/personen/'.$id)) {cms_dateisystem_ordner_loeschen('../../../dateien/schulhof/personen/'.$id);}
	}

	$sql = $dbp->prepare("SET FOREIGN_KEY_CHECKS = 0;");
	$sql->execute();
	$sql = $dbp->prepare("DROP TABLE postausgang_".$id.", posteingang_".$id.", postentwurf_".$id.", postgetaggedausgang_".$id.", postgetaggedeingang_".$id.", postgetaggedentwurf_".$id.", posttags_".$id.", termine_".$id.";");
	$sql->execute();

	$sql = $dbs->prepare("DELETE FROM personen WHERE id = ?");
	$sql->bind_param("i", $id);
	$sql->execute();
	$sql->close();

	if ($nutzerkonto) {
		$empfaenger = $email;
		$betreff = "Löschung Nutzerkonto";

		$anrede = cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht);

		$CMS_WICHTIG = cms_einstellungen_laden("wichtigeeinstellungen");
		$CMS_MAIL = cms_einstellungen_laden("maileinstellungen");

		$text = "<p>".$anrede."</p>";
		if ($art != 's') {
			$text .= "<p>Ihr Nutzerkonto wurde gelöscht!</p>";
			$text .= "<p>Diese Aktion kann nicht rückgängig gemacht werden. Falls Sie weiterhin ein Notzerkonto benötigen, muss ein neues Nutzerkonto angelegt werden. Kontaktieren Sie dazu bitte die Schule.</p>";
		}
		else {
			$text .= "<p>Dein Nutzerkonto wurde gelöscht!</p>";
			$text .= "<p>Diese Aktion kann nicht rückgängig gemacht werden. Falls Du weiterhin ein Notzerkonto benötigst, muss ein neues Nutzerkonto angelegt werden. Kontaktiere dazu bitte die Schule.</p>";
		}

		// Mail verschicken:
		$empfaenger = cms_generiere_anzeigename($vorname, $nachname, $titel);
		$mailerfolg = cms_mailsenden($empfaenger, $email, $betreff, $text);
	}
	return "ERFOLG";
}
?>
