<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Neuer Newsletter</h1>

<?php
  if(!$CMS_RECHTE["Website"]["Newsletter anlegen"])
    echo cms_meldung_berechtigung();
  else {
    include_once("php/schulhof/seiten/website/newsletter/details.php");
    include_once("php/schulhof/seiten/verwaltung/gruppen/zuordnungen.php");

    echo cms_newsletter_details_laden($_SESSION["NEWSLETTERID"], $_SESSION["NEWSLETTERZIEL"]);
  }
?>


<div class="cms_clear"></div>
