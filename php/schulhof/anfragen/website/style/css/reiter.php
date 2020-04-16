<?php
fwrite($hell, ".cms_reitermenue, .cms_reitermenue li {\n");
fwrite($hell, "margin-bottom: 0px !important;\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "list-style-type: none;\n");
fwrite($hell, "margin-left: 0px;\n");
fwrite($hell, "margin-top: 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_reiter, .cms_reiter_aktiv {\n");
fwrite($hell, "display: inline-block;\n");
fwrite($hell, "padding: 7px 10px 4px 10px;\n");
fwrite($hell, "transition: 250ms ease-in-out;\n");
fwrite($hell, "font-weight: bold;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_reiter {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_reiter_farbe'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_reiter_hintergrund'].";\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_reiter_radiusoben'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_reiter_radiusoben'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_reiter_radiusunten'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_reiter_radiusunten'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_reiter:hover, .cms_reiter_aktiv:hover {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_reiter_farbehover'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_reiter_hintergrundhover'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_reiter_radiusoben'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_reiter_radiusoben'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_reiter_radiusunten'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_reiter_radiusunten'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_reiter_aktiv {\n");
fwrite($hell, "color: ".$_POST['cms_style_h_reiter_farbeaktiv'].";\n");
fwrite($hell, "background: ".$_POST['cms_style_h_reiter_hintergrundaktiv'].";\n");
fwrite($hell, "cursor: pointer;\n");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_reiter_radiusoben'].";\n");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_reiter_radiusoben'].";\n");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_reiter_radiusunten'].";\n");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_reiter_radiusunten'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_reitermenue_o {\n");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_abstufung2'].";\n");
fwrite($hell, "border-style: solid;\n");
fwrite($hell, "border-width: 0px;\n");
fwrite($hell, "border-top-width: 2px;\n");
fwrite($hell, "border-bottom-width: 2px;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_reitermenue_i {\n");
fwrite($hell, "padding: 10px 0px;\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_unternavigation_o .cms_reitermenue_i {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";\n");
fwrite($hell, "}\n");

fwrite($hell, ".cms_hauptteil_o .cms_reitermenue_i {\n");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";\n");
fwrite($hell, "}\n");









// DARKMODE
fwrite($dunkel, ".cms_reiter {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_reiter_farbe'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_reiter_hintergrund'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_reiter:hover, .cms_reiter_aktiv:hover {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_reiter_farbehover'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_reiter_hintergrundhover'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_reiter_aktiv {\n");
fwrite($dunkel, "color: ".$_POST['cms_style_d_reiter_farbeaktiv'].";\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_reiter_hintergrundaktiv'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_reitermenue_o {\n");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_abstufung2'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_unternavigation_o .cms_reitermenue_i {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";\n");
fwrite($dunkel, "}\n");

fwrite($dunkel, ".cms_hauptteil_o .cms_reitermenue_i {\n");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";\n");
fwrite($dunkel, "}\n");
?>
