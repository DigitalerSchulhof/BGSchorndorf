<?php
  include_once("../../schulhof/funktionen/texttrafo.php");
  include_once("../../allgemein/funktionen/sql.php");
  include_once("../../allgemein/funktionen/mail.php");
  include_once("../../schulhof/funktionen/config.php");
  include_once("../../schulhof/funktionen/check.php");
  include_once("../../schulhof/funktionen/generieren.php");
  include_once("../../website/funktionen/positionen.php");
  require_once '../../phpmailer/PHPMailerAutoload.php';
  session_start();
  // Variablen einlesen, falls übergeben
  postLesen(array("id", "absender", "mail", "betreff", "nachricht", "anhaenge", "empfaenger", "uid", "code"));
  postLesen("kopie", false);

  $betreff = cms_texttrafo_e_db($betreff);
  $nachricht = cms_texttrafo_e_db($nachricht);

  $k = $kopie;  // Wird noch überschrieben werden
  $b = $betreff;  // Wird noch überschrieben werden

  if(!cms_check_ganzzahl($anhaenge, 0))
    die("FEHLER");
  $anhang = array();
  for ($i=0; $i < intval($anhaenge); $i++) {
    if(isset($_FILES["anhang_$i"]))
      array_push($anhang, $_FILES["anhang_$i"]);
    else
      die("FEHLER");
  }

  $anhaenge = $anhang; // Wird noch überschrieben werden

  if(!cms_check_ganzzahl($empfaenger, 0))
    die("FEHLER");
  if(!cms_check_nametitel($absender))
    die("FEHLER");
  if(!cms_check_mail($mail))
    die("FEHLER");
  if(!$nachricht)
    die("FEHLER");

  if (isset($_SESSION['SPAMSCHUTZ_'.$uid])) {$codevergleich = $_SESSION['SPAMSCHUTZ_'.$uid];} else {echo "FEHLER"; exit;}
  unset($_SESSION['SPAMSCHUTZ_'.$uid]);

  if ($code != $codevergleich) {echo "CODE"; exit;}

  $dbs = cms_verbinden("s");

  $sql = $dbs->prepare("SELECT aktiv, betreffaktuell as betreff, kopieaktuell as kopie, anhangaktuell as anhang FROM kontaktformulare WHERE id = ?");
  $sql->bind_param("i", $id);
  if ($sql->execute()) {
    $sql->bind_result($aktiv, $betreff, $kopie, $anhang);
    if (!$sql->fetch()) {
      $sql->close();
      echo "FEHLER"; exit;
    }
  }
  else {echo "FEHLER"; exit;}
  $sql->close();


  $sql = $dbs->prepare("SELECT name, mail as email FROM kontaktformulareempfaenger WHERE id = ? AND kontaktformular = ?");
  $sql->bind_param("ii", $empfaenger, $id);
  if ($sql->execute()) {
    $sql->bind_result($name, $email);
    if (!$sql->fetch()) {
      $sql->close();
      echo "FEHLER"; exit;
    }
  }
  else {echo "FEHLER"; exit;}
  $sql->close();

  if(!$aktiv)
    die("FEHLER");

  // Magic lol
  if($kopie == 2)
    $kopie = $k;

  if(!cms_check_toggle($kopie))
    die("FEHLER");

  $mailer = new PHPMailer();
  $mailer->CharSet = 'utf-8';
  $mailer->isSMTP();
  $mailer->Host           = $CMS_MAILHOST;
	$mailer->SMTPAuth       = $CMS_MAILSMTPAUTH;
	$mailer->Username       = $CMS_MAILUSERNAME;
	$mailer->Password       = $CMS_MAILPASSWORT;
  $mailer->From           = $CMS_MAILABSENDER;
  $mailer->FromName       = $CMS_WICHTIG['Schulname']." ".$CMS_WICHTIG['Schule Ort'];

  $mailer->AddReplyTo($CMS_WICHTIG['Webmaster Mail'], "Webmaster Schulhof ".$CMS_WICHTIG['Schulname']." ".$CMS_WICHTIG['Schule Ort']);
	$mailer->AddAddress($email, $name);

  $mailer->IsHTML(true);

  $mailer->Subject = $betreff. " ".$b;

  if (count($anhaenge))
    if($anhang)
        foreach ($anhaenge as $i => $a)
            $mailer->AddAttachment($a["tmp_name"], $a["name"]);
    else
      die("FEHLER");

  $HTML = "<html>";
	$HTML .= "<body style=\"background: #ffffff;font-family: sans-serif;font-size: 13px;font-weight: normal;padding: 0;margin: 0;list-style-type: none;line-height: 1.2em;text-decoration: none;box-sizing: border-box;\">";
	$HTML .= "<div style=\"width:100%;padding: 10px;margin-bottom: 10px; border-bottom: 3px solid #000000;text-align: left;box-sizing: border-box;\">";
		$HTML .= "<a style=\"display:inline-block;text-decoration:none;font-size:inherit; text-align: left;\" href=\"".$CMS_WICHTIG['Schule Domain']."\">";
		  $HTML .= "<img style=\"float:left;padding-right:10px; color: #000000;\" src=\"".$CMS_WICHTIG['Schule Domain']."/dateien/schulspezifisch/logo.png\"/>";
	    $HTML .= "<span style=\"float:left;display:block; color: #000000;\">";
	      $HTML .= "<span style=\"font-weight:bold;font-size:22px;height:28px;padding:2px 0 0 0;display:block;line-height:1\">".$CMS_WICHTIG['Schulname']."</span>";
	      $HTML .= "<span style=\"font-size:22px;height:28px;padding:2px 0 0 0;display:block;line-height:1\">".$CMS_WICHTIG['Schule Ort']."</span>";
	    $HTML .= "</span>";
			$HTML .= "<div style=\"clear:both\"></div>";
	  $HTML .= "</a>";
	$HTML .= "</div>";

	$HTML .= "<div style=\"width:100%;padding: 10px;margin-bottom: 10px;box-sizing: border-box;\">";
    $HTML .= "<table style=\"width: 80%\">";
      $HTML .= "<tr><td>Absender: </td><td>$absender</td></tr>";
      $HTML .= "<tr><td>eMailadresse: </td><td>$mail</td></tr>";
      $HTML .= "<tr><td>Betreff: </td><td>$b</td></tr>";
      $HTML .= "<tr><td>Kopie wurde an den Absender gesandt: </td><td>".($kopie?'Ja':'Nein')."</td></tr>";
      $HTML .= "<tr><td></td></tr>";  // Ganz kleine Lücke
      $HTML .= "<tr><td>Nachricht: </td><td>".nl2br($nachricht)."</td></tr>";
    $HTML .= "</table>";
	$HTML .= "</div>";

	$HTML .= "</body>";
	$HTML .= "</html>";
  $mailer->Body = $HTML;
  $ALT = "Absender: $absender\n";
  $ALT .= "eMailadresse: $mail\n";
  $ALT .= "Betreff: $b\n";
  $ALT .= "Kopie wurde an den Absender gesandt: ".($kopie?'Ja':'Nein')."\n";
  $ALT .= "\n";
  $ALT .= "Nachricht: $nachricht";
  $mailer->AltBody = $ALT;
  $mailer->send();
  if($kopie) {    // Anti-eMailadresse-Leak
    $mailer->Subject = "Kopie von: ".$betreff. " ".$b;
    $mailer->ClearAllRecipients();
    $mailer->AddAddress($mail, $absender);
    $mailer->send();
  }

  $mailer->SmtpClose();
  echo "ERFOLG";
?>
