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

  if(!cms_check_ganzzahl(array($id, $empfaenger), 0))
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

  $sql = "SELECT aktiv, betreffaktuell as betreff, kopieaktuell as kopie, anhangaktuell as anhang FROM kontaktformulare WHERE id = $id";
  $sql = $dbs->query($sql);
  if(!$sql)
    die("FEHLER");
  if(!$sqld = $sql->fetch_assoc())
    die("FEHLER");

  sqlLesen($sqld, array("aktiv", "betreff", "kopie", "anhang"));

  $sql = "SELECT nameaktuell as name, mailaktuell as e_mail FROM kontaktformulareempfaenger WHERE id = $empfaenger AND kontaktformular = $id";
  $sql = $dbs->query($sql);
  if(!$sql)
    die("FEHLER");
  if(!$sqld = $sql->fetch_assoc())
    die("FEHLER");

  sqlLesen($sqld, array("name", "e_mail"));

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
  $mailer->FromName       = $CMS_SCHULE." ".$CMS_ORT;

  $mailer->AddReplyTo($CMS_WEBMASTER, "Webmaster Schulhof ".$CMS_SCHULE." ".$CMS_ORT);
	$mailer->AddAddress($e_mail, $name);

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
		$HTML .= "<a style=\"display:inline-block;text-decoration:none;font-size:inherit; text-align: left;\" href=\"$CMS_DOMAIN\">";
		  $HTML .= "<img style=\"float:left;padding-right:10px; color: #000000;\" src=\"$CMS_DOMAIN/res/logos/$CMS_LOGO\"/>";
	    $HTML .= "<span style=\"float:left;display:block; color: #000000;\">";
	      $HTML .= "<span style=\"font-weight:bold;font-size:22px;height:28px;padding:2px 0 0 0;display:block;line-height:1\">Burg-Gymnasium</span>";
	      $HTML .= "<span style=\"font-size:22px;height:28px;padding:2px 0 0 0;display:block;line-height:1\">Schorndorf</span>";
	    $HTML .= "</span>";
			$HTML .= "<div style=\"clear:both\"></div>";
	  $HTML .= "</a>";
	$HTML .= "</div>";

	$HTML .= "<div style=\"width:100%;padding: 10px;margin-bottom: 10px;box-sizing: border-box;\">";
    $HTML .= "<table style=\"width: 80%\">";
      $HTML .= "<tr><td>Absender: </td><td>$absender</td></tr>";
      $HTML .= "<tr><td>E-Mail-Adresse: </td><td>$mail</td></tr>";
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
  $ALT .= "E-Mail-Adresse: $mail\n";
  $ALT .= "Betreff: $b\n";
  $ALT .= "Kopie wurde an den Absender gesandt: ".($kopie?'Ja':'Nein')."\n";
  $ALT .= "\n";
  $ALT .= "Nachricht: $nachricht";
  $mailer->AltBody = $ALT;
  $mailer->send();
  if($kopie) {    // Anti-E-Mail-Adresse-Leak
    $mailer->Subject = "Kopie von: ".$betreff. " ".$b;
    $mailer->ClearAllRecipients();
    $mailer->AddAddress($mail, $absender);
    $mailer->send();
  }

  $mailer->SmtpClose();
  echo "ERFOLG";
?>
