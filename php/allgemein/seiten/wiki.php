<?php
	if(is_null($CMS_WIKI)) {

	}

	if(!is_null($CMS_WIKI) && $CMS_WIKI !== false) {
		echo "<span id=\"cms_wiki\" class=\"cms_aktion_klein\" onclick=\"cms_link('$CMS_WIKI', true)\" style=\"padding: 2px;\"><span class=\"cms_hinweis cms_hinweis_links\" style=\"bottom: 45px;\">Hilfe</span><img src=\"res/icons/gross/wiki.png\"></span>";
	}
?>
