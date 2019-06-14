<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>
<?php
  $zugriff = $CMS_RECHTE['Website']['Feedback sehen'];
  if (!$zugriff) {
    echo cms_meldung_berechtigung();
  } else {
    $code = "";
    include_once "php/schulhof/seiten/website/feedback/auswerten.php";
    $code .= cms_feedback_liste();
    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
