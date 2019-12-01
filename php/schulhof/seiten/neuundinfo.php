<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$code = '<ul class="cms_systemvoraussetzung">';
		$code .= '<li><img src="res/icons/gross/version.png"><br><b>Version '.$CMS_VERSION.'</b></li>';
		$code .= '<li><img src="res/icons/gross/cookies.png"><br>Cookies aktiv?</li>';
		$code .= '<li><img src="res/icons/gross/javascript.png"><br>JavaScript aktiv?</li>';
		$code .= '<li><img src="res/icons/gross/multinutzer.png"><br>Nur ein Nutzer pro Browser zur selben Zeit!</li>';
		$code .= '<li></li>';
		$code .= '<li id="cms_browsertest"><img src="res/icons/gross/warnung.png"><br>Der Browser untestützt womöglich nicht alle Funktionen.</li>';
	$code .= '</ul>';

	$code .= "<script>cms_check_browserunterstuetzung();</script>";
	echo $code;
	?>
</div>
</div>

<div class="cms_spalte_3">
<div class="cms_spalte_i">
	<?php
	$code = "<h2>Externe Links</h2>";
	$code .= "<h3>Schüler und Lehrer</h3>";
	//$code .= '<p><span class="cms_button_passiv">Dateien im Schulnetzwerk<span class="cms_hinweis">Aktuell liegt ein Serverfehler vor. Der Dienst ist bald wieder erreichbar.</span></span></p>';
	$code .= '<p><a class="cms_button" href="https://filr-schulen.schorndorf.de" target="_blank">Dateien im Schulnetzwerk</a></p>';
	$code .= '<p><a href="http://www.mitte.mensa-pro.de" class="cms_button" target="_blank">Buchungssystem der Mensa Mitte</a></p>';
	$code .= "<h3>Lehrer</h3>";
	$code .= '<p><a href="https://webmail.all-inkl.com/index.php" class="cms_button" target="_blank">Webmail-Portal für Lehrer<span class="cms_hinweis">Demnächst auch im Schulhof!<br> Ziel: Osterferien</span></a></p>';
	echo $code;
	?>
</div>
</div>

<div class="cms_clear"></div>


<div class="cms_spalte_i">
	<h2>Neuerungen</h2>
	<?php
	$aeltere = "";

	$code = "<h4>Version 0.5.7 - Samstag, den 29. November 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>In Listen erscheinen mit einem Rechtsklick Kontextmenüs zur Ausführung von Aktionen.</li>";
		$code .= "<li>Newsletter können angelegt werden. Über die Website kann man sich für Newsletter registieren. Per Link in einem Newsletter kann man sich wieder abmelden.</li>";
		$code .= "<li>Chats funktionieren jetzt basierend auf Sockets.</li>";
		$code .= "<li>Beim Anmelden in den Schulhof wird überprüft, ob der Browser unterstützt wird.</li>";
	$code .= "</ul>";
	echo cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_7', 'Neuerungen in Version 0.5.7 einblenden', 'Neuerungen in Version 0.5.6 ausblenden', $code, 1);

	$code = "<h4>Version 0.5.6 - Samstag, den 23. November 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>Anpassungen des Vertretungsplans.</li>";
		$code .= "<li>Einführung des Moduls »Mein Tag«.</li>";
		$code .= "<li>Geräteprobleme können nun direkt ins Hausmeisterbuch deligiert werden.</li>";
		$code .= "<li>Hausmeisteraufträge können nun auch eine Zieluhrzeit erhalten.</li>";
		$code .= "<li>Datenschutzrichtlinien wurden an die Vorgaben des Kultusministeriums angepasst.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_6', 'Neuerungen in Version 0.5.6 einblenden', 'Neuerungen in Version 0.5.6 ausblenden', $code, 0);

	$code = "<h4>Version 0.5.5 - Dienstag, den 05. November 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>Die Bedienbarkeit des Vertretungsplans im Schulhof wurde verbessert und neue Funktionen wurden hinzugefügt. Die Ausgabe auf Monitoren wurde bereitgestellt.</li>";
		$code .= "<li>Kleinere Fehler wurde behoben.</li>";
		$code .= "<li>Stundenpläne können aus Untis in den digitalen Schulhof importiert werden.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_5', 'Neuerungen in Version 0.5.5 einblenden', 'Neuerungen in Version 0.5.5 ausblenden', $code, 0);

	$code = "<h4>Version 0.5.4 - Freitag, den 06. September 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>Behebung diverser kleiner Fehler und Inkomatibilitäten.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_4', 'Neuerungen in Version 0.5.4 einblenden', 'Neuerungen in Version 0.5.4 ausblenden', $code, 0);

	$code = "<h4>Version 0.5.3 - Freitag, den 06. September 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>Die Dateiverschlüsselung wurde erheblich verbessert.</li>";
		$code .= "<li>Beim Upload von Dateien werden in Dateinamen Leerzeichen, Umlaute und »ß« automatisch ersetzt.</li>";
		$code .= "<li>Der Benutzertyp »extern« wurde für Personen hinzugefügt, die nur indirekt mit dem Schulbetrieb zu tun haben (z.B. Hausmeister, oder externe Dienstleister).</li>";
		$code .= "<li>Das Hausmeister-Auftragsbuch bietet die Möglichkeit, online Mängel und Wünsche an die Hausmeister zu richten.</li>";
		$code .= "<li>An Pinnwänden können Zettel auf bestimmte Zeit angepinnt werden.</li>";
		$code .= "<li>Dauerbrenner wurden eingeführt. Dabei handelt es sich um Seiten, die allgemeine Informationen enthalten, die ständig wiederkehren. Beispiele: Sicherheitshinweise, Abläufe für neue Kollegen etc.</li>";
		$code .= "<li>Interne Termine und intere Blogeinträge können nun im Genehmigungscenter genehemigt werden.</li>";
		$code .= "<li>Interne Termine und intere Blogeinträge unterscheiden sich nun optisch in den zugehörigen Gruppen (interne Inhalte erhalten ein Icon für »intern«).</li>";
		$code .= "<li>In Gruppen wird bei den Personen unterschieden zwischen »hat kein Nutzerkonto« (grau umrandet dargestellt), »hat ein Nutzerkonto, kann aber nicht per Postfach kontaktiert werden« (schwarz umrandet dargestellt) und »hat ein Nutzerkonto und kann kontaktiert werden« (normaler Button).</li>";
		$code .= "<li>Listen wurden dahingehend überarbeitet, dass Zugriffsrechte weiter gefächert erteilt werden können.</li>";
		$code .= "<li>Tab-Titel im Browser wurden um den Seitentitel ergänzt.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_3', 'Neuerungen in Version 0.5.3 einblenden', 'Neuerungen in Version 0.5.3 ausblenden', $code, 0);

	$code = "<h4>Version 0.5.2 - Dienstag, den 12. März 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>Raumbuchungen sind möglich. Räume können zentral blockiert werden.</li>";
		$code .= "<li>Die Buchung von Leihgeräten ist möglich. Leihgeräte können zentral blockiert werden.</li>";
		$code .= "<li>Das Meldesystem für defekte Geräte wurde überarbeitet und eine Übersicht für Infomonitore wurde ergänzt.</li>";
		$code .= "<li>Es kann für jede einzelne Gruppe entschieden werden, ob sich die Mitglieder schreiben dürfen.</li>";
		$code .= "<li>Es können Blogeinträge und Termine auch innerhalb von Gruppen angelegt werden, die nicht der Veröffentlichung dienen. Verknüpfte Dateien sind nicht über das Internet frei zugänglich. Ein Nutzerkonto ist nötig.</li>";
		$code .= "<li>Der Terminkalender wurde überarbeitet.</li>";
		$code .= "<li>Die Sicherheit des Systems wurde verbessert.</li>";
		$code .= "<li>Zugriffsstatistiken der Website können eingesehen werden.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_2', 'Neuerungen in Version 0.5.2 einblenden', 'Neuerungen in Version 0.5.2 ausblenden', $code, 0);

	$code = "<h4>Version 0.5.1 - Sonntag, den 17. Februar 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>Das Postfach wurde dahingehend überarbeitet, dass über die Einstellungen des Schulhofs gesteuert werden kann, welche Benutzergruppe welcher Benutzergruppe schreiben darf.</li>";
		$code .= "<li>Downloads sind auch bei Terminen möglich.</li>";
		$code .= "<li>Im Postfach können nur noch bis zu einer bestimmten Grenze an Speicher Daten abgelegt werden.</li>";
		$code .= "<li>Kleinere Bugs wurden behoben und die Darstellung wurde an einigen Stellen überarbeitet.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_1', 'Neuerungen in Version 0.5.1 einblenden', 'Neuerungen in Version 0.5.1 ausblenden', $code, 0);

	$code = "<h4>Version 0.5.0 - Sonntag, den 10. Februar 2019</h4>";
	$code .= "<ul>";
		$code .= "<li>Gruppen wurden vereinheitlicht und damit die Stabilität des Schulhofs erhöht.</li>";
		$code .= "<li>Termine wurden umgestaltet, sodass nun mehrere Kategorien einem Termin zugewisesen werden können. Außerdem können nun von ganzen Personengruppen Termine unter Vorbehalt erstellt werden.</li>";
		$code .= "<li>Blogeinträge wurden umgestaltet, sodass nun mehrere Kategorien einem Blogeintrag zugewisesen werden können. Außerdem können nun von ganzen Personengruppen Blogeinträge unter Vorbehalt erstellt werden.</li>";
		$code .= "<li>Termine können sich wiederholen.</li>";
		$code .= "<li>Innerhalb eines Tages dürfen keine zwei Termine/Blogeinträge mit gleicher Bezeichnung existieren. (Bisher innerhalb eines Monats.)</li>";
		$code .= "<li>Welcher Text bei Blogeinträgen in der Vorschau angezeigt wird, ist ab sofort von Hand einstellbar.</li>";
		$code .= "<li>Autoren von Blogeinträgen können veröffentlicht werden.</li>";
		$code .= "<li>Wenn Termine weitere Informationen als nur das Datum enthalten, wird dies nun durch »Mehr erfahren« angedeutet.</li>";
		$code .= "<li>Ferientermine werden nicht mehr als normale Termine dargesetllt, sondern in einem eigenen Ferienplaner.</li>";
		$code .= "<li>Auf der Startseite des Nutzerkontos können schnell Notizen gespeichert werden.</li>";
		$code .= "<li>Identitäsdiebstähle können direkt gemeldet werden, wenn bemerkt wird, dass die letzte Anmeldung nicht durch die eigene Person erfolgt ist.</li>";
		$code .= "<li>Neuigkeiten wurden überarbeitet und mit Links zum betroffenen Inhalt versehen. <b>Alte Neuigkeiten wurden gelöscht.</b></li>";
		$code .= "<li>Es kann eingesetllt werden, nach welcher Zeitspanne der Inaktivität sich das System automatisch abmeldet.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_5_0', 'Neuerungen in Version 0.5.0 einblenden', 'Neuerungen in Version 0.5.0 ausblenden', $code, 0);

	$code = "<h4>Version 0.4.4 - Sonntag, den 02. Dezember 2018</h4>";
	$code .= "</ul>";
		$code .= "<li>Die Sicherheit des Systems wurde weiter verbessert.</li>";
		$code .= "<li>In Postfächern sind Anhänge möglich.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_4_4', 'Neuerungen in Version 0.4.4 einblenden', 'Neuerungen in Version 0.4.4 ausblenden', $code, 0);

	$code = "<h4>Version 0.4.3 - Sonntag, den 25. November 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Auch die Kurrsstufe kann nun online ihren Vertretungsplan einsehen. (Testphase mit limitierter Schülerzahl)<br><b>Der Online-Vertretungsplan ist in der Testphase! Die ausgehängten Vertretungspläne haben Gültigkeit!!</b></li>";
		$code .= "<li>Lehrer können in Gremien und Fachschaften auch ohne VPN Material tauschen, sofern es keine Relevanz für die Verwaltung hat und keine personenbezogenen Daten enthält.</li>";
		$code .= "<li>Für Klassen und Kurse stehen Räume zum Datentausch bereit. Damit ist das »Moodle«-Modul nun auch im Schulhof enthalten. Blogeinträge und Chats folgen so bald wie möglich.</li>";
		$code .= "<li>Lehrkräfte sowie die Verwaltung haben Einsicht in die Stundenpläne aller Klassen, Lehrkräfte und Räume.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_4_3', 'Neuerungen in Version 0.4.3 einblenden', 'Neuerungen in Version 0.4.3 ausblenden', $code, 0);


	$code = "<h4>Version 0.4.2 - Sonntag, den 18. November 2018</h4>";
	$code .= "<ul>";
		$code .= "<li><b>Willkommen liebe Schüler im digitalen Schulhof!! Ein Traum wird wahr ... :)</b></li>";
		$code .= "<li>Für die Schüler steht der Vertretungsplan online zur Verfügung.<br><b>Der Online-Vertretungsplan ist in der Testphase! Die ausgehängten Vertretungspläne haben Gültigkeit!!</b></li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_4_2', 'Neuerungen in Version 0.4.2 einblenden', 'Neuerungen in Version 0.4.2 ausblenden', $code, 0);

	$code = "<h4>Version 0.4.1 - Sonntag, den 11. November 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Für die Lehrer und das Sekretariat stehen die Vertretungspläne online zur Verfügung.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_4_1', 'Neuerungen in Version 0.4.1 einblenden', 'Neuerungen in Version 0.4.1 ausblenden', $code, 0);


	$code = "<h4>Version 0.4.0 - Sonntag, den 04. November 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Klassen und Kurse können angelegt, bearbeitet und gelöscht werden.</li>";
		$code .= "<li>Zur Stundenplanung können Zeiträume mit jeweiliger Unterrichtsstundengestaltung angelegt werden.</li>";
		$code .= "<li>Die Stundenplanung kann über den Schulhof erfolgen.</li>";
		$code .= "<li>Raumpläne können von Lehrern und der Verwaltung eingesehen werden.</li>";
		$code .= "<li>Stundenpläne der Lehrer können von Lehrern und der Verwaltung eingesehen werden.</li>";
		$code .= "<li>Die Vertretungsplanung kann über den Schulhof erfolgen und wird zunächst erprobt. <b>Die ausgehängten Versionen haben Gültigkeit!!</b></li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_4_0', 'Neuerungen in Version 0.4.0 einblenden', 'Neuerungen in Version 0.4.0 ausblenden', $code, 0);

	$code = "<h4>Version 0.3.5 - Sonntag, den 23. September 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Ein kleiner Fehler in der Funktion »Allen Antworten« im Postfach wurde behoben, sodass nun wirklich alle vorigen Empfänger und der Absender als neue Empfänger angegeben werden.</li>";
		$code .= "<li>Bei der Veränderung des Monats oder des Jahres im Kalender verändert sich nun automatisch auch die Anzeige unten, in der die zugehörigen Termine angezeigt werden.</li>";
		$code .= "<li>Nach dem Auswählen einer Person aus der Personensuche werden die Eingaben aus der Personensuche zurückgesetzt.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_3_5', 'Neuerungen in Version 0.3.5 einblenden', 'Neuerungen in Version 0.3.5 ausblenden', $code, 0);

	$code = "<h4>Version 0.3.4 - Montag, den 03. September 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>In Gremien und Fachschaften können an Blogeinträge Downloads angehängt werden.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_3_4', 'Neuerungen in Version 0.3.4 einblenden', 'Neuerungen in Version 0.3.4 ausblenden', $code, 0);

	$code = "<h4>Version 0.3.3 - Samstag, den 01. September 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Die Website wird abhängig vom benutzten Gerät verschieden angezeigt. Diese Einstellung kann auch manuell geändert werden.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_3_3', 'Neuerungen in Version 0.3.3 einblenden', 'Neuerungen in Version 0.3.3 ausblenden', $code, 0);

	$code = "<h4>Version 0.3.2 - Sonntag, den 26. August 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Das neue Element »Eventübersicht« ist für die Website verfügbar.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_3_2', 'Neuerungen in Version 0.3.2 einblenden', 'Neuerungen in Version 0.3.2 ausblenden', $code, 0);

	$code = "<h4>Version 0.3.1 - Freitag, den 24. August 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Termine können Texte enthalten.</li>";
		$code .= "<li>Auf der Website ist das neue Element »Boxen« verfügbar.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_3_1', 'Neuerungen in Version 0.3.1 einblenden', 'Neuerungen in Version 0.3.1 ausblenden', $code, 0);

	$code = "<h4>Version 0.3.0 - Samstag, den 21. August 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>DSGVO-konform wird der Benutzer der Seite nun darüber informiert, dass Cookies gespeichert werden. Das war bisher nicht notwendig, da das bisher nur im Schulhof der Fall war und die Benutzer des Schulhofs diese Information beim Beantragen des Nutzerkontos in Papierform unterschrieben haben.</li>";
		$code .= "<li>Es können öffentliche Seiten für die Website auf beliebig vielen verschienden Ebenen angelegt werden.</li>";
		$code .= "<li>Die Navigationen auf der Website in Kopfzeile und Sidebar, sowie in der Fußzeile können festgelegt werden.</li>";
		$code .= "<li>Öffentlihe Termine werden aus dem Schulhof generiert und auf der Website angezeigt.</li>";
		$code .= "<li>Der Texteditor Summernote wurde abgewandelt und in den digitalen Schulhof integriert.</li>";
		$code .= "<li>Blogeinträge in Gremien und Fachschaften können mit einem Texteditor, ähnlich wie Word (Pages, OOOWriter) bearbeitet werden.</li>";
		$code .= "<li>Auf Websites können Seiten mit dem Texteditor gestaltet werden.</li>";
		$code .= "<li>Auf Websites können Downloads erstellt werden.</li>";
		$code .= "<li>Der Webmaster kann in allen Gruppen Termine erstellen und hat auf alle Termine Zugriff.</li>";
		$code .= "<li>Gruppen, in denen man Mitglied ist, werden auf der Starseite des Schulhofs angezeigt.</li>";
		$code .= "<li>Beim Hochladen von Dateien können Bilder unter Angabe der maximalen Seitenlänge automatisch skaliert werden. Das Seitenverhältnis bleibt dabeie erhalten.</li>";
		$code .= "<li>Der Websiteblog ist bereit zur Verwendung.</li>";
		$code .= "<li>Der Websiteblog wird auf der Website angezeigt.</li>";
		$code .= "<li>Blogs und Termine gleicher Kategorien verweisen aufeinander.</li>";
		$code .= "<li>In Allgemeinen Einstellungen kann der Download aus sichtbaren Gremien und Fachschaften für alle Lehrer und das Verwaltungspersonal freigegeben werden.</li>";
		$code .= "<li>Der Titel zur Verwaltung von technsichen Defekten in der Ausrüstung wurde von »Raumgeräte« auf »Geräte« verallgemeinert.</li>";
		$code .= "<li>Die Sicherheitsstufe für gesicherte Dateien wurde beim Zugriff und beim Download erhöht.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_3_0', 'Neuerungen in Version 0.3.0 einblenden', 'Neuerungen in Version 0.3.0 ausblenden', $code, 0);

	$code = "<h4>Version 0.2.9 - Donnerstag, den 02. August 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Ein Problem mit nicht angezeigten Terminen in diversen Kalendern wurde behoben.</li>";
		$code .= "<li>Ein Problem mit fäschlicherweise angezeigten Symbolen zum Bearbeiten von Terminen in den Kalendern »Sonstige« wurde behoben.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_9', 'Neuerungen in Version 0.2.9 einblenden', 'Neuerungen in Version 0.2.9 ausblenden', $code, 0);

	$code = "<h4>Version 0.2.8 - Sonntag, den 29. Juli 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Leihgeräte können angelegt werden.</li>";
		$code .= "<li>In Fachschaften können Fachabteilungsleiter hinterlegt werden.</li>";
		$code .= "<li>Termine aller sichtbaren Gremien und Fachschaften können in Kalendern zur Abstimmung von Terminüberschneidungen angezeigt werden. Zudem wurde die Zugriffszeit auf Termine verbessert. Dies wird allerdings jetzt kaum spürbar sein, da sich diese erst mit zunehmender Terminanazhl im Schulhof bemerkbar machen wird.</li>";
		$code .= "<li>Ein Problem mit Notifikationen wurde behoben, sodass nun nicht mehr zu viele Schulhofnutzer Notifikationen erhalten.</li>";
		$code .= "<li>Ein Problem mit fehlenden Verweisen auf Fachschaften und Gremien im Nutzerprofil wurde behoben.</li>";
		$code .= "<li>Ein Problem beim Bearbeiten und Erstellen von Schuljahren wurde behoben.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_8', 'Neuerungen in Version 0.2.8 einblenden', 'Neuerungen in Version 0.2.8 ausblenden', $code, 0);


	$code = "<h4>Version 0.2.7 - Mittwoch, den 11. Juli 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Als öffentlich markierte Termine werden bei allen Nutzern des Schulhofs als »Ereignis« angezeigt. Außerdem werden nun auch Notifikationen an alle verschickt, sofern diese Termine genehmigt sind.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_7', 'Neuerungen in Version 0.2.7 einblenden', 'Neuerungen in Version 0.2.7 ausblenden', $code, 0);


	$code = "<h4>Version 0.2.6 - Montag, den 09. Juli 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Für jeden Raum sind nun beliebig viele Geräte anlegbar. Die Problemmeldung ist auch weiterhin möglich. Die interne Geräteverwaltung kann nun Hinweise zum aktuellen Gerätestatus geben. Außerdem können automatisch generierte Mails mit Tickets an die externe Geräteverwaltung geschickt werden. Diese kann die Geräte per Link wieder freigeben.</li>";
		$code .= "<li>Ein Problem beim Schreiben an alle in Gruppen, die auch Mitglieder ohne Benutzerkonto enthält, wurde behoben.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_6', 'Neuerungen in Version 0.2.6 einblenden', 'Neuerungen in Version 0.2.6 ausblenden', $code, 0);


	$code = "<h4>Version 0.2.5 - Dienstag, den 03. Juli 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Durch ein geringfügiges Face-Lifting wurde die Bedienbarkeit des digitalen Schulhofs verbessert. (Navigationen verschwinden mit Verzögerungen, ...)</li>";
		$code .= "<li>Zur besseren Identifikation einzelner Gruppen, kann jeder Gruppe nun ein Icon zugeteilt werden.</li>";
		$code .= "<li>Durch gezielte Notifikationen werden die Nutzer des Schulhofs darüber informiert, was sich seit dem letzten Besuch verändert hat.</li>";
		$code .= "<li>Schulweite Termine können erstellt werden.</li>";
		$code .= "<li>Termine aus Gremien bedürfen der Genehmigung durch dazu berechtigte Personen. Aufgrund der nötigen Datenstrukturänderung wurden alte Termine gelöscht.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_5', 'Neuerungen in Version 0.2.5 einblenden', 'Neuerungen in Version 0.2.5 ausblenden', $code, 0);


	$code = "<h4>Version 0.2.4 - Dienstag, den 19. Juni 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Personen können unabhängig von Nutzerkonten eingerichtet werden. Dabei werden von diesen Personen nur Daten gespeichert, die auf einer rechtlichen Grundlage beruhen. Welche das sind, kann unter <a href=\"Website/Datenschutz\">Datenschutz</a> eingesehen werden.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_4', 'Neuerungen in Version 0.2.4 einblenden', 'Neuerungen in Version 0.2.4 ausblenden', $code, 0);

	$code = "<h4>Version 0.2.3 - Montag, den 04. Juni 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Anpassung an die EU-DSGVO: Die Vorschriften der EU-DSGVO wurden umgesetzt. Entsprechende Informationsseiten wurden erstellt, die über die Rechte der Nutzer aufklären. Außerdem kann jeder Nutzer alle über ihn/sie hinterlegten Daten per Mausklick einsehen. Die Möglichkeit das Benutzerkonto selbstständig zu löschen, bestand von Beginn an. (Einzige Ausnahme ist der Administrator, der auch weiterhin nicht gelöscht werden kann, da er/sie für die Einhaltung u.A. der EU-DSGVO verantwortlich ist.)</li>";
		$code .= "<li>Klassenstufen können angelegt, verwaltet und gelöscht werden.</li>";
		$code .= "<li>Fächer können angelegt, verwaltet und gelöscht werden.</li>";
		$code .= "<li>Listen von Lehrern und Verwaltungspersonal können von Lehrern und Verwaltungspersonal zum schnellen Kontakt eingesehen werden.</li>";
		$code .= "<li>Menüpunkte erscheinen nur noch, wenn sich dahinter für die jeweiligen Nutzer auch Funktionen verbergen. Unnötige Menüpunkte werden ausgeblendet.</li>";
		$code .= "<li>Es wird nicht mehr auf künftige Funktionsumfangserweiterungen hingewiesen. Diese sind diesem Neuerungenlogbuch zu entnehmen.</li>";
		$code .= "<li>In Gremien können jetzt auch Dateien hoch- und heruntergeladen werden. Der Download von ganzen Verzeichnissen ist ebenfalls möglich.</li>";
		$code .= "<li>Fachschaften stehen jetzt zur Verfügung.</li>";
		$code .= "<li>Bei Änderungen in Fachschaften und Gremien werden Notifikationen und - je nach Einstellung - auch Benachrichtigungen per Mail versendet.</li>";
		$code .= "<li>Ferientermine können im Schulhof hinterlegt werden.</li>";
		$code .= "<li>Der persönliche Kalender steht zur Verfügung. Dabei kann zwischen verschiedenen Ansichten (Tag, Woche, Monat, Jahr) gewählt werden. Einzelne Terminkategorien können ein- oder ausgeblendet werden.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_3', 'Neuerungen in Version 0.2.3 einblenden', 'Neuerungen in Version 0.2.3 ausblenden', $code, 0);

	$code = "<h4>Version 0.2.2 - Sonntag, den 08. April 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Mehr Datenschutz: Onlinezeiten können nur von explizit dazu berechtigten Personen eingesehen werden. Benutzername und eMailadresse kann nur von den Personen eingesehen werden, die sie auch bearbeiten können.</li>";
		$code .= "<li>Postfach: Probleme mit Nachrichten an mehr als eine Person behoben.</li>";
		$code .= "<li>Der gesicherte Lehrerbereich ist jetzt erreichbar.</li>";
		$code .= "<li>In Gremien können Blogeinträge und Beschlüsse erstellt werden und diese nach Schuljahr und Datum sortiert gesucht werden.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_2', 'Neuerungen in Version 0.2.2 einblenden', 'Neuerungen in Version 0.2.2 ausblenden', $code, 0);

	$code = "<h4>Version 0.2.1 - Mittwoch, den 04. April 2018</h4>";
	$code .= "<ul>";
		$code .= "<li>Die Anmeldung und das Anfordern eines neuen Passworts ist auch mit der Eingabetaste möglich.</li>";
		$code .= "<li>Die Suche nach Personen und Nachrichten ist unabhängig von Groß- und Kleinschreibung.</li>";
		$code .= "<li>Auch gesendete Nachrichten können nach Empfänger durchsucht werden.</li>";
		$code .= "<li>Die Menüführung wird auch für Tablets und hochauflösende Smartphones unterstützt.</li>";
		$code .= "<li>Kleinere Änderungen an der Darstellung zur besseren Sichtbarkeit von Symbolen und Texten wurden durchgeführt.</li>";
	$code .= "</ul>";
	$aeltere .= cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_0_2_1', 'Neuerungen in Version 0.2.1 einblenden', 'Neuerungen in Version 0.2.1 ausblenden', $code, 0);

	echo cms_toggleeinblenden_generieren ('cms_neuerungenverlaufknopf_aeltere', 'Neuerungen älterer Versionen einblenden', 'Neuerungen älterer Version ausblenden', $aeltere, 0);;
	?>
	</div>
</div>
