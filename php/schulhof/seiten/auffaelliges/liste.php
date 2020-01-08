<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<?php
  if (!cms_r("schulhof.verwaltung.nutzerkonten.verstöße.auffälliges"))) {
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
