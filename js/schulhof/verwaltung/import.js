function cms_import_analysieren(csvid, trennid, zielfunktion) {
  var trennung = document.getElementById(trennid).value;
  var csv = document.getElementById(csvid).value;

  // Nur analysieren, wenn es etwas zu analysieren gibt
  if ((csv.length > 0) && (trennung.length > 0)) {
    csv = csv.split("\n");

    var maxspalten = 0;
    for (var i = 0; i < csv.length; i++) {
      var aktspalten = csv[i].split(trennung).length;
      if (aktspalten > maxspalten) {maxspalten = aktspalten;}
    }

    zielfunktion(maxspalten, true, maxspalten);
  }
  else {zielfunktion(0, false);}
}

function cms_faecher_import(spaltenzahl, an, maxspalten) {
  var code = '<option value="-">nicht importieren</option>';
  if (an) {
    for (var i=1; i<=spaltenzahl;i++) {
      code += '<option value="'+i+'">aus Spalte '+i+'</option>';
    }
  }
  document.getElementById("cms_faecher_import_bezeichnung").innerHTML = code;
  document.getElementById("cms_faecher_import_kuerzel").innerHTML = code;
  document.getElementById("cms_faecher_import_farbe").innerHTML = code;
  document.getElementById("cms_faecher_import_icon").innerHTML = code;

  document.getElementById("cms_faecher_import_bezeichnung").disabled = !an;
  document.getElementById("cms_faecher_import_kuerzel").disabled = !an;
  document.getElementById("cms_faecher_import_farbe").disabled = !an;
  document.getElementById("cms_faecher_import_icon").disabled = !an;
}

function cms_stundenplanung_import(spaltenzahl, an, maxspalten) {
  var code = '<option value="-">nicht importieren</option>';
  if (an) {
    for (var i=1; i<=spaltenzahl;i++) {
      code += '<option value="'+i+'">aus Spalte '+i+'</option>';
    }
  }
  document.getElementById("cms_stundenplanung_import_lehrer").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_tag").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_stunde").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_fach").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_raum").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_rythmen").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_schienen").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_klasse").innerHTML = code;

  document.getElementById("cms_stundenplanung_import_lehrer").disabled = !an;
  document.getElementById("cms_stundenplanung_import_tag").disabled = !an;
  document.getElementById("cms_stundenplanung_import_stunde").disabled = !an;
  document.getElementById("cms_stundenplanung_import_fach").disabled = !an;
  document.getElementById("cms_stundenplanung_import_raum").disabled = !an;
  document.getElementById("cms_stundenplanung_import_rythmen").disabled = !an;
  document.getElementById("cms_stundenplanung_import_schienen").disabled = !an;
  document.getElementById("cms_stundenplanung_import_klasse").disabled = !an;

  if ((maxspalten > 9) && (an)) {
    document.getElementById("cms_stundenplanung_import_lehrer").value = 1;
    document.getElementById("cms_stundenplanung_import_tag").value = 2;
    document.getElementById("cms_stundenplanung_import_stunde").value = 3;
    document.getElementById("cms_stundenplanung_import_fach").value = 4;
    document.getElementById("cms_stundenplanung_import_raum").value = 5;
    document.getElementById("cms_stundenplanung_import_rythmen").value = 9;
    document.getElementById("cms_stundenplanung_import_schienen").value = 6;
    document.getElementById("cms_stundenplanung_import_klasse").value = 8;
  }
}

function cms_personenimport(spaltenzahl, an, maxspalten) {
  var code = '<option value="-">nicht importieren</option>';
  if (an) {
    for (var i=1; i<=spaltenzahl;i++) {
      code += '<option value="'+i+'">aus Spalte '+i+'</option>';
    }
  }
  document.getElementById("cms_personenimport_id").innerHTML = code;
  document.getElementById("cms_personenimport_vornach").innerHTML = code;
  document.getElementById("cms_personenimport_nachvor").innerHTML = code;
  document.getElementById("cms_personenimport_nach").innerHTML = code;
  document.getElementById("cms_personenimport_vor").innerHTML = code;

  document.getElementById("cms_personenimport_id").disabled = !an;
  document.getElementById("cms_personenimport_vornach").disabled = !an;
  document.getElementById("cms_personenimport_nachvor").disabled = !an;
  document.getElementById("cms_personenimport_nach").disabled = !an;
  document.getElementById("cms_personenimport_vor").disabled = !an;
}

function cms_kurszuordnungimport(spaltenzahl, an, maxspalten) {
  var code = '<option value="-">nicht importieren</option>';
  if (an) {
    for (var i=1; i<=spaltenzahl;i++) {
      code += '<option value="'+i+'">aus Spalte '+i+'</option>';
    }
  }
  document.getElementById("cms_kurszuordnungimport_kurs").innerHTML = code;
  document.getElementById("cms_kurszuordnungimport_tutor").innerHTML = code;
  document.getElementById("cms_kurszuordnungimport_schueler").innerHTML = code;

  document.getElementById("cms_kurszuordnungimport_kurs").disabled = !an;
  document.getElementById("cms_kurszuordnungimport_tutor").disabled = !an;
  document.getElementById("cms_kurszuordnungimport_schueler").disabled = !an;
}
