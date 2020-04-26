<div id="cms_kopfzeile_o">

	<div id="cms_kopfzeile_m">
		<div id="cms_kopfzeile_i">

			<?php
				if ($CMS_URL[0] != "App") {
					echo "<ul class=\"cms_kopfnavigation\">";
						if ($CMS_URL[0] == "Website") {
							echo "<li><div class=\"cms_websitesuche\"><input type=\"text\" placeholder=\"Suchen ...\" name=\"cms_websitesuche_pc_suchbegriff\" id=\"cms_websitesuche_pc_suchbegriff\" onkeyup=\"cms_websuche_suchen('cms_websitesuche_pc_suchbegriff', 'cms_websitesuche_pc_ergebnisse')\">";

							echo "<div id=\"cms_websitesuche_pc_ergebnisse\">";
							  echo "<span class=\"cms_button_nein cms_websitesuche_schliessen\" onclick=\"cms_websuche_schliessen('cms_websitesuche_pc_suchbegriff', 'cms_websitesuche_pc_ergebnisse')\">×</span>";
								echo "<div id=\"cms_websitesuche_pc_ergebnisse_inhalt\">";
									echo "<p class=\"cms_notiz\">Bitte warten...</p>";
								echo "</div>";
							echo "</div></div></li>";
							echo "<li><a class=\"cms_button cms_button_aktiv\" href=\"Website\">Website</a></li>";
						}
						else {
							echo "<li><a class=\"cms_button\" href=\"Website\">Website</a></li>";
						}

						if (($CMS_URL[0] == "Schulhof") || ($CMS_URL[0] == "Lehrerzimmer")) {
							echo "<li><a class=\"cms_button cms_button_aktiv\" href=\"Schulhof/Nutzerkonto\">Schulhof</a></li>";
						}
						else {
							echo "<li><a class=\"cms_button\" href=\"Schulhof/Nutzerkonto\">Schulhof</a></li>";
						}
					echo "</ul>";
				}

				if ($CMS_URL[0] != 'App') {
					echo "<a id=\"cms_logo\" href=\"".$CMS_DOMAIN.($CMS_URL[0] == "Schulhof"?"/Schulhof/Nutzerkonto":"")."\">";
				}
				else {
					echo "<a id=\"cms_logo\" href=\"".$CMS_DOMAIN."/App\">";
				}

				echo "<img id=\"cms_logo_bild\" src=\"res/logos/$CMS_LOGO\">";
				echo "<span id=\"cms_logo_schrift\">";
					echo "<span id=\"cms_logo_o\">$CMS_SCHULE</span>";
					echo "<span id=\"cms_logo_u\">$CMS_ORT</span>";
				echo "</span>";
				echo "<div class=\"cms_clear\"></div>";
				echo "</a>";

			if ($CMS_URL[0] != "App") {
				if ($CMS_ANGEMELDET && (($CMS_URL[0] == "Schulhof") || ($CMS_URL[0] == "Lehrerzimmer"))) {
					include_once('php/schulhof/seiten/navigation.php');
				}
				else {
					echo cms_navigation_ausgeben('h');
				}
			}
			else {
				if ($CMS_ANGEMELDET) {
					echo "<span id=\"cms_appnavigation\" onclick=\"cms_einblenden('cms_appmenue_a')\"><span class=\"cms_menuicon\"></span><span class=\"cms_menuicon\"></span><span class=\"cms_menuicon\"></span></span>";
					echo "<span id=\"cms_appzurueck\" class=\"cms_link\" onclick=\"window.history.back();\">&larr; zurück</span>";
					include_once('php/app/seiten/menue.php');
					echo "<div id=\"cms_appmenue_a\">".cms_appmenue()."<span id=\"cms_appmenue_schliessen\" onclick=\"cms_ausblenden('cms_appmenue_a')\">&times;</span>";
					echo "<p id=\"cms_app_impressum\">Verantwortlich für die Verarbeitung von Daten in dieser App ist das Land Baden-Württemberg vertreten durch<br>$CMS_NAMESCHULLEITER • $CMS_SCHULE<br>$CMS_STRASSE • $CMS_PLZORT<br><br>";
				  echo "Die verwendeten Icons stammen von Fatcow und wurden unter der Lizenz Creative Commons Attribution 3.0 veröffentlicht.</p>";
					echo "</div>";
				}
			}

			?>

			<div class="cms_clear"></div>
		</div>
	</div>
</div>
