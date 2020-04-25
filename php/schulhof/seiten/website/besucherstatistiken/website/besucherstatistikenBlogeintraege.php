<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Besucherstatistiken - Website (Blog)</h1>
<p>
    <?php
      include_once "php/schulhof/seiten/website/besucherstatistiken/website/auswerten.php";
      echo cms_besucherstatistik_website_jahresplaettchen('b');

      $code = "";
      $code .= cms_besucherstatistik_website("b", "gesamtaufrufe_linie");
      $code .= cms_besucherstatistik_website("b", "bereiche_balken");

      if(strlen($code) && cms_r("statistik.besucher.website.blogeinträge")) {
        echo "<br>Balkendiagramm:";
        echo " <span id='cms_besucherstatistik_website_geloescht_toggle' class='cms_toggle' onclick='cms_besucherstatistik_website_geloescht_toggle(\"b\")'>Gelöschte Blogeinträge ausblenden</span>";
      }
    ?>
</p>
<div id="besucherstatistik">
</div>
<?php
  if (!cms_r("statistik.besucher.website.blogeinträge")) {
    echo cms_meldung_berechtigung();
  } else {
    echo $code;
  }
  ?>
</div>
<div class="cms_clear"></div>
