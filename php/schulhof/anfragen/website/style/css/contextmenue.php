<?php
fwrite($hell, "#contextmenue {\n");
fwrite($hell, "position: absolute;\n");
fwrite($hell, "display: none;\n");
fwrite($hell, "z-index: 1000;\n");
fwrite($hell, "padding: 4px;\n");
fwrite($hell, "padding-bottom: 3px;  // Weil cms_aktion hat\n");
fwrite($hell, "background-color: rgba(0, 0, 0, 0.75);\n");
fwrite($hell, "border-radius: 5px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#contextmenue .cms_aktion_klein {\n");
fwrite($hell, "display: block;\n");
fwrite($hell, "}\n");

fwrite($hell, "#contextmenue .cms_aktion_klein .cms_alter_hinweis {\n");
fwrite($hell, "margin-left: 3px;\n");
fwrite($hell, "}\n");

fwrite($hell, "#contextmenue .cms_aktion_klein img {\n");
fwrite($hell, "float: left;\n");
fwrite($hell, "}\n");
?>
