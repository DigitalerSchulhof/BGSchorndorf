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
    $bid = $CMS_BENUTZERID;

    foreach($CMS_EMOTICONS as $r) {
      if(!$r["aktiv"])
        continue;
      $sql = "SELECT 1 FROM reaktionen WHERE typ = ? AND id = ? AND gruppe = ? AND emoticon = ? AND von = ?";
      $dbs = cms_verbinden("s");
      $sql = $dbs->prepare($sql);
      $sql->bind_param("sissi", $typ, $id, $gruppenid, $r["id"], $bid);
      $sql->bind_result($check);
      $c = "";
      if($sql->execute() && $sql->fetch() && $check == 1) {
        $c = " cms_reagiert";
      }
      $sql->close();

      $sql = "SELECT COUNT(*) FROM reaktionen WHERE typ = ? AND id = ? AND gruppe = ? AND emoticon = ?";
      $sql = $dbs->prepare($sql);
      $sql->bind_param("siss", $typ, $id, $gruppenid, $r["id"]);
      $sql->bind_result($anz);
      $sql->execute();
      $sql->fetch();
      $sql->close();

      $code .= "<div class=\"cms_reaktion$c cms_reaktion_".$r["id"]."\"><img ".(cms_angemeldet()?"onclick=\"cms_reaktion('".$r["id"]."', '$typ', '$id', '$gruppenid')\"":"")." src=\"res/emoticons/gross/".$r["datei"]."\" title=\"".$r["name"]."\" data-reaktion=\"".$r["id"]."\"></img> <span>".($anz==0?"&nbsp;":$anz)."</span></div>";
    }

    $code .= "<div class=\"cms_notiz\"><a onclick=\"cms_link('Schulhof/Anmeldung')\">Melden Sie sich an</a>, um eine Reaktion zu hinterlassen</div></div>";
    return $code;
  }
?>
