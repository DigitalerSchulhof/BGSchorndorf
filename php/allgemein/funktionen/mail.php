<?php
function cms_mailsenden($empfaenger, $mailempfaenger, $betreff, $textHTML, $text) {
	global $CMS_MAILHOST, $CMS_MAILSMTPAUTH, $CMS_MAILUSERNAME, $CMS_MAILPASSWORT, $CMS_MAILABSENDER, $CMS_SCHULE, $CMS_ORT, $CMS_WEBMASTER, $CMS_DOMAIN, $CMS_LOGO;
	set_time_limit(0);
	$umschlag = new PHPMailer();
	$umschlag->CharSet  = 'utf-8';
	$umschlag->IsSMTP();
    $umschlag->Host     = $CMS_MAILHOST;
	$umschlag->SMTPAuth = $CMS_MAILSMTPAUTH;
	$umschlag->Username = $CMS_MAILUSERNAME;
	$umschlag->Password = $CMS_MAILPASSWORT;
    $umschlag->From     = $CMS_MAILABSENDER;
    $umschlag->FromName = $CMS_SCHULE." ".$CMS_ORT." Schulhof";
	$umschlag->AddReplyTo($CMS_WEBMASTER, "Webmaster Schulhof ".$CMS_SCHULE." ".$CMS_ORT);
	$umschlag->AddAddress($mailempfaenger, $empfaenger);
	$umschlag->Subject = $betreff;
	$umschlag->IsHTML(true);

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
	$HTML .= $textHTML;
	$HTML .= "</div>";

	$HTML .= "</body>";
	$HTML .= "</html>";

	$umschlag->Body = $HTML;
	$umschlag->AltBody  =  $text;
	return $mailerfolg = $umschlag->Send();
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
