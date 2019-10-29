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

    zielfunktion(maxspalten, true);
  }
  else {zielfunktion(0, false);}
}

function cms_faecher_import(spaltenzahl, an) {
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

function cms_stundenplanung_import(spaltenzahl, an) {
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
  document.getElementById("cms_stundenplanung_import_schienen").innerHTML = code;
  document.getElementById("cms_stundenplanung_import_klasse").innerHTML = code;

  document.getElementById("cms_stundenplanung_import_lehrer").disabled = !an;
  document.getElementById("cms_stundenplanung_import_tag").disabled = !an;
  document.getElementById("cms_stundenplanung_import_stunde").disabled = !an;
  document.getElementById("cms_stundenplanung_import_fach").disabled = !an;
  document.getElementById("cms_stundenplanung_import_raum").disabled = !an;
  document.getElementById("cms_stundenplanung_import_schienen").disabled = !an;
  document.getElementById("cms_stundenplanung_import_klasse").disabled = !an;
}
