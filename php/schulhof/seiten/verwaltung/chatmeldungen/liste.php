<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<?php
  $zugriff = $CMS_RECHTE['Gruppen']['Chatmeldungen sehen'] || $CMS_RECHTE['Gruppen']['Chatmeldungen verwalten'];
  if (!$zugriff) {
    echo cms_meldung_berechtigung();
  } else {
    $code = "";
    include_once "php/schulhof/seiten/verwaltung/chatmeldungen/auswerten.php";
    $code .= cms_chatmeldungen_liste();
    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
