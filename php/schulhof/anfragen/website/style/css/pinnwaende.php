<?php
fwrite($hell, ".cms_pinnwand_anschlaege {\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_anschlag_aussen {\n");
fwrite($hell, "width: 50%;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_anschlag_innen {\n");
fwrite($hell, "margin: 10px;\n");
fwrite($hell, "box-shadow: 0px 0px 10px ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_datum,\n");
fwrite($hell, ".cms_pinnwand_ersteller,\n");
fwrite($hell, ".cms_pinnwand_titel,\n");
fwrite($hell, ".cms_pinnwand_inhalt {\n");
fwrite($hell, "padding: 0px 10px 0px 10px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_titel {\n");
fwrite($hell, "padding-top: 10px;\n");
fwrite($hell, "padding-bottom: 5px;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_ersteller {\n");
fwrite($hell, "padding-top: 5px;\n");
fwrite($hell, "padding-bottom: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_titel, .cms_pinnwand_datum, .cms_pinnwand_ersteller {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "padding-bottom: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_inhalt {\n");
fwrite($hell, "padding-top: 10px;\n");
fwrite($hell, "padding-bottom: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_datum, .cms_pinnwand_ersteller {\n");
fwrite($hell, "font-size: 75%;\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_schriftfarbepositiv'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_pinnwand_ersteller .cms_link {font-size: inherit;}\n");






// DARKMODE
fwrite($dunkel, ".cms_pinnwand_anschlag_innen {\n");
fwrite($dunkel, "box-shadow: 0px 0px 10px ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_pinnwand_titel {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_pinnwand_titel, .cms_pinnwand_datum, .cms_pinnwand_ersteller {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_pinnwand_datum, .cms_pinnwand_ersteller {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_schriftfarbepositiv'].";\n");
fwrite($dunkel, "}\n");
?>
