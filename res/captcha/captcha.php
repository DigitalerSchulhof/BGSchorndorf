<?php
session_start();

function cms_zufallstext($laenge) {
  $auswahlzeichen = "abdefhijkmnpqrtyABDEFGHJKLMNPRTY2345678";
  $text = array();
  while(count($text) < $laenge) {
    $stelle = rand(1,strlen($auswahlzeichen));
    array_push($text, substr($auswahlzeichen,$stelle-1,1));
  }
  return($text);
}

if (isset($_SESSION['SPAMSCHUTZERLAUBNIS'])) {
  //if ($_SESSION['SPAMSCHUTZERLAUBNIS']) {
    unset($_SESSION['SPAMSCHUTZ']);
    $text = cms_zufallstext(5);
    $_SESSION['SPAMSCHUTZ'] = implode('', $text);

    header('Content-type: image/png');
    $captchanr = rand(0,5);
    $bild = ImageCreateFromPNG("captcha$captchanr.PNG");
    $schrift = "../fonts/roboto-b.ttf";
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
    imagepng($bild);
    imagedestroy($bild);
    $_SESSION['SPAMSCHUTZERLAUBNIS'] = false;
  //}
}
?>
