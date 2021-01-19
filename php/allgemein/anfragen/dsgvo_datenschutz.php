<?php
if (isset($_POST['fenster'])) {$fenster = $_POST['fenster'];} else {echo "FEHLER"; exit;}
if (isset($_POST['einwilligungA'])) {$einwilligungA = $_POST['einwilligungA'];} else {echo "FEHLER"; exit;}
if (isset($_POST['einwilligungB'])) {$einwilligungB = $_POST['einwilligungB'];} else {echo "FEHLER"; exit;}

session_start();
if ($fenster == 'j') {$_SESSION['DSGVO_FENSTERWEG'] = true;}
if ($fenster == 'n') {$_SESSION['DSGVO_FENSTERWEG'] = false;}
if ($einwilligungA == 'j') {$_SESSION['DSGVO_EINWILLIGUNG_A'] = true;}
if ($einwilligungA == 'n') {$_SESSION['DSGVO_EINWILLIGUNG_A'] = false;}
if ($einwilligungB == 'j') {$_SESSION['DSGVO_EINWILLIGUNG_B'] = true;}
if ($einwilligungB == 'n') {$_SESSION['DSGVO_EINWILLIGUNG_B'] = false;}

?>
