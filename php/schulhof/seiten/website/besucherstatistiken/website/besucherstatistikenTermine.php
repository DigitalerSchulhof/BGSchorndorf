<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Besucherstatistiken - Website (Termine)</h1>
<p>
    <?php
      include_once "php/schulhof/seiten/website/besucherstatistiken/website/auswerten.php";
      echo cms_besucherstatistik_website_jahresplaettchen('t');

      $code = "";
      $code .= cms_besucherstatistik_website("t", "gesamtaufrufe_linie");
      $code .= cms_besucherstatistik_website("t", "bereiche_balken");

      if(strlen($code) && cms_r("statistik.besucher.website.termine"))) {
        echo "<br>Balkendiagramm:";
        echo " <span id='cms_besucherstatistik_website_geloescht_toggle' class='cms_toggle' onclick='cms_besucherstatistik_website_geloescht_toggle(\"t\")'>Gelöschte Termine ausblenden</span>";
      }
    ?>
</p>
<div id="besucherstatistik">
</div>
<?php
  if (!cms_r("statistik.besucher.website.termine"))) {
    echo cms_meldung_berechtigung();
  } else {
    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
