<?php
fwrite($hell, ".cms_reitermenue, .cms_reitermenue li {");
fwrite($hell, "margin-bottom: 0px !important;");
fwrite($hell, "display: inline-block;");
fwrite($hell, "list-style-type: none;");
fwrite($hell, "margin-left: 0px;");
fwrite($hell, "margin-top: 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_reiter, .cms_reiter_aktiv {");
fwrite($hell, "display: inline-block;");
fwrite($hell, "padding: 7px 10px 4px 10px;");
fwrite($hell, "transition: 250ms ease-in-out;");
fwrite($hell, "font-weight: bold;");
fwrite($hell, "}");

fwrite($hell, ".cms_reiter {");
fwrite($hell, "color: ".$_POST['cms_style_h_reiter_farbe'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_reiter_hintergrund'].";");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_reiter_radiusoben'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_reiter_radiusoben'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_reiter_radiusunten'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_reiter_radiusunten'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_reiter:hover, .cms_reiter_aktiv:hover {");
fwrite($hell, "color: ".$_POST['cms_style_h_reiter_farbehover'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_reiter_hintergrundhover'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_reiter_radiusoben'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_reiter_radiusoben'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_reiter_radiusunten'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_reiter_radiusunten'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_reiter_aktiv {");
fwrite($hell, "color: ".$_POST['cms_style_h_reiter_farbeaktiv'].";");
fwrite($hell, "background: ".$_POST['cms_style_h_reiter_hintergrundaktiv'].";");
fwrite($hell, "cursor: pointer;");
fwrite($hell, "border-top-right-radius: ".$_POST['cms_style_reiter_radiusoben'].";");
fwrite($hell, "border-top-left-radius: ".$_POST['cms_style_reiter_radiusoben'].";");
fwrite($hell, "border-bottom-right-radius: ".$_POST['cms_style_reiter_radiusunten'].";");
fwrite($hell, "border-bottom-left-radius: ".$_POST['cms_style_reiter_radiusunten'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_reitermenue_o {");
fwrite($hell, "border-color: ".$_POST['cms_style_h_haupt_abstufung2'].";");
fwrite($hell, "border-style: solid;");
fwrite($hell, "border-width: 0px;");
fwrite($hell, "border-top-width: 2px;");
fwrite($hell, "border-bottom-width: 2px;");
fwrite($hell, "display: none;");
fwrite($hell, "}");

fwrite($hell, ".cms_reitermenue_i {");
fwrite($hell, "padding: 10px 0px;");
fwrite($hell, "}");

fwrite($hell, ".cms_unternavigation_o .cms_reitermenue_i {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_abstufung1'].";");
fwrite($hell, "}");

fwrite($hell, ".cms_hauptteil_o .cms_reitermenue_i {");
fwrite($hell, "background: ".$_POST['cms_style_h_haupt_hintergrund'].";");
fwrite($hell, "}");









// DARKMODE
fwrite($dunkel, ".cms_reiter {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_reiter_farbe'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_reiter_hintergrund'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_reiter:hover, .cms_reiter_aktiv:hover {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_reiter_farbehover'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_reiter_hintergrundhover'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_reiter_aktiv {");
fwrite($dunkel, "color: ".$_POST['cms_style_d_reiter_farbeaktiv'].";");
fwrite($dunkel, "background: ".$_POST['cms_style_d_reiter_hintergrundaktiv'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_reitermenue_o {");
fwrite($dunkel, "border-color: ".$_POST['cms_style_d_haupt_abstufung2'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_unternavigation_o .cms_reitermenue_i {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_abstufung1'].";");
fwrite($dunkel, "}");

fwrite($dunkel, ".cms_hauptteil_o .cms_reitermenue_i {");
fwrite($dunkel, "background: ".$_POST['cms_style_d_haupt_hintergrund'].";");
fwrite($dunkel, "}");
?>
