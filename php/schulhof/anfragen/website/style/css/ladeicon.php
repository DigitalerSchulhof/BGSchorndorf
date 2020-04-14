<?php
fwrite($hell, ".cms_ladeicon {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "position: relative;");
fwrite($hell, "width: 64px;");
fwrite($hell, "height: 11px;");
fwrite($hell, "}");

fwrite($hell, ".cms_ladeicon div {");
fwrite($hell, "position: absolute;");
fwrite($hell, "width: 11px;");
fwrite($hell, "height: 11px;");
fwrite($hell, "border-radius: 50%;");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "animation-timing-function: cubic-bezier(0, 1, 1, 0);");
fwrite($hell, "}");

fwrite($hell, ".cms_ladeicon div:nth-child(1) {");
fwrite($hell, "left: 0px;");
fwrite($hell, "animation: cms_ladeicon1 0.6s infinite;");
fwrite($hell, "}");

fwrite($hell, ".cms_ladeicon div:nth-child(2) {");
fwrite($hell, "left: 0px;");
fwrite($hell, "animation: cms_ladeicon2 0.6s infinite;");
fwrite($hell, "}");

fwrite($hell, ".cms_ladeicon div:nth-child(3) {");
fwrite($hell, "left: 20px;");
fwrite($hell, "animation: cms_ladeicon2 0.6s infinite;");
fwrite($hell, "}");

fwrite($hell, ".cms_ladeicon div:nth-child(4) {");
fwrite($hell, "left: 39px;");
fwrite($hell, "animation: cms_ladeicon3 0.6s infinite;");
fwrite($hell, "}");

fwrite($hell, "@keyframes cms_ladeicon1 {");
fwrite($hell, "0% {");
fwrite($hell, "transform: scale(0);");
fwrite($hell, "}");
fwrite($hell, "100% {");
fwrite($hell, "transform: scale(1);");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, "@keyframes cms_ladeicon3 {");
fwrite($hell, "0% {");
fwrite($hell, "transform: scale(1);");
fwrite($hell, "}");
fwrite($hell, "100% {");
fwrite($hell, "transform: scale(0);");
fwrite($hell, "}");
fwrite($hell, "}");

fwrite($hell, "@keyframes cms_ladeicon2 {");
fwrite($hell, "0% {");
fwrite($hell, "transform: translate(0, 0);");
fwrite($hell, "}");
fwrite($hell, "100% {");
fwrite($hell, "transform: translate(19px, 0);");
fwrite($hell, "}");
fwrite($hell, "}");


// DARKMODE
fwrite($dunkel, ".cms_ladeicon div {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");
?>
