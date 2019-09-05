<div id="cms_kopfzeile_o">

	<div id="cms_kopfzeile_m">
		<div id="cms_kopfzeile_i">

			<?php
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
				echo "<a id=\"cms_logo\" href=\"".$CMS_DOMAIN.($CMS_URL[0] == "Schulhof"?"/Schulhof/Nutzerkonto":"")."\">";
				// <span id="cms_logo_bild">
				// 	<span class="cms_logo_mauer" id="cms_logo_mauer_l"></span>
				// 	<span class="cms_logo_mauer" id="cms_logo_mauer_r"></span>
				// 	<span class="cms_logo_mauer" id="cms_logo_zinne_l1"></span>
				// 	<span class="cms_logo_mauer" id="cms_logo_zinne_l2"></span>
				// 	<span class="cms_logo_mauer" id="cms_logo_zinne_l3"></span>
				// 	<span class="cms_logo_mauer" id="cms_logo_zinne_r1"></span>
				// 	<span class="cms_logo_mauer" id="cms_logo_zinne_r2"></span>
				// 	<span class="cms_logo_mauer" id="cms_logo_zinne_r3"></span>
				// 	<span class="cms_logo_turm" id="cms_logo_turm_mu"></span>
				// 	<span class="cms_logo_schrift_bg" id="cms_logo_schrift_b">B</span>
				// 	<span class="cms_logo_schrift_bg" id="cms_logo_schrift_g">G</span>
				// 	<span class="cms_logo_turm" id="cms_logo_turm_mo"></span>
				// 	<span class="cms_logo_turm" id="cms_logo_zinne_m1"></span>
				// 	<span class="cms_logo_turm" id="cms_logo_zinne_m2"></span>
				// 	<span class="cms_logo_turm" id="cms_logo_zinne_m3"></span>
				// </span>

				echo "<img id=\"cms_logo_bild\" src=\"res/logos/$CMS_LOGO\">";
				echo "<span id=\"cms_logo_schrift\">";
					echo "<span id=\"cms_logo_o\">$CMS_SCHULE</span>";
					echo "<span id=\"cms_logo_u\">$CMS_ORT</span>";
				echo "</span>";
				echo "<div class=\"cms_clear\"></div>";
				echo "</a>";

			if ($CMS_ANGEMELDET && (($CMS_URL[0] == "Schulhof") || ($CMS_URL[0] == "Lehrerzimmer"))) {
				include_once('php/schulhof/seiten/navigation.php');
			}
			else {
				echo cms_navigation_ausgeben('h');
			}
			?>

			<div class="cms_clear"></div>
		</div>
	</div>
</div>
