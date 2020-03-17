<?php
include_once("../../schulhof/funktionen/texttrafo.php");
include_once("../../allgemein/funktionen/sql.php");
include_once("../../schulhof/funktionen/config.php");
include_once("../../schulhof/funktionen/check.php");
include_once("../../schulhof/funktionen/generieren.php");
include_once("../../allgemein/funktionen/mail.php");
include_once("../../schulhof/funktionen/dateisystem.php");

session_start();

// Variablen einlesen, falls übergeben
if (isset($_POST['idreg'])) {$idreg = $_POST['idreg'];} else {echo "FEHLER"; exit;}
if (isset($_POST['benutzername'])) {$benutzername = $_POST['benutzername'];} else {echo "FEHLER"; exit;}
if (isset($_POST['idper'])) {$idper = $_POST['idper'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($idreg,0)) {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($idper,0)) {echo "FEHLER"; exit;}
if (strlen($benutzername) < 6) {echo "FEHLER"; exit;}
$fehler = false;

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Nutzerkonten anlegen'];
if (cms_angemeldet() && $zugriff) {

	// Prüfen, ob der benutzer bereits besteht
	$dbs = cms_verbinden('s');
	// Prüfen, ob der Benutzername bereits vergeben ist
	$sql = $dbs->prepare("SELECT count(id) AS anzahl FROM nutzerkonten WHERE id = ?");
  $sql->bind_param("i", $idper);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl, AES_DECRYPT(titel, '$CMS_SCHLUESSEL'), AES_DECRYPT(vorname, '$CMS_SCHLUESSEL'), AES_DECRYPT(nachname, '$CMS_SCHLUESSEL'), AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL'), AES_DECRYPT(art, '$CMS_SCHLUESSEL') FROM personen WHERE id = ?");
	$sql->bind_param("i", $idper);
	if ($sql->execute()) {
    $sql->bind_result($anzahl, $titel, $vorname, $nachname, $geschlecht, $art);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	$sql = $dbs->prepare("SELECT count(id) AS anzahl, AES_DECRYPT(email, '$CMS_SCHLUESSEL'), AES_DECRYPT(salt, '$CMS_SCHLUESSEL'), passwort FROM nutzerregistrierung WHERE id = ?");
  $sql->bind_param("i", $idreg);
  if ($sql->execute()) {
    $sql->bind_result($anzahl, $mail, $salt, $passwort);
    if ($sql->fetch()) {if ($anzahl != 1) {$fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();

	cms_trennen($dbs);


	if (!$fehler) {
		// PERSON EINTRAGEN
		$dbs = cms_verbinden('s');

		$jetzt = time();
		// Aktuelles Schuljahr suchen
		$schuljahr = "-";
		$sql = $dbs->prepare("SELECT id FROM schuljahre WHERE beginn <= ? AND ende > ?");
	  $sql->bind_param("ii", $jetzt, $jetzt);
	  if ($sql->execute()) {
	    $sql->bind_result($schuljahr);
	    $sql->fetch();
	  }
	  $sql->close();

		$id = $idper;

		// PASSWORT GENERIEREN
		$sql = $dbs->prepare("INSERT INTO nutzerkonten (id, benutzername, passwort, passworttimeout, salt, sessionid, sessiontimeout, schuljahr, email, letzteanmeldung, vorletzteanmeldung, erstellt, notizen, letztenotifikation) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), ?, 0, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), '', null, ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), null, null, ?, '', ?)");
	  $sql->bind_param("isssisii", $id, $benutzername, $passwort, $salt, $schuljahr, $mail, $jetzt, $jetzt);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("INSERT INTO personen_signaturen (person, signatur) VALUES (?, AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
	  $sql->bind_param("i", $id);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("DELETE FROM nutzerregistrierung WHERE id = ?");
	  $sql->bind_param("i", $idreg);
	  $sql->execute();
	  $sql->close();

		if (is_dir("../../../dateien/schulhof/personen/".$id)) {
			cms_dateisystem_ordner_loeschen("../../../dateien/schulhof/personen/".$id);
		}
		mkdir("../../../dateien/schulhof/personen/".$id, 0775);
		mkdir("../../../dateien/schulhof/personen/".$id."/postfach", 0775);
		mkdir("../../../dateien/schulhof/personen/".$id."/postfach/temp", 0775);
		mkdir("../../../dateien/schulhof/personen/".$id."/postfach/eingang", 0775);
		mkdir("../../../dateien/schulhof/personen/".$id."/postfach/ausgang", 0775);
		mkdir("../../../dateien/schulhof/personen/".$id."/postfach/entwuerfe", 0775);

		$dbp = cms_verbinden('p');
		$sql = $dbp->prepare("CREATE TABLE postausgang_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			absender bigint(255) UNSIGNED NULL DEFAULT NULL,
			empfaenger text COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			zeit bigint(255) UNSIGNED DEFAULT NULL,
			betreff varbinary(5000) DEFAULT NULL,
			nachricht longblob DEFAULT NULL,
			papierkorb varbinary(50) DEFAULT NULL,
			papierkorbseit bigint(255) DEFAULT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("CREATE TABLE posteingang_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			absender bigint(255) UNSIGNED NULL DEFAULT NULL,
			empfaenger bigint(255) UNSIGNED NULL DEFAULT NULL,
			alle text COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			zeit bigint(255) UNSIGNED DEFAULT NULL,
			betreff varbinary(5000) DEFAULT NULL,
			nachricht longblob DEFAULT NULL,
			gelesen varbinary(50) DEFAULT NULL,
			papierkorb varbinary(50) DEFAULT NULL,
			papierkorbseit bigint(255) UNSIGNED NULL DEFAULT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("CREATE TABLE postentwurf_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			absender bigint(255) UNSIGNED NULL DEFAULT NULL,
			empfaenger text COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
			zeit bigint(255) UNSIGNED DEFAULT NULL,
			betreff varbinary(5000) DEFAULT NULL,
			nachricht longblob DEFAULT NULL,
			papierkorb varbinary(50) DEFAULT NULL,
			papierkorbseit bigint(255) UNSIGNED NULL DEFAULT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("CREATE TABLE postgetaggedausgang_".$id." (
			tag bigint(255) UNSIGNED NOT NULL,
			nachricht bigint(255) UNSIGNED NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("CREATE TABLE postgetaggedeingang_".$id." (
			tag bigint(255) UNSIGNED NOT NULL,
			nachricht bigint(255) UNSIGNED NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("CREATE TABLE postgetaggedentwurf_".$id." (
			tag bigint(255) UNSIGNED NOT NULL,
			nachricht bigint(255) UNSIGNED NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("CREATE TABLE posttags_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			person bigint(255) UNSIGNED NULL DEFAULT NULL,
			titel varbinary(2000) DEFAULT NULL,
			farbe int(2) NOT NULL DEFAULT 0,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("CREATE TABLE termine_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			person bigint(255) UNSIGNED NULL DEFAULT NULL,
			bezeichnung varbinary(5000) DEFAULT NULL,
			ort varbinary(5000) DEFAULT NULL,
			beginn bigint(255) UNSIGNED NULL DEFAULT NULL,
			ende bigint(255) UNSIGNED NULL DEFAULT NULL,
			mehrtaegigt varbinary(50) DEFAULT NULL,
			uhrzeitbt varbinary(50) DEFAULT NULL,
			uhrzeitet varbinary(50) DEFAULT NULL,
			ortt varbinary(50) DEFAULT NULL,
			text longblob DEFAULT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postausgang_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY nachrichtengesendetpersonen (absender);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE posteingang_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY nachrichteneingangpersonen (empfaenger);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postentwurf_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY nachrichtenentwurf (absender);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postgetaggedausgang_".$id."
		ADD UNIQUE KEY tag (tag,nachricht),
		ADD KEY nachrichtposttaggedausgang_".$id." (nachricht);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postgetaggedeingang_".$id."
		ADD UNIQUE KEY tag (tag,nachricht),
		ADD KEY nachrichtposttaggedeingang_".$id." (nachricht);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postgetaggedentwurf_".$id."
		ADD UNIQUE KEY tag (tag,nachricht),
		ADD KEY nachrichtposttaggedentwurf_".$id." (nachricht);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE posttags_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY postfachtagspersonen (person);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE termine_".$id."
		ADD KEY personentermine_".$id." (person);");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postausgang_".$id."
		ADD CONSTRAINT personpostausgang_".$id." FOREIGN KEY (absender) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE posteingang_".$id."
		ADD CONSTRAINT personeinposteingang_".$id." FOREIGN KEY (empfaenger) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postentwurf_".$id."
		ADD CONSTRAINT personpostentwurf_".$id." FOREIGN KEY (absender) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postgetaggedausgang_".$id."
		ADD CONSTRAINT nachrichtposttaggedausgang_".$id." FOREIGN KEY (nachricht) REFERENCES postausgang_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE,
		ADD CONSTRAINT tagposttaggedausgang_".$id." FOREIGN KEY (tag) REFERENCES posttags_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postgetaggedeingang_".$id."
		ADD CONSTRAINT nachrichtposttaggedeingang_".$id." FOREIGN KEY (nachricht) REFERENCES posteingang_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE,
		ADD CONSTRAINT tagposttaggedeingang_".$id." FOREIGN KEY (tag) REFERENCES posttags_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE postgetaggedentwurf_".$id."
		ADD CONSTRAINT nachrichtposttaggedentwurf_".$id." FOREIGN KEY (nachricht) REFERENCES postentwurf_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE,
		ADD CONSTRAINT tagposttaggedentwurf_".$id." FOREIGN KEY (tag) REFERENCES posttags_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE posttags_".$id."
		ADD CONSTRAINT personenposttags_".$id." FOREIGN KEY (person) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		$sql = $dbp->prepare("ALTER TABLE termine_".$id."
		ADD CONSTRAINT personentermine_".$id." FOREIGN KEY (person) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;");
		$sql->execute();

		cms_trennen($dbp);

		// PASSWORT VERSCHICKEN
		$empfaenger = $mail;
		$betreff = $CMS_SCHULE.' '.$CMS_ORT.' Schulhof - Neues Nutzerkonto';

		$anrede = cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht);

		$text = array();
		for ($i=0; $i<2; $i++) {
			$text[$i] = $anrede.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Es wurde ein neues Nutzerkonto erstellt. Hier sind die Zugangsdaten:'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Benutzername: '.$benutzername.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'eMailadresse: '.$mail.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Die Anmeldung kann unter '.$CMS_DOMAIN.'/Schulhof/Anmeldung vorgenommen werden.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Das Passwort ist das, das bei der Registrierung eingegeben wurde.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Viel Spaß mit dem neuen Konto!'.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$CMS_MAILSIGNATUR[$i];
		}

		require_once '../../phpmailer/PHPMailerAutoload.php';

		// Mail verschicken:
		$mailerfolg = cms_mailsenden($anrede, $mail, $betreff, $text[1], $text[0]);

		cms_trennen($dbs);
		echo "ERFOLG";
	}
	else {
		echo "FEHLER";
	}
}
else {
	echo "BERECHTIGUNG";
}
?>
