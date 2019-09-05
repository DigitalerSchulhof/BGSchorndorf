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
if (isset($_POST['benutzername'])) {$benutzername = $_POST['benutzername'];} else {echo "FEHLER"; exit;}
if (isset($_POST['email'])) {$mail = $_POST['email'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['PERSONENDETAILS'])) {$id = $_SESSION['PERSONENDETAILS'];} else {echo "FEHLER"; exit;}
if (!cms_check_ganzzahl($id,0)) {echo "FEHLER"; exit;}

$CMS_RECHTE = cms_rechte_laden();
$zugriff = $CMS_RECHTE['Personen']['Nutzerkonten anlegen'];

if (cms_angemeldet() && $zugriff) {

	// Zusammenbauen der Bedingung
	$sqlwhere = '';
	$fehler = false;

	// Pflichteingaben prüfen
	if (strlen($benutzername) < 6) {
		$fehler = true;
	}

	if (!cms_check_mail($mail)) {
		$fehler = true;
	}

	// Prüfen, ob Benutzername bereits existiert
	$dbs = cms_verbinden('s');
	$benutzername = cms_texttrafo_e_db($benutzername);
	$mail = cms_texttrafo_e_db($mail);
	// Prüfen, ob der Benutzername bereits vergeben ist
	$sql = $dbs->prepare("SELECT count(id) AS anzahl FROM nutzerkonten WHERE benutzername = AES_ENCRYPT(?, '$CMS_SCHLUESSEL') AND id != ?");
  $sql->bind_param("si", $benutzername, $id);
  if ($sql->execute()) {
    $sql->bind_result($anzahl);
    if ($sql->fetch()) {if ($anzahl != 0) {echo "DOPPELT"; $fehler = true;}}
		else {$fehler = true;}
  }
  else {$fehler = true;}
  $sql->close();


	// Zugehöriges Konto laden
	$sql = $dbs->prepare("SELECT AES_DECRYPT(titel, '$CMS_SCHLUESSEL') AS titel, AES_DECRYPT(vorname, '$CMS_SCHLUESSEL') AS vorname, AES_DECRYPT(nachname, '$CMS_SCHLUESSEL') AS nachname, AES_DECRYPT(art, '$CMS_SCHLUESSEL') AS art, AES_DECRYPT(geschlecht, '$CMS_SCHLUESSEL') AS geschlecht FROM personen WHERE id = ?");
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($titel, $vorname, $nachname, $art, $geschlecht);
    if (!$sql->fetch()) {$fehler = true;}
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

		// PASSWORT GENERIEREN
		// 24 Stunden in Sekunden später läuft das Passwort ab
		$passwort = cms_generiere_passwort();
		$passworttimeout = $jetzt + 24*60*60;

		$salt = cms_generiere_passwort().cms_generiere_passwort();

		$salt = cms_texttrafo_e_db($salt);
		$passwortsalted = $passwort.$salt;
		$passwortsalted = cms_texttrafo_e_db($passwortsalted);

		$sql = $dbs->prepare("INSERT INTO nutzerkonten (id, benutzername, passwort, passworttimeout, salt, sessionid, sessiontimeout, schuljahr, email, letzteanmeldung, vorletzteanmeldung, erstellt, notizen, letztenotifikation) VALUES (?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), SHA1(?), passworttimeout = ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), '', '', ?, AES_ENCRYPT(?, '$CMS_SCHLUESSEL'), 0, 0, ?, '', ?)");
	  $sql->bind_param("issisisii", $id, $benutzername, $passwortsalted, $passworttimeout, $salt, $schuljahr, $mail, $jetzt, $jetzt);
	  $sql->execute();
	  $sql->close();

		$sql = $dbs->prepare("INSERT INTO personen_signaturen (person, signatur) VALUES (?, AES_ENCRYPT('', '$CMS_SCHLUESSEL'))");
	  $sql->bind_param("i", $id);
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
		$sql = "CREATE TABLE postausgang_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			absender bigint(255) UNSIGNED NOT NULL,
			empfaenger text COLLATE utf8_unicode_ci NOT NULL,
			zeit bigint(255) UNSIGNED NOT NULL,
			betreff varbinary(5000) NOT NULL,
			nachricht longblob NOT NULL,
			papierkorb varbinary(50) NOT NULL,
			papierkorbseit bigint(255) UNSIGNED NOT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "CREATE TABLE posteingang_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			absender bigint(255) UNSIGNED NOT NULL,
			empfaenger bigint(255) UNSIGNED NOT NULL,
			alle text COLLATE utf8_unicode_ci NOT NULL,
			zeit bigint(255) UNSIGNED NOT NULL,
			betreff varbinary(5000) NOT NULL,
			nachricht longblob NOT NULL,
			gelesen varbinary(50) NOT NULL,
			papierkorb varbinary(50) NOT NULL,
			papierkorbseit bigint(255) UNSIGNED NOT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "CREATE TABLE postentwurf_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			absender bigint(255) UNSIGNED NOT NULL,
			empfaenger text COLLATE utf8_unicode_ci NOT NULL,
			zeit bigint(255) UNSIGNED NOT NULL,
			betreff varbinary(5000) NOT NULL,
			nachricht longblob NOT NULL,
			papierkorb varbinary(50) NOT NULL,
			papierkorbseit bigint(255) UNSIGNED NOT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "CREATE TABLE postgetaggedausgang_".$id." (
			tag bigint(255) UNSIGNED NOT NULL,
			nachricht bigint(255) UNSIGNED NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "CREATE TABLE postgetaggedeingang_".$id." (
			tag bigint(255) UNSIGNED NOT NULL,
			nachricht bigint(255) UNSIGNED NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "CREATE TABLE postgetaggedentwurf_".$id." (
			tag bigint(255) UNSIGNED NOT NULL,
			nachricht bigint(255) UNSIGNED NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "CREATE TABLE posttags_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			person bigint(255) UNSIGNED NOT NULL,
			titel varbinary(2000) NOT NULL,
			farbe int(2) NOT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "CREATE TABLE termine_".$id." (
			id bigint(255) UNSIGNED NOT NULL,
			person bigint(255) UNSIGNED NOT NULL,
			bezeichnung varbinary(5000) NOT NULL,
			ort varbinary(5000) NOT NULL,
			beginn bigint(255) UNSIGNED NOT NULL,
			ende bigint(255) UNSIGNED NOT NULL,
			mehrtaegigt varbinary(50) NOT NULL,
			uhrzeitbt varbinary(50) NOT NULL,
			uhrzeitet varbinary(50) NOT NULL,
			ortt varbinary(50) NOT NULL,
			text longblob NOT NULL,
			idvon bigint(255) UNSIGNED DEFAULT NULL,
			idzeit bigint(255) UNSIGNED DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";
		$dbp->query($sql);

		$sql = "ALTER TABLE postausgang_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY nachrichtengesendetpersonen (absender);";
		$dbp->query($sql);

		$sql = "ALTER TABLE posteingang_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY nachrichteneingangpersonen (empfaenger);";
		$dbp->query($sql);

		$sql = "ALTER TABLE postentwurf_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY nachrichtenentwurf (absender);";
		$dbp->query($sql);

		$sql = "ALTER TABLE postgetaggedausgang_".$id."
		ADD UNIQUE KEY tag (tag,nachricht),
		ADD KEY nachrichtposttaggedausgang_".$id." (nachricht);";
		$dbp->query($sql);

		$sql = "ALTER TABLE postgetaggedeingang_".$id."
		ADD UNIQUE KEY tag (tag,nachricht),
		ADD KEY nachrichtposttaggedeingang_".$id." (nachricht);";
		$dbp->query($sql);

		$sql = "ALTER TABLE postgetaggedentwurf_".$id."
		ADD UNIQUE KEY tag (tag,nachricht),
		ADD KEY nachrichtposttaggedentwurf_".$id." (nachricht);";
		$dbp->query($sql);

		$sql = "ALTER TABLE posttags_".$id."
		ADD PRIMARY KEY (id),
		ADD KEY postfachtagspersonen (person);";
		$dbp->query($sql);

		$sql = "ALTER TABLE termine_".$id."
		ADD KEY personentermine_".$id." (person);";
		$dbp->query($sql);

		$sql = "ALTER TABLE postausgang_".$id."
		ADD CONSTRAINT personpostausgang_".$id." FOREIGN KEY (absender) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

		$sql = "ALTER TABLE posteingang_".$id."
		ADD CONSTRAINT personposteingang_".$id." FOREIGN KEY (empfaenger) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

		$sql = "ALTER TABLE postentwurf_".$id."
		ADD CONSTRAINT personpostentwurf_".$id." FOREIGN KEY (absender) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

		$sql = "ALTER TABLE postgetaggedausgang_".$id."
		ADD CONSTRAINT nachrichtposttaggedausgang_".$id." FOREIGN KEY (nachricht) REFERENCES postausgang_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE,
		ADD CONSTRAINT tagposttaggedausgang_".$id." FOREIGN KEY (tag) REFERENCES posttags_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

		$sql = "ALTER TABLE postgetaggedeingang_".$id."
		ADD CONSTRAINT nachrichtposttaggedeingang_".$id." FOREIGN KEY (nachricht) REFERENCES posteingang_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE,
		ADD CONSTRAINT tagposttaggedeingang_".$id." FOREIGN KEY (tag) REFERENCES posttags_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

		$sql = "ALTER TABLE postgetaggedentwurf_".$id."
		ADD CONSTRAINT nachrichtposttaggedentwurf_".$id." FOREIGN KEY (nachricht) REFERENCES postentwurf_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE,
		ADD CONSTRAINT tagposttaggedentwurf_".$id." FOREIGN KEY (tag) REFERENCES posttags_".$id." (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

		$sql = "ALTER TABLE posttags_".$id."
		ADD CONSTRAINT personenposttags_".$id." FOREIGN KEY (person) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

		$sql = "ALTER TABLE termine_".$id."
		ADD CONSTRAINT personentermine_".$id." FOREIGN KEY (person) REFERENCES $CMS_DBS_DB.personen (id) ON DELETE CASCADE ON UPDATE CASCADE;";
		$dbp->query($sql);

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
			$text[$i] = $text[$i].'Passwort: '.$passwort.$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'E-Mail-Adresse: '.$mail.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].'Die Anmeldung kann unter '.$CMS_DOMAIN.'/Schulhof/Anmeldung vorgenommen werden.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$CMS_MAILWV[$i].'Achtung!'.$CMS_MAILWH[$i].' Dieses Passwort ist aus Sicherheitsgründen ab jetzt nur '.$CMS_MAILWV[$i].'24 Stunden'.$CMS_MAILWH[$i].' gültig. Verstreicht diese Zeit, ohne dass eine Änderung am Passwort vorgenommen wurde, muss bei der Anmeldung über '.$CMS_MAILHV[$i].'Passwort vergessen?'.$CMS_MAILHH[$i].' ein neues Passwort angefordert werden. Dazu werden die Angaben '.$CMS_MAILHV[$i].'Benutzername'.$CMS_MAILHH[$i].' und '.$CMS_MAILHV[$i].'E-Mail-Adresse'.$CMS_MAILHH[$i].' benötigt. Das neue Passwort ist dann auch nur eine Stunde gültig.'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
			$text[$i] = $text[$i].$CMS_MAILWV[$i].'Kurz:'.$CMS_MAILWH[$i].' Das Passwort sollte sobald wie möglich geändert werden!!'.$CMS_MAILZ[$i].$CMS_MAILZ[$i];
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
