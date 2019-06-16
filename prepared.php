<?php
$sql = $dbs->prepare("SELECT COUNT(*) AS anzahl FROM ferien WHERE ? BETWEEN beginn AND ende ORDER BY bezeichnung ASC");
$sql->bind_param("i", intval($buchungstag));
if ($sql->execute()) {
  $sql->bind_result($anzahl);
  if ($sql->fetch()) {if ($anzahl > 0) {$ferien[$i] = true;}}
}
$sql->close();
?>
