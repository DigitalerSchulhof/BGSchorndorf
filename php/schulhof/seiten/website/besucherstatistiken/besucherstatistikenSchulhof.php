<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Besucherstatistiken - Schulhof</h1>
<p>
    <?php
      include_once "php/schulhof/seiten/website/besucherstatistiken/auswerten.php";
      echo cms_besucherstatistik_schulhof_jahresplaettchen();
    ?>
</p>
<div id="besucherstatistik">
</div>
<?php
  $zugriff = $CMS_RECHTE['Website']['Besucherstatistiken - Schulhof sehen'];
  if (!$zugriff) {
    echo cms_meldung_berechtigung();
  } else {
    $code = "";
    $code .= cms_besucherstatistik_schulhof("gesamtaufrufe_linie");
    $code .= cms_besucherstatistik_schulhof("rollen_pie");
    $code .= cms_besucherstatistik_schulhof("bereiche_balken");

    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
