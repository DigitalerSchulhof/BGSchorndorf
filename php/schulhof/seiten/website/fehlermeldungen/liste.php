<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<?php
  $zugriff = $CMS_RECHTE['Website']['Fehlermeldungen sehen'];
  if (!$zugriff) {
    echo cms_meldung_berechtigung();
  } else {
    $code = "";
    include_once "php/schulhof/seiten/website/fehlermeldungen/auswerten.php";
    $code .= cms_fehlermeldungen_liste();
    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
