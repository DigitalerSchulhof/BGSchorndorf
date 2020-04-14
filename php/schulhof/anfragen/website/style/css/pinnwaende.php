<?php
fwrite($hell, ".cms_pinnwand_anschlaege {");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_anschlag_aussen {");
fwrite($hell, "width: 50%;");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_anschlag_innen {");
fwrite($hell, "margin: 10px;");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_datum,");
fwrite($hell, ".cms_pinnwand_ersteller,");
fwrite($hell, ".cms_pinnwand_titel,");
fwrite($hell, ".cms_pinnwand_inhalt {");
fwrite($hell, "padding: 0px 10px 0px 10px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_titel {");
fwrite($hell, "padding-top: 10px;");
fwrite($hell, "padding-bottom: 5px;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_ersteller {");
fwrite($hell, "padding-top: 5px;");
fwrite($hell, "padding-bottom: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_titel, .cms_pinnwand_datum, .cms_pinnwand_ersteller {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "padding-bottom: 5px;");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_inhalt {");
fwrite($hell, "padding-top: 10px;");
fwrite($hell, "padding-bottom: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_datum, .cms_pinnwand_ersteller {");
fwrite($hell, "font-size: 75%;");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_pinnwand_ersteller .cms_link {font-size: inherit;}");






// DARKMODE
fwrite($dunkel, ".cms_pinnwand_anschlag_innen {");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_pinnwand_titel {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_pinnwand_titel, .cms_pinnwand_datum, .cms_pinnwand_ersteller {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_pinnwand_datum, .cms_pinnwand_ersteller {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";");
fwrite($dunkel, "}");
?>
