<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Besucherstatistiken - Website (Übersicht)</h1>
<p>
    <?php
      include_once "php/schulhof/seiten/website/besucherstatistiken/website/auswerten.php";
      echo cms_besucherstatistik_website_jahresplaettchen('w');
      if(cms_r("statistik.besucher.website.seiten"))) {
        echo "<br>Balkendiagramm: <span id='cms_besucherstatistik_website_startseite_ausblenden' class='cms_toggle' onclick='cms_besucherstatistik_website_startseite_toggle(\"w\")'>Startseite ausblenden</span>";
        echo " <span id='cms_besucherstatistik_website_geloescht_toggle' class='cms_toggle' onclick='cms_besucherstatistik_website_geloescht_toggle(\"w\")'>Gelöschte Seiten ausblenden</span>";
      }
    ?>
</p>
<div id="besucherstatistik">
</div>
<?php
  if (!cms_r("statistik.besucher.website.seiten"))) {
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
