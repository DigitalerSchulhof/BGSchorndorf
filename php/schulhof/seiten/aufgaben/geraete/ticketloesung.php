<?php
$dbs = cms_verbinden('s');
$ticket = substr($CMS_URL[1], 7);
$sql = $dbs->prepare("UPDATE leihengeraete SET statusnr = 5, kommentar = AES_ENCRYPT('', '$CMS_SCHLUESSEL'), ticket = '' WHERE ticket = ?");
$sql->bind_param("s", $ticket);
$sql->execute();
$sql->close();

$sql = $dbs->prepare("UPDATE raeumegeraete SET statusnr = 5, kommentar = AES_ENCRYPT('', '$CMS_SCHLUESSEL'), ticket = '' WHERE ticket = ?");
$sql->bind_param("s", $ticket);
$sql->execute();
$sql->close();
cms_trennen($dbs);

$code = '';
$code .= '<div class="cms_spalte_i">';
$code .= '<p class="cms_brotkrumen">'.cms_brotkrumen($CMS_URL).'</p>';
$code .= '<h1>Externe Problembehebung</h1>';
$code .= '<p>Vielen Dank für die Lösung des Problems. Das Gerät wurde wieder freigegeben.</p>';
$code .= '<p><a class="cms_button" href="Website">zur Website</a></p>';
$code .= '</div>';
echo $code;
?>
