<?php
  $sql = $dbs->prepare("SQL");
  $sql->bind_param("i", $p1);
  $sql->bind_result($e1, $e2);
  if ($sql->execute()) {
    if ($sql->fetch()) {
      shwubb($e1, $e2);
    }
  }
  $sql->close();
?>
