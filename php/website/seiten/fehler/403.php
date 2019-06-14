<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>Fehler 403</h1>

<?php
$code = "";
$code .= cms_meldung('fehler', '<h4>Zugriff verweigert!</h4><p>Die gesuchte Seite darf nicht angezeigt werden. Dies kann folgende Gründe haben:</p><ul><li>Es besteht keine Berechtigung die Seite zu sehen.</li><li>Im Browser sind Cookies deaktiviert.</li></ul>');
$code .= '<p><a class="cms_button" href="Website/Start">zur Website</a></p>';
echo $code;
?>

</div>
