<?php
$CMS_SESSIONID  = $sessionid;
$CMS_IV 		= substr($sessionid, 0, 16);
$CMS_BENUTZERID	= openssl_decrypt ($nutzerid, 'aes128', $CMS_IV, 0, $CMS_IV);
$CMS_SESSIONTIMEOUT = 0;
$CMS_BENUTZERTITEL = null;
$CMS_BENUTZERNAME = null;
$CMS_BENUZTERVORNAME = null;
$CMS_BENUZTERNACHNAME = null;
$CMS_BENUTZERART = null;
?>
