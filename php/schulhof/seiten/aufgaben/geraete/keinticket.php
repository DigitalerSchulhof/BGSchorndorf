<?php
$code = '';
$code .= '<div class="cms_spalte_i">';
$code .= '<p class="cms_brotkrumen">'.cms_brotkrumen($CMS_URL).'</p>';
$code .= '<h1>Externe Problembehebung</h1>';
$code .= cms_meldung('warnung', '<h4>Kein Ticket</h4><p>Zur externen Problemlösung ist ein Ticket notwendig. Es wurde kein Ticket übermittelt.</p>');
$code .= '<p><a class="cms_button" href="Website">zur Website</a></p>';
$code .= '</div>';
echo $code;
?>
