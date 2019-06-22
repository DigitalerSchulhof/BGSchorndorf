<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Besucherstatistiken - Website (Galerien)</h1>
<p>
    <?php
      include_once "php/schulhof/seiten/website/besucherstatistiken/website/auswerten.php";
      echo cms_besucherstatistik_website_jahresplaettchen('g');
      if($CMS_RECHTE['Website']['Besucherstatistiken - Website sehen']) {
        echo "<br>Balkendiagramm:";
        echo " <span id='cms_besucherstatistik_website_geloescht_toggle' class='cms_toggle' onclick='cms_besucherstatistik_website_geloescht_toggle(\"g\")'>Gelöschte Galerien ausblenden</span>";
      }
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
    $code .= cms_besucherstatistik_website("g", "gesamtaufrufe_linie");
    $code .= cms_besucherstatistik_website("g", "bereiche_balken");

    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
