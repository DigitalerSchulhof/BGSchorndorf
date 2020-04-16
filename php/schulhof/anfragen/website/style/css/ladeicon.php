<?php
fwrite($hell, ".cms_ladeicon {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "position: relative;\n");
fwrite($hell, "width: 64px;\n");
fwrite($hell, "height: 11px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ladeicon div {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "width: 11px;\n");
fwrite($hell, "height: 11px;\n");
fwrite($hell, "border-radius: 50%;\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "animation-timing-function: cubic-bezier(0, 1, 1, 0);\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ladeicon div:nth-child(1) {\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "animation: cms_ladeicon1 0.6s infinite;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ladeicon div:nth-child(2) {\n");
fwrite($hell, "left: 0px;\n");
fwrite($hell, "animation: cms_ladeicon2 0.6s infinite;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ladeicon div:nth-child(3) {\n");
fwrite($hell, "left: 20px;\n");
fwrite($hell, "animation: cms_ladeicon2 0.6s infinite;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_ladeicon div:nth-child(4) {\n");
fwrite($hell, "left: 39px;\n");
fwrite($hell, "animation: cms_ladeicon3 0.6s infinite;\n");
fwrite($hell, "}\n");

fwrite($hell, "@keyframes cms_ladeicon1 {\n");
fwrite($hell, "0% {transform: scale(0);}\n");
fwrite($hell, "100% {transform: scale(1);}\n");
fwrite($hell, "}\n");

fwrite($hell, "@keyframes cms_ladeicon3 {\n");
fwrite($hell, "0% {transform: scale(1);}\n");
fwrite($hell, "100% {transform: scale(0);}\n");
fwrite($hell, "}\n");

fwrite($hell, "@keyframes cms_ladeicon2 {\n");
fwrite($hell, "0% {transform: translate(0, 0);}\n");
fwrite($hell, "100% {transform: translate(19px, 0);}\n");
fwrite($hell, "}\n");


// DARKMODE
fwrite($dunkel, ".cms_ladeicon div {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");
?>
