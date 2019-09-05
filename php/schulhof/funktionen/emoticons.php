<?php

$CMS_EMOTICONS = array();

cms_registeriere_emoticon($CMS_EMOTICONS, "cool",            "cool.png",           "Cool",          );
cms_registeriere_emoticon($CMS_EMOTICONS, "geist",           "geist.png",          "Geist",         );
cms_registeriere_emoticon($CMS_EMOTICONS, "grinsen",         "grinsen.png",        "Grinsen",       );
cms_registeriere_emoticon($CMS_EMOTICONS, "heiss",           "heiss.png",          "Heiß",          );
cms_registeriere_emoticon($CMS_EMOTICONS, "hp",              "harry_potter.png",   "Harry Potter",  );
cms_registeriere_emoticon($CMS_EMOTICONS, "krankheit",       "krankheit.png",      "krankheit",     );
cms_registeriere_emoticon($CMS_EMOTICONS, "liebe",           "liebe.png",          "Liebe",         );
cms_registeriere_emoticon($CMS_EMOTICONS, "lol",             "lol.png",            "Lol",           );
cms_registeriere_emoticon($CMS_EMOTICONS, "matrix",          "matrix.png",         "Matrix",        );
cms_registeriere_emoticon($CMS_EMOTICONS, "party",           "party.png",          "Party",         );
cms_registeriere_emoticon($CMS_EMOTICONS, "trauer",          "trauer.png",         "Trauer",        );
cms_registeriere_emoticon($CMS_EMOTICONS, "ueberraschung",   "ueberraschung.png",  "Überraschung",  );
cms_registeriere_emoticon($CMS_EMOTICONS, "erstaunen",       "erstaunen.png",      "Erstaunen",     );
cms_registeriere_emoticon($CMS_EMOTICONS, "zweifel",         "zweifel.png",        "Zweifel",       );
cms_registeriere_emoticon($CMS_EMOTICONS, "verwirrung",      "verwirrung.png",     "Verwirrung",    );

function cms_registeriere_emoticon(&$m, $i, $d, $n) {
  $sql = "SELECT aktiv FROM emoticons WHERE id = ?";
  $dbs = cms_verbinden("s");
  $sql = $dbs->prepare($sql);
  $sql->bind_param("s", $i);
  $a = true;
  $sql->bind_result($a);
  $sql->execute();
  $sql->fetch();
  array_push($m, array("id" => $i, "datei" => $d, "name" => $n, "aktiv" => $a));
}
?>
