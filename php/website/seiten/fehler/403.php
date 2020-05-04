<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Fehler 403</h1>

<?php
$code = "";
$code .= cms_meldung('info', '<h4>Zugriff verweigert</h4><p>Der Zugriff auf die gewünschte Seite wurde verweigert. Dies kann folgende Gründe haben:</p><ul><li>Es handelt sich um eine systeminterne Seite.</li></ul>');
$code .= '<p><a class="cms_button" href="">zur Website</a></p>';
echo $code;
?>

</div>
