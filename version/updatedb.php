<?php
	$versionen = array(
		
		);

	// Version	Tab		function($dbs, $dbp, $dbl)
	// "1.0"	=> function($dbs, $dbp, $dbl) { }

	$dbs = cms_verbinden("s");
	$dbp = cms_verbinden("p");
	$dbl = cms_verbinden("l");
	foreach($versionen as $v => $update) {
		if(version_compare($version, $v, "lt")) {
			$update($dbs, $dbp, $dbl);
		}
	}

	function query($db, $sql) {
		$sql = $db->prepare($sql);
		$sql->execute();
		$sql->close();
	}
	function querygk($db, $sql) {
		global $CMS_GRUPPEN;
		foreach($CMS_GRUPPEN as $g) {
			$gk = cms_textzudb($g);
			query($db, str_replace("{gk}", $gk, $sql));
		}
	}
?>
