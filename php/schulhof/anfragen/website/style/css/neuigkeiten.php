<?php
fwrite($hell, ".cms_neuigkeiten {\n");
fwrite($hell, "padding: 0px;\n");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";\n");
fwrite($hell, "display: flex;\n");
fwrite($hell, "flex-wrap: wrap;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit {\n");
fwrite($hell, "width: 25%;\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit a {color: ".$_POST['cms_style_h_neuigkeit_schrift'].";}\n");
fwrite($hell, ".cms_neuigkeit a:hover {color: ".$_POST['cms_style_h_neuigkeit_schrifthover'].";}\n");

fwrite($hell, ".cms_neuigkeit_notfall {\n");
fwrite($hell, "text-align: left !important;\n");
fwrite($hell, "width: 100%;\n");
fwrite($hell, "padding: 10px;\n");
fwrite($hell, "margin: 0px;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "animation-name: notfallanimationhell;\n");
fwrite($hell, "animation-delay: 1s;\n");
fwrite($hell, "animation-duration: 4s;\n");
fwrite($hell, "animation-iteration-count: infinite;\n");
fwrite($hell, "}\n");

fwrite($hell, "@keyframes notfallanimationhell {\n");
fwrite($hell, "0%   {background-color:".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");
fwrite($hell, "37.5%  {background-color:".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}\n");
fwrite($hell, "75%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");
fwrite($hell, "100%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_ln {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund']." !important;\n");
fwrite($hell, "padding: 8px !important;\n");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_ln h4, .cms_neuigkeit_ln p {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_ganz:hover {\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_optimierung_H .cms_neuigkeit {\n");
fwrite($hell, "width: 50% !important;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit:hover {\n");
fwrite($hell, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_icon {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "top: 10px;\n");
fwrite($hell, "left: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_inhalt {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "margin-left: 42px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_inhalt p {\n");
fwrite($hell, "overflow: hidden;\n");
fwrite($hell, "text-overflow: ellipsis;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_inhalt h4 {\n");
fwrite($hell, "margin-top: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_inhalt .cms_neuigkeit_vorschau {\n");
fwrite($hell, "font-size: 70%;\n");
fwrite($hell, "margin-bottom: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_schliessen, .cms_neuigkeit_oeffnen {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "bottom: 8px;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "line-height: 1;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_schliessen {\n");
fwrite($hell, "left: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit_oeffnen {\n");
fwrite($hell, "right: 10px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_neuigkeit:hover .cms_neuigkeit_schliessen,\n");
fwrite($hell, ".cms_neuigkeit:hover .cms_neuigkeit_oeffnen {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");







// DARKMODE
fwrite($dunkel, ".cms_neuigkeit {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_neuigkeit a {color: ".$_POST['cms_style_d_neuigkeit_schrift'].";}\n");
fwrite($dunkel, ".cms_neuigkeit a:hover {color: ".$_POST['cms_style_d_neuigkeit_schrifthover'].";}\n");

fwrite($dunkel, ".cms_neuigkeit_notfall {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";\n");
fwrite($dunkel, "animation-name: notfallanimationdunkel;\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, "@keyframes notfallanimationdunkel {\n");
fwrite($dunkel, "0%   {background-color:".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}\n");
fwrite($dunkel, "37.5%  {background-color:".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}\n");
fwrite($dunkel, "75%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}\n");
fwrite($dunkel, "100%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_neuigkeit_ln {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund']." !important;\n");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_neuigkeit_ln h4, .cms_neuigkeit_ln p {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_neuigkeit:hover {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");
?>
