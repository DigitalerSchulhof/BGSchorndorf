<?php
function cms_dauerbrenner_links_anzeigen () {
  global $CMS_SCHLUESSEL, $CMS_BENUTZERART;
  $ausgabe = "";

  if ($CMS_BENUTZERART == 's') {$sqlwhere = "sichtbars = '1'";}
  else if ($CMS_BENUTZERART == 'l') {$sqlwhere = "sichtbarl = '1'";}
  else if ($CMS_BENUTZERART == 'e') {$sqlwhere = "sichtbare = '1'";}
  else if ($CMS_BENUTZERART == 'v') {$sqlwhere = "sichtbarv = '1'";}
  else if ($CMS_BENUTZERART == 'x') {$sqlwhere = "sichtbarx = '1'";}
  else {$sqlwhere = "sichtbarx = '2'";}

	$dbs = cms_verbinden('s');
  $sql = "SELECT * FROM (SELECT AES_DECRYPT(bezeichnung, '$CMS_SCHLUESSEL') AS bezeichnung FROM dauerbrenner WHERE $sqlwhere) AS x ORDER BY bezeichnung";
	if ($anfrage = $dbs->query($sql)) {  // Safe weil keine Eingabe
		while ($daten = $anfrage->fetch_assoc()) {
      $anzeigename = $daten['bezeichnung'];
      $anzeigenamelink = cms_textzulink($anzeigename);
      $ausgabe .= "<li><a class=\"cms_button\" href=\"Schulhof/Dauerbrenner/$anzeigenamelink\">".$anzeigename."</a></li> ";
		}
    $anfrage->free();
	}
  cms_trennen($dbs);
  if (strlen($ausgabe) > 0) {$ausgabe = "<ul>".$ausgabe."</ul>";}
  else {$ausgabe = '<p class="cms_notiz">Keine Dauerbrenner angelegt</p>';}

  return $ausgabe;
}
?>
