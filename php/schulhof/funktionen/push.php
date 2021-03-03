<?php

include_once(__DIR__ . "/../../vendor/autoload.php");

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

function cms_push() {
  $push = new WebPush([
    "VAPID" => [
      "subject" => "Digitaler Schulhof",
      "publicKey" => "BMkNl_l1GmaJIf3PSMW6tYn8ENpH-2f9qLbCwBS244Fuh_lDkHK5r8FzJwNcQNglbNZrkUDLBrRc\ncS01SkpCdEM",
      "privateKey" => "W2zjAtq_jJIGfDIBNCL4imWFct2uIjGRkVCRn1R0aqg"
    ]
  ]);
  $push->setReuseVAPIDHeaders(true);
  return $push;
}

function cms_push_hinzufuegen($dbs, $push, $nutzerid, $inhalt) {
  global $CMS_SCHLUESSEL;

  $sql = $dbs->prepare("SELECT AES_DECRYPT(endpoint, '$CMS_SCHLUESSEL'), AES_DECRYPT(p256dh, '$CMS_SCHLUESSEL'), AES_DECRYPT(auth, '$CMS_SCHLUESSEL') FROM pushendpoints WHERE nutzer = ?");
  $sql->bind_param("i", $nutzerid);
  $sql->bind_result($endpoint, $publicKey, $auth);
  $sql->execute();


  while($sql->fetch()) {
    $push->queueNotification(Subscription::create([
      "endpoint" => $endpoint,
      "publicKey" => $publicKey,
      "authToken" => $auth
    ]), json_encode($inhalt));
  }
  $sql->close();
}

function cms_push_senden($dbs, $push) {
  global $CMS_SCHLUESSEL;

  $abgelaufen = [];
  foreach ($push->flush() as $status) {
    if ($status->isSubscriptionExpired()) {
      $abgelaufen[] = $status->getEndpoint();
    }
  }

  if(count($abgelaufen)) {
    $sql = $dbs->prepare("DELETE FROM pushendpoints WHERE AES_DECRYPT(endpoint, '$CMS_SCHLUESSEL') IN ('" . implode("','", $abgelaufen) . "')");
    $sql->execute();
    $sql->close();
  }
}
