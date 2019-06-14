<?php
session_start();
if (isset($_SESSION['DOWNLOADPFAD'])) {
	if (preg_match("/\.\./", $_SESSION['DOWNLOADPFAD']) == 1) {echo "FEHLER"; exit;}
	$pfad = '../../../../'.$_SESSION['DOWNLOADPFAD'];
} else {echo "FEHLER"; exit;}
if (isset($_SESSION['DOWNLOADNAME'])) {$name = $_SESSION['DOWNLOADNAME'];} else {echo "FEHLER"; exit;}
if (isset($_SESSION['DOWNLOADGROESSE'])) {$groesse = $_SESSION['DOWNLOADGROESSE'];} else {echo "FEHLER"; exit;}



if ((is_file($pfad)) && (substr($pfad, 20, 7) == 'website')) {
	header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename="'.$name.'"');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: '.$groesse);
  readfile($pfad);

	/*header("Content-Type: application/force-download");
	header("Content-Length: ".$groesse);
	header("Content-Disposition: attachment; filename=".$name);
	readfile($pfad);*/
}
else  {
	echo "FEHLER";
}
?>
