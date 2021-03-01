<?php
function cms_webeditor($id, $inhalt = '') {
  $code = "";

  $code .= "<div id=\"$id\">$inhalt</div>";

  $code .= "<script>
    $('#$id').summernote({
      toolbar: [
        // [groupName, [list of button]]
        ['textstil', ['style', 'paragraph', 'fontsize', 'fontname']],
        ['textformat', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'clear']],
        ['textfarbe', ['color']],
        ['listentabellen', ['ul', 'ol', 'table']],
        ['insert', ['link', 'picture', 'video']],
        ['aktionen', ['undo', 'redo']],
        ['vollbild', ['fullscreen']],
        ['fortgeschritten', ['codeview']]
      ]
    });
  </script>";

  return $code;
}

function cms_gruppeneditor($id, $inhalt = '') {
  $code = "";

  $code .= "<div id=\"$id\">$inhalt</div>";

  $code .= "<script>
    $('#$id').summernote({
      toolbar: [
        // [groupName, [list of button]]
        ['textstil', ['style', 'paragraph', 'fontsize', 'fontname']],
        ['textformat', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'clear']],
        ['textfarbe', ['color']],
        ['listentabellen', ['ul', 'ol', 'table']],
        ['insert', ['link']],
        ['aktionen', ['undo', 'redo']],
        ['vollbild', ['fullscreen']],
        ['fortgeschritten', ['codeview']]
      ]
    });
  </script>";

  return $code;
}
?>
