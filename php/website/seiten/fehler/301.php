<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Fehler 301</h1>

<?php
$code = "";
$code .= cms_meldung('info', '<h4>Permanenter Umzug</h4><p>Die angeforderte Website steht unter dieser Adresse nicht mehr zur Verfügung.</p>');
$code .= '<p><a class="cms_button" href="">zur Website</a></p>';
echo $code;
?>

</div>
