<?php
if (isset($_POST['navi'])) {$navi = $_POST['navi'];} else {exit;}

session_start();
if (isset($_SESSION['DSGVO_EINWILLIGUNG_A'])) {
  if ($_SESSION['DSGVO_EINWILLIGUNG_A']) {
    $_SESSION['MOBILNAVIGATION'] = "<div id=\"cms_mobilmenue_seiten\">".$navi."</div>";
  }
}
?>
