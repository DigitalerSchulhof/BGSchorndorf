<?php

function cms_zufallstext($laenge) {
  $auswahlzeichen = "abdefhijkmnpqrtyABDEFGHJKLMNPRTY2345678";
  $text = array();
  while(count($text) < $laenge) {
    $stelle = rand(1,strlen($auswahlzeichen));
    array_push($text, substr($auswahlzeichen,$stelle-1,1));
  }
  return($text);
}

function cms_captcha_generieren($id = "") {
  do {
    $uid = uniqid();
  } while(isset($_SESSION["SPAMSCHUTZ_".$uid]));
  unset($_SESSION["SPAMSCHUTZ_".$uid]);
  $text = cms_zufallstext(5);
  $_SESSION["SPAMSCHUTZ_".$uid] = implode('', $text);

  $captchanr = rand(0,5);
  $bild = ImageCreateFromPNG(dirname(__FILE__)."/../../../res/captcha/captcha$captchanr.PNG");
  $schrift = dirname(__FILE__)."/../../../res/fonts/roboto-b.ttf";
  $schriftgroesse = 25;
  $x = -10;
  $y = 35;
  foreach ($text as $t) {
    $farbe1 = rand(1,255);
    $farbe2 = rand(1,255);
    $farbe3 = rand(1,255);
    while (($farbe1 + $farbe2 + $farbe3) < 550) {
      $farbe1 += 50;
      $farbe2 += 50;
      $farbe3 += 50;
      if ($farbe1 > 255) {$farbe1 = 255;}
      if ($farbe2 > 255) {$farbe2 = 255;}
      if ($farbe3 > 255) {$farbe3 = 255;}
    }
    $farbe = ImageColorAllocate($bild, $farbe1, $farbe2, $farbe3);
    $x += 30;
    $winkel = rand(-10,10);
    $abstandx = $x + rand(-5,5);
    $abstandy = $y + rand(-3,3);
    imagettftext($bild, $schriftgroesse, $winkel, $abstandx, $abstandy, $farbe, $schrift, $t);
  }
  ob_start();
  imagepng($bild);
  $b64 = ob_get_contents();
  ob_end_clean();
  $b64 = base64_encode($b64);
  imagedestroy($bild);
  return "<img id=\"$id\" src=\"data:image/png;base64, $b64\" class=\"cms_spamschutz\" style=\"float: left; margin-right: 10px;\" data-uuid=\"$uid\">";
}


?>
