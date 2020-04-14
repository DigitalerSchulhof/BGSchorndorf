<?php
fwrite($hell, ".cms_neuigkeiten {");
fwrite($hell, "padding: 0px;");
fwrite($hell, "margin-bottom: ".$_POST['cms_style_haupt_absatzschulhof'].";");
fwrite($hell, "display: flex;");
fwrite($hell, "flex-wrap: wrap;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit {");
fwrite($hell, "width: 25%;");
fwrite($hell, "padding: 10px;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "display: inline-block;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "position: relative;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit a {color: ".$_POST['cms_style_h_neuigkeit_schrift'].";}");
fwrite($hell, ".cms_neuigkeit a:hover {color: ".$_POST['cms_style_h_neuigkeit_schrifthover'].";}");

fwrite($hell, ".cms_neuigkeit_notfall {");
fwrite($hell, "text-align: left !important;");
fwrite($hell, "width: 100%;");
fwrite($hell, "padding: 10px;");
fwrite($hell, "margin: 0px;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "display: block;");
fwrite($hell, "position: relative;");
fwrite($hell, "animation-name: notfallanimationhell;");
fwrite($hell, "animation-delay: 1s;");
fwrite($hell, "animation-duration: 4s;");
fwrite($hell, "animation-iteration-count: infinite;");
fwrite($hell, "}");

fwrite($hell, "@keyframes notfallanimationhell {");
fwrite($hell, "0%   {background-color:".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");
fwrite($hell, "37.5%  {background-color:".$_POST['cms_style_h_haupt_meldungfehlerhinter'].";}");
fwrite($hell, "75%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");
fwrite($hell, "100%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnunghinter'].";}");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_ln {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund']." !important;");
fwrite($hell, "padding: 8px !important;");
fwrite($hell, "border: 2px dashed ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_ln h4, .cms_neuigkeit_ln p {");
fwrite($hell, "color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_ganz:hover {");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "}");

fwrite($hell, ".cms_optimierung_H .cms_neuigkeit {");
fwrite($hell, "width: 50% !important;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit:hover {");
fwrite($hell, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_icon {");
fwrite($hell, "position: absolute;");
fwrite($hell, "top: 10px;");
fwrite($hell, "left: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_inhalt {");
fwrite($hell, "display: block;");
fwrite($hell, "margin-left: 42px;");
fwrite($hell, "p {");
fwrite($hell, "overflow: hidden;");
fwrite($hell, "text-overflow: ellipsis;");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_inhalt h4 {");
fwrite($hell, "margin-top: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_inhalt .cms_neuigkeit_vorschau {");
fwrite($hell, "font-size: 70%;");
fwrite($hell, "margin-bottom: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_schliessen, .cms_neuigkeit_oeffnen {");
fwrite($hell, "position: absolute;");
fwrite($hell, "bottom: 8px;");
fwrite($hell, "display: none;");
fwrite($hell, "line-height: 1;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_schliessen {");
fwrite($hell, "left: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit_oeffnen {");
fwrite($hell, "right: 10px;");
fwrite($hell, "}");

fwrite($hell, ".cms_neuigkeit:hover .cms_neuigkeit_schliessen,");
fwrite($hell, ".cms_neuigkeit:hover .cms_neuigkeit_oeffnen {");
fwrite($hell, "display: block;");
fwrite($hell, "}");







// DARKMODE
fwrite($dunkel, ".cms_neuigkeit {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_neuigkeit a {color: ".$_POST['cms_style_d_neuigkeit_schrift'].";}");
fwrite($dunkel, ".cms_neuigkeit a:hover {color: ".$_POST['cms_style_d_neuigkeit_schrifthover'].";}");

fwrite($dunkel, ".cms_neuigkeit_notfall {");
fwrite($dunkel, "background: ".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";");
fwrite($dunkel, "animation-name: notfallanimationdunkel;");
fwrite($dunkel, "}");

fwrite($dunkel, "@keyframes notfallanimationdunkel {");
fwrite($dunkel, "0%   {background-color:".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");
fwrite($dunkel, "37.5%  {background-color:".$_POST['cms_style_h_haupt_meldungfehlerakzent'].";}");
fwrite($dunkel, "75%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");
fwrite($dunkel, "100%  {background-color:".$_POST['cms_style_h_haupt_meldungwarnungakzent'].";}");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_neuigkeit_ln {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund']." !important;");
fwrite($dunkel, "border: 2px dashed ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_neuigkeit_ln h4, .cms_neuigkeit_ln p {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_neuigkeit:hover {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");
?>
