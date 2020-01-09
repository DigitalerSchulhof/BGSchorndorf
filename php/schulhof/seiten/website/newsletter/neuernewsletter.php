<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neuer Newsletter</h1>

<?php
  if(!cms_r("schulhof.information.newsletter.anlegen"))
    echo cms_meldung_berechtigung();
  else if(!isset($_SESSION["NEWSLETTERID"]) || !isset($_SESSION["NEWSLETTERZIEL"]))
      echo cms_meldung_bastler();
    else {
      include_once("php/schulhof/seiten/website/newsletter/details.php");
      include_once("php/schulhof/seiten/verwaltung/gruppen/zuordnungen.php");

      echo cms_newsletter_details_laden($_SESSION["NEWSLETTERID"], $_SESSION["NEWSLETTERZIEL"]);
    }
?>


<div class="cms_clear"></div>
