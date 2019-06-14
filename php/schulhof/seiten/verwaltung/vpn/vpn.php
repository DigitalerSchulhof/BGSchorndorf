<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>VPN-Anleitung gestalten</h1>

<?php
$zugriff = $CMS_RECHTE['Administration']['VPN verwalten'];

if ($zugriff) {



}
else {
	cms_meldung_berechtigung();
}

?>
</div>
