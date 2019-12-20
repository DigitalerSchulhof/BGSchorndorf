<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Newsletter ansehen</h1>
</div>

<?php
  if(!$CMS_RECHTE["Website"]["Newsletter bearbeiten"] && !$CMS_RECHTE["Website"]["Newsletter Empfängerliste sehen"])
    echo cms_meldung_berechtigung();
  else if(!isset($_SESSION["NEWSLETTERID"]) || !isset($_SESSION["NEWSLETTERZIEL"]))
    echo cms_meldung_bastler();
  else {
    include_once("php/schulhof/seiten/website/newsletter/details.php");
    include_once("php/schulhof/seiten/verwaltung/gruppen/zuordnungen.php");

    $newsletteransehen = true;
    echo cms_newsletter_details_laden($_SESSION["NEWSLETTERID"], $_SESSION["NEWSLETTERZIEL"]);
  }
?>

<div class="cms_clear"></div>
