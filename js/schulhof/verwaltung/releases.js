function cms_release_waehlen(id, version) {
  cms_laden_an();
  var v = version;
  var i = $(".cms_release_"+id+">.cms_release_i").text();
  if(!i.length) {
    cms_meldung_fehler();
    return;
  }
  $("#cms_aktuelles_release_v").text(v);
  $("#cms_gewaehltes_release").val(id);
  $("#cms_aktuelles_release_i").text(i);
  setTimeout(function() {
    cms_laden_aus();
  }, 100);
}

function cms_release_hochladen_vorbereiten() {
  var id = $("#cms_gewaehltes_release").val();
  var v = $(".cms_release_"+id+">.cms_release_v").text();

  cms_meldung_an('warnung', 'Version ändern', '<p>Soll die Version <b>'+v+'</b> wirklich hochgeladen werden?<br>Die gesamte Website wird für wenige Minuten komplett deaktiviert und die neue Version wird hochgeladen.</p>', '<p><span class="cms_button_nein" onclick="cms_meldung_aus();">Abbrechen</span> <span class="cms_button_ja" onclick="cms_release_hochladen(\''+id+'\')">Version hochladen</span></p>');
}

function cms_release_hochladen(id) {
  cms_laden_an('Version ändern', 'Die Version wird hochgeladen.<br>Dies kann einen Moment dauern.');

  var formulardaten = new FormData();
  formulardaten.append("version",       id    );
  formulardaten.append("anfragenziel", 	'281' );
  function anfragennachbehandlung(rueckgabe) {
    if (rueckgabe == "ERFOLG") {
      cms_meldung_an('erfolg', 'Version ändern', '<p>Die Änderungen wurden übernommen</p>', '<p><span class="cms_button" onclick="location.reload()">OK</span></p>');
    }
    else {
      cms_fehlerbehandlung(rueckgabe);
    }
  }

  cms_ajaxanfrage (false, formulardaten, anfragennachbehandlung);
}
