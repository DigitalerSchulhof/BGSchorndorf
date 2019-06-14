<?php
function cms_stundenplanung_navigation() {
  global $CMS_RECHTE;
  $code = "";

  if ($CMS_RECHTE['Planung']['Vertretungen planen']) {
    $code .= "<li>";
      $code .= "<a class=\"cms_uebersicht_verwaltung_vertretungsplanung\" href=\"Schulhof/Verwaltung/Vertretungsplanung/\">";
        $code .=  "<h3>Vertretungsplanung</h3>";
        $code .=  "<p>Vertretungen planen.</p>";
      $code .=  "</a>";
    $code .=  "</li>";
  }
  if ($CMS_RECHTE['Planung']['Stundenplanzeiträume anlegen'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume bearbeiten'] || $CMS_RECHTE['Planung']['Stundenplanzeiträume löschen'] ||
      $CMS_RECHTE['Planung']['Stunden anlegen'] || $CMS_RECHTE['Planung']['Stunden löschen']) {
  	$code .= "<li>";
  		$code .= "<a class=\"cms_uebersicht_verwaltung_stundenplanung\" href=\"Schulhof/Verwaltung/Stundenplanung/\">";
  			$code .=  "<h3>Stundenplanung</h3>";
  			$code .=  "<p>Stundenplanzeiträume anlegen, bearbeiten und löschen. Stundenpläne anlegen, bearbeiten, löschen.</p>";
  		$code .=  "</a>";
  	$code .=  "</li>";
  }
  if ($CMS_RECHTE['Organisation']['Räume anlegen'] || $CMS_RECHTE['Organisation']['Räume bearbeiten'] || $CMS_RECHTE['Organisation']['Räume löschen']) {
  	$code .=  "<li>";
  		$code .=  "<a class=\"cms_uebersicht_verwaltung_raeume\" href=\"Schulhof/Verwaltung/Räume/\">";
  			$code .=  "<h3>Räume</h3>";
  			$code .=  "<p>Räume anlegen, bearbeiten und löschen, sowie sie als Klassenzimmer festlegen.</p>";
  		$code .=  "</a>";
  	$code .=  "</li>";
  }
  if ($CMS_RECHTE['Gruppen']['Kurse anlegen'] || $CMS_RECHTE['Gruppen']['Kurse bearbeiten'] || $CMS_RECHTE['Gruppen']['Kurse löschen']) {
  	$code .=  "<li>";
  		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_kurse\" href=\"Schulhof/Verwaltung/Kurse/\">";
  			$code .=  "<h3>Kurse</h3>";
  			$code .=  "<p>Kurse anlegen, bearbeiten und löschen.</p>";
  		$code .=  "</a>";
  	$code .=  "</li>";
  }
  if ($CMS_RECHTE['Gruppen']['Klassen anlegen'] || $CMS_RECHTE['Gruppen']['Klassen bearbeiten'] || $CMS_RECHTE['Gruppen']['Klassen löschen']) {
  	$code .=  "<li>";
  		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_klassen\" href=\"Schulhof/Verwaltung/Klassen/\">";
  			$code .=  "<h3>Klassen und Tutorenklassen</h3>";
  			$code .=  "<p>Klassen und Tutorenklassen anlegen, bearbeiten und löschen.</p>";
  		$code .=  "</a>";
  	$code .=  "</li>";
  }
  if ($CMS_RECHTE['Gruppen']['Stufen anlegen'] || $CMS_RECHTE['Gruppen']['Stufen bearbeiten'] || $CMS_RECHTE['Gruppen']['Stufen löschen']) {
  	$code .=  "<li>";
  		$code .=  "<a class=\"cms_uebersicht_verwaltung_gruppen_klassenstufen\" href=\"Schulhof/Verwaltung/Stufen/\">";
  			$code .=  "<h3>Klassenstufen</h3>";
  			$code .=  "<p>Klassenstufen anlegen, bearbeiten und löschen.</p>";
  		$code .=  "</a>";
  	$code .=  "</li>";
  }
  if ($CMS_RECHTE['Organisation']['Schuljahre anlegen'] || $CMS_RECHTE['Organisation']['Schuljahre bearbeiten'] || $CMS_RECHTE['Organisation']['Schuljahre löschen']) {
  	$code .=  "<li>";
  		$code .=  "<a class=\"cms_uebersicht_verwaltung_schuljahre\" href=\"Schulhof/Verwaltung/Schuljahre/\">";
  			$code .=  "<h3>Schuljahre</h3>";
  			$code .=  "<p>Schuljahre erstellen, bearbeiten und löschen. Schlüsselpositionen in den Schuljahren besetzen.</p>";
  		$code .=  "</a>";
  	$code .=  "</li>";
  }
  if ($CMS_RECHTE['Organisation']['Fächer anlegen'] || $CMS_RECHTE['Organisation']['Fächer bearbeiten'] || $CMS_RECHTE['Organisation']['Fächer löschen']) {
  	$code .=  "<li>";
  		$code .=  "<a class=\"cms_uebersicht_verwaltung_faecher\" href=\"Schulhof/Verwaltung/Fächer/\">";
  			$code .=  "<h3>Fächer</h3>";
  			$code .=  "<p>Fächer anlegen, bearbeiten und löschen, sowie Fächer den Klassenstufen zuordnen.</p>";
  		$code .=  "</a>";
  	$code .=  "</li>";
  }

  if (strlen($code) > 0) {
    $code = "<ul class=\"cms_uebersicht\">".$code."</ul>";
  }
  return $code;
}
?>
