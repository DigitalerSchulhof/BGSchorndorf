<?php
  function cms_artikel_reaktionen($typ, $id, $gruppenid) {
    global $CMS_BENUTZERID, $CMS_EINSTELLUNGEN;
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
    $code = "<div id=\"cms_reaktionen\" class=\"".(cms_angemeldet()?"":"cms_noch_anmelden")."\">";

    $reaktionen = array();
    r($reaktionen, "cool",            "cool.png",           "Cool");
    r($reaktionen, "geist",           "geist.png",          "Geist");
    r($reaktionen, "grinsen",         "grinsen.png",        "Grinsen");
    r($reaktionen, "heiss",           "heiss.png",          "Heiß");
    r($reaktionen, "hp",              "hp.png",             "Harry Potter");
    r($reaktionen, "krankheit",       "krankheit.png",      "krankheit");
    r($reaktionen, "liebe",           "liebe.png",          "Liebe");
    r($reaktionen, "lol",             "lol.png",            "Lol");
    r($reaktionen, "matrix",          "matrix.png",         "Matrix");
    r($reaktionen, "party",           "party.png",          "Party");
    r($reaktionen, "trauer",          "trauer.png",         "Trauer");
    r($reaktionen, "ueberraschung",   "ueberraschung.png",  "Überraschung");
    r($reaktionen, "waaas",           "waaas.png",          "Erstaunen");
    r($reaktionen, "zweifel",         "zweifel.png",        "Zweifel");
    r($reaktionen, "verwirrung",      "verwirrung.png",     "Verwirrung");

    foreach($reaktionen as $r) {
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

  function r(&$m, $i, $d, $n) {
    array_push($m, array("id" => $i, "datei" => $d, "name" => $n));
  }
?>
