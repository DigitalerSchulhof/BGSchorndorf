<?php
function cms_meldung ($art, $inhalt) {
	$code = "";
	$code .= '<div class="cms_meldung cms_meldung_'.$art.'">';
	$code .= $inhalt.'</div>';
	return $code;
}


function cms_meldung_bastler () {
	$code = "";

	if (isset($_SESSION['BENUTZERART'])) {$art = $_SESSION['BENUTZERART'];}
	else {$art = "";}

	$inhalt = '<h4>Sicherheitseinschränkung</h4>';
	$inhalt .= '<p>Diese Seite wurde nicht über einen Link im System aufgerufen, sondern durch direkte Eingabe. Daher sind nicht genügend Informationen vorhanden, um sie anzuzeigen. Aus Sicherheits- und Datenschutzgründen enthalten Links nicht alle Informationen, um Seiten mit persönlichen Daten zu öffnen.</p>';
	if ($art == "s") {
		$inhalt .= '<p>Bitte verwende die Benutzeroberfläche, um die gewünschten Seiten aufzurufen.</p>';
	}
	else {
		$inhalt .= '<p>Bitte verwenden Sie die Benutzeroberfläche, um die gewünschten Seiten aufzurufen.</p>';
	}

	$code .= '<div class="cms_meldung cms_meldung_warnung">';
	$code .= $inhalt.'</div>';

	return $code;
}

function cms_meldung_berechtigung () {
	if (isset($_SESSION['BENUTZERART'])) {$art = $_SESSION['BENUTZERART'];}
	else {$art = "";}

	if ($art == "s") {
		$inhalt = '<p>Du bist nicht berechtigt, diese Seite zu sehen!</p>';
	}
	else {
		$inhalt = '<p>Sie sind nicht berechtigt, diese Seite zu sehen!</p>';
	}

	return cms_meldung ("fehler", "<h4>Zugriff verweigert</h4>".$inhalt);
}

function cms_meldung_firewall () {
	$inhalt = '<p>Aus diesem Netz ist kein Zugriff auf diesen Inhalt möglich.</p>';
	return cms_meldung ("firewall", "<h4>Falsches Netz</h4>".$inhalt);
}

function cms_meldung_fehler () {
	$inhalt = '<p>Ein unbekannter Fehler ist aufgetreten. Bitte den Administrator über den Link in der Fußzeile informieren.</p>';
	return cms_meldung ("fehler", "<h4>Fehler</h4>".$inhalt);
}

function cms_meldung_unbekannt () {
	$inhalt = '<h4>Unbekannter Fehler</h4><p>Ein unbekannter Fehler ist aufgetreten. Es wurden keine Datensätze gefunden. Bitte den Administrator informieren!</p>';
	return cms_meldung ("fehler", $inhalt);
}

function cms_meldung_geschuetzer_inhalt () {
	$code = '<div class="cms_geschuetzerinhalt">';
	$code .= '<p><img src="res/icons/gross/firewall.png"/></p>';
	$code .= '<p>Auf diesen Inhalt kann aus diesem Netz nicht zugegriffen werden.</p>';
	$code .= '</div>';
	return $code;
}
?>
