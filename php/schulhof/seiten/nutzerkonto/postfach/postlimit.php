<?php
if ($CMS_BENUTZERART == 's') {$POSTLIMIT = 100*1024*1024;}
else if ($CMS_BENUTZERART == 'l') {$POSTLIMIT = 1024*1024*1024;}
else if ($CMS_BENUTZERART == 'v') {$POSTLIMIT = 1024*1024*1024;}
else if ($CMS_BENUTZERART == 'e') {$POSTLIMIT = 10*1024*1024;}
else if ($CMS_BENUTZERART == 'x') {$POSTLIMIT = 10*1024*1024;}
else {$POSTLIMIT = 0;}
?>