<div class="cms_spalte_i">
<p class="cms_brotkrumen"><?php echo cms_brotkrumen($CMS_URL); ?></p>

<h1>VPN-Anleitung gestalten</h1>

<?php
if (r("technik.server.vpn")) {



}
else {
	cms_meldung_berechtigung();
}

?>
</div>
