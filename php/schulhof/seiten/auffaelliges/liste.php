<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<?php
  $zugriff = $CMS_RECHTE['Website']['Auffälliges sehen'] || $CMS_RECHTE['Website']['Auffälliges verwalten'];
  if (!$zugriff) {
    echo cms_meldung_berechtigung();
  } else {
    $code = "";
    include_once "php/schulhof/seiten/auffaelliges/auswerten.php";
    $code .= cms_auffaellig_liste();
    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
