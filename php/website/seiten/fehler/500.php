<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Fehler 500</h1>

<?php
$code = "";
$code .= cms_meldung('info', '<h4>Interner Serverfehler</h4><p>Der Server ist momentan nicht erreichbar. Bitte versuchen Sie es zu einem späteren Zeitpunkt erneut.</p>');
$code .= '<p><a class="cms_button" href="">zur Website</a></p>';
echo $code;
?>

</div>
