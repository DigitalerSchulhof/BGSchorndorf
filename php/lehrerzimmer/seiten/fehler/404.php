<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_MODUS, $CMS_BEREICH, $CMS_SEITE, $CMS_ZUSATZ); ?></p>

<h1>Fehler 404</h1>

<?php
$code = "";
$code .= cms_meldung('info', '<h4>Seite nicht gefunden</h4><p>Die gesuchte Seite existiert nicht oder nicht mehr. Das kann mehrere Gründe haben:</p><ul><li>Der verwendete Link ist veraltet.</li><li>Im Link liegt ein Tippfehler vor.</li></ul>');
$code .= '<p><a class="cms_button" href="Website/Start">zur Website</a> <a class="cms_button" href="Schulhof/Nutzerkonto">zum Nutzerkonto</a></p>';
echo $code;
?>

</div>
