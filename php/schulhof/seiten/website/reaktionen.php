<?php
  function cms_artikel_reaktionen($typ, $id) {
    $dbs = cms_verbinden("s");
    $code = "<div id=\"cms_reaktionen\">";

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
      $sql = "SELECT von FROM reaktionen WHERE typ = ? AND id = ? AND emoticon = ?";
      $dbs = cms_verbinden("s");
      $sql = $dbs->prepare($sql);
      $sql->bind_param("sis", $typ, $id, $r["id"]);
      if($sql->execute()) {
        $sql->bind_result($ips);
        if (!$sql->fetch()) {$ips = "";}
      }

      $ips = explode(" ", $ips);

      $ip = getUserIpAddr();
      $ip = md5($ip);

      $c = "";

      // Toggle
      if(in_array($ip, $ips))
        $c = " cms_reagiert";
      $code .= "<div class=\"cms_reaktion$c cms_reaktion_".$r["id"]."\"><img src=\"res/emoticons/gross/".$r["datei"]."\" title=\"".$r["name"]."\" data-reaktion=\"".$r["id"]."\"></img> <span>".((count($ips)-1)==0?"&nbsp;":count($ips)-1)."</span></div>";
    }


      // $sql = "SELECT * FROM reaktionen WHERE typ='$typ' AND id=$id";
      // $sql = $dbs->query($sql);
      // $rk = array();
      // if($sql)
      //   while($sqld = $sql->fetch_assoc())
      //     $rk[$sqld["emoticon"]] = explode(" ", $sqld["von"]);
      //
      // foreach($rk as $emo => $ips) {
      //   $ip = getUserIpAddr();
      //   $ip = md5($ip);
      //   $c = "";
      //   array_shift($ips);
      //
      //   foreach($reaktionen as $r)
      //     if($r["id"] == $emo) {
      //       $emo = $r["datei"];
      //       break;
      //     }
      //
      //   if(in_array($ip, $ips))
      //     $c = " cms_reagiert";
      //   if(count($ips) > 0)
      //     $code .= "<div class=\"cms_reaktion$c\"><img src=\"res/emoticons/gross/".$emo."\"> ".count($ips)."</img></div>";
      // }


    //   $code .= "<div id=\"cms_neue_reaktion\" class=\"cms_reaktion\"><img src=\"res/icons/gross/plus.png\">&nbsp;";
    //     $code .= "<div id=\"cms_reaktionswahl\">";
    //
    //
    //       foreach($reaktionen as $r)
    //         $code .= "<img src=\"res/emoticons/gross/".$r["datei"]."\" data-reaktion=\"".$r["id"]."\" title=\"".$r["name"]."\"></img>";
    //     $code .= "</div>";
    //   $code .= "</div>";
    // $code .= "</div>";
    $code .= "<script>var cms_typ = '$typ'; var cms_id = $id;</script>";
    return $code;
  }

  function r(&$m, $i, $d, $n) {
    array_push($m, array("id" => $i, "datei" => $d, "name" => $n));
  }
?>
