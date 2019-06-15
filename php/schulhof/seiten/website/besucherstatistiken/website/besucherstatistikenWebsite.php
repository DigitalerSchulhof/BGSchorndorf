<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Besucherstatistiken - Website (Übersicht)</h1>
<p>
    <?php
      include_once "php/schulhof/seiten/website/besucherstatistiken/website/auswerten.php";
      echo cms_besucherstatistik_website_jahresplaettchen('w');
    ?>
</p>
<div id="besucherstatistik">
</div>
<?php
  $zugriff = $CMS_RECHTE['Website']['Besucherstatistiken - Website sehen'];
  if (!$zugriff) {
    echo cms_meldung_berechtigung();
  } else {
    $code = "";
    $code .= cms_besucherstatistik_website("w", "gesamtaufrufe_linie");
    $code .= cms_besucherstatistik_website("w", "bereiche_balken");

    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
