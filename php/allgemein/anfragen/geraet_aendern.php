<?php
if (isset($_POST['geraet'])) {$geraet = $_POST['geraet'];} else {echo "FEHLER"; exit;}

session_start();
if (isset($_SESSION['DSGVO_COOKIESAKZEPTIERT'])) {
  if ($_SESSION['DSGVO_COOKIESAKZEPTIERT']) {
    if ($geraet == 'H') {$_SESSION['GERAET'] = 'H';}
    else if ($geraet == 'T') {$_SESSION['GERAET'] = 'T';}
    else {$_SESSION['GERAET'] = 'P';}
    echo "ERFOLG";
  }
  else {echo "DATENSCHUTZ";}
}
else {echo "DATENSCHUTZ";}
?>
