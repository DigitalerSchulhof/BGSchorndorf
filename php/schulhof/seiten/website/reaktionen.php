<?php
  function cms_artikel_reaktionen($typ, $id, $gruppenid) {
    global $CMS_BENUTZERID, $CMS_EINSTELLUNGEN, $CMS_EMOTICONS;
    include_once("php/schulhof/funktionen/emoticons.php");
    if($typ == "b")
      $typp = "Blogeinträge";
    else if($typ == "t")
      $typp = "Termine";
    else if($typ == "g")
      $typp = "Galerien";
    else return cms_meldung_fehler();
    if(!$CMS_EINSTELLUNGEN["Reaktionen auf $typp"])
      return "";
    $dbs = cms_verbinden("s");
    $code = "<div id=\"cms_reaktionen\" class=\"".(cms_angemeldet()?"":"cms_noch_anmelden ")."cms_spalte_i\">";

    foreach($CMS_EMOTICONS as $r) {
      if(!$r["aktiv"])
        continue;
      $sql = "SELECT von FROM reaktionen WHERE typ = ? AND id = ? AND gruppe = ? AND emoticon = ?";
      $dbs = cms_verbinden("s");
      $sql = $dbs->prepare($sql);
      $sql->bind_param("siss", $typ, $id, $gruppenid, $r["id"]);
      if($sql->execute()) {
        $sql->bind_result($von);
        if (!$sql->fetch()) {$von = "";}
      }

      $von = explode(" ", $von);

      $c = "";
      $bid = $CMS_BENUTZERID;

      // Toggle
      if(in_array($bid, $von))
        $c = " cms_reagiert";
      $code .= "<div class=\"cms_reaktion$c cms_reaktion_".$r["id"]."\"><img src=\"res/emoticons/gross/".$r["datei"]."\" title=\"".$r["name"]."\" data-reaktion=\"".$r["id"]."\"></img> <span>".((count($von)-1)==0?"&nbsp;":count($von)-1)."</span></div>";
    }

    $code .= "</div><div class=\"cms_notiz\"><a onclick=\"cms_link('Schulhof/Nutzerkonto/Anmeldung')\">Melden Sie sich an</a>, um eine Reaktion zu hinterlassen</div><script>var cms_typ = '$typ'; var cms_id = $id; var cms_gid = '$gruppenid';</script>";
    return $code;
  }
?>
