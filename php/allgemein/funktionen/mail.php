<?php
function cms_mailsenden($empfaenger, $mailempfaenger, $betreff, $text, $textPlain = false, $signatur = true, $antwort = false) {
	global $CMS_MAIL, $CMS_WICHTIG;

	if (($CMS_MAIL['SMTP-Authentifizierung'] == 1) || ($CMS_MAIL['SMTP-Authentifizierung'] == 'true')) {$CMS_MAIL['SMTP-Authentifizierung'] = true;}
	else {$CMS_MAIL['SMTP-Authentifizierung'] = false;}

	// Vorbereitungen treffen
	$umschlag = new PHPMailer();
	$umschlag->CharSet  = 'utf-8';
	$umschlag->IsSMTP();
  $umschlag->Host     = $CMS_MAIL['SMTP-Host'];
	$umschlag->SMTPAuth = $CMS_MAIL['SMTP-Authentifizierung'];
	$umschlag->Username = $CMS_MAIL['Benutzername'];
	$umschlag->Password = $CMS_MAIL['Passwort'];
    $umschlag->From     = $CMS_MAIL['Absender'];
    $umschlag->FromName = $CMS_WICHTIG['Schulname']." ".$CMS_WICHTIG['Schule Ort']." Schulhof";
	if ($antwort) {
		$umschlag->AddReplyTo($CMS_WICHTIG[$antwort], "Schulhof ".$CMS_WICHTIG['Schulname']." ".$CMS_WICHTIG['Schule Ort']);
	}
	$umschlag->AddAddress($mailempfaenger, $empfaenger);
	$umschlag->Subject = $betreff." – ".$CMS_WICHTIG['Schulname']." ".$CMS_WICHTIG['Schule Ort']." Schulhof";
	$umschlag->IsHTML(true);

	// HTML-Nachricht zusammenbauen
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
	$HTML .= $text;
	if ($signatur) {$HTML .= $CMS_MAIL['Signatur HTML'];}
	$HTML .= "</div>";
	$HTML .= "</body>";
	$HTML .= "</html>";
	$umschlag->Body = $HTML;

	// HTML in Plain umwandeln
	if (!$textPlain) {
		$plain = str_replace("<p>", "", $text);
		$plain = str_replace("</p>", "<br>", $plain);
		$plain = str_replace("<i>", "›", $plain);
		$plain = str_replace("</i>", "‹", $plain);
		$plain = str_replace("<b>", "»", $plain);
		$plain = str_replace("</b>", "«", $plain);
	}
	else {
		$plain = $textPlain;
	}
	if ($signatur) {$plain .= $CMS_MAIL['Signatur Text'];}
	$umschlag->AltBody  =  $plain;

	return $umschlag->Send();
}

function cms_mail_anrede($titel, $vorname, $nachname, $art, $geschlecht) {
	if ($geschlecht == "m") {
		if ($art == "s") {$anrede = "Lieber ".$vorname.",";}
		else {
			if (strlen($titel) > 0) {$anrede = "Sehr geehrter Herr ".$titel." ".$nachname.",";}
			else {$anrede = "Sehr geehrter Herr ".$nachname.",";}
		}
	}
	else if ($geschlecht == "w") {
		if ($art == "s") {$anrede = "Liebe ".$vorname.",";}
		else {
			if (strlen($titel) > 0) {$anrede = "Sehr geehrte Frau ".$titel." ".$nachname.",";}
			else {$anrede = "Sehr geehrte Frau ".$nachname.",";}
		}
	}
	else {
		if ($art == "s") {$anrede = "Liebes ".$vorname.",";}
		else {
			if (strlen($titel) > 0) {$anrede = "Sehr geehrtes ".$titel." ".$vorname." ".$nachname.",";}
			else {$anrede = "Sehr geehrtes ".$vorname." ".$nachname.",";}
		}
	}
	return $anrede;
}
?>
