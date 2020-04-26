<?php
// Stellt Verbindung zur SchulhofDatenbank her
function cms_zur_db($host, $user, $pass, $db) {
	$db = new mysqli($host, $user, $pass, $db);
	$db -> set_charset("utf8");
	return $db;
}

// Stellt Verbindung zur Schulhofdatenbank her
function cms_verbinden($art = 'l') {
	if ($art == 's') {
		global $CMS_DBS_HOST, $CMS_DBS_USER, $CMS_DBS_PASS, $CMS_DBS_DB;
		return cms_zur_db($CMS_DBS_HOST, $CMS_DBS_USER, $CMS_DBS_PASS, $CMS_DBS_DB);
	}
	else if ($art == 'p') {
		global $CMS_DBP_HOST, $CMS_DBP_USER, $CMS_DBP_PASS, $CMS_DBP_DB;
		return cms_zur_db($CMS_DBP_HOST, $CMS_DBP_USER, $CMS_DBP_PASS, $CMS_DBP_DB);
	}
	else if ($art == 'l') {
		global $CMS_DBL_HOST, $CMS_DBL_USER, $CMS_DBL_PASS, $CMS_DBL_DB;
		return cms_zur_db($CMS_DBL_HOST, $CMS_DBL_USER, $CMS_DBL_PASS, $CMS_DBL_DB);
	}
	else if ($art == 'ü'){
		global $CMS_DBS_HOST, $CMS_DBS_USER, $CMS_DBS_PASS;
		$db = new mysqli($CMS_DBS_HOST, $CMS_DBS_USER, $CMS_DBS_PASS);
		$db -> set_charset("utf8");
		return $db;
	}
	return false;
}

// Trennt die Verbindung zur Datenbank $db
function cms_trennen($db) {
	if (is_resource($db)) {
		return $db->close();
	}
}
?>
